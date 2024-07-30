<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewAnnotationStatusType extends Repository {
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('view_annotationstatustype')
            ->attribute('idType', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('info')
            ->attribute('flag', type: Type::INTEGER)
            ->attribute('idColor', type: Type::INTEGER)
            ->attribute('idEntity', type: Type::INTEGER)
            ->associationOne('entries', model: 'ViewEntryLanguage', key: 'entry')
            ->associationOne('color', model: 'Color', key: 'idColor');
    }
}

