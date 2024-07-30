<?php

namespace App\Repositories;

use App\Data\Timeline\CreateData;
use Carbon\Carbon;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;
use Orkester\Security\MAuth;

class _Timeline extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('timeline')
            ->attribute('idTimeline', key: Key::PRIMARY)
            ->attribute('tlDateTime', type: Type::DATETIME)
            ->attribute('author')
            ->attribute('operation')
            ->attribute('tableName')
            ->attribute('idTable', field: 'id', type: Type::INTEGER)
            ->attribute('idUser', type: Type::INTEGER);
    }
    public static function addTimeline(string $tableName, int $idTable, string $operation = 'S')
    {
        $data = CreateData::from([
            'tableName' => $tableName,
            'idTable' => $idTable,
            'opration' => $operation
        ]);
        self::save($data);
    }

    /*
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idTimeline');
        if ($filter->idTimeline) {
            $criteria->where("idTimeline = {$filter->idTimeline}");
        }
        return $criteria;
    }

    public function newTimeline($tl, $operation = 'S')
    {
        $timeline = 'tl_' . $tl;
        $result = $this->getCriteria()->select('max(numOrder) as max')->where("upper(timeline) = upper('{$timeline}')")->asQuery()->getResult();
        $max = $result[0]['max'];
        $this->setPersistent(false);
        $this->operation = $operation;
        $this->tlDateTime = Carbon::now();
        $this->idUser = Base::getCurrentUser()->getId();
        $this->author = MAuth::getLogin() ? MAuth::getLogin()->login : 'offline';
        $this->save();
        return $timeline;
    }

    public function updateTimeline($oldTl, $newTl)
    {
        $oldTl = 'tl_' . $oldTl;
        $newTl = 'tl_' . $newTl;
//        $criteria = $this->getUpdateCriteria();
//        $criteria->addColumnAttribute('timeline');
//        $criteria->where("timeline = '{$oldTl}'");
//        $criteria->update($newTl);
        return $newTl;
    }


    */


}

