<?php
namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class RelationGroup extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('relationgroup')
            ->attribute('idRelationGroup', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationOne('entity', model: 'Entity')
            ->associationMany('entries', model: 'Entry', keys: 'entry:entry');
    }
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idRelationGroup');
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria->where("idLanguage", "=", $idLanguage);
        if (isset($filter->idRelationGroup)) {
            $criteria->where("idRelationGroup", "=", $filter->idRelationGroup);
        }
        if (isset($filter->entry)) {
            $criteria->where("entry", "startswith", $filter->entry);
        }
        if (isset($filter->name)) {
            $criteria->where("name", "startswith", $filter->name);
        }
        if (isset($filter->relationGroup)) {
            $criteria->where("name", "startswith", $filter->relationGroup);
        }
        return $criteria;
    }

    public function listRelationType()
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $rt = new RelationType();
        $criteria = $rt->getCriteria()
            ->select('*')
            ->where("idLanguage", "=", $idLanguage)
            ->where("idRelationGroup", "=", $this->idRelationGroup)
            ->orderBy('name');
        return $criteria;
    }

    public function listForSelect($name = '')
    {
        $criteria = $this->getCriteria()
            ->select(['idRelationGroup','name'])
            ->orderBy('name');
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria->where("idLanguage", "=", $idLanguage);
        $criteria->where("upper(name)", "startswith", strtoupper($name));
        return $criteria;
    }
    public function create($data)
    {
        $this->beginTransaction();
        try {
            $baseEntry = strtolower('rgp_' . $data->nameEn);
            $entity = new Entity();
            $idEntity = $entity->create('RG', $baseEntry);
            $entry = new Entry();
            $entry->create($baseEntry, $data->nameEn, $idEntity);
            $id = $this->saveData([
                'entry' => $baseEntry,
                'idEntity' => $idEntity
            ]);
            Timeline::addTimeline("relationgroup", $id, "C");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

}

