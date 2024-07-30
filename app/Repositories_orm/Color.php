<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Color extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('color')
            ->attribute('idColor', key: Key::PRIMARY)
            ->attribute('name')
            ->attribute('rgbFg')
            ->attribute('rgbBg');
    }
    public function listByFilter($filter)
    {
        $criteria = self::getCriteria()
            ->select(['idColor','name','rgbFg','rgbBg'])
            ->orderBy('idColor');
        return self::filter([
            ['idColor','=',$filter?->idColor ?? null]
        ], $criteria);
    }

    public static function listForSelect()
    {
        return self::getCriteria()
            ->select(['idColor','name','rgbFg','rgbBg'])
            ->orderBy('name');
    }

}
