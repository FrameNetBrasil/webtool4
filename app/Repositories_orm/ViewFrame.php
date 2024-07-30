<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewFrame extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('view_frame')
            ->attribute('idFrame', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('active', type: Type::INTEGER)
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity')
            ->associationMany('lus', model: 'LU', keys: 'idFrame')
            ->associationMany('fes', model: 'FrameElement', keys: 'idFrame')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity')
            ->associationMany('relations', model: 'Relation', keys: 'idEntity:idEntity1')
            ->associationMany('inverseRelations', model: 'Relation', keys: 'idEntity:idEntity2');
    }
    public static function listByFilter($filter): Criteria
    {
        $listBy = $filter->listBy ?? '';
        $select = ['idFrame','entry','active','idEntity','name','description'];
        if ($listBy == 'cluster') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as cluster';
        }
        if ($listBy == 'type') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as type';
        }
        if ($listBy == 'domain') {
            $listBySelect = ',toRelations.toSemanticType.entries.name as domain';
        }
        $criteria = static::getCriteria()
            ->select($select)
            ->orderBy('entries.name');
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria->where("idLanguage", "=", $idLanguage);

        if ($filter?->idFrame ?? null) {
            $criteria->where("idFrame","=",$filter->idFrame);
        }
        if ($filter?->idEntity ?? null) {
            $criteria->where("idEntity","=",$filter->idEntity);
        }
        if ($filter?->frame ?? null) {
            $criteria->where("name","startswith",$filter->frame);
        }
        if ($filter?->lu ?? null) {
            $criteria->distinct(true);
            $criteria->where("lus.name","startswith",$filter->lu);
            $criteria->where("lus.idLanguage","=",$idLanguage);
        }
        if ($filter?->idLU ?? null) {
            if (is_array($filter->idLU)) {
                $criteria->where("lus.idLU", "IN", $filter->idLU);
            } else {
                $criteria->where("lus.idLU","=",$filter->idLU);
            }
        }
        if ($filter?->fe ?? null) {
            $criteria->distinct(true);
            $criteria->where("fes.idLanguage","=",AppService::getCurrentIdLanguage());
            $criteria->where("fes.name","startswith",$filter->fe);
        }
        if ($filter?->idFramalDomain ?? null) {
            $criteria->where('relations.relationType.entry', '=', "rel_framal_domain");
            $criteria->where("relations.semanticType2.idSemanticType","=", $filter->idFramalDomain);
        }
        if ($filter?->idFramalType ?? null) {
            $criteria->where('relations.relationType.entry', '=', "rel_framal_type");
            $criteria->where("relations.semanticType2.idSemanticType","=", $filter->idFramalType);
        }
        return $criteria;
    }


}
