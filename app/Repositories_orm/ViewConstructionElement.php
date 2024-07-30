<?php
namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewConstructionElement extends Repository {

    public static function map(ClassMap $classMap): void
    {
        $classMap->table('view_constructionelement')
            ->attribute('idConstructionElement', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('active', type: Type::INTEGER)
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('idColor', key: Key::FOREIGN)
            ->attribute('idConstruction', key: Key::FOREIGN)
            ->attribute('constructionEntry')
            ->attribute('constructionIdEntity', type: Type::INTEGER)
            ->associationOne('entries', model: 'Entry', key: 'entry')
            ->associationOne('construction', model: 'Construction', key: 'idConstruction')
            ->associationOne('color', model: 'Color', key: 'idColor');
    }

    public function listSiblingsCE($idCE)
    {
        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT ce.idConstructionElement, e.name
        FROM View_ConstructionElement ce
            INNER JOIN View_EntryLanguage e ON (e.entry = ce.entry)
            INNER JOIN (
            SELECT idConstruction
            FROM View_ConstructionElement
            WHERE (idConstructionElement = {$idCE})) ce1 on (ce.idConstruction = ce1.idConstruction)
        WHERE (e.idLanguage = {$idLanguage})
            AND (ce.idConstructionElement <> {$idCE})
HERE;
        return $this->getDb()->getQueryCommand($cmd);

    }

    public function listCEByConstructionEntity($idEntityCxn)
    {
        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT ce.idConstructionElement, e.name, ce.entry, ce.optional, ce.head, ce.multiple
        FROM View_ConstructionElement ce
            INNER JOIN View_EntryLanguage e ON (e.entry = ce.entry)
        WHERE (e.idLanguage = {$idLanguage})
            AND (ce.constructionIdEntity = {$idEntityCxn})
HERE;
        return $this->getDb()->getQueryCommand($cmd);

    }

    public function listCEByIdConstruction($idCxn)
    {
        $idLanguage = \Manager::getSession()->idLanguage;

        $cmd = <<<HERE
        SELECT ce.idConstructionElement, ce.idEntity, e.name, e.nick, ce.entry, ce.optional, ce.head, ce.multiple
        FROM View_ConstructionElement ce
            INNER JOIN View_EntryLanguage e ON (e.entry = ce.entry)
        WHERE (e.idLanguage = {$idLanguage})
            AND (ce.idConstruction = {$idCxn})
HERE;
        return $this->getDb()->getQueryCommand($cmd);
    }

    public function getByIdEntity($idEntity) {
        $criteria = $this->getCriteria()->select('*,entries.name as name, entries.nick as nick, cxnEntries.name as cxnName');
        $criteria->where("idEntity = {$idEntity}");
        $criteria->associationAlias("construction.entries", "cxnEntries");
        Base::entryLanguage($criteria);
        return (object)$criteria->asQuery()->getResult()[0];
    }

    public function listForExport($idCxn)
    {
        $criteria = $this->getCriteria()->select('idConstructionElement, entry, active, idEntity, idColor, optional, head, multiple')->orderBy('entry');
        $criteria->where("idConstruction =  {$idCxn}");
        return $criteria;
    }
}

