<?php

namespace App\Services;

use App\Data\Annotation\DynamicMode\ObjectAnnotationData;
use App\Data\Annotation\DynamicMode\ObjectData;
use App\Data\Annotation\DynamicMode\UpdateBBoxData;
use App\Database\Criteria;
use App\Repositories\AnnotationSet;
use App\Repositories\Base;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\DynamicSentenceMM;
use App\Repositories\Label;
use App\Repositories\LayerType;
use App\Repositories\Sentence;
use App\Repositories\Timeline;
use App\Repositories\User;
use App\Repositories\UserAnnotation;
use App\Repositories\Video;
use App\Repositories\ViewAnnotationSet;
use App\Repositories\WordForm;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Orkester\Manager;


class AnnotationDynamicService
{
    private static function getCurrentUserTask(int $idDocument): object|null
    {
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        // get usertask for this document
        $usertask = Criteria::table("usertask_document")
            ->join("usertask as ut", "ut.idUserTask", "=", "usertask_document.idUserTask")
            ->where("usertask_document.idDocument", $idDocument)
            ->where("ut.idUser", $idUser)
            ->select("ut.idUserTask", "ut.idTask")
            ->first();
        if (empty($usertask)) { // usa a task -> dataset -> corpus -> document
            if (User::isManager($user) || User::isMemberOf($user,'MASTER')) {
                $usertask = Criteria::table("usertask_document")
                    ->join("usertask as ut", "ut.idUserTask", "=", "usertask_document.idUserTask")
                    ->where("usertask_document.idDocument", $idDocument)
                    ->where("ut.idUser", -2)
                    ->select("ut.idUserTask", "ut.idTask")
                    ->first();
            } else {
                $usertask = null;
            }
        }
        return $usertask;
    }

    private static function deleteBBoxesByDynamicObject(int $idDynamicObject)
    {
        $bboxes = Criteria::table("view_dynamicobject_boundingbox as db")
            ->join("boundingbox as bb", "db.idBoundingBox", "=", "bb.idBoundingBox")
            ->where("db.idDynamicObject", $idDynamicObject)
            ->select("bb.idAnnotationObject")
            ->chunkResult("idAnnotationObject", "idAnnotationObject");
        Criteria::table("annotationobjectrelation")
            ->whereIn("idAnnotationObject2", $bboxes)
            ->delete();
        Criteria::table("boundingbox")
            ->whereIn("idAnnotationObject", $bboxes)
            ->delete();
        Criteria::table("annotationobject")
            ->whereIn("idAnnotationObject", $bboxes)
            ->delete();
    }

    public static function getObject(int $idDynamicObject): object|null
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $do = Criteria::table("view_annotation_dynamic")
            ->where("idLanguage", "left", $idLanguage)
            ->where("idDynamicObject", $idDynamicObject)
            ->select("idDynamicObject", "name", "startFrame", "endFrame", "startTime", "endTime", "status", "origin", "idAnnotationLU", "idLU", "lu", "idAnnotationFE", "idFrameElement", "idFrame", "frame", "fe", "color")
            ->orderBy("startFrame")
            ->orderBy("endFrame")
            ->first();
        return $do;
    }

    public static function getObjectsByDocument(int $idDocument): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $result = Criteria::table("view_annotation_dynamic")
//            ->orWhere(function (Builder $query) {
//                $query->where('idLanguage', $idLanguage)
//                    ->whereNull('idLanguage');
//            })
            ->where("idLanguage", "left", $idLanguage)
            ->where("idDocument", $idDocument)
            ->select("idDynamicObject", "name", "startFrame", "endFrame", "startTime", "endTime", "status", "origin", "idAnnotationLU", "idLU", "lu", "idAnnotationFE", "idFrameElement", "idFrame", "frame", "fe", "color")
            ->orderBy("startFrame")
            ->orderBy("endFrame")
            ->orderBy("idDynamicObject")
            ->all();
        $oMM = [];
        $bboxes = [];
        foreach ($result as $row) {
            $oMM[] = $row->idDynamicObject;
        }
        if (count($result) > 0) {
            $bboxList = Criteria::table("view_dynamicobject_boundingbox")
                ->whereIN("idDynamicObject", $oMM)
                ->all();
            foreach ($bboxList as $bbox) {
                $bboxes[$bbox->idDynamicObject][] = $bbox;
            }
        }
        $objects = [];
        foreach ($result as $i => $row) {
            $row->order = $i + 1;
            $row->bboxes = $bboxes[$row->idDynamicObject] ?? [];
            $objects[] = $row;
        }
        return $objects;
    }

    public static function updateObjectAnnotation(ObjectAnnotationData $data): int
    {
        debug($data);
        $usertask = self::getCurrentUserTask($data->idDocument);
        $do = Criteria::byId("dynamicobject", "idDynamicObject", $data->idDynamicObject);
        Criteria::deleteById("annotation", "idAnnotationObject", $do->idAnnotationObject);
        if ($data->idFrameElement) {
            $fe = Criteria::byId("frameelement", "idFrameElement", $data->idFrameElement);
            $json = json_encode([
                'idEntity' => $fe->idEntity,
                'idAnnotationObject' => $do->idAnnotationObject,
                'relationType' => 'rel_annotation',
                'idUserTask' => $usertask->idUserTask
            ]);
            $idAnnotation = Criteria::function("annotation_create(?)", [$json]);
            Timeline::addTimeline("annotation", $idAnnotation, "C");
        }
        if ($data->idLU) {
            $lu = Criteria::byId("lu", "idLU", $data->idLU);
            $json = json_encode([
                'idEntity' => $lu->idEntity,
                'idAnnotationObject' => $do->idAnnotationObject,
                'relationType' => 'rel_annotation',
                'idUserTask' => $usertask->idUserTask
            ]);
            $idAnnotation = Criteria::function("annotation_create(?)", [$json]);
            Timeline::addTimeline("annotation", $idAnnotation, "C");
        }
        return $data->idDynamicObject;
    }
    public static function updateObject(ObjectData $data): int
    {
        // if idDynamicObject = null : object create
        if (is_null($data->idDynamicObject)) {
            $do = json_encode([
                'name' => $data->name,
                'startFrame' => (int)$data->startFrame,
                'endFrame' => (int)$data->endFrame,
                'startTime' => (float)$data->startTime,
                'endTime' => (float)$data->endTime,
                'status' => (int)$data->status,
                'origin' => (int)$data->origin
            ]);
            $idDynamicObject = Criteria::function("dynamicobject_create(?)", [$do]);
            $dynamicObject = Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObject);
            $documentVideo = Criteria::table("view_document_video")
                ->where("idDocument", $data->idDocument)
                ->first();
            $video = Video::byId($documentVideo->idVideo);
            // create annotationobjectrelation for rel_video_dynobj
            $relation = json_encode([
                'idAnnotationObject1' => $video->idAnnotationObject,
                'idAnnotationObject2' => $dynamicObject->idAnnotationObject,
                'relationType' => 'rel_video_dynobj'
            ]);
            $idObjectRelation = Criteria::function("objectrelation_create(?)", [$relation]);
            if (count($data->frames)) {
                foreach ($data->frames as $frame) {
                    $json = json_encode([
                        'frameNumber' => (int)$frame['frameNumber'],
                        'frameTime' => (float)$frame['frameTime'],
                        'x' => (int)$frame['x'],
                        'y' => (int)$frame['y'],
                        'width' => (int)$frame['width'],
                        'height' => (int)$frame['height'],
                        'blocked' => (int)$frame['blocked'],
                        'idDynamicObject' => (int)$idDynamicObject
                    ]);
                    $idBoundingBox = Criteria::function("boundingbox_dynamic_create(?)", [$json]);
                }
            }
        } else {
            // if idDynamicObject != null : update object and  boundingboxes
            $idDynamicObject = $data->idDynamicObject;
            Criteria::table("dynamicobject")
                ->where("idDynamicObject", $idDynamicObject)
                ->update([
                    'startFrame' => $data->startFrame,
                    'endFrame' => $data->endFrame,
                    'startTime' => $data->startTime,
                    'endTime' => $data->endTime,
            ]);
            if (count($data->frames)) {
                self::deleteBBoxesByDynamicObject($idDynamicObject);
                foreach ($data->frames as $frame) {
                    $json = json_encode([
                        'frameNumber' => (int)$frame['frameNumber'],
                        'frameTime' => (float)$frame['frameTime'],
                        'x' => (int)$frame['x'],
                        'y' => (int)$frame['y'],
                        'width' => (int)$frame['width'],
                        'height' => (int)$frame['height'],
                        'blocked' => (int)$frame['blocked'],
                        'idDynamicObject' => (int)$idDynamicObject
                    ]);
                    $idBoundingBox = Criteria::function("boundingbox_dynamic_create(?)", [$json]);
                }
            }
        }
        return $idDynamicObject;
    }

    public static function deleteObject(int $idDynamicObject): void
    {
        // se pode remover o objeto se for Manager ou se for o criador do objeto
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        if (!User::isManager($user)) {
            $tl = Criteria::table("timeline")
                ->where("tablename", "dynamicobject")
                ->where("id", $idDynamicObject)
                ->select("idUser")
                ->first();
            if ($tl->idUser != $idUser) {
                throw new \Exception("Object can not be removed.");
            }
        }
        DB::transaction(function () use ($idDynamicObject) {
//            $vd = Criteria::table("view_video_dynamicobject")
//                ->where("idDynamicObject", $idDynamicObject)
//                ->select("idVideo")
//                ->first();
//            $dv = Criteria::table("view_document_video")
//                ->where("idVideo", $vd->idVideo)
//                ->select("idDocument")
//                ->first();
//            $usertask = self::getCurrentUserTask($dv->idDocument);
//            if (is_null($usertask)) {
//                throw new \Exception("UserTask not found!");
//            }
            // remove boundingbox
            self::deleteBBoxesByDynamicObject($idDynamicObject);
            // remove dynamicobject
            $idUser = AppService::getCurrentIdUser();
            Criteria::function("dynamicobject_delete(?,?)", [$idDynamicObject, $idUser]);
        });
    }

    public static function updateBBox(UpdateBBoxData $data): int
    {
        Criteria::table("boundingbox")
            ->where("idBoundingBox", $data->idBoundingBox)
            ->update($data->bbox);
        return $data->idBoundingBox;
    }

    public static function listSentencesByDocument($idDocument): array
    {
        $sentences = Criteria::table("sentence")
            ->join("view_document_sentence as ds", "sentence.idSentence", "=", "ds.idSentence")
            ->join("view_sentence_timespan as st", "sentence.idSentence", "=", "st.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->where("d.idDocument", $idDocument)
            ->select("sentence.idSentence", "sentence.text", "ds.idDocumentSentence", "st.startTime", "st.endTime")
            ->orderBy("ds.idDocumentSentence")
            ->limit(1000)
            ->get()->keyBy("idDocumentSentence")->all();
        if (!empty($sentences)) {
            $targets = collect(AnnotationSet::listTargetsForDocumentSentence(array_keys($sentences)))->groupBy('idDocumentSentence')->toArray();
            foreach ($targets as $idDocumentSentence => $spans) {
                $sentences[$idDocumentSentence]->text = self::decorateSentenceTarget($sentences[$idDocumentSentence]->text, $spans);
            }
        }
        return $sentences;
    }


    public static function decorateSentenceTarget($text, $spans)
    {
        $decorated = "";
        $ni = "";
        $i = 0;
        foreach ($spans as $span) {
            //$style = 'background-color:#' . $label['rgbBg'] . ';color:#' . $label['rgbFg'] . ';';
            if ($span->startChar >= 0) {
                $decorated .= mb_substr($text, $i, $span->startChar - $i);
                $decorated .= "<span class='color_target'>" . mb_substr($text, $span->startChar, $span->endChar - $span->startChar + 1) . "</span>";
                $i = $span->endChar + 1;
            }
        }
        $decorated = $decorated . mb_substr($text, $i);
        return $decorated;
    }


}
