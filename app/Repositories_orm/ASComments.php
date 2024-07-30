<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ASComments extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('ascomments')
            ->attribute('idASComments', key: Key::PRIMARY)
            ->attribute('extraThematicFE')
            ->attribute('extraThematicFEOther')
            ->attribute('comment')
            ->attribute('construction')
            ->attribute('idAnnotationSet', key: Key::FOREIGN)
            ->associationOne('annotationSet', model: 'AnnotationSet', key: 'idAnnotationSet');
    }
    public static function deleteByAnnotationSet(int $idAnnotationSet): void
    {
        self::getCriteria()
            ->where("idAnnotationSet", "=", $idAnnotationSet)
            ->delete();
    }

    /*
    public function getByAnnotationSet($idAnnotationSet)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("idAnnotationSet = {$idAnnotationSet}");
        $this->retrieveFromCriteria($criteria);
    }

    */
}

