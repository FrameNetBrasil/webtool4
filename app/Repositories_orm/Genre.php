<?php


namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Genre extends Repository {

    public static function map(ClassMap $classMap): void
    {

        $classMap->table('genre')
            ->attribute('idGenre', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->attribute('idGenreType', key: Key::FOREIGN)
            ->associationOne('genreType', model: 'GenreType')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity');
    }
    public function listAllGenres()
    {
        $criteria = $this->getCriteria()->select('idGenre, entries.name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('idGenre,idGenreType, entry, entries.name name')->orderBy('idGenre');
        if ($filter->idGenre){
            $criteria->where("idGenre = {$filter->idGenre}");
        }
        if ($filter->idGenreType){
            $criteria->where("idGenreType = {$filter->idGenreType}");
        }
        if ($filter->entry){
            $criteria->where("entry LIKE '%{$filter->entry}%'");
        }
        Base::entryLanguage($criteria);
        return $criteria;
    }
/*
    public function save($data)
    {
        $this->setData($data);
        $transaction = $this->beginTransaction();
        try {
            if (!$this->isPersistent()) {
                $entity = new Entity();
                $entity->setAlias($this->getEntry());
                $entity->setType('GR');
                $entity->save();
                $this->setIdEntity($entity->getId());
                $entry = new Entry();
                $entry->newEntry($this->getEntry(),$entity->getId());
            }
            parent::save();
            Timeline::addTimeline("genre",$this->getId(),"S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
*/

}
