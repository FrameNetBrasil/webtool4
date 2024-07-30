<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class TopFrame extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('topframe')
            ->attribute('idTopFrame', key: Key::PRIMARY)
            ->attribute('frameBase')
            ->attribute('frameTop')
            ->attribute('frameCategory')
            ->attribute('score', type: Type::FLOAT)
            ->associationOne('frame', model: 'Frame', key: 'frameBase:entry');
    }
    public static function listByFilter(object $filter)
    {
        $criteria = self::getCriteria()
            ->select(['idTopFrame','frameBase','frameTop','frameCategory','frame.name'])
            ->where("frame.idLanguage", "=", AppService::getCurrentIdLanguage())
            ->orderBy('frame.name');
        if ($filter->idTimeline) {
            $criteria->where("idTimeline = {$filter->idTimeline}");
        }
        return $criteria;
    }

}

