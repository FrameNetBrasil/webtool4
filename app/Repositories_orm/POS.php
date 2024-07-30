<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class POS extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('pos')
            ->attribute('idPOS', key: Key::PRIMARY)
            ->attribute('POS')
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->associationOne('entity', model: 'Entity')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity');
    }
    /*
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idPOS');
        if ($filter->idPOS) {
            $criteria->where("idPOS LIKE '{$filter->idPOS}%'");
        }
        if ($filter->POS) {
            $criteria->where("POS = upper('{$filter->POS}')");
        }
        return $criteria;
    }

    public function listForSelection()
    {
        $criteria = $this->getCriteria()
            ->select(['idPOS','POS'])
            ->orderBy('POS');
        return $criteria;
    }

    public function getByPOS($POS)
    {
        $filter = (object)[
            'POS' => $POS
        ];
        $criteria = $this->listByFilter($filter);
        $this->retrieveFromCriteria($criteria);
    }

    public function save(): ?int
    {
        //Base::entityTimelineSave($this->getIdEntity());
        parent::save();
        Timeline::addTimeline("pos", $this->getId(), "S");
        return $this->getId();
    }

    public function delete()
    {
        Timeline::addTimeline("pos", $this->getId(), "D");
//        Base::entityTimelineDelete($this->getIdEntity());
        parent::delete();
    }
    */


}
