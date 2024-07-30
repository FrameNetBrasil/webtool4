<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class AnnotationMM extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('annotationmm')
            ->attribute('idAnnotationMM', key: Key::PRIMARY)
            ->attribute('idObjectSentenceMM', key: Key::FOREIGN)
            ->attribute('idFrameElement', key: Key::FOREIGN)
            ->attribute('idFrame', key: Key::FOREIGN)
            ->associationOne('objectSentenceMM', model: 'ObjectSentenceMM', key: 'idObjectSentenceMM')
            ->associationOne('frameElement', model: 'FrameElement', key: 'idFrameElement')
            ->associationOne('frame', model: 'Frame', key: 'idFrame');
    }
    public function getByObjectSentenceMM(int $idObjectSentenceMM) {
        $criteria = $this->getCriteria()
            ->select('*')
            ->where("idObjectSentenceMM","=",$idObjectSentenceMM);
        $this->retrieveFromCriteria($criteria);
    }

}
