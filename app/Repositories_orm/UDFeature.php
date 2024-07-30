<?php
namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class UDFeature extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('udfeature')
            ->attribute('idUDFeature', key: Key::PRIMARY)
            ->attribute('udFeature')
            ->attribute('info')
            ->attribute('type', reference: 'typeinstance.typeInstance')
            ->associationOne('entity', model: 'Entity')
            ->associationOne('typeinstance', model: 'TypeInstance');
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idTypeInstance');
        if ($filter->idTypeInstance) {
            $criteria->where("idTypeInstance LIKE '{$filter->idTypeInstance}%'");
        }
        return $criteria;
    }

    public function listForLookup($type)
    {
        $whereType = ($type == '*') ? '' : "WHERE (t.entry = '{$type}')";
        $cmd = <<<HERE
        SELECT u.idUDRelation, u.info
        FROM UDRelation u
        JOIN TypeInstance t on (u.idTypeInstance = t.idTypeInstance)
        {$whereType}
        ORDER BY u.info

HERE;
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

    public function listForLookupEntity($type)
    {
        $whereType = ($type == '*') ? '' : "WHERE (t.entry = '{$type}')";
        $cmd = <<<HERE
        SELECT u.idEntity, concat(t.info, ' - ', u.info) as info
        FROM UDFeature u
        JOIN TypeInstance t on (u.idTypeInstance = t.idTypeInstance)
        {$whereType}
        ORDER BY 2

HERE;
        $query = $this->getDb()->getQueryCommand($cmd);
        return $query;
    }

}
