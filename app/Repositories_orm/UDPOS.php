<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class UDPOS extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('udpos')
            ->attribute('idUDPOS', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('name', reference: 'entity.entries.name')
            ->attribute('description', reference: 'entity.entries.description')
            ->attribute('idLanguage', reference: 'entity.entries.idLanguage')
            ->associationOne('entity', model: 'Entity')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity');
    }
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('POS');
        if ($filter->POS) {
            $criteria->where("POS LIKE '{$filter->POS}%'");
        }
        return $criteria;
    }

    public function listForLookup($type)
    {
        $whereType = ($type == '*') ? '' : "WHERE (t.entry = '{$type}')";
        $cmd = <<<HERE
        SELECT u.idUDPOS, u.POS
        FROM UDPOS u
        {$whereType}
        ORDER BY u.POS

HERE;
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function listForLookupEntity($type)
    {
        $whereType = ($type == '*') ? '' : "WHERE (t.entry = '{$type}')";
        $cmd = <<<HERE
        SELECT u.idUDPOS, u.POS, u.idEntity
        FROM UDPOS u
        {$whereType}
        ORDER BY u.POS

HERE;
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

}
