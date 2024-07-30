<?php

namespace App\Repositories;

use App\Data\Domain\SearchData;
use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\PersistenceManager;
use Orkester\Persistence\Repository;

class Domain extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('domain')
            ->attribute('idDomain', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationOne('entity', model: 'Entity', key: 'idEntity')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity');
    }

    public static function getById(int $id): object
    {
        return (object)self::first([
            ['idDomain', '=', $id],
            ['idLanguage', '=', AppService::getCurrentIdLanguage()]
        ]);
    }
    public static function listToGrid(SearchData $search): array
    {
        $criteria = self::getCriteria()
            ->select(['idDomain', 'entry', 'idEntity', 'name','entries.description'])
            ->orderBy('name');
        return self::filter([
            ['idLanguage', '=', AppService::getCurrentIdLanguage()],
            ["upper(entries.name)", "startswith", strtoupper($search->domain)]
        ], $criteria)->all();
    }

    public static function create(object $domain): ?int
    {
        PersistenceManager::beginTransaction();
        try {
            debug($domain);
            $baseEntry = strtolower('dom_' . $domain->nameEn);
            debug($baseEntry);
            $idEntity = Entity::create('DO', $baseEntry);
            debug($idEntity);
            Entry::create($baseEntry, $domain->nameEn, $idEntity);
            debug('entry created');
            $idDomain = self::save((object)[
                'entry' => $baseEntry,
                'idEntity' => $idEntity
            ]);
            Timeline::addTimeline("domain", $idDomain, "C");
            PersistenceManager::commit();
            return $idDomain;
        } catch (\Exception $e) {
            PersistenceManager::rollback();
            return null;
        }
    }
    /*
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('entry');
        if ($filter->idDomain) {
            $criteria->where("idDomain = {$filter->idDomain}");
        }
        if ($filter->entry) {
            $criteria->where("entry LIKE '%{$filter->entry}%'");
        }
        return $criteria;
    }

//    public function listAll()
//    {
//        $criteria = $this->getCriteria()->select('idDomain, entries.name as name, idEntity')->orderBy('entries.name');
//        Base::entryLanguage($criteria);
//        return $criteria;
//    }

    public function listForSelection()
    {
        $criteria = $this->getCriteria()->select('idDomain, entries.name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $criteria->orderBy('entries.name');
        return $criteria;
    }

    public function save(): ?int
    {
        $transaction = $this->beginTransaction();
        try {
            $idEntity = $this->getIdEntity();
            $entity = new Entity($idEntity);
            $entity->setAlias($this->getEntry());
            $entity->setType('DO');
            $entity->save();
            $this->setIdEntity($entity->getId());
            $entry = new Entry();
            $entry->newEntry($this->getEntry(), $entity->getId());
//            Base::entityTimelineSave($this->getIdEntity());
            $idDomain = parent::save();
            Timeline::addTimeline("domain", $this->getId(), "S");
            $transaction->commit();
            return $idDomain;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function addEntity($idEntity, $relation = 'rel_hasdomain')
    {
        Base::createEntityRelation($idEntity, $relation, $this->getIdEntity());
    }

    public function delDomainFromEntity($idEntity, $idDomainEntity = [], $relation = 'rel_hasdomain')
    {
        $rt = new RelationType();
        $c = $rt->getCriteria()->select('idRelationType')->where("entry = '{$relation}'");
        $er = new EntityRelation();
        $transaction = $er->beginTransaction();
        $criteria = $er->getDeleteCriteria();
        $criteria->where("idEntity1 = {$idEntity}");
        $criteria->where("idEntity2", "IN", $idDomainEntity);
        $criteria->where("idRelationType", "=", $c);
        $criteria->delete();
        $transaction->commit();
    }
*/

}
