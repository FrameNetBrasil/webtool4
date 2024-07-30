<?php

namespace App\Repositories;

use Orkester\Persistence\Criteria\Criteria;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;
class Layer extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('layer')
            ->attribute('idLayer', key: Key::PRIMARY)
            ->attribute('rank')
            ->attribute('idLayerType', key: Key::FOREIGN)
            ->attribute('idAnnotationSet', key: Key::FOREIGN)
            ->associationOne('layerType', model: 'LayerType', key: 'idLayerType')
            ->associationOne('annotationSet', model: 'AnnotationSet', key: 'idAnnotationSet')
            ->associationMany('labels', model: 'Label', keys: 'idLayer');
    }
    public static function save(object $model): ?int
    {
        $idLayer = parent::save($model);
        Timeline::addTimeline("layer", $idLayer, "C");
        return $idLayer;
    }

    public static function deleteByAnnotationSet(int $idAnnotationSet): void
    {
        self::beginTransaction();
        try {
            $layers = self::listByAnnotationSet($idAnnotationSet)->all();
            foreach ($layers as $layer) {
                Label::getCriteria()
                    ->where("idLayer", "=", $layer->idLayer)
                    ->delete();
                self::getCriteria()
                    ->where("idLayer", "=", $layer->idLayer)
                    ->delete();
            }
            self::commit();
        } catch (\Exception $e) {
            self::rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public static function listByAnnotationSet($idAnnotationSet): Criteria
    {
        return self::getCriteria()
            ->select('*')
            ->where("idAnnotationSet","=",$idAnnotationSet);
    }

    /*
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idLayer');
        if ($filter->idLayer) {
            $criteria->where("idLayer LIKE '{$filter->idLayer}%'");
        }
        return $criteria;
    }


    public function save(): ?int
    {
        parent::save();
        Timeline::addTimeline("layer", $this->getId(), "S");
        return $this->getId();
    }


    public function delete()
    {
        $this->beginTransaction();
        try {
            $label = new Label();
            $label->getCriteria()
                ->where("idLayer", "=", $this->getId())
                ->delete();
            Timeline::addTimeline("layer", $this->getId(), "D");
            parent::delete();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }
*/
}

