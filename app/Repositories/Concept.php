<?php

namespace App\Repositories;

use App\Database\Criteria;
use App\Services\AppService;
use Illuminate\Support\Facades\DB;

class Concept
{
    public static function byId(int $id): object
    {
        return Criteria::byFilterLanguage("view_concept", ['idConcept', '=', $id])->first();
    }

    public static function byIdEntity(int $idEntity): object
    {
        return Criteria::byFilterLanguage("view_concept", ['idEntity', '=', $idEntity])->first();
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

    public static function listTree(string $concept)
    {
        $rows = Criteria::table("view_concept")
            ->filter([
                ["view_concept.name", "startswith", $concept],
                ["view_concept.idLanguage", "=", AppService::getCurrentIdLanguage()]
            ])->select("view_concept.idConcept", "view_concept.idEntity", "view_concept.name","view_concept.type")
            ->orderBy("view_concept.name")->all();
        foreach ($rows as $row) {
            $row->n = Criteria::table("view_relation")
                ->where("view_relation.idEntity2", "=", $row->idEntity)
                ->where("view_relation.relationType", "=", "rel_subtypeof")
                ->count();
        }
        return $rows;
    }

    public static function listChildren(int $idEntity)
    {
        $components = ['rel_constituentof','rel_roleof','rel_attributeof'];
        $criteriaConstituent = Criteria::table("view_relation")
            ->select('idEntity1')
            ->whereIn("relationType", $components);
        $rows = Criteria::table("view_relation")
            ->join("view_concept", "view_relation.idEntity1", "=", "view_concept.idEntity")
            ->filter([
                ["view_relation.idEntity2", "=", $idEntity],
                ["view_relation.relationType", "=", "rel_subtypeof"],
                ["view_concept.idLanguage", "=", AppService::getCurrentIdLanguage()]
            ])->select("view_concept.idConcept", "view_concept.idEntity", "view_concept.name","view_concept.type")
            ->where("view_concept.idEntity", "NOT IN", $criteriaConstituent)
            ->whereNotNull("view_concept.type")
            ->orderBy("view_concept.name")->all();
        foreach ($rows as $row) {
            $row->n = Criteria::table("view_relation")
                ->where("view_relation.idEntity2", "=", $row->idEntity)
                ->where("view_relation.relationType", "=", "rel_subtypeof")
                ->count();
        }
        return $rows;
    }

    public static function listTypeChildren(int $idType): array
    {
        $criteriaER = Criteria::table("view_relation")
            ->select('idEntity1')
            ->where("relationType", "=", 'rel_subtypeof');
        $components = ['rel_constituentof','rel_roleof','rel_attributeof'];
        $criteriaConstituent = Criteria::table("view_relation")
            ->select('idEntity1')
            ->whereIn("relationType", $components);
        $rows = Criteria::table("view_concept")
            ->where("idEntity", "NOT IN", $criteriaER)
            ->where("idEntity", "NOT IN", $criteriaConstituent)
            ->where("idLanguage", AppService::getCurrentIdLanguage())
            ->where("idType", $idType)
            ->where("status","<>","deleted")
            ->whereNotNull("keyword")
            ->whereNotNull("view_concept.type")
            ->select("view_concept.idConcept", "view_concept.idEntity", "view_concept.name","view_concept.type")
            ->orderBy("view_concept.name")->all();
        foreach ($rows as $row) {
            $row->n = Criteria::table("view_relation")
                ->where("view_relation.idEntity2", "=", $row->idEntity)
                ->where("view_relation.relationType", "=", "rel_subtypeof")
                ->count();
        }
        return $rows;
    }

    public static function listRoots(): array
    {
        $rows = Criteria::table("view_type as t")
            ->join("concept as c", "t.idType","=","c.idType")
            ->select("t.idType", "t.name","c.type")
            ->distinct()
            ->where("t.idTypeGroup",16)
            ->whereNotNull("c.type")
            ->orderBy("t.name")
            ->all();
        return $rows;
    }

}

