<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Sentence extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('sentence')
            ->attribute('idSentence', key: Key::PRIMARY)
            ->attribute('text')
            ->attribute('paragraphOrder', type: Type::INTEGER)
            ->attribute('idParagraph', key: Key::FOREIGN)
            ->attribute('idLanguage', key: Key::FOREIGN)
            ->attribute('idDocument', key: Key::FOREIGN)
            ->associationOne('paragraph', model: 'Paragraph', key: 'idParagraph')
            ->associationOne('document', model: 'Document', key: 'idDocument')
            ->associationOne('language', model: 'Language', key: 'idLanguage')
            ->associationMany('sentenceMM', model: 'SentenceMM', keys: 'idSentence:idSentence')
            ->associationMany('documents', model: 'Document', associativeTable: 'document_sentence');
    }

    public static function listByFilter($filter)
    {
        $criteria = self::getCriteria()
            ->select(['*'])
            ->distinct()
            ->orderBy('idSentence');
        return self::filter([
            ['idSentence','=',$filter?->idSentence ?? null],
            ['text','contains',$filter?->sentence ?? null],
            ['documents.idDocument','=',$filter?->idDocument ?? null],
        ], $criteria);
    }
    /*

    public function save(): ?int
    {
        parent::save();
    }

    public function delete()
    {
        $cmd = <<<HERE

select s.idSentenceMM
FROM sentenceMM s
where (s.idSentence = {$this->getId()})

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        $cmd3 = "delete from SentenceMM where idSentence = {$this->getId()}";
        $this->getDb()->executeCommand($cmd3);
        $cmd4 = "delete from AnnotationSet where idSentence = {$this->getId()}";
        $this->getDb()->executeCommand($cmd4);
        parent::delete();
    }

    public function hasAnnotation()
    {
        return (count($this->getAnnotationsets()) > 0);
    }
*/
}
