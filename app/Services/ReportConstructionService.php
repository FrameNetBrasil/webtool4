<?php

namespace App\Services;

use App\Database\Criteria;
use App\Repositories\Construction;

class ReportConstructionService
{

    public static function report(int|string $idConstruction, string $lang = ''): array
    {
        $report = [];
        if ($lang != '') {
            $language = Criteria::byId("language", "language", $lang);
            $idLanguage = $language->idLanguage;
            AppService::setCurrentLanguage($idLanguage);
        } else {
            $idLanguage = AppService::getCurrentIdLanguage();
        }
        if (is_numeric($idConstruction)) {
            $cxn = Construction::byId($idConstruction);
        } else {
            $cxn = Criteria::table("view_construction")
                ->where("name", $idConstruction)
                ->where("idLanguage", $idLanguage)
                ->first();
        }
        $report['construction'] = $cxn;
        $report['ce'] = [];//self::getFEData($frame, $idLanguage);
        //$report['construction']->description = self::decorate($cxn->description, $report['ce']['styles']);
        $report['concepts'] = self::getConcepts($cxn->idEntity);
        $report['evokes'] = self::getEvokes($cxn->idEntity);
        $report['relations'] = self::getRelations($cxn);
        return $report;
    }

    public static function getFEData($frame, int $idLanguage): array
    {
        $fes = Criteria::table("view_frameelement")
            ->where("idLanguage", "=", $idLanguage)
            ->where("idFrame", "=", $frame->idFrame)
            ->all();
        $core = [];
        $coreun = [];
        $coreper = [];
        $coreext = [];
        $noncore = [];
        $feByEntry = [];
        foreach ($fes as $fe) {
            $feByEntry[$fe->entry] = $fe;
        }
        //$config = config('webtool.relations');
        $relations = RelationService::listRelationsFEInternal($frame->idFrame);
        $relationsByIdFE = [];
        foreach ($relations as $relation) {
            $relationsByIdFE[$relation->feIdFrameElement][] = [
                'relatedFEName' => $relation->relatedFEName,
                'relatedFEIdColor' => $relation->relatedFEIdColor,
                'name' => $relation->name,
                'color' => $relation->color,
            ];
        }
        $semanticTypes = RelationService::listFEST($frame->idFrame);
        $styles = [];
        foreach ($fes as $fe) {
            $styles[strtolower($fe->name)] = "color_{$fe->idColor}";
        }
        foreach ($fes as $fe) {
            $fe->relations = $relationsByIdFE[$fe->idFrameElement] ?? [];
            $fe->lower = strtolower($fe->name);
            $fe->description = self::decorate($fe->description, $styles);
            if ($fe->coreType == 'cty_core') {
                $core[] = $fe;
            } else if ($fe->coreType == 'cty_core-unexpressed') {
                $coreun[] = $fe;
            } else {
                if ($fe->coreType == 'cty_peripheral') {
                    $coreper[] = $fe;
                }
                if ($fe->coreType == 'cty_extra-thematic') {
                    $coreext[] = $fe;
                }
                $noncore[] = $fe;
            }
        }
        return [
            'styles' => $styles,
            'core' => $core,
            'core_unexpressed' => $coreun,
            'peripheral' => $coreper,
            'extra_thematic' => $coreext,
            'noncore' => $noncore,
            'semanticTypes' => $semanticTypes
        ];
    }

    public static function getFECoreSet($frame): string
    {
        $feCoreSet = Frame::listFECoreSet($frame->idFrame);
        $s = [];
        foreach ($feCoreSet as $i => $cs) {
            $s[$i] = "{" . implode(',', $cs) . "}";
        }
        $result = implode(', ', $s);
        return $result;
    }

    public static function getRelations($cxn): array
    {
        $relations = [];
        $result = RelationService::listRelationsCxn($cxn->idConstruction);
        foreach ($result as $row) {
            $relationName = $row->relationType . '|' . $row->name;
            $relations[$relationName][$row->idCxnRelated] = [
                'idEntityRelation' => $row->idEntityRelation,
                'idConstruction' => $row->idCxnRelated,
                'name' => $row->related,
                'color' => $row->color
            ];
        }
        ksort($relations);
        return $relations;
    }

    public static function getConcepts(int $idEntity): array
    {
        $concepts = Criteria::table("view_relation as r")
            ->join("view_concept as c", "r.idEntity2", "=", "c.idEntity")
            ->where("r.idEntity1", $idEntity)
            ->where("r.relationType","rel_hasconcept")
            ->where("c.idLanguage", AppService::getCurrentIdLanguage())
            ->select("r.relationType","c.idConcept","c.name")
            ->orderBy("c.name")
            ->all();
        return $concepts;
    }

    public static function getEvokes(int $idEntity): array
    {
        $evokes = Criteria::table("view_relation as r")
            ->join("view_frame as f", "r.idEntity2", "=", "f.idEntity")
            ->where("r.idEntity1", $idEntity)
            ->where("r.relationType","rel_evokes")
            ->where("f.idLanguage", AppService::getCurrentIdLanguage())
            ->select("r.relationType","f.idFrame","f.name")
            ->orderBy("f.name")
            ->all();
        return $evokes;
    }

    public static function decorate($description, $styles)
    {
        $sentence = utf8_decode($description);
        $decorated = preg_replace_callback(
            "/\#([^\s\.\,\;\?\!\']*)/i",
            function ($matches) use ($styles) {
                $m = substr($matches[0], 1);
                $l = strtolower($m);
                foreach ($styles as $fe => $s) {
                    if(utf8_encode($l) ==  $fe) {
                        return "<span class='{$s}'>{$m}</span>";
                    }
                }
                return $m;
            },
            $sentence
        );
        $partial = utf8_encode($decorated);
        $final = preg_replace_callback(
            "/\[([^\]]*)\]/i",
            function ($matches) use ($styles) {
                $m = substr($matches[0], 1, -1);
                $l = strtolower($m);
                foreach ($styles as $fe => $s) {
                    if (str_contains(utf8_encode($l), '|target')) {
                        $m = substr($m, 0, strpos($m, '|'));
                        return "<span class='color_target'>{$m}</span>";
                    } else {
                        if (str_contains(utf8_encode($l), '|' . $fe)) {
                            $m = substr($m, 0, strpos($m, '|'));
                            return "<span class='{$s}'>{$m}</span>";
                        }
                    }
                }
                return $m;
            },
            $partial
        );
        return $final;
    }


}
