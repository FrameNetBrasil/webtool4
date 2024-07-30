<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class ViewFrameElement extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('view_frameelement')
            ->attribute('idFrameElement', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('typeEntry')
            ->attribute('frameEntry')
            ->attribute('frameIdEntity', type: Type::INTEGER)
            ->attribute('active', type: Type::INTEGER)
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('idFrame', key: Key::FOREIGN)
            ->attribute('idColor', key: Key::FOREIGN)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity')
            ->associationOne('frame', model: 'Frame', key: 'idFrame')
            ->associationOne('color', model: 'Color', key: 'idColor')
            ->associationMany('labels', model: 'Label', keys: 'idFrameElement:idLabelType');
    }
    public static function listByFilter($filter)
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $criteria = self::getCriteria()
            ->select([
                'idFrameElement',
                'entry',
                'typeEntry as coreType',
                'frameEntry',
                'frameIdEntity',
                'active',
                'idEntity',
                'idFrame',
                'idColor',
                'name',
                'description',
                'frame.name as frameName'
            ])->where('idLanguage','=', $idLanguage)
            ->where('frame.idLanguage','=', $idLanguage)
            ->orderBy('name');
        if ($filter->idFrameElement ?? false) {
            $op = is_array($filter->idFrameElement) ? "IN" : "=";
            $criteria->where("idFrameElement", $op, $filter->idFrameElement);
        }
        if ($filter->fe ?? false) {
            $criteria->where("name","startswith",$filter->fe);
        }
        $criteria->order([["name"],["frame.name"]]);
        return $criteria;
    }

    public function relations($idFrameElement = '', $relationType = '', $relationGroup = '')
    {
        $criteria = $this->getCriteria()->select('vr.*');
        $criteria->join('ViewFrameElement', 'ViewRelation vr', "(vr.idEntity1 = ViewFrameElement.idEntity) or (vr.idEntity2 = ViewFrameElement.idEntity) or (vr.idEntity3 = ViewFrameElement.idEntity)");
        if ($idFrameElement != '') {
            $criteria->where("idFrameElement = {$idFrameElement}");
        }
        if ($relationType != '') {
            $criteria->where("vr.relationType = '{$relationType}'");
        }
        if ($relationGroup != '') {
            $criteria->where("vr.relationGroup = '{$relationGroup}'");
        }
        return $criteria;
    }

    public function hasAnnotations($idFrameElement)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("idFrameElement = {$idFrameElement}");
        $criteria->where("idEntity = labels.idLabelType");
        $count = $criteria->asQuery()->count();
        return ($count > 0);
    }

    public function getByIdEntity($idEntity)
    {
        $criteria = $this->getCriteria()->select('*,entries.name as name, entries.nick as nick, frameEntries.name as frameName');
        $criteria->where("idEntity = {$idEntity}");
        $criteria->associationAlias("frame.entries", "frameEntries");
        Base::entryLanguage($criteria);
        Base::entryLanguage($criteria, 'frameEntries.');
        return (object)$criteria->asQuery()->getResult()[0];
    }


    public function listByIdEntityFrame($idEntityFrame, $idLanguage = '')
    {
        $criteria = $this->getCriteria()->select('*,entries.name as name')->orderBy('entries.name');
        $criteria->where("frame.idEntity = {$idEntityFrame}");
        if ($idLanguage != '') {
            $criteria->where("entries.idLanguage = {$idLanguage}");
        }
        return $criteria;
    }

    public function listFEFrameRelations($idEntityFE, $idLanguage = '')
    {
        $relation = new ViewRelation();
        $criteria = $relation->getCriteria()->select('relationType, entity1Type, entity2Type, entity3Type, idEntity1, idEntity2, idEntity3');
        $criteria->where("relationType = 'rel_constraint_frame'");
        $criteria->where("idEntity2 = {$idEntityFE}");
        $criteria->setAlias('R');
        $frame = new Frame();
        $frameCriteria = $frame->getCriteria()->select('entries.name, idEntity');
        $frameCriteria->joinCriteria($criteria, "R.idEntity3 = idEntity");
        if ($idLanguage != '') {
            $frameCriteria->where("entries.idLanguage = {$idLanguage}");
        }
        return $frameCriteria;
    }

}

