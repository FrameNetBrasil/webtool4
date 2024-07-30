<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class OriginMM extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('originmm')
            ->attribute('idOriginMM', key: Key::PRIMARY)
            ->attribute('origin');
    }

}
