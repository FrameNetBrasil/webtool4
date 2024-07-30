<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class StaticBBoxMM extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('staticbboxmm')
            ->attribute('idStaticBBoxMM', key: Key::PRIMARY)
            ->attribute('x', type: Type::INTEGER)
            ->attribute('y', type: Type::INTEGER)
            ->attribute('width', type: Type::INTEGER)
            ->attribute('height', type: Type::INTEGER)
            ->attribute('idStaticObjectMM', type: Type::INTEGER, key: Key::FOREIGN)
            ->associationOne('staticObjectMM', model: 'StaticObjectMM', key: 'idObjectMM');
    }
    public static function listByObjectMM($idStaticObjectMM)
    {
        $criteria = self::getCriteria()
            ->select(['idStaticBBoxMM', 'x', 'y', 'width', 'height'])
            ->where("idStaticObjectMM", "=", $idStaticObjectMM)
            ->orderBy('idStaticBBoxMM');
        return $criteria;
    }

    public function putFrames($idObjectMM, $frames)
    {
        $transaction = $this->beginTransaction();
        try {
            $deleteCriteria = $this->getDeleteCriteria();
            $deleteCriteria->where("idObjectMM = {$idObjectMM}");
            $deleteCriteria->delete();
            foreach ($frames as $row) {
                $frame = (object)$row;
                $this->setPersistent(false);
                $frame->idObjectMM = $idObjectMM;
                $this->setData($frame);
                parent::save();
                Timeline::addTimeline("objectframemm", $this->getId(), "S");
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }


//    public function save($data = null)
//    {
//        $transaction = $this->beginTransaction();
//        try {
//            $this->setData($data);
//            parent::save();
//            Timeline::addTimeline("objectframemm", $this->getId(), "S");
//            $transaction->commit();
//        } catch (\Exception $e) {
//            $transaction->rollback();
//            throw new \Exception($e->getMessage());
//        }
//    }


}
