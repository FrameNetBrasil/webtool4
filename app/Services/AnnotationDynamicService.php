<?php

namespace App\Services;

use App\Database\Criteria;
use App\Repositories\AnnotationSet;
use App\Repositories\Base;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\DynamicSentenceMM;
use App\Repositories\Label;
use App\Repositories\LayerType;
use App\Repositories\Sentence;
use App\Repositories\UserAnnotation;
use App\Repositories\ViewAnnotationSet;
use App\Repositories\WordForm;
use Illuminate\Support\Facades\DB;
use Orkester\Manager;


class AnnotationDynamicService
{
    public static function getObjectsByDocument(int $idDocument): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $result = Criteria::table("view_annotation_dynamic")
            ->where("idLanguage", $idLanguage)
            ->where("idDocument", $idDocument)
            ->select("idDynamicObject","name","startFrame","endFrame","startTime","endTime","status","origin","idAnnotationLU","idLU","lu","idAnnotationFE","idFrameElement","idFrame","frame","fe","color")
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
