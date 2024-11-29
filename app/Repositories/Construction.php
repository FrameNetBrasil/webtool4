<?php

namespace App\Repositories;

use App\Database\Criteria;
use App\Services\AppService;

class Construction
{
    public static function byId(int $id): object
    {
        return Criteria::byFilterLanguage("view_construction", ['idConstruction', '=', $id])->first();
    }
    public static function byIdEntity(int $idEntity): object
    {
        return Criteria::byFilterLanguage("view_construction", ['idEntity', '=', $idEntity])->first();
    }
    public static function listRelations(int $idEntity)
    {
        return Criteria::table("view_relation")
            ->join("view_semantictype", "view_relation.idEntity2", "=", "view_semantictype.idEntity")
            ->filter([
                ["view_relation.idEntity1", "=", $idEntity],
                ["view_relation.relationType", "=", "rel_hassemtype"],
                ["view_semantictype.idLanguage", "=", AppService::getCurrentIdLanguage()]
            ])->orderBy("view_semantictype.name")->all();
    }

    public static function listChildren(int $idEntity)
    {
        $rows = Criteria::table("view_relation")
            ->join("view_semantictype", "view_relation.idEntity1", "=", "view_semantictype.idEntity")
            ->filter([
                ["view_relation.idEntity2", "=", $idEntity],
                ["view_relation.relationType", "=", "rel_subtypeof"],
                ["view_semantictype.idLanguage", "=", AppService::getCurrentIdLanguage()]
            ])->select("view_semantictype.idSemanticType", "view_semantictype.idEntity", "view_semantictype.name","view_relation.idEntityRelation")
            ->orderBy("view_semantictype.name")->all();
        foreach($rows as $row) {
            $row->n = Criteria::table("view_relation")
                ->where("view_relation.idEntity2", "=", $row->idEntity)
                ->where("view_relation.relationType", "=", "rel_subtypeof")
                ->count();
        }
        return $rows;
    }

    public static function listTree(string $cxn)
    {
        $rows = Criteria::table("view_construction as cxn")
            ->join("language as l", "cxn.cxIdLanguage", "=", "l.idLanguage")
            ->where("cxn.name", "startswith", $cxn)
            ->where("cxn.idLanguage", "=", AppService::getCurrentIdLanguage())
            ->select("cxn.idConstruction", "cxn.idEntity", "cxn.name","l.language")
            ->orderBy("cxn.name.name")->all();
        return $rows;
    }

    public static function listRoots() : array
    {
        $criteriaER = Criteria::table("view_relation")
            ->select('idEntity1')
            ->where("relationType", "=", 'rel_subtypeof');
        $rows = Criteria::table("view_semantictype")
            ->where("view_semantictype.idEntity", "NOT IN", $criteriaER)
            ->filter([
                ['view_semantictype.idLanguage', '=', AppService::getCurrentIdLanguage()],
            ])->select("view_semantictype.idSemanticType", "view_semantictype.idEntity", "view_semantictype.name")
            ->orderBy("view_semantictype.name")->all();
        foreach($rows as $row) {
            $row->n = Criteria::table("view_relation")
                ->where("view_relation.idEntity2", "=", $row->idEntity)
                ->where("view_relation.relationType", "=", "rel_subtypeof")
                ->count();
        }
        return $rows;
    }

}

