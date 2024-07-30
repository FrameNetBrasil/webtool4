<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class _ConstraintInstance extends Repository
{
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('entityrelation')
            ->attribute('idConstraintInstance', field: 'idEntityRelation', key: Key::PRIMARY)
            ->attribute('idConstraintType', field: 'idRelationType', type: Type::INTEGER)
            ->attribute('idConstraint', field: 'idEntity1', type: Type::INTEGER)
            ->attribute('idConstrained', field: 'idEntity2', type: Type::INTEGER)
            ->attribute('idConstrainedBy', field: 'idEntity3', type: Type::INTEGER)
            ->associationOne('constraintType', model: 'RelationType', key: 'idConstraintType:idRelationType')
            ->associationOne('entityConstraint', model: 'Entity', key: 'idConstraint:idEntity')
            ->associationOne('entityConstrained', model: 'Entity', key: 'idConstrained:idEntity')
            ->associationOne('entityConstrainedBy', model: 'Entity', key: 'idConstrainedBy:idEntity')
            ->associationOne('constrainedFE', model: 'FrameElement', key: 'idConstrained:idEntity');
    }
    static public function create(int $idConstraint, string $constraintTypeEntry, int $idConstrained, int $idConstrainedBy): object
    {
        $ct = ConstraintType::getByEntry($constraintTypeEntry);
        debug($ct);
        $data = (object)[
            'idConstraintType' => $ct->idConstraintType,
            'idConstraint' => $idConstraint,
            'idConstrained' => $idConstrained,
            'idConstrainedBy' => $idConstrainedBy
        ];
        $data->idConstraintInstance = ConstraintInstance::save($data);
        return $data;
    }

    /*
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('idConstraintInstance, idConstraintType, idConstraint, idConstrained, idConstrainedBy');
        if ($filter->idConstraintType) {
            $criteria->where("idConstraintType = {$filter->idConstraintType}");
        }
        if ($filter->idConstraint) {
            $criteria->where("idConstraint = {$filter->idConstraint}");
        }
        return $criteria;
    }

    public function getByIdConstraint($idConstraint)
    {
        $filter = (object)[
            'idConstraint' => $idConstraint
        ];
        $criteria = $this->listByFilter($filter);
        $this->retrieveFromCriteria($criteria);
    }

    public function delete()
    {
        $transaction = $this->beginTransaction();
        try {
            $view = new ViewConstraint();
            $data = $view->getConstraintData($this->getIdConstraint());
            ddump($data);
            if ($data->entry == 'rel_constraint_constraint') {
                Base::deleteAllEntityRelation($data->idConstrainedBy);
                $entity = new Entity($data->idConstrainedBy);
                $entity->delete();
            }
            // remove relations
            Base::deleteAllEntityRelation($this->getId());
            // remove this entity
            parent::delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function getConstraintData()
    {
        $view = new ViewConstraint();
        return $view->getConstraintData($this->getIdConstraint());
    }

    public function listConstraints()
    {
        $constraint = new ViewConstraint();
        return $constraint->getByIdConstrained($this->getId());
    }

    public function listByIdConstrained($idConstrained)
    {
        $constraint = new ViewConstraint();
        return $constraint->getByIdConstrained($idConstrained);
    }

    public function listConstraintsCN()
    {
        $constraint = new ViewConstraint();
        $cn = $constraint->listConstraintsCNCE($this->getId());
        if ($cn[0]['name'] == '') {
            $cn = $constraint->listConstraintsCNCN($this->getId());
        }
        return $cn;
    }

    public function deleteConstraintLU($idEntityLU, $idEntityConstraint)
    {
        $er = new EntityRelation();
        $transaction = $er->beginTransaction();
        $criteria = $er->getDeleteCriteria();
        $criteria->where("idEntity1 = {$idEntityLU}");
        $criteria->where("idEntity2 = {$idEntityConstraint}");
        $criteria->delete();
        $transaction->commit();
    }

    public function deleteConstraint()
    {
        $transaction = $this->beginTransaction();
        try {
            $entity = Entity::create($this->idConstraint);
            parent::delete();
            Base::deleteAllEntityRelation($entity->getId());
            $entity->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteConstraintMetonymyFEFE($idEntity1FE, $idEntity2FE)
    {
        Base::deleteEntityRelation($idEntity1FE, 'rel_festandsforfe', $idEntity2FE);
    }

    public function deleteConstraintMetonymyFELU($idEntityFE, $idEntityLU)
    {
        Base::deleteEntityRelation($idEntityFE, 'rel_festandsforlu', $idEntityLU);
    }
    */

}
