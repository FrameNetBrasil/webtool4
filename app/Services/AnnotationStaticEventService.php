<?php

namespace App\Services;

use App\Database\Criteria;
use App\Repositories\AnnotationSet;
use App\Repositories\Base;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\FrameElement;
use App\Repositories\StaticAnnotationMM;
use App\Repositories\StaticBBoxMM;
use App\Repositories\StaticObjectSentenceMM;
use App\Repositories\StaticSentenceMM;
use App\Repositories\Task;
use App\Repositories\User;
use App\Repositories\UserAnnotation;
use App\Repositories\Timeline;
use Illuminate\Support\Facades\DB;
use Orkester\Persistence\Model;
use Orkester\Manager;
use Orkester\Persistence\Repository;


class AnnotationStaticEventService
{

    public static function listSentences(int $idDocument): array
    {
        $sentences = Criteria::table("sentence")
            ->join("view_document_sentence as ds", "sentence.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->join("view_image_sentence as is", "sentence.idSentence", "=", "is.idSentence")
            ->join("image as i", "is.idImage", "=", "i.idImage")
            ->where("d.idDocument", $idDocument)
            ->select("sentence.idSentence", "sentence.text", "i.name as imageName", "ds.idDocumentSentence")
            ->distinct()
            ->orderBy("ds.idDocumentSentence")
            ->limit(1500)
            ->get()->keyBy("idSentence")->all();
        return $sentences;
    }

    public static function getPrevious(int $idDocument, int $idDocumentSentence)
    {
        $i = Criteria::table("view_document_sentence")
            ->where("idDocument", "=", $idDocument)
            ->where("idDocumentSentence", "<", $idDocumentSentence)
            ->max('idDocumentSentence');
        return $i ?? null;
    }

    public static function getNext(int $idDocument, int $idDocumentSentence)
    {
        $i = Criteria::table("view_document_sentence")
            ->where("idDocument", "=", $idDocument)
            ->where("idDocumentSentence", ">", $idDocumentSentence)
            ->min('idDocumentSentence');
        return $i ?? null;
    }

    private static function getCurrentUserTask(int $idDocument): object|null
    {
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        // get usertask for this document
        $usertask = Criteria::table("usertask_document")
            ->join("usertask as ut", "ut.idUserTask", "=", "usertask_document.idUserTask")
            ->where("usertask_document.idDocument", $idDocument)
            ->where("ut.idUser", $idUser)
            ->select("ut.idUserTask","ut.idTask")
            ->first();
        if (empty($usertask)) { // usa a task -> dataset -> corpus -> document
//            if (User::isManager($user)) {
//                $usertask = Criteria::table("usertask_document")
//                    ->join("usertask as ut", "ut.idUserTask", "=", "usertask_document.idUserTask")
//                    ->where("usertask_document.idDocument", $idDocument)
//                    ->where("ut.idUser", -2)
//                    ->select("ut.idUserTask","ut.idTask")
//                    ->first();
//            } else {
                $usertask = null;
//            }
        }
        return $usertask;
    }

    public static function getObjectsForAnnotationImage(int $idDocument, int $idSentence): array
    {
        $usertask = self::getCurrentUserTask($idDocument);
        if (is_null($usertask)) {
            return [
                'objects' => [],
                'frames' => []
            ];
        }
        $task = Task::byId($usertask->idTask);
        debug($task);
        //objects for document_sentence
        $criteria = Criteria::table("view_staticobject_textspan")
            ->where("idDocument", $idDocument)
            ->where("idSentence", $idSentence);
        $idLanguage = AppService::getCurrentIdLanguage();
        $objects = $criteria->get()->keyBy('idStaticObject')->all();
        $idObject = 1;
        foreach ($objects as $i => $object) {
            $bboxes = Criteria::table("view_staticobject_boundingbox")
                ->where("idStaticObject", $i)
                ->select("x", "y", "width", "height")
                ->all();
//            debug($bboxes);
            $object->idObject = $idObject++;
            $object->bboxes = $bboxes;
        }
        $frames = [];
        foreach ($objects as $object) {
//            debug($object);
            $annotations = Criteria::table("view_annotation as a")
                ->join("view_frameelement as fe", "a.idEntity", "=", "fe.idEntity")
                ->where("a.idAnnotationObject", "=", $object->idAnnotationObject)
                ->where("a.idUserTask", "=", $usertask->idUserTask)
                ->where("fe.idLanguage", "=", $idLanguage)
                ->select([
                    "a.idAnnotation",
                    "fe.idFrame",
                    "fe.frameName as frameName",
                    "fe.idFrameElement"
                ])
                ->orderBy("fe.idFrame")
                ->all();
            foreach ($annotations as $annotation) {
                if (!isset($frames[$annotation->idFrame])) {
                    $frames[$annotation->idFrame] = [
                        'idFrame' => $annotation->idFrame,
                        'name' => $annotation->frameName,
                        'objects' => []
                    ];
                }
                if (is_null($annotation->idFrameElement)) {
                    $annotation->idFrameElement = -1;
                }
                $frames[$annotation->idFrame]['objects'][$object->idStaticObject] = $annotation;
            }
        }
        return [
            'type' => $task->type,
            'objects' => $objects,
            'frames' => $frames
        ];
    }

    public static function deleteAnnotationByFrame(int $idDocumentSentence, int $idFrame)
    {
        DB::transaction(function () use ($idDocumentSentence, $idFrame) {
            $idLanguage = AppService::getCurrentIdLanguage();
            $ds = Criteria::table("view_document_sentence")
                ->where("idDocumentSentence", $idDocumentSentence)
                ->select("idDocument","idSentence")
                ->first();
            $usertask = self::getCurrentUserTask($ds->idDocument);
            if (is_null($usertask)) {
                throw new \Exception("UserTask not found!");
            }
            $criteria = Criteria::table("view_staticobject_textspan")
                ->where("idDocument", $ds->idDocument)
                ->where("idSentence", $ds->idSentence);
            $objects = $criteria->get()->pluck('idAnnotationObject')->all();
            $annotations = Criteria::table("annotation as a")
                ->join("view_frameelement as fe", "a.idEntity", "=", "fe.idEntity")
                ->where("a.idUserTask", "=", $usertask->idUserTask)
                ->where("fe.idFrame", "=", $idFrame)
                ->where("fe.idLanguage", "=", $idLanguage)
                ->whereIn("a.idAnnotationObject", $objects)
                ->select("a.idAnnotation")
                ->get()->pluck('idAnnotation')->all();
            Criteria::table("annotation")
                ->whereIn("idAnnotation", $annotations)
                ->delete();
        });
    }

    public static function updateAnnotation(int $idDocumentSentence, int $idFrame, array $staticObjectFEs)
    {
        DB::transaction(function () use ($idDocumentSentence, $idFrame, $staticObjectFEs) {
            $idLanguage = AppService::getCurrentIdLanguage();
            $relation = Criteria::table("view_document_sentence")
                ->where("idDocumentSentence", $idDocumentSentence)
                ->select("idDocument")
                ->first();
            $usertask = self::getCurrentUserTask($relation->idDocument);
            if (is_null($usertask)) {
                throw new \Exception("UserTask not found!");
            }
            $idStaticObject = array_keys($staticObjectFEs[$idFrame]);
            $annotations = Criteria::table("annotation as a")
                ->join("view_frameelement as fe", "a.idEntity", "=", "fe.idEntity")
                ->where("a.idAnnotationObject", "IN", $idStaticObject)
                ->where("a.idUserTask", "=", $usertask->idUserTask)
                ->where("fe.idFrame", "=", $idFrame)
                ->where("fe.idLanguage", "=", $idLanguage)
                ->select("a.idAnnotation")
                ->get()->pluck('idAnnotation')->all();
            Criteria::table("annotation")
                ->whereIn("idAnnotation", $annotations)
                ->delete();
            foreach ($staticObjectFEs[$idFrame] as $idStaticObject => $idFrameElement) {
                if ($idFrameElement != -1) {
                    $fe = Criteria::table("frameelement")
                        ->where("idFrameElement", $idFrameElement)
                        ->first();
                    $data = json_encode([
                        'idEntity' => $fe->idEntity,
                        'idAnnotationObject' => $idStaticObject,
                        'relationType' => 'rel_annotation',
                        'idUserTask' => $usertask->idUserTask
                    ]);
                    $idAnnotation = Criteria::function("annotation_create(?)", [$data]);
                    Timeline::addTimeline("annotation", $idAnnotation, "C");
                }
            }
        });
    }


}
