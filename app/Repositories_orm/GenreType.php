<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class GenreType extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('genretype')
            ->attribute('idGenreType', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity');
    }
    /*
    public function getEntryObject()
    {
        $criteria = $this->getCriteria()->select('entries.name, entries.description, entries.nick');
        $criteria->where("idGenreType = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->asObjectArray()[0];
    }

    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idGenreType = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->getResult()[0]['name'];
    }

    public function listAll()
    {
        $criteria = $this->getCriteria()->select('idGenreType, entries.name as name')->orderBy('entry');
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria();
        $criteria->setAssociationAlias('entries', 'centry');
        $criteria->select('distinct idGenreType, entry, centry.name as name')->orderBy('centry.name');
        Base::entryLanguage($criteria);
        if ($filter->idGenreType) {
            $criteria->where("idGenreType = {$filter->idGenrType}");
        }
        if ($filter->genreType) {
            $criteria->where("upper(centry.name) LIKE upper('%{$filter->genreType}%')");
        }
        if ($filter->entry) {
            $criteria->where("upper(entry) LIKE upper('%{$filter->entry}%')");
        }
        return $criteria;
    }

    public function save($data)
    {
        $this->setData($data);
        $transaction = $this->beginTransaction();
        try {
            $entity = new Entity();
            $entity->setAlias($this->getEntry());
            $entity->setType('GT');
            $entity->save();
            $this->setIdEntity($entity->getId());
            $entry = new Entry();
            $entry->newEntry($this->getEntry(),$entity->getId());
            parent::save();
            Timeline::addTimeline("genretype",$this->getId(),"S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateEntry($newEntry)
    {
        $transaction = $this->beginTransaction();
        try {
//            Base::updateTimeLine($this->getEntry(), $newEntry);
            Timeline::addTimeline("genretype",$this->getId(),"S");
            $entry = new Entry();
            $entry->updateEntry($this->getEntry(), $newEntry);
            $this->setEntry($newEntry);
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */
}
