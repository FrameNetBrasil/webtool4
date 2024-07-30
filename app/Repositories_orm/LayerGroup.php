<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class LayerGroup extends Repository {

    public static function map(ClassMap $classMap): void
    {

        $classMap->table('layergroup')
            ->attribute('idLayerGroup', key: Key::PRIMARY)
            ->attribute('name')
            ->associationMany('layerType', model: 'LayerType', keys: 'idLayerGroup');
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLayerGroup');
        if ($filter->idLayerGroup){
            $criteria->where("idLayerGroup LIKE '{$filter->idLayerGroup}%'");
        }
        return $criteria;
    }

    public function listAll(){
        $criteria = $this->getCriteria()->select('idLayerGroup, name')->orderBy('name');
        return $criteria;
    }
}
