<?php


namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Type extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('type')
            ->attribute('idType', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationOne('entity', model: 'Entity')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity')
            ->associationMany('typeInstances', model: 'TypeInstance', keys: 'idType:idType');
    }

    public static function getInstantiationType(string $entry = '')
    {
        $criteria = self::getCriteria()
            ->select(['typeInstances.idTypeInstance as idInstantiationType','typeInstances.name as instantiationType'])
            ->where("entry","=",'typ_instantiationtype')
            ->where("typeInstances.idLanguage","=",AppService::getCurrentIdLanguage());
        if ($entry != '') {
            $criteria->where('typeInstances.entry','=',$entry);
        }
        return $criteria;
    }


    /*
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idType');
        if ($filter->idType) {
            $criteria->where("idType = {$filter->idType}");
        }
        if ($filter->entry) {
            $criteria->where("entry LIKE '%{$filter->entry}%'");
        }
        return $criteria;
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
            }
            parent::save();
            Timeline::addTimeline("type", $this->getId(), "S");
            $transaction->commit();
            return $this->getId();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */

}
