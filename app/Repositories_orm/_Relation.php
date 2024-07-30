<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Enum\Join;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class _Relation extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('entityrelation')
            ->attribute('idEntityRelation', key: Key::PRIMARY)
            ->attribute('idRelation', key: Key::FOREIGN)
            ->attribute('idEntity1', key: Key::FOREIGN)
            ->attribute('idEntity2', key: Key::FOREIGN)
            ->attribute('idEntity3', key: Key::FOREIGN)
            ->attribute('entry', reference: 'relationType.entry')
            ->attribute('entity1Type', reference: 'entity1.type')
            ->attribute('entity2Type', reference: 'entity2.type')
            ->attribute('entity3Type', reference: 'entity3.type')
            ->associationOne('relationType', model: 'RelationType', key: 'idRelationType')
//        ->associationMany('entries', model:  'Entry', keys: 'entry:entry')
            ->associationOne('entity1', model: 'Entity', key: 'idEntity1')
            ->associationOne('entity2', model: 'Entity', key: 'idEntity2')
            ->associationOne('entity3', model: 'Entity', key: 'idEntity3', join: Join::LEFT)
            ->associationOne('lu1', model: 'LU', key: 'idEntity1:idEntity')
            ->associationOne('lu2', model: 'LU', key: 'idEntity2:idEntity')
            ->associationOne('frame1', model: 'Frame', key: 'idEntity1:idEntity')
            ->associationOne('frame2', model: 'Frame', key: 'idEntity2:idEntity')
            ->associationOne('frame', model: 'Frame', key: 'idEntity2:idEntity')
            ->associationOne('construction1', model: 'Construction', key: 'idEntity1:idEntity')
            ->associationOne('construction2', model: 'Construction', key: 'idEntity2:idEntity')
            ->associationOne('construction', model: 'Construction', key: 'idEntity2:idEntity')
            ->associationOne('semanticType1', model: 'SemanticType', key: 'idEntity1:idEntity')
            ->associationOne('semanticType2', model: 'SemanticType', key: 'idEntity2:idEntity')
            ->associationOne('semanticType', model: 'SemanticType', key: 'idEntity2:idEntity')
            ->associationOne('constructionElement1', model: 'ConstructionElement', key: 'idEntity1:idEntity')
            ->associationOne('constructionElement2', model: 'ConstructionElement', key: 'idEntity2:idEntity')
            ->associationOne('constructionElement', model: 'ConstructionElement', key: 'idEntity2:idEntity')
            ->associationOne('frameElement1', model: 'FrameElement', key: 'idEntity1:idEntity')
            ->associationOne('frameElement2', model: 'FrameElement', key: 'idEntity2:idEntity')
            ->associationOne('frameElement', model: 'FrameElement', key: 'idEntity2:idEntity')
            ->associationOne('inverseFrame', model: 'Frame', key: 'idEntity1:idEntity')
            ->associationOne('inverseConstruction', model: 'Construction', key: 'idEntity1:idEntity')
            ->associationOne('inverseSemanticType', model: 'SemanticType', key: 'idEntity1:idEntity')
            ->associationOne('subtypeOfConcept', model: 'Concept', key: 'idEntity2:idEntity')
            ->associationOne('associatedToConcept', model: 'Concept', key: 'idEntity2:idEntity')
            ->associationOne('domain', model: 'Domain', key: 'idEntity2:idEntity')
            ->associationOne('layerType', model: 'LayerType', key: 'idEntity1:idEntity')
            ->associationOne('genericLabel', model: 'GenericLabel', key: 'idEntity2:idEntity')
            ->associationOne('qualia', model: 'Qualia', key: 'idEntity3:idEntity')
            ->associationOne('pos', model: 'POS', key: 'idEntity2:idEntity');
    }

    public static function getById(int $id): object
    {
        return (object)self::first([
            ['idEntityRelation', '=', $id],
        ],[
            'idEntityRelation',
            'idRelationType',
            'idRelation',
            'idEntity1',
            'idEntity2',
            'idEntity3',
            'entry',
            'entity1Type',
            'entity2Type',
            'entity3Type',
        ]);
    }

    public static function removeFromEntityByRelationType(int $idEntity, int $idRelationType)
    {
        self::getCriteria()
            ->whereRaw("(idEntity1 = {$idEntity}) and (idRelationType = {$idRelationType})")
            ->delete();
    }

    public static function listForFrameGraph(int $idEntity): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        return self::getCriteria()
            ->select(['idEntityRelation','idRelationType', 'entry', 'entity1Type', 'entity2Type', 'entity3Type', 'idEntity1', 'idEntity2', 'idEntity3',
                'frame1.name frame1Name',
                'frame2.name frame2Name',
            ])
            ->where('entity1Type', '=', 'FR')
            ->where('entity2Type', '=', 'FR')
            ->where('frame1.idLanguage', '=', $idLanguage)
            ->where('frame2.idLanguage', '=', $idLanguage)
            ->whereRaw("((idEntity1 = {$idEntity}) or (idEntity2 = {$idEntity}))")
            ->all();
    }

    /*
    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idEntityRelation');
        if ($filter->idEntityRelation) {
            $criteria->where("idEntityRelation LIKE '{$filter->idEntityRelation}%'");
        }
        return $criteria;
    }

    public function listFrameRelations($idEntityFrame = '')
    {
        $criteria = $this->getCriteria()->select('idEntity1 as superFrame, relationtype.entry as idType, idEntity2 as subFrame');
        $criteria->where("relationtype.relationgroup.entry = 'rgp_frame_relations'");
        if ($idEntityFrame != '') {
            $criteria->where("((idEntity1 = {$idEntityFrame}) or (idEntity2 = {$idEntityFrame}))");
        }
        return $criteria;
    }

    public function listFrameElementRelations($idEntityFrame1, $idEntityFrame2, $relationEntry)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $frameElement = new FrameElement();
        $criteria1 = $frameElement->getCriteria()->select('idEntity');
        Base::relation($criteria1, 'FrameElement', 'Frame', 'rel_elementof');
        $criteria1->where("frame.idEntity = {$idEntityFrame1}");
        $fe1 = $criteria1->asQuery()->chunkResult('idEntity', 'idEntity');
        $criteria2 = $frameElement->getCriteria()->select('idEntity');
        Base::relation($criteria2, 'FrameElement', 'Frame', 'rel_elementof');
        $criteria2->where("frame.idEntity = {$idEntityFrame2}");
        $fe2 = $criteria2->asQuery()->chunkResult('idEntity', 'idEntity');
        $criteria = $this->getCriteria()->select('idEntity1 as superFE, relationtype.entry as idType, idEntity2 as subFE');
        $criteria->where("relationtype.entry = '{$relationEntry}'");
        $criteria->where("idEntity1", "in", $fe1);
        $criteria->where("idEntity2", "in", $fe2);
        return $criteria;
    }

    public function listFrameElementCoreRelations($idEntityFrame)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $frameElement = new FrameElement();
        $criteria1 = $frameElement->getCriteria()->select('idEntity');
        Base::relation($criteria1, 'FrameElement', 'Frame', 'rel_elementof');
        $criteria1->where("frame.idEntity = {$idEntityFrame}");
        $fe1 = $criteria1->asQuery()->chunkResult('idEntity', 'idEntity');
        //$criteria2 = $frameElement->getCriteria()->select('idEntity');
        //Base::relation($criteria2, 'frameelement', 'frame', 'rel_elementof');
        //$criteria2->where("frame.idFrame = {$idFrame}");
        //$fe2 = $criteria2->asQuery()->chunkResult('idEntity','idEntity');
        $criteria = $this->getCriteria()->select('idEntity1 as superFE, relationtype.entry as idType, idEntity2 as subFE');
        $criteria->where("relationtype.relationgroup.entry = 'rgp_fe_relations'");
        $criteria->where("idEntity1", "in", $fe1);
        $criteria->where("idEntity2", "in", $fe1);
        return $criteria;
    }

    public function listCxnRelations($idEntityCxn = '')
    {
        $criteria = $this->getCriteria()->select('idEntity1 as superCxn, relationtype.entry as idType, idEntity2 as subCxn');
        $criteria->where("relationtype.relationgroup.entry = 'rgp_cxn_relations'");
        if ($idEntityCxn != '') {
            $criteria->where("((idEntity1 = {$idEntityCxn}) or (idEntity2 = {$idEntityCxn}))");
        }
        return $criteria;
    }

    public function listCxnFrameRelations($idEntityCxn = '')
    {
        $criteria = $this->getCriteria()->select('idEntity1, relationtype.entry as idType, idEntity2');
        $criteria->where("relationtype.entry = 'rel_evokes'");
        if ($idEntityCxn != '') {
            $criteria->where("((idEntity1 = {$idEntityCxn}) or (idEntity2 = {$idEntityCxn}))");
        }
        return $criteria;
    }

    public function listCERelations($idEntityCxn1, $idEntityCxn2, $relationEntry)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
//        $ce = new ConstructionElement();
//        $criteria1 = $ce->getCriteria()->select('idEntity');
//        Base::relation($criteria1, 'ConstructionElement', 'Construction', 'rel_elementof');
//        $criteria1->where("construction.idEntity = {$idEntityCxn1}");
//        $ce1 = $criteria1->asQuery()->chunkResult('idEntity', 'idEntity');
//        $criteria2 = $ce->getCriteria()->select('idEntity');
//        Base::relation($criteria2, 'ConstructionElement', 'Construction', 'rel_elementof');
//        $criteria2->where("construction.idEntity = {$idEntityCxn2}");
//        $ce2 = $criteria2->asQuery()->chunkResult('idEntity', 'idEntity');
//        $criteria = $this->getCriteria()->select('idEntity1 as superCE, relationtype.entry as idType, idEntity2 as subCE');
//        $criteria->where("relationtype.entry = '{$relationEntry}'");
//        $criteria->where("idEntity1", "in", $ce1);
//        $criteria->where("idEntity2", "in", $ce2);
//        return $criteria;
        $cmd = <<<HERE
        SELECT idEntity1,r.relationType as idType, idEntity2
        FROM view_relation r
        JOIN (
            select ce.idEntity
            from constructionelement ce
            join construction c on (ce.idConstruction = c.idConstruction)
            where (c.idEntity = {$idEntityCxn1})
        ) sce1 on (r.idEntity1 = sce1.idEntity)
        JOIN (
            select ce.idEntity
            from constructionelement ce
            join construction c on (ce.idConstruction = c.idConstruction)
            where (c.idEntity = {$idEntityCxn2})
        ) sce2 on (r.idEntity1 = sce2.idEntity)
        WHERE (r.relationType = '{$relationEntry}')
HERE;
        return $this->getDb()->getQueryCommand($cmd)->getResult();
    }

    public function listCEFERelations($idEntityCxn, $idEntityFrame, $relationEntry)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
//        $cxnElement = new ConstructionElement();
//        $criteria1 = $cxnElement->getCriteria()->select('idEntity');
//        Base::relation($criteria1, 'ConstructionElement', 'Construction', 'rel_elementof');
//        $criteria1->where("construction.idEntity = {$idEntityCxn}");
//        $ce = $criteria1->asQuery()->chunkResult('idEntity', 'idEntity');
//        $frameElement = new FrameElement();
//        $criteria2 = $frameElement->getCriteria()->select('idEntity');
//        Base::relation($criteria2, 'FrameElement', 'Frame', 'rel_elementof');
//        $criteria2->where("frame.idEntity = {$idEntityFrame}");
//        $fe = $criteria2->asQuery()->chunkResult('idEntity', 'idEntity');
//        $criteria = $this->getCriteria()->select('idEntity1, relationtype.entry as idType, idEntity2');
//        $criteria->where("relationtype.entry = '{$relationEntry}'");
//        $criteria->where("idEntity1", "in", $ce);
//        $criteria->where("idEntity2", "in", $fe);
//        return $criteria;

        $cmd = <<<HERE
        SELECT idEntity1,r.relationType as idType, idEntity2
        FROM view_relation r
        JOIN (
            select ce.idEntity
            from constructionelement ce
            join construction c on (ce.idConstruction = c.idConstruction)
            where (c.idEntity = {$idEntityCxn})
        ) sce on (r.idEntity1 = sce.idEntity)
        JOIN (
            select fe.idEntity
            from frameelement fe
            join frame f on (fe.idFrame = f.idFrame)
            where (f.idEntity = {$idEntityFrame})
        ) sfe on (r.idEntity2 = sfe.idEntity)
        WHERE (r.relationType = '{$relationEntry}')
HERE;
        return $this->getDb()->getQueryCommand($cmd)->getResult();

    }

    public function remove($idRelationType, $idEntity1, $idEntity2)
    {
        $delete = $this->getDeleteCriteria();
        $delete->where('idRelationType', '=', $idRelationType);
        $delete->where('idEntity1', '=', $idEntity1);
        $delete->where('idEntity2', '=', $idEntity2);
        $delete->delete();
    }

    public function removeAllFromEntity(int $idEntity)
    {
        $this->getCriteria()
            ->whereRaw("(idEntity1 = {$idEntity}) or (idEntity2 = {$idEntity})  or (idEntity3 = {$idEntity})")
            ->delete();
    }



    public function saveFrameRelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $this->setPersistent(false);
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                // remove and save - to avoid duplicates
                $this->remove($idRelationType, $relation->idSource, $relation->idTarget);
                $this->setIdRelationType($idRelationType);
                $this->setIdEntity1($relation->idSource);
                $this->setIdEntity2($relation->idTarget);
                $this->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error saving relations.");
        }
    }

    public function deleteFrameRelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $relationType->getByEntry($relation->idRelationType);
                $idRelationType = $relationType->getIdRelationType();
                $this->remove($idRelationType, $relation->superFrame, $relation->subFrame);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error deleting relations. " . $e);
        }
    }

    public function deleteFramalDomain(Frame $frame)
    {
        $this->beginTransaction();
        try {
            $relationType = new RelationType();
            $relationType->getByEntry('rel_framal_domain');
            $this->removeFromEntityByRelationType($frame->idEntity, $relationType->idRelationType);
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new ERunTimeException("Error deleting relations. " . $e);
        }
    }

    public function saveFERelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $this->setPersistent(false);
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                // remove and save - to avoid duplicates
                $this->remove($idRelationType, $relation->idSource, $relation->idTarget);
                $this->setIdRelationType($idRelationType);
                $this->setIdEntity1($relation->idSource);
                $this->setIdEntity2($relation->idTarget);
                $this->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error saving relations.");
        }
    }

    public function deleteFERelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $relationType->getByEntry($relation->idRelationType);
                $idRelationType = $relationType->getIdRelationType();
                $this->remove($idRelationType, $relation->superFrameElement, $relation->subFrameElement);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error deleting relations. " . $e);
        }
    }

    public function saveFECoreRelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $this->setPersistent(false);
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                // remove and save - to avoid duplicates
                $this->remove($idRelationType, $relation->idSource, $relation->idTarget);
                $this->setIdRelationType($idRelationType);
                $this->setIdEntity1($relation->idSource);
                $this->setIdEntity2($relation->idTarget);
                $this->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error saving relations.");
        }
    }

    public function deleteFECoreRelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $relationType->getByEntry($relation->idRelationType);
                $idRelationType = $relationType->getIdRelationType();
                $this->remove($idRelationType, $relation->superFE, $relation->subFE);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error deleting relations. " . $e);
        }
    }

    public function saveCxnRelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $this->setPersistent(false);
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                // remove and save - to avoid duplicates
                $this->remove($idRelationType, $relation->idSource, $relation->idTarget);
                $this->setIdRelationType($idRelationType);
                $this->setIdEntity1($relation->idSource);
                $this->setIdEntity2($relation->idTarget);
                $this->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error saving relations.");
        }
    }

    public function deleteCxnRelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $relationType->getByEntry($relation->idRelationType);
                $idRelationType = $relationType->getIdRelationType();
                $this->remove($idRelationType, $relation->superCxn, $relation->subCxn);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error deleting relations. " . $e);
        }
    }

    public function saveCERelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $this->setPersistent(false);
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                // remove and save - to avoid duplicates
                $this->remove($idRelationType, $relation->idSource, $relation->idTarget);
                $this->setIdRelationType($idRelationType);
                $this->setIdEntity1($relation->idSource);
                $this->setIdEntity2($relation->idTarget);
                $this->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error saving relations.");
        }
    }

    public function deleteCERelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $relationType->getByEntry($relation->idRelationType);
                $idRelationType = $relationType->getIdRelationType();
                $this->remove($idRelationType, $relation->superCE, $relation->subCE);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error deleting relations. " . $e);
        }
    }

    public function saveCxnFrameRelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $this->setPersistent(false);
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                // remove and save - to avoid duplicates
                $this->remove($idRelationType, $relation->idEntity1, $relation->idEntity2);
                $this->setIdRelationType($idRelationType);
                $this->setIdEntity1($relation->idEntity1);
                $this->setIdEntity2($relation->idEntity2);
                $this->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error saving relations.");
        }
    }

    public function deleteCxnFrameRelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                $this->remove($idRelationType, $relation->idEntity1, $relation->idEntity2);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error deleting relations. " . $e);
        }
    }

    public function saveCEFERelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $this->setPersistent(false);
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                // remove and save - to avoid duplicates
                $this->remove($idRelationType, $relation->idEntity1, $relation->idEntity2);
                $this->setIdRelationType($idRelationType);
                $this->setIdEntity1($relation->idEntity1);
                $this->setIdEntity2($relation->idEntity2);
                $this->save();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error saving relations.");
        }
    }

    public function deleteCEFERelations($relations)
    {
        $transaction = $this->beginTransaction();
        try {
            $relationType = new RelationType();
            foreach ($relations as $relation) {
                $relationType->getByEntry($relation->idType);
                $idRelationType = $relationType->getIdRelationType();
                $this->remove($idRelationType, $relation->idEntity1, $relation->idEntity2);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new ERunTimeException("Error deleting relations. " . $e);
        }
    }
*/
}
