<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewConstruction extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('view_construction')
            ->attribute('idConstruction', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('active', type: Type::INTEGER)
            ->attribute('idLanguage', type: Type::INTEGER)
            ->attribute('idEntity', key: Key::FOREIGN)
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity')
            ->associationOne('entity', model: 'Entity', key: 'idEentity')
            ->associationMany('ces', model: 'ViewConstructionElement', keys: 'idConstruction')
            ->associationMany('annotationSets', model: 'ViewAnnotationSet', keys: 'idConstruction');
    }
    public function listByFilter($filter)
    {
        ddump('==== listByFilter');
        $criteria = $this->getCriteria()->select('idConstruction, entry, active, idEntity, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idConstruction) {
            $criteria->where("idConstruction = {$filter->idConstruction}");
        }
        if ($filter->construction) {
            $criteria->where("entries.name LIKE '{$filter->construction}%'");
        }
        if ($filter->ce) {
            $criteria->distinct(true);
            $criteria->associationAlias("ces.entries", "ceEntries");
            Base::entryLanguage($criteria,"ceEntries.");
            $criteria->where("ceEntries.name LIKE '{$filter->ce}%'");
        }
        if ($filter->active == '1') {
            $criteria->where("active = 1");
        }
        if ($filter->idEntity != '') {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        if ($filter->cxn) {
            $criteria->where("upper(entries.name) LIKE upper('%{$filter->cxn}%')");
        }
        if ($filter->name) {
            $name = (strlen($filter->name) > 1) ? $filter->name: 'none';
            $criteria->where("upper(entries.name) LIKE upper('{$name}%')");
        }
        if ($filter->idDomain) {
            Base::relation($criteria, 'ViewConstruction', 'Domain', 'rel_hasdomain');
            $criteria->where("Domain.idDomain = {$filter->idDomain}");
        }
        return $criteria;
    }

    public function listByLanguageFilter($filter)
    {
        ddump('==== listByFilter');
        $criteria = $this->getCriteria()->select('idLanguage, language.description language, idConstruction, entry, active, idEntity, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idConstruction) {
            $criteria->where("idConstruction = {$filter->idConstruction}");
        }
        if ($filter->construction) {
            $criteria->where("entries.name LIKE '{$filter->construction}%'");
        }
        if ($filter->ce) {
            $criteria->distinct(true);
            $criteria->associationAlias("ces.entries", "ceEntries");
            Base::entryLanguage($criteria,"ceEntries.");
            $criteria->where("ceEntries.name LIKE '{$filter->ce}%'");
        }
        if ($filter->active == '1') {
            $criteria->where("active = 1");
        }
        if ($filter->idEntity != '') {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        if ($filter->cxn) {
            $criteria->where("upper(entries.name) LIKE upper('%{$filter->cxn}%')");
        }
        if ($filter->name) {
            $name = (strlen($filter->name) > 1) ? $filter->name: 'none';
            $criteria->where("upper(entries.name) LIKE upper('{$name}%')");
        }
        if ($filter->idDomain) {
            Base::relation($criteria, 'ViewConstruction', 'Domain', 'rel_hasdomain');
            $criteria->where("Domain.idDomain = {$filter->idDomain}");
        }
        if ($filter->idLanguage) {
            $criteria->where("idLanguage = {$filter->idLanguage}");
        }
        return $criteria;
    }

    public function listToAnnotation($idLanguage = '')
    {
        $criteria = $this->getCriteria()
//            ->select('idConstruction as idCxn, entries.name as name, count(subcorpus.annotationsets.idAnnotationSet) as quant')
            ->select('idConstruction as idCxn, entries.name as name, count(annotationsets.idAnnotationSet) as quant')
            ->where("entries.idLanguage = {$idLanguage}")
            ->groupBy('view_construction.idConstruction,name')
            ->orderBy('name');
        return $criteria;
    }

}
