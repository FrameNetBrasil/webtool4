<?php

namespace App\Services;

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
        $cmd = <<<SQL

select do.idDynamicObject as idObject,
       do.startFrame,
       do.endFrame,
       do.startTime,
       do.endTime,
       do.status,
       do.origin,
       do.idLU,
       IF(do.idLU,concat(entries_flu.name,'.',lu.name),'') as lu,
       do.idFrameElement,
       fe.idFrame,
       IFNULL(entries_f.name, '')  as frame,
       do.idFrameElement as idFE,
       IFNULL(entries_fe.name, '') as fe,
       color.rgbBg as color
from dynamicobject do
         left join view_frameelement as fe on do.idFrameElement = fe.idFrameElement
         left join view_ frame as f on fe.idFrame = f.idFrame
         left join color on fe.idColor = color.idColor
         left join view_lu on do.idLU = lu.idLU
         left join view_frame flu on lu.idFrame = flu.idFrame
where (do.idDocument = {$idDocument})
and ((entries_f.idLanguage = {$idLanguage}) or (entries_f.idLanguage is null))
and ((entries_fe.idLanguage = {$idLanguage}) or (entries_fe.idLanguage is null))
and ((entries_flu.idLanguage = {$idLanguage}) or (entries_flu.idLanguage is null))
order by do.startTime asc,do.endTime asc

SQL;
        $result = DB::select($cmd);
        $oMM = [];
        foreach ($result as $i => $row) {
            $oMM[] = $row->idObjectMM;
        }
        $bboxes = [];
        if (count($result) > 0) {
            $bboxList = DynamicBBoxMM::listByObjectsMM($oMM)->all();
            foreach ($bboxList as $bbox) {
                $bboxes[$bbox->idObjectMM][] = $bbox;
            }
        }
        $objects = [];
        foreach ($result as $i => $row) {
            $row->order = $i + 1;
            $row->bboxes = $bboxes[$row->idObjectMM] ?? [];
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
