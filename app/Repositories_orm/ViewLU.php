<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewLU extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('view_lu')
            ->attribute('idLU', key: Key::PRIMARY)
            ->attribute('name')
            ->attribute('senseDescription')
            ->attribute('active', type: Type::INTEGER)
            ->attribute('importNum', type: Type::INTEGER)
            ->attribute('incorporatedFE', type: Type::INTEGER)
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('idLemma', key: Key::FOREIGN)
            ->attribute('idFrame', key: Key::FOREIGN)
            ->attribute('frameEntry')
            ->attribute('lemmaName')
            ->attribute('idLanguage', key: Key::FOREIGN)
            ->attribute('idPOS', key: Key::FOREIGN)
            ->associationOne('lemma', model: 'Lemma')
            ->associationOne('frame', model: 'Frame', key: 'idFrame')
            ->associationOne('pos', model: 'POS', key: 'idPOS')
            ->associationOne('language', model: 'Language', key: 'idLanguage')
            ->associationMany('annotationSets', model: 'ViewAnnotationSet', keys: 'idLU');
    }
    public static function getById(int $id): object
    {
        $lu = (object)self::first([
            ['idLU', '=', $id],
        ]);
        $lu->frame = Frame::getById($lu->idFrame);
        return $lu;
    }
    public static function listByFilter($filter)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = self::getCriteria()
            ->select([
                'idLU',
                'name',
                'senseDescription',
                'active',
                'importNum',
                'incorporatedFE',
                'idEntity',
                'idLemma',
                'idFrame',
                'frameEntry',
                'lemmaName',
                'idLanguage',
                'idPOS',
                'frame.name as frameName',
                'language.language'
            ])
            ->where('idLanguage', '=', $idLanguage)
            ->where('frame.idLanguage', '=', $idLanguage)
            ->orderBy('name');
        if ($filter->idLU ?? false) {
            $op = is_array($filter->idLU) ? "IN" : "=";
            $criteria->where("idLU", $op, $filter->idFrameElement);
        }
        if ($filter->lu ?? false) {
            $criteria->where("name", "startswith", $filter->lu);
        }
        if ($filter->idLanguage ?? false) {
            $criteria->where("idLanguage", "=", $filter->idLanguage);
        }
        return $criteria;
    }

    public static function listByFrame(int $idFrame, int $idLanguage, int|array|null $idLU = NULL)
    {
        $criteria = self::getCriteria()
            ->select(['idLU', 'name', 'senseDescription', 'active', 'importNum', 'incorporatedFE', 'idEntity', 'idLemma', 'idFrame', 'frameEntry', 'lemmaName', 'idLanguage', 'idPOS' ,'pos.POS'])
            ->orderBy('name')
            ->where('idFrame', '=', $idFrame)
            ->where('idLanguage', '=', $idLanguage);
        if ($idLU) {
            if (is_array($idLU)) {
                $criteria->where("idLU", "IN", $idLU);
            } else {
                $criteria->where('idLU', '=', $idLU);
            }
        }
        return $criteria;
    }

    public function listByFrameToAnnotation($idFrame, $idLanguage = '', $idLU = NULL)
    {
        $criteria = $this->getCriteria()
//            ->select('idLU, name, count(subcorpus.annotationsets.idAnnotationSet) as quant')
            ->select('idLU, name, count(annotationsets.idAnnotationSet) as quant')
            ->where("idFrame = {$idFrame}")
            ->where("idLanguage = {$idLanguage}")
            ->groupBy('idLU,name')
            ->orderBy('name');
        if ($idLU) {
            if (is_array($idLU)) {
                $criteria->where("idLU", "IN", $idLU);
            } else {
                $criteria->where("idLU = {$idLU}");
            }
        }
        return $criteria;
    }

    public function listByLemmaFrame($lemma, $idFrame)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("idFrame = {$idFrame}");
        $criteria->where("lemmaName = '{$lemma}'");
        return $criteria;
    }

    public function listByIdEntityFrame($idEntityFrame, $idLanguage = '')
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('name');
        $criteria->where("frame.idEntity = {$idEntityFrame}");
        if ($idLanguage != '') {
            $criteria->where("idLanguage = {$idLanguage}");
        }
        return $criteria;
    }

    public function listQualiaRelations($idEntityLU, $idLanguage = '')
    {
        $relation = new ViewRelation();
        $criteria = $relation->getCriteria()->select('relationType, entity1Type, entity2Type, entity3Type, idEntity1, idEntity2, idEntity3');
        $criteria->where("relationGroup = 'rgp_qualia'");
        $criteria->where("idEntity1 = {$idEntityLU}");
        $criteria->setAlias('R');
        $luCriteria = $this->getCriteria()->select('name, R.relationType, R.idEntity2, frame.idEntity idEntityFrame, frame.entries.name nameFrame');
        $luCriteria->joinCriteria($criteria, "R.idEntity2 = idEntity");
        if ($idLanguage != '') {
            $luCriteria->where("idLanguage = {$idLanguage}");
        }
        return $luCriteria;
    }

}

