<?php

namespace App\Services;

use App\Repositories\Base;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\StaticAnnotationMM;
use App\Repositories\StaticBBoxMM;
use App\Repositories\StaticObjectSentenceMM;
use App\Repositories\StaticSentenceMM;
use App\Repositories\UserAnnotation;
use App\Repositories\Timeline;
use Orkester\Persistence\Model;
use Orkester\Manager;
use Orkester\Persistence\Repository;


class AnnotationStaticFrameMode1Service
{
    public static function getPrevious(int $idStaticSentenceMM)
    {
        $cmd = "
select max(sm.idStaticSentenceMM) i
from StaticSentenceMM sm
where (idStaticSentenceMM < {$idStaticSentenceMM})
and (idDocument = (select idDocument from StaticSentenceMM where idStaticSentenceMM = {$idStaticSentenceMM}))
        ";
        $first = Repository::query($cmd)[0];
        return $first->i;
    }

    public static function getNext(int $idStaticSentenceMM)
    {
        $cmd = "
select min(sm.idStaticSentenceMM) i
from StaticSentenceMM sm
where (idStaticSentenceMM > {$idStaticSentenceMM})
and (idDocument = (select idDocument from StaticSentenceMM where idStaticSentenceMM = {$idStaticSentenceMM}))
        ";
        $first = Repository::query($cmd)[0];
        return $first->i;
    }

    public static function getObjectsForAnnotationImage(int $idStaticSentenceMM): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = StaticObjectSentenceMM::getCriteria()
            ->where("idStaticSentenceMM", "=", $idStaticSentenceMM)
            ->where("idStaticObjectMM", "<>", -1)
            ->select([
                "idStaticObjectSentenceMM",
                "idStaticObjectMM",
                "name",
                "staticObjectMM.idFlickr30kEntitiesChain",
                "startWord",
                "endWord"
            ])
            ->orderBy("idStaticObjectSentenceMM");
        $objects = $criteria->get()->keyBy('idStaticObjectSentenceMM')->all();
        foreach ($objects as $i => $object) {
            $idStaticObjectMM = $object->idStaticObjectMM;
            $boxes = StaticBBoxMM::listByObjectMM($idStaticObjectMM)->all();
            debug($boxes);
            $objects[$i]->bboxes = $boxes;
        }
        $frames = [];
        foreach ($objects as $object) {
            $criteria = StaticAnnotationMM::getCriteria()
                ->where("idStaticObjectSentenceMM", "=", $object->idStaticObjectSentenceMM)
                ->where("frame.idLanguage", "=", $idLanguage)
                ->select([
                    "idStaticAnnotationMM",
                    "idStaticObjectSentenceMM",
                    "idFrame",
                    "frame.name as frameName",
                    "idFrameElement"
                ]);
            $criteria->order("idFrame");
            $annotations = $criteria->all();

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
                $frames[$annotation->idFrame]['objects'][$annotation->idStaticObjectSentenceMM] = $annotation;
            }
        }
        return [
            'objects' => $objects,
            'frames' => $frames
        ];
    }

    public static function hasFrame(int $idStaticSentenceMM, int $idFrame)
    {
        // verifica se o frame já não foi adicionado antes
        $criteria = StaticAnnotationMM::getCriteria()
            ->where("staticObjectSentenceMM.idStaticSentenceMM", "=", $idStaticSentenceMM)
            ->where("idFrame", "=", $idFrame)
            ->select('*');
        $annotations = $criteria->all();
        return (count($annotations) > 0);
    }

    public static function deleteAnnotationByFrame(int $idStaticSentenceMM, int $idFrame)
    {
        $criteria = StaticAnnotationMM::getCriteria()
            ->where("staticObjectSentenceMM.idStaticSentenceMM", "=", $idStaticSentenceMM)
            ->where("idFrame", "=", $idFrame)
            ->select('idStaticAnnotationMM');
        $annotations = $criteria->get()->pluck('idStaticAnnotationMM')->all();
        if (!empty($annotations)) {
            StaticAnnotationMM::getCriteria()
                ->where("idStaticAnnotationMM", "IN", $annotations)
                ->delete();
        }
    }

    public static function updateObjectSentenceFE(int $idStaticSentenceMM, int $idFrame, array $staticObjectSentenceFEs)
    {
        self::deleteAnnotationByFrame($idStaticSentenceMM, $idFrame);
        foreach ($staticObjectSentenceFEs as $objects) {
            foreach ($objects as $idStaticObjectSentenceMM => $idFrameElement) {
                if ($idFrameElement == -1) {
                    $idFrameElement = null;
                }
                $data = (object)[
                    'idStaticAnnotationMM' => null,
                    'idStaticObjectSentenceMM' => $idStaticObjectSentenceMM,
                    'idFrameElement' => $idFrameElement,
                    'idFrame' => $idFrame,
                ];
                $idStaticAnnotationMM = StaticAnnotationMM::save($data);
                Timeline::addTimeline("staticannotationmm", $idStaticAnnotationMM, "C");
            }
        }
    }


}
