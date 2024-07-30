<?php
namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewWfLexemeLemma extends Repository {

    public static function map(ClassMap $classMap): void
    {
        $classMap->table('view_wflexemelemma')
            ->attribute('idWordForm', key: Key::PRIMARY)
            ->attribute('form')
            ->attribute('idLexeme', key: Key::FOREIGN)
            ->attribute('lexeme')
            ->attribute('idPOSLexeme', key: Key::FOREIGN)
            ->attribute('POSLexeme')
            ->attribute('idLanguage', key: Key::FOREIGN)
            ->attribute('idLexemeEntry', key: Key::FOREIGN)
            ->attribute('lexemeOrder')
            ->attribute('headWord')
            ->attribute('idLemma', key: Key::FOREIGN)
            ->attribute('idPOSLemma', key: Key::FOREIGN)
            ->attribute('POSLemma');
    }

    public function listByFilter($filter = NULL)
    {
        $criteria = $this->getCriteria()->select('idWordForm, form, idLexeme, lexeme, idPOSLexeme, POSLexeme, idLanguage, idLexemeEntry, lexemeOrder, breakBefore, headWord, idLemma, lemma, idPOSLemma, POSLemma, language');
        if (is_null($filter)) {
            $criteria->where("form = ''");
        } else {
            if ($filter->form != '') {
                $criteria->where("form = '{$filter->form}'");
            }
            if ($filter->lexeme != '') {
                $criteria->where("lexeme = '{$filter->lexeme}'");
            }
            if ($filter->arrayForm != '') {
                $criteria->where("form", "in", $filter->arrayForm);
            }
            if ($filter->idLanguage != '') {
                $criteria->where("idLanguage = {$filter->idLanguage}");
            }
        }
        return $criteria;
    }


}

