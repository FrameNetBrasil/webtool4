<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewInstantiationType extends Repository {
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('view_instantiationtype')
            ->attribute('idTypeInstance', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('info')
            ->attribute('flag', type: Type::INTEGER)
            ->attribute('idColor', type: Type::INTEGER)
            ->attribute('idType', type: Type::INTEGER)
            ->attribute('idEntity', type: Type::INTEGER)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity')
            ->associationOne('color', model: 'Color', key: 'idColor');
    }

}

