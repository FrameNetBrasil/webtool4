<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class StatusMM extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('statusmm')
            ->attribute('idStatusMM', key: Key::PRIMARY)
            ->attribute('file')
            ->attribute('video', type: Type::INTEGER)
            ->attribute('audio', type: Type::INTEGER)
            ->attribute('speechToText', type: Type::FLOAT)
            ->attribute('frames', type: Type::INTEGER)
            ->attribute('yolo', type: Type::INTEGER)
            ->attribute('idDocumentMM', type: Type::INTEGER, key: Key::FOREIGN);
    }
}
