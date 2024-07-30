<?php


namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ConstraintType extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('relationtype')
            ->attribute('idConstraintType', field: 'idRelationType', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('prefix')
            ->attribute('idTypeInstance', key: Key::FOREIGN)
            ->attribute('idRelationGroup', key: Key::FOREIGN);
    }

    public static function getByEntry(string $entry): object
    {
        return self::first([
            ['entry','=',$entry]
        ]);
    }

//    public function listAll()
//    {
//        $criteria = $this->getCriteria()->select('idConstraintType, entry, prefix, typeEntity1, typeEntity2, idTypeInstance, entries.name as name')->orderBy('entries.name');
//        Base::entryLanguage($criteria);
//        $criteria->where("relationgroup.entry = 'rgp_constraints'");
//        return $criteria;
//    }
/*

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idConstraintType, entry, prefix, typeEntity1, typeEntity2, idTypeInstance, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idConstraintType) {
            $criteria->where("idConstraintType = {$filter->idConstraintType}");
        }
        if ($filter->constraintType) {
            $criteria->where("upper(entries.name) LIKE upper('{$filter->constraintType}%')");
        }
        $criteria->where("relationgroup.entry = 'rgp_constraints'");
        return $criteria;
    }

    public function hasInstances()
    {
        $ci = new ConstraintInstance();
        $filter = (object)[
            'idConstraintType' => $this->getId()
        ];
        $criteria = $ci->listByFilter($filter);
        $result = $criteria->asQuery()->getResult();
        return (count($result) > 0);
    }

    public function saveData($data): int
    {
        $data->entry = 'rel_' . mb_strtolower(str_replace('rel_', '', $data->name));
        $transaction = $this->beginTransaction();
        try {
            $entry = new Entry();
            if ($this->isPersistent()) {
                $entry->updateEntry($this->getEntry(), $data->entry);
            } else {
                $data->idEntity = $this->getIdEntity();
                $entry->newEntryByData($data);
            }
            $this->setData($data);
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }

    }
*/

}
