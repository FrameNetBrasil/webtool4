<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ImageMM extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('imagemm')
            ->attribute('idImageMM', key: Key::PRIMARY)
            ->attribute('name')
            ->attribute('width', type: Type::INTEGER)
            ->attribute('height', type: Type::INTEGER)
            ->attribute('depth', type: Type::FLOAT)
            ->attribute('imagePath');
    }
    public function getObjects()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $viewFrameElement = new ViewFrameElement();
        $criteria = $this->getCriteria();
        $criteria->select("objectmm.idObjectMM, objectmm.name,
        objectmm.status, objectmm.origin, objectmm.idFlickr30k,
        objectmm.idFrameElement, '' as idFrame, '' as frame, '' as idFE, '' as fe, '' as color");
        $criteria->where("objectmm.idImageMM = {$this->getId()}");
        $criteria->orderBy('objectmm.idFlickr30k');
        $objects = $criteria->asQuery()->getResult();
        $oMM = [];
        foreach ($objects as $object) {
            //ddump($object);
            if ($object['idFrameElement']) {
                $feCriteria = $viewFrameElement->getCriteria();
                $feCriteria->setAssociationAlias('frame.entries', 'frameEntries');
                $feCriteria->select('idFrame, frameEntries.name as frame, idFrameElement as idFE, entries.name as fe, color.rgbBg as color');
                $feCriteria->where("frameEntries.idLanguage = {$idLanguage}");
                $feCriteria->where("entries.idLanguage = {$idLanguage}");
                $feCriteria->where("idFrameElement = {$object['idFrameElement']}");
                $fe = $feCriteria->asQuery()->getResult()[0];
                $object['idFrame'] = $fe['idFrame'];
                $object['frame'] = $fe['frame'];
                $object['idFE'] = $fe['idFE'];
                $object['fe'] = $fe['fe'];
                $object['color'] = $fe['color'];

            }
            $oMM[] = $object;
        }
        $objects = [];
        $objectFrameMM = new ObjectFrameMM();
        foreach ($oMM as $object) {
            $idObjectMM = $object['idObjectMM'];
            $framesList = $objectFrameMM->listByObjectMM($idObjectMM)->asQuery()->getResult();
            $object['frames'] = $framesList;
            $objects[] = (object)$object;
        }
        return $objects;
    }


}
