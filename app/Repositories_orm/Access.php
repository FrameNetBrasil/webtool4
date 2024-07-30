<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Access extends Repository {

    public static function map(ClassMap $classMap): void
    {
        $classMap->table('access')
            ->attribute('idAccess', key: Key::PRIMARY)
            ->attribute('rights', type: Type::INTEGER)
            ->attribute('idGroup', type: Type::INTEGER)
            ->attribute('idTransaction', type: Type::INTEGER)
            ->associationOne('group', model: 'Group', key: 'idGroup')
            ->associationOne('transaction', model: 'Transaction', key: 'idTransaction');
    }
    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idAccess');
        if ($filter->idAccess){
            $criteria->where("idAccess LIKE '{$filter->idAccess}%'");
        }
        return $criteria;
    }
}
