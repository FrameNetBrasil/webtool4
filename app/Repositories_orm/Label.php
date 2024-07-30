<?php

namespace App\Repositories;

use App\Data\Label\CreateData;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Label extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('label')
            ->attribute('idLabel', key: Key::PRIMARY)
            ->attribute('startChar')
            ->attribute('endChar')
            ->attribute('multi')
            ->attribute('idLabelType', key: Key::FOREIGN)
            ->attribute('idLayer', key: Key::FOREIGN)
            ->attribute('idInstantiationType', key: Key::FOREIGN)
            ->associationOne('genericLabel', model: 'GenericLabel', key: 'idLabelType:idEntity')
            ->associationOne('frameElement', model: 'FrameElement', key: 'idLabelType:idEntity')
            ->associationOne('constructionElement', model: 'ConstructionElement', key: 'idLabelType:idEntity')
            ->associationOne('layer', model: 'Layer', key: 'idLayer')
            ->associationOne('instantiationType', model: 'TypeInstance', key: 'idInstantiationType:idTypeInstance');
    }
    /**
     * @throws \Exception
     */
    public static function create(CreateData $data)
    {
        self::beginTransaction();
        try {
            $idLabel = self::save($data);
            Timeline::addTimeline("label", $idLabel, "C");
            self::commit();
        } catch (\Exception $e) {
            self::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public static function update(object $data): void
    {
        self::beginTransaction();
        try {
            self::getCriteria()
                ->where("idLayer","=",$data->idLayer)
                ->where("startChar","=",$data->startChar)
                ->delete();
            $idLabel = self::save($data);
            Timeline::addTimeline("label", $idLabel, "U");
            self::commit();
        } catch (\Exception $e) {
            self::rollback();
            throw new \Exception($e->getMessage());
        }
    }
/*

    public function setIdInstantiationTypeFromEntry($entry)
    {
        $ti = new TypeInstance();
        $idInstantiationType = $ti->getIdInstantiationTypeByEntry($entry);
        parent::setIdInstantiationType($idInstantiationType);
    }

    public function setIdLabelTypeFromEntry($entry)
    {
        $cmd = <<<HERE

        SELECT FrameElement.idEntity
        FROM FrameElement
        WHERE (FrameElement.entry like '{$entry}

%
')
        UNION
        SELECT GenericLabel.idEntity
        FROM GenericLabel
        WHERE (GenericLabel.entry like '{
    $entry}%')
        UNION
        SELECT ConstructionElement.idEntity
        FROM ConstructionElement
        WHERE (ConstructionElement.entry like '{
    $entry}%')

HERE;
        $idLabelType = $this->getDb()->getQueryCommand($cmd)->getResult()[0][0];
        parent::setIdLabelType($idLabelType);
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select(' * ')->orderBy('idLabel');
        if ($filter->idLabel){
            $criteria->where("idLabel LIKE '{
    $filter->idLabel}%'");
        }
        return $criteria;
    }

    public function deleteByIdLabelType($idLabelType) {
        $criteria = $this->getDeleteCriteria();
        $criteria->where("idLabelType = {$idLabelType}");
        $criteria->delete();
    }

    public function update($data)
    {
        $this->beginTransaction();
        try {
            $this->getCriteria()
                ->where("idLayer","=",$data->idLayer)
                ->where("startChar","=",$data->startChar)
                ->delete();
            $this->saveData($data);
            Timeline::addTimeline("label", $this->getId(), "U");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function save(): ?int
    {
        Timeline::addTimeline("label", $this->getId(), "S");
        return parent::save();
    }

    public function delete(): void
    {
        $this->beginTransaction();
        try {
            Timeline::addTimeline("label", $this->getId(), "U");
            parent::delete();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */

}
