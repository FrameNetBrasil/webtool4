<?php
namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewEntryLanguage extends Repository {
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('view_entrylanguage')
            ->attribute('idEntry', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('name')
            ->attribute('description')
            ->attribute('language')
            ->attribute('idLanguage', key: Key::FOREIGN)
            ->attribute('idEntity', key: Key::FOREIGN)
            ->associationOne('language', model: 'Language');
    }
}

