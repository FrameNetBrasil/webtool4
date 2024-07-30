<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class UserAnnotation extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('userannotation')
            ->attribute('idUserAnnotation', key: Key::FOREIGN)
            ->attribute('idUser', key: Key::FOREIGN)
            ->attribute('idSentenceStart', type: Type::INTEGER)
            ->attribute('idSentenceEnd', key: Key::FOREIGN)
            ->attribute('idDocument', key: Key::FOREIGN)
            ->associationOne('document', model: 'Document', key: 'idDocument')
            ->associationOne('sentenceStart', model: 'Sentence', key: 'idSentenceStart:idSentence')
            ->associationOne('sentenceEnd', model: 'Sentence', key: 'idSentenceEnd:idSentence')
            ->associationOne('user', model: 'User', key: 'idUser:idUser');
    }
    public static function listSentenceByUser(int $idUser, int $idDocument): ?array
    {
        $cmd = <<<HERE
select s.idSentence
from userannotation ua
join sentence s on ((s.idSentence >= ua.idSentenceStart) and (s.idSentence <= ua.idSentenceEnd))
join document_sentence ds on (s.idSentence = ds.idSentence)
where (ds.idDocument = {$idDocument})
and (ua.idUser = {$idUser})

HERE;
        return array_column(self::query($cmd), 'idSentence');
    }
}
