<?php

namespace App\Services;

use App\Data\Annotation\StaticBBox\CloneData;
use App\Data\Annotation\StaticBBox\ObjectAnnotationData;
use App\Data\Annotation\StaticBBox\ObjectData;
use App\Data\Annotation\StaticBBox\UpdateBBoxData;
use App\Database\Criteria;
use App\Repositories\AnnotationSet;
use App\Repositories\Image;
use App\Repositories\Timeline;
use App\Repositories\User;
use Illuminate\Support\Facades\DB;

class AnnotationStaticBBoxService
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
            if (User::isManager($user) || User::isMemberOf($user, 'MASTER')) {
                $usertask = (object)[
                    "idUserTask" => 1
                ];
            } else {
                $usertask = null;
            }
        }
        return $usertask;
    }

    private static function deleteBBoxesByStaticBBoxObject(int $idStaticObject)
    {
        $bboxes = Criteria::table("view_staticobject_boundingbox as sb")
            ->join("boundingbox as bb", "sb.idBoundingBox", "=", "bb.idBoundingBox")
            ->where("sb.idStaticObject", $idStaticObject)
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

    public static function getObject(int $idStaticObject): object|null
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $do = Criteria::table("view_annotation_static")
            ->where("idLanguage", "left", $idLanguage)
            ->where("idStaticObject", $idStaticObject)
            ->select("idStaticObject", "name", "idAnnotationLU", "idLU", "lu", "idAnnotationFE", "idFrameElement", "idFrame", "frame", "fe", "color")
            ->orderBy("idStaticObject")
            ->first();
        return $do;
    }

    public static function getObjectsByDocument(int $idDocument): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $result = Criteria::table("view_annotation_static as sta")
            ->leftJoin("view_lu", "sta.idLu", "=", "view_lu.idLU")
            ->leftJoin("view_frame", "view_lu.idFrame", "=", "view_frame.idFrame")
            ->where("sta.idLanguage", "left", $idLanguage)
            ->where("idDocument", $idDocument)
            ->where("view_frame.idLanguage", "left", $idLanguage)
            ->select("idStaticObject", "sta.name", "idAnnotationLU", "sta.idLU", "lu", "view_lu.name as luName", "view_frame.name as luFrameName", "idAnnotationFE", "idFrameElement", "sta.idFrame", "frame", "fe", "color")
            ->orderBy("idStaticObject")
            ->all();
        $oMM = [];
        $bbox = [];
        foreach ($result as $row) {
            $oMM[] = $row->idStaticObject;
        }
        if (count($result) > 0) {
            $bboxObjects = Criteria::table("view_staticobject_boundingbox")
                ->whereIN("idStaticObject", $oMM)
                ->all();
            foreach($bboxObjects as $bboxObject) {
                $bbox[$bboxObject->idStaticObject] = $bboxObject;
            }
        }
        $objects = [];
        foreach ($result as $i => $row) {
            $row->order = $i + 1;
            $row->bbox = $bbox[$row->idStaticObject] ?? null;
            $objects[] = $row;
        }
        return $objects;
    }

    public static function updateObjectAnnotation(ObjectAnnotationData $data): int
    {
        $usertask = self::getCurrentUserTask($data->idDocument);
        $sob = Criteria::byId("staticobject", "idStaticObject", $data->idStaticObject);
        Criteria::deleteById("annotation", "idAnnotationObject", $sob->idAnnotationObject);
        if ($data->idFrameElement) {
            $fe = Criteria::byId("frameelement", "idFrameElement", $data->idFrameElement);
            $json = json_encode([
                'idEntity' => $fe->idEntity,
                'idAnnotationObject' => $sob->idAnnotationObject,
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
                'idAnnotationObject' => $sob->idAnnotationObject,
                'relationType' => 'rel_annotation',
                'idUserTask' => $usertask->idUserTask
            ]);
            $idAnnotation = Criteria::function("annotation_create(?)", [$json]);
            Timeline::addTimeline("annotation", $idAnnotation, "C");
        }
        return $data->idStaticObject;
    }

    public static function updateObject(ObjectData $data): int
    {
        debug($data);
        $idUser = AppService::getCurrentIdUser();
        // if idStaticObject = null : object create
        if (is_null($data->idStaticObject)) {
            $sob = json_encode([
                'name' => $data->name,
                'scene' => 0,
                'idFlickr30kEntitiesChain' => 0,
                'nobndbox' => 0,
                'idUser' => $idUser
            ]);
            $idStaticObject = Criteria::function("staticobject_create(?)", [$sob]);
            $staticObject = Criteria::byId("staticobject", "idStaticObject", $idStaticObject);
            $documentImage = Criteria::table("view_document_image")
                ->where("idDocument", $data->idDocument)
                ->first();
            $image = Image::byId($documentImage->idImage);
            // create annotationobjectrelation for rel_image_staobj
            $relation = json_encode([
                'idAnnotationObject1' => $image->idAnnotationObject,
                'idAnnotationObject2' => $staticObject->idAnnotationObject,
                'relationType' => 'rel_image_staobj'
            ]);
            $idObjectRelation = Criteria::function("objectrelation_create(?)", [$relation]);
            if (count($data->bbox)) {
                $bbox = $data->bbox;
                $json = json_encode([
                    'frameNumber' => 0,
                    'frameTime' => 0,
                    'x' => (int)$bbox['x'],
                    'y' => (int)$bbox['y'],
                    'width' => (int)$bbox['width'],
                    'height' => (int)$bbox['height'],
                    'blocked' => (int)$bbox['blocked'],
                    'idStaticObject' => (int)$idStaticObject
                ]);
                $idBoundingBox = Criteria::function("boundingbox_static_create(?)", [$json]);
            }
        } else {
            // if idStaticObject != null : update object and  boundingbox
            $idStaticObject = $data->idStaticObject;
            if (count($data->bbox)) {
                $bbox = $data->bbox;
                $json = json_encode([
                    'frameNumber' => 0,
                    'frameTime' => 0,
                    'x' => (int)$bbox['x'],
                    'y' => (int)$bbox['y'],
                    'width' => (int)$bbox['width'],
                    'height' => (int)$bbox['height'],
                    'blocked' => (int)$bbox['blocked'],
                    'idStaticObject' => (int)$idStaticObject
                ]);
                $idBoundingBox = Criteria::function("boundingbox_static_create(?)", [$json]);
            }
        }
        return $idStaticObject;
    }

    public static function cloneObject(CloneData $data): int
    {
        $idUser = AppService::getCurrentIdUser();
        $idStaticObject = $data->idStaticObject;
        $sob = self::getObject($idStaticObject);
        $clone = json_encode([
            'name' => $sob->name,
            'scene' => (int)$sob->scene,
            'nobdnbox' => (int)$sob->nobdnbox,
            'idUser' => $idUser
        ]);
        $idStaticObjectClone = Criteria::function("staticobject_create(?)", [$clone]);
        $staticObjectClone = Criteria::byId("staticobject", "idStaticObject", $idStaticObjectClone);
        $documentImage = Criteria::table("view_document_image")
            ->where("idDocument", $data->idDocument)
            ->first();
        $image = Image::byId($documentImage->idImage);
        // create annotationobjectrelation for rel_image_staobj
        $relation = json_encode([
            'idAnnotationObject1' => $image->idAnnotationObject,
            'idAnnotationObject2' => $staticObjectClone->idAnnotationObject,
            'relationType' => 'rel_image_staobj'
        ]);
        $idObjectRelation = Criteria::function("objectrelation_create(?)", [$relation]);
        // cloning bboxes
        $bboxes = Criteria::table("view_staticobject_boundingbox")
            ->where("idStaticObject", $idStaticObject)
            ->all();
        foreach ($bboxes as $bbox) {
            $json = json_encode([
                'frameNumber' => 0,
                'frameTime' => 0,
                'x' => (int)$bbox->x,
                'y' => (int)$bbox->y,
                'width' => (int)$bbox->width,
                'height' => (int)$bbox->height,
                'blocked' => (int)$bbox->blocked,
                'idStaticObject' => (int)$idStaticObject
            ]);
            $idBoundingBox = Criteria::function("boundingbox_static_create(?)", [$json]);
        }
        return $idStaticObject;
    }

    public static function deleteObject(int $idStaticObject): void
    {
        // se pode remover o objeto se for Manager ou se for o criador do objeto
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        if (!User::isManager($user)) {
            $tl = Criteria::table("timeline")
                ->where("tablename", "staticobject")
                ->where("id", $idStaticObject)
                ->select("idUser")
                ->first();
            if ($tl->idUser != $idUser) {
                throw new \Exception("Object can not be removed.");
            }
        }
        DB::transaction(function () use ($idStaticObject) {
            // remove boundingbox
            self::deleteBBoxesByStaticBBoxObject($idStaticObject);
            // remove staticobject
            $idUser = AppService::getCurrentIdUser();
            Criteria::function("staticobject_delete(?,?)", [$idStaticObject, $idUser]);
        });
    }

    public static function updateBBox(UpdateBBoxData $data): int
    {
        $bbox = Criteria::table("view_staticobject_boundingbox")
            ->where("idStaticObject", $data->idStaticObject)
            ->first();
        Criteria::table("boundingbox")
            ->where("idBoundingBox", $bbox->idBoundingBox)
            ->update($data->bbox);
        return $data->idStaticObject;
    }

    public static function listSentencesByDocument($idDocument): array
    {
        $sentences = Criteria::table("sentence")
            ->join("view_document_sentence as ds", "sentence.idSentence", "=", "ds.idSentence")
            ->join("view_sentence_timespan as st", "sentence.idSentence", "=", "st.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->leftJoin("originmm as o", "sentence.idOriginMM", "=", "o.idOriginMM")
            ->where("d.idDocument", $idDocument)
            ->select("sentence.idSentence", "sentence.text", "ds.idDocumentSentence", "st.startTime", "st.endTime", "o.origin", "d.idDocument")
            ->orderBy("st.startTime")
            ->orderBy("st.endTime")
            ->limit(1000)
            ->get()->keyBy("idDocumentSentence")->all();
        if (!empty($sentences)) {
            $targets = collect(AnnotationSet::listTargetsForDocumentSentence(array_keys($sentences)))->groupBy('idDocumentSentence')->toArray();
            foreach ($targets as $idDocumentSentence => $spans) {
                debug($spans);
                $sentences[$idDocumentSentence]->text = self::decorateSentenceTarget($sentences[$idDocumentSentence]->text, $spans);
            }
        }
        return $sentences;
    }


    public static function decorateSentenceTarget($text, $spans)
    {
        $decorated = "";
        $i = 0;
        foreach ($spans as $span) {
            if ($span->startChar >= 0) {
                $decorated .= mb_substr($text, $i, $span->startChar - $i);
                $decorated .= "<span class='color_target' style='cursor:default' title='{$span->frameName}'>" . mb_substr($text, $span->startChar, $span->endChar - $span->startChar + 1) . "</span>";
                $i = $span->endChar + 1;
            }
        }
        $decorated = $decorated . mb_substr($text, $i);
        return $decorated;
    }
}
