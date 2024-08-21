<?php

namespace App\Services;

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
            if (User::isManager($user)) {
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

    public static function getObjectsByDocument(int $idDocument): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $result = Criteria::table("view_annotation_dynamic")
//            ->orWhere(function (Builder $query) {
//                $query->where('idLanguage', $idLanguage)
//                    ->whereNull('idLanguage');
//            })
            ->where("idLanguage", "left",  $idLanguage)
            ->where("idDocument", $idDocument)
            ->select("idDynamicObject", "name", "startFrame", "endFrame", "startTime", "endTime", "status", "origin", "idAnnotationLU", "idLU", "lu", "idAnnotationFE", "idFrameElement", "idFrame", "frame", "fe", "color")
            ->orderBy("startFrame")
            ->orderBy("endFrame")
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
            // if idDynamicObject != null : object annotation (fe/lu)
            $idDynamicObject = $data->idDynamicObject;
            $usertask = self::getCurrentUserTask($data->idDocument);
            $do = Criteria::byId("dynamicobject", "idDynamicObject", $data->idDynamicObject);
            Criteria::table("annotation")
                ->where("idAnnotationObject", $do->idAnnotationObject)
                ->delete();
            if ($data->idFrameElement) {
                $fe = Criteria::byId("frameelement", "idFrameElement", $data->idFrameElement);
                $data = json_encode([
                    'idEntity' => $fe->idEntity,
                    'idAnnotationObject' => $do->idAnnotationObject,
                    'relationType' => 'rel_annotation',
                    'idUserTask' => $usertask->idUserTask
                ]);
                $idAnnotation = Criteria::function("annotation_create(?)", [$data]);
                Timeline::addTimeline("annotation", $idAnnotation, "C");
            }
            if ($data->idLU) {
                $lu = Criteria::byId("lu", "idLU", $data->idLU);
                $data = json_encode([
                    'idEntity' => $lu->idEntity,
                    'idAnnotationObject' => $do->idAnnotationObject,
                    'relationType' => 'rel_annotation',
                    'idUserTask' => $usertask->idUserTask
                ]);
                $idAnnotation = Criteria::function("annotation_create(?)", [$data]);
                Timeline::addTimeline("annotation", $idAnnotation, "C");
            }
        }
        return $idDynamicObject;
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
        $result = [];
        $sentences = DynamicSentenceMM::listByDocument($idDocument);
        $annotation = collect(ViewAnnotationSet::listFECEByIdDocument($idDocument))->groupBy('idSentence')->all();
        foreach ($sentences as $sentence) {
            if (isset($annotation[$sentence->idSentence])) {
                $sentence->decorated = self::decorateSentence($sentence->text, $annotation[$sentence->idSentence]);
            } else {
                $targets = [];
                $sentence->decorated = self::decorateSentence($sentence->text, $targets);
            }
            $result[] = $sentence;
        }
        return $result;
    }

    public static function decorateSentence($sentence, $labels): string
    {
        $decorated = "";
        $ni = "";
        $i = 0;
        foreach ($labels as $label) {
            $style = 'background-color:#' . $label->rgbBg . ';color:#' . $label->rgbFg . ';';
            if ($label->startChar >= 0) {
                $title = isset($label->frameName) ? " title='{$label->frameName}' " : '';
                $decorated .= mb_substr($sentence, $i, $label->startChar - $i);
                $decorated .= "<span {$title} style='{$style}'>" . mb_substr($sentence, $label->startChar, $label->endChar - $label->startChar + 1) . "</span>";
                $i = $label->endChar + 1;
            } else { // null instantiation
                $ni .= "<span style='{$style}'>" . $label->instantiationType . "</span> " . $decorated;
            }
        }
        return $ni . $decorated . mb_substr($sentence, $i);
    }


}
