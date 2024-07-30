<?php

namespace App\Repositories;

use App\Data\CreateRelationTypeData;
use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class RelationType extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('relationtype')
            ->attribute('idRelationType', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('prefix')
            ->attribute('nameEntity1')
            ->attribute('nameEntity2')
            ->attribute('nameEntity3')
            ->attribute('idDomain', type: Type::INTEGER)
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('idTypeInstance', key: Key::FOREIGN)
            ->attribute('idRelationGroup', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationOne('entity', model: 'Entity')
            ->associationOne('typeInstance', model: 'TypeInstance')
            ->associationOne('relationGroup', model: 'RelationGroup', key: 'idRelationGroup')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity');

    }
    public static function getById(int $id): object
    {
        $rt = (object)self::first([
            ['idRelationType', '=', $id],
            ['idLanguage', '=', AppService::getCurrentIdLanguage()]
        ]);
        return $rt;
    }

    public static function getByEntry(string $entry): object
    {
        $rt = (object)self::first([
            ['entry', '=', $entry],
        ]);
        return $rt;
    }

    public static function listByFilter($filter)
    {
        $criteria = self::getCriteria()
            ->select(['idRelationType', 'entry', 'name', 'description'])
            ->orderBy('name');
        return self::filter([
            ['idLanguage', '=', AppService::getCurrentIdLanguage()],
            ["idRelationType", "=", $filter->idRelationType ?? null],
            ["entry", "=", $filter->entry ?? null],
            ["relationGroup.entry", "=", $filter->group ?? null],
            ["name", "startswith", $filter->name ?? null],
            ["name", "startswith", $filter->relationType ?? null],
        ], $criteria);
    }

    /*

    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idRelationType = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->fields('name');
    }



    public function create(CreateRelationTypeData $data)
    {
        $this->beginTransaction();
        try {
            $baseEntry = strtolower('rty_' . $data->nameEn . '_' . $data->idRelationGroup);
            $entity = new Entity();
            $idEntity = $entity->create('RT', $baseEntry);
            $entry = new Entry();
            $entry->create($baseEntry, $data->nameEn, $idEntity);
            $id = $this->saveData([
                'entry' => $baseEntry,
                'idDomain' => $data->idDomain,
                'idRelationGroup' => $data->idRelationGroup,
                'nameEntity1' => '',
                'nameEntity2' => '',
                'nameEntity3' => '',
                'idEntity' => $idEntity
            ]);
            Timeline::addTimeline("relationtype", $id, "C");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    public function update(CreateRelationTypeData $data)
    {
        $this->beginTransaction();
        try {
            $this->saveData($data->toArray());
            Timeline::addTimeline("relationtype", $this->getId(), "U");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

//    public function listAll()
//    {
//        $criteria = $this->getCriteria()->select('idRelationType, entry, nameEntity1, nameEntity2, entries.name as name')->orderBy('entries.name');
//        Base::entryLanguage($criteria);
//        return $criteria;
//    }

/*
    public function getByEntry(string $entry)
    {
        $criteria = $this->getCriteria()
            ->select('*')
            ->where("entry", "=", $entry);
        $this->retrieveFromCriteria($criteria);
    }

    public function save(): ?int
    {
        $transaction = $this->beginTransaction();
        try {
            if (!$this->isPersistent()) {
                $entity = new Entity();
                $entity->setAlias($this->getEntry());
                $entity->setType('GT');
                $entity->save();
                $this->setIdEntity($entity->getId());
                $entry = new Entry();
                $entry->newEntry($this->getEntry(), $entity->getId());
                $translation = new Translation();
                $translation->newResource($this->getNameEntity1());
                $translation->newResource($this->getNameEntity2());
            }
            $idRelationType = parent::save();
            $transaction->commit();
            return $idRelationType;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateEntry($newEntry)
    {
        $transaction = $this->beginTransaction();
        try {
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
