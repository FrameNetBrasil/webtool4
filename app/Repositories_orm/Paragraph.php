<?php
namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Paragraph extends Repository {

    public static function map(ClassMap $classMap): void
    {

        $classMap->table('paragraph')
            ->attribute('idParagraph', key: Key::PRIMARY)
            ->attribute('documentOrder', type: Type::INTEGER)
            ->attribute('idDocument', key: Key::FOREIGN)
            ->associationMany('sentences', model: 'Sentence', keys: 'idParagraph')
            ->associationOne('document', model: 'Document', key: 'idDocument');
    }
    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idParagraph');
        if ($filter->idParagraph){
            $criteria->where("idParagraph LIKE '{$filter->idParagraph}%'");
        }
        return $criteria;
    }
}
