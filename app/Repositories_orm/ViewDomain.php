<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewDomain extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('view_domain')
            ->attribute('idDomain', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->attribute('idEntityRel', key: Key::FOREIGN)
            ->attribute('entityType')
            ->attribute('nameRel');
    }
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idDomain, entry, idEntity, name, idEntityRel, entityType, idLanguage, entryRel, nameRel')->orderBy('name, nameRel');
        $idLanguage = \Manager::getSession()->idLanguage;
        $criteria->where("idLanguage = {$idLanguage}");
        if ($filter->idDomain) {
            $criteria->where("idDomain = {$filter->idDomain}");
        }
        if ($filter->idEntity) {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        if ($filter->idEntityRel) {
            $criteria->where("idEntityRel = {$filter->idEntityRel}");
        }
        if ($filter->entityType) {
            $criteria->where("entityType = '{$filter->entityType}'");
        }
        return $criteria;
    }

}
