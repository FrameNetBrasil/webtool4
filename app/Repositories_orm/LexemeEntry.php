<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class LexemeEntry extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('lexemeentry')
            ->attribute('idLexemeEntry', key: Key::PRIMARY)
            ->attribute('lexemeOrder', type: Type::INTEGER)
            ->attribute('breakBefore', type: Type::INTEGER)
            ->attribute('headWord', type: Type::INTEGER)
            ->attribute('idLemma', key: Key::FOREIGN)
            ->attribute('idLexeme', key: Key::FOREIGN)
            ->associationOne('lemma', model: 'Lemma', key: "idLemma")
            ->associationOne('lexeme', model: 'Lexeme', key: "idLexeme")
            ->associationOne('wordForm', model: 'WordForm', key: "idWordForm");
    }
    public function deleteByLemma(int $idLemma)
    {
        $this->getCriteria()
            ->where('idLemma', '=', $idLemma)
            ->delete();

    }


}
