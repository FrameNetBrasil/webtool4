<?php
namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Construction extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('construction')
            ->attribute('idConstruction', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('active', type: Type::INTEGER)
            ->attribute('abstract', type: Type::INTEGER)
            ->attribute('name', reference: 'entries.name')
            ->attribute('description', reference: 'entries.description')
            ->attribute('idLanguageCxn', field: 'idLanguage')
            ->attribute('idLanguage', reference: 'entries.idLanguage')
            ->associationMany('entries', model: 'Entry', keys: 'idEntity:idEntity')
            ->associationMany('relations', model: 'Relation', keys: 'idEntity:idEntity1')
            ->associationMany('inverseRelations', model: 'Relation', keys: 'idEntity:idEntity2')
            ->associationOne('entity', model: 'Entity')
            ->associationOne('language', model: 'Language');

    }
    /*
    public function getData()
    {
        $data = parent::getData();
        $data = (object)array_merge((array)$data, (array)$this->getEntryObject());
        $data = (object)array_merge((array)$data, (array)$this->getLanguage()->getData());
        return $data;
    }

    public function getByEntry($entry)
    {
        $criteria = $this->getCriteria()->select('*');
        $criteria->where("entry = '{$entry}'");
        $this->retrieveFromCriteria($criteria);
    }

    public function getEntryObject()
    {
        $criteria = $this->getCriteria()->select('entries.name, entries.description, entries.nick');
        $criteria->where("idConstruction = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->asObjectArray()[0];
    }

    public function getName()
    {
        $criteria = $this->getCriteria()->select('entries.name as name');
        $criteria->where("idConstruction = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->getResult()[0]['name'];
    }

    public function getNick()
    {
        $criteria = $this->getCriteria()->select('entries.nick as nick');
        $criteria->where("idConstruction = {$this->getId()}");
        Base::entryLanguage($criteria);
        return $criteria->asQuery()->getResult()[0]['nick'];
    }

    public function getByIdEntity($idEntity)
    {
        $filter = (object)[
            'idEntity' => $idEntity
        ];
        $criteria = $this->listByFilter($filter);
        $this->retrieveFromCriteria($criteria);
    }

    public function listAll()
    {
        $criteria = $this->getCriteria()->select('*, entries.name as name')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        return $criteria;
    }

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*, entries.name as name, language.language')->orderBy('entries.name');
        Base::entryLanguage($criteria);
        if ($filter->idConstruction) {
            $criteria->where("idConstruction = {$filter->idConstruction}");
        }
        if ($filter->active == '1') {
            $criteria->where("active = 1");
        }
        if ($filter->idEntity != '') {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        if ($filter->idEntity != '') {
            $criteria->where("idEntity = {$filter->idEntity}");
        }
        if ($filter->idLanguage != '') {
            $criteria->where("idLanguage = {$filter->idLanguage}");
        }
        if ($filter->cxn) {
            $criteria->where("upper(entries.name) LIKE upper('%{$filter->cxn}%')");
        }
        if ($filter->name) {
            $name = (strlen($filter->name) > 1) ? $filter->name : 'none';
            $criteria->where("upper(entries.name) LIKE upper('{$name}%')");
        }
        return $criteria;
    }

    public function listForLookupName($name = '')
    {
        $criteria = $this->getCriteria()->select("idConstruction, concat(entries.name, ' [', language.language,']') as name")->orderBy('entries.name');
        Base::entryLanguage($criteria);
        $name = (strlen($name) > 1) ? $name : 'none';
        $criteria->where("upper(entries.name) LIKE upper('{$name}%')");
        return $criteria;
    }

    public function listForExport($idCxns)
    {
        $criteria = $this->getCriteria()->select('idConstruction, entry, abstract, active, idEntity, language.language')->orderBy('entry');
        $criteria->where("idConstruction", "in", $idCxns);
        return $criteria;
    }

    public function listCE()
    {
        $ce = new ConstructionElement();
        $criteria = $ce->getCriteria()->select('idConstructionElement, entry, entries.name as name, color.rgbFg, color.rgbBg, color.idColor, idEntity, head');
        Base::entryLanguage($criteria);
        Base::relation($criteria, 'ConstructionElement', 'construction', 'rel_elementof');
        $criteria->where("construction.idConstruction = {$this->getId()}");
        $criteria->orderBy('entries.name');
        return $criteria;
    }

    public function listConstraints()
    {
        $constraint = new ViewConstraint();
        return $constraint->getByIdConstrained($this->getIdEntity());
    }

    public function listCEConstraints()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE
SELECT ce.idEntity,  ceentries.name as name
    FROM View_ConstructionElement ce
    JOIN entry ceentries on (ce.entry = ceentries.entry)
    WHERE ceentries.idLanguage = {$idLanguage}
    AND ce.idConstruction = {$this->getId()}
UNION
SELECT cn2.idConstraint, concat(ce1entries.name,'.',ce2entries.name) name
    FROM View_ConstructionElement ce1
    JOIN View_Constraint cn1 on (ce1.idEntity = cn1.idConstrained)
    JOIN View_Constraint cn2 on (cn1.idConstraint = cn2.idConstrained)
    JOIN View_ConstructionElement ce2 on (cn2.idConstrainedBy = ce2.idEntity)
    JOIN entry ce1entries on (ce1.entry = ce1entries.entry)
    JOIN entry ce2entries on (ce2.entry = ce2entries.entry)
    WHERE (cn1.entry = 'con_cxn')
    AND (cn2.entry = 'con_element')
    AND (ce1entries.idLanguage = {$idLanguage})
    AND (ce2entries.idLanguage = {$idLanguage})
    AND (ce1.idConstruction = {$this->getId()})
UNION
SELECT cn4.idConstraint, concat(ce1entries.name,'.',ce2entries.name,'.',ce3entries.name) name
    FROM View_ConstructionElement ce1
    JOIN View_Constraint cn1 on (ce1.idEntity = cn1.idConstrained)
    JOIN View_Constraint cn2 on (cn1.idConstraint = cn2.idConstrained)
    JOIN View_ConstructionElement ce2 on (cn2.idConstrainedBy = ce2.idEntity)
    JOIN View_Constraint cn3 on (cn2.idConstraint = cn3.idConstrained)
    JOIN View_Constraint cn4 on (cn3.idConstraint = cn4.idConstrained)
    JOIN View_ConstructionElement ce3 on (cn4.idConstrainedBy = ce3.idEntity)
    JOIN entry ce1entries on (ce1.entry = ce1entries.entry)
    JOIN entry ce2entries on (ce2.entry = ce2entries.entry)
    JOIN entry ce3entries on (ce3.entry = ce3entries.entry)
    WHERE (cn1.entry = 'con_cxn')
    AND (cn2.entry = 'con_element')
    AND (cn3.entry = 'con_cxn')
    AND (cn4.entry = 'con_element')
    AND (ce1entries.idLanguage = {$idLanguage})
    AND (ce2entries.idLanguage = {$idLanguage})
    AND (ce3entries.idLanguage = {$idLanguage})
    AND (ce1.idConstruction = {$this->getId()})

HERE;

        $result = $this->getDb()->getQueryCommand($cmd)->chunkResult('idEntity', 'name');
        return $result;
    }

    public function listDirectRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedCxn.name, relatedCxn.idEntity, relatedCxn.idConstruction, entry_relatedCxn.entry as cxnEntry
        FROM Construction
            INNER JOIN Entity entity1
                ON (Construction.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN Construction relatedCxn
                ON (entity2.idEntity = relatedCxn.idEntity)
            INNER JOIN Entry entry_relatedCxn
                ON (relatedCxn.entry = entry_relatedCxn.entry)
        WHERE (Construction.idConstruction = {$this->getId()})
            AND (RelationType.entry in (
                'rel_inheritance_cxn', 'rel_inhibits'))
           AND (entry_relatedCxn.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedCxn.name

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idConstruction,cxnEntry');
        return $result;

    }

    public function listInverseRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT RelationType.entry, entry_relatedCxn.name, relatedCxn.idEntity, relatedCxn.idConstruction, entry_relatedCxn.entry as cxnEntry
        FROM Construction
            INNER JOIN Entity entity2
                ON (Construction.idEntity = entity2.idEntity)
            INNER JOIN EntityRelation
                ON (entity2.idEntity = EntityRelation.idEntity2)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity1
                ON (EntityRelation.idEntity1 = entity1.idEntity)
            INNER JOIN Construction relatedCxn
                ON (entity1.idEntity = relatedCxn.idEntity)
            INNER JOIN Entry entry_relatedCxn
                ON (relatedCxn.entry = entry_relatedCxn.entry)
        WHERE (Construction.idConstruction = {$this->getId()})
            AND (RelationType.entry in (
                'rel_inheritance_cxn' ))
           AND (entry_relatedCxn.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, entry_relatedCxn.name

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idConstruction,cxnEntry');
        return $result;

    }

    public function listInheritanceFromRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

        SELECT RelationType.entry, Entry.name, Construction.idEntity, Construction.idConstruction, Construction.entry as cxnEntry, EntityRelation.idEntityRelation
        FROM Construction
            INNER JOIN Entity entity1
                ON (Construction.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN Construction relatedCxn
                ON (entity2.idEntity = relatedCxn.idEntity)
            INNER JOIN Entry
                ON (Entry.entry = Construction.entry)
        WHERE (relatedCxn.idConstruction = {$this->getId()})
            AND (RelationType.entry in ('rel_inheritance_cxn'))
           AND (Entry.idLanguage = {$idLanguage} )
        ORDER BY RelationType.entry, Entry.name

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idConstruction,cxnEntry,idEntityRelation');
        return $result;

    }

    public function listEvokesRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE


SELECT entry, name, nick, idEntity, idFrame, frameEntry, type,idEntityRelation
FROM (
        SELECT RelationType.entry, entry_relatedFrame.name, entry_relatedFrame.nick, relatedFrame.idEntity, relatedFrame.idFrame, relatedFrame.entry as frameEntry, entity2.type, EntityRelation.idEntityRelation
        FROM Construction
            INNER JOIN Entity entity1
                ON (Construction.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN Frame relatedFrame
                ON (entity2.idEntity = relatedFrame.idEntity)
            INNER JOIN Entry entry_relatedFrame
                ON (relatedFrame.entry = entry_relatedFrame.entry)
        WHERE (Construction.idConstruction = {$this->getId()})
            AND (RelationType.entry in (
                'rel_evokes'))
           AND (entry_relatedFrame.idLanguage = {$idLanguage} )
        UNION
        SELECT RelationType.entry, entry_relatedConcept.name, entry_relatedConcept.nick, relatedConcept.idEntity, relatedConcept.idConcept idFrame, relatedConcept.entry as frameEntry, entity2.type, EntityRelation.idEntityRelation
        FROM Construction
            INNER JOIN Entity entity1
                ON (Construction.idEntity = entity1.idEntity)
            INNER JOIN EntityRelation
                ON (entity1.idEntity = EntityRelation.idEntity1)
            INNER JOIN RelationType
                ON (EntityRelation.idRelationType = RelationType.idRelationType)
            INNER JOIN Entity entity2
                ON (EntityRelation.idEntity2 = entity2.idEntity)
            INNER JOIN Concept relatedConcept
                ON (entity2.idEntity = relatedConcept.idEntity)
            INNER JOIN Entry entry_relatedConcept
                ON (relatedConcept.entry = entry_relatedConcept.entry)
        WHERE (Construction.idConstruction = {$this->getId()})
            AND (RelationType.entry in (
                'rel_hasconcept'))
           AND (entry_relatedConcept.idLanguage = {$idLanguage} )
) evokes
ORDER BY entry, name

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->treeResult('entry', 'name,idEntity,idFrame,frameEntry,idEntityRelation');
        return $result;

    }

    public function listDaughterRelations()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

SELECT RelationType.entry, entry_relatedCxn.name, cx1.idEntity, cx1.idConstruction
FROM Construction cx1
     INNER JOIN Entity entity1
        ON (cx1.idEntity = entity1.idEntity)
     INNER JOIN EntityRelation
        ON (entity1.idEntity = EntityRelation.idEntity1)
     INNER JOIN RelationType
        ON (EntityRelation.idRelationType = RelationType.idRelationType)
     INNER JOIN Entity entity2
        ON (EntityRelation.idEntity2 = entity2.idEntity)
     INNER JOIN Construction cx2
        ON (entity2.idEntity = cx2.idEntity)
     INNER JOIN Entry entry_relatedCxn
        ON (cx1.entry = entry_relatedCxn.entry)
     WHERE (cx2.idConstruction =  {$this->getId()})
        AND (RelationType.entry in (
           'rel_daughter_of'))
        AND (entry_relatedCxn.idLanguage = {$idLanguage} )
ORDER BY RelationType.entry, entry_relatedCxn.name

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        return $result;

    }

    public function listAllHeiress($idCxn, &$heiress = [])
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $cmd = <<<HERE

SELECT RelationType.entry, entry_relatedCxn.name, cx2.idEntity, cx2.idConstruction
FROM Construction cx1
     INNER JOIN Entity entity1
        ON (cx1.idEntity = entity1.idEntity)
     INNER JOIN EntityRelation
        ON (entity1.idEntity = EntityRelation.idEntity1)
     INNER JOIN RelationType
        ON (EntityRelation.idRelationType = RelationType.idRelationType)
     INNER JOIN Entity entity2
        ON (EntityRelation.idEntity2 = entity2.idEntity)
     INNER JOIN Construction cx2
        ON (entity2.idEntity = cx2.idEntity)
     INNER JOIN Entry entry_relatedCxn
        ON (cx2.entry = entry_relatedCxn.entry)
     WHERE (cx1.idConstruction =  {$idCxn})
        AND (RelationType.entry in (
           'rel_inheritance_cxn'))
        AND (entry_relatedCxn.idLanguage = {$idLanguage} )
ORDER BY RelationType.entry, entry_relatedCxn.name

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        if (count($result) > 0) {
            foreach ($result as $row) {
                $heiress[] = $row;
                $this->listAllHeiress($row['idConstruction'], $heiress);
            }
        }

    }

    public function getStructure()
    {
        $idEntity = $this->getIdEntity();
        $cxnObject = (object)[
            'id' => $idEntity,
            'name' => $this->getName(),
            'entry' => $this->getEntry(),
            'type' => 'cxn',
            'abstract' => $this->getAbstract() ? true : false,
            'attributes' => (object)[],
        ];
        $vce = new ViewConstructionElement();
        $ces = $vce->listCEByIdConstruction($this->getId())->getResult();
        if (count($ces) == 0) {
            $cxnObject->type = 'pos';
            return $cxnObject;
        }
        $vc = new ViewConstraint();
        ddump('=======1 ======' . $this->getEntry());
        $idConstrainedSet = [];
        foreach ($ces as $ce) {
            $idConstrainedSet[] = $ce['idEntity'];
        }

        ddump('=== idConstrainedSet ==== ');
        ddump($idConstrainedSet);
        ddump('===========2 ============= ');

        $constraints = $vc->getByIdConstrainedSet($idConstrainedSet);
        foreach ($ces as $ce) {
            $ceEntry = $ce['entry'];
            $ceIdEntity = $ce['idEntity'];
            $ceEntryByIdEntity[$ce['idEntity']] = $ce['entry'];
            $ceObject = (object)[
                'name' => $ce['name'],
                'id' => $ceIdEntity,
                'type' => 'ce',
                'optional' => $ce['optional'] ? true : false,
                'head' => $ce['head'] ? true : false,
                'multiple' => $ce['multiple'] ? true : false,
            ];
            $cxnObject->attributes->$ceEntry = $ceObject;
            ddump($constraints);
            foreach ($constraints as $constraint) {
                if ($constraint['idConstrained'] == $ceIdEntity) {
                    if ($ceObject->constraints == '') {
                        $ceObject->constraints = (object)[];
                    }
                    $c = $constraint['relationType'];
                    if (!isset($ceObject->constraints->$c)) {
                        $ceObject->constraints->$c = [];
                    }

                    if ($constraint['relationType'] == 'rel_constraint_cxn') {
                        $recCxn = new Construction();
                        $recCxn->getByIdEntity($constraint['idConstrainedBy']);
                        $ceObject->constraints->$c[] = $recCxn->getStructure();
                    } else {
                        $ceObject->constraints->$c[] = $constraint['entry'];
                    }
                }
            }
        }
        ddump('===========3 ============= ');

        $chain = [];
        $vc->getChainByIdConstrained($idEntity, $idEntity, $chain);
        foreach ($chain as $constrainedBy) {
            $idConstraint = $constrainedBy['idConstrainedBy'];
            if ($cxnObject->constraints == '') {
                $cxnObject->constraints = (object)[];
            }
            $constraint = $vc->getConstraintData($idConstraint);
            ddump($constraint);
            $type = $constraint->entry;
            if (!isset($cxnObject->constraints->$type)) {
                $cxnObject->constraints->$type = [];
            }
            $cxnObject->constraints->$type[] = [
                $constraint->entry,
                $constraint->idConstrained,
                $constraint->idConstrainedBy
            ];
        }
        ddump('===========4 ============= ');

        $er = new EntityRelation();
        $vfe = new ViewFrameElement();
        $evokes = $this->listEvokesRelations();
        if (is_array($evokes['rel_evokes'])) {
            foreach ($evokes['rel_evokes'] as $frame) {
                if ($cxnObject->evokes == '') {
                    $cxnObject->evokes = (object)[];
                }
                $frameEntry = $frame['frameEntry'];
                $frameObject = $cxnObject->evokes->$frameEntry = (object)[];
                ddump('=========== 4.1 ============= ');
                //$cefeRelation = $er->listCEFERelations($idEntity, $frame['idEntity'], 'rel_evokes')->asQuery()->getResult();
                $cefeRelation = $er->listCEFERelations($idEntity, $frame['idEntity'], 'rel_evokes');
                ddump($cefeRelation);
                foreach ($cefeRelation as $relation) {
                    $ceEntry = $ceEntryByIdEntity[$relation['idEntity1']];
                    if ($frameObject->$ceEntry == '') {
                        $frameObject->$ceEntry = [];
                    }
                    ddump('=========== 4.2 ============= ');
                    $fe = $vfe->getByIdEntity($relation['idEntity2']);
                    $feEntry = $fe->entry;
                    $frameObject->$ceEntry[] = $feEntry;
                }
            }
        }

        ddump('===========5 ============= extends');
        $extends = $this->listInverseRelations();
        ddump($extends);
        if (is_array($extends['rel_inheritance_cxn'])) {
            foreach ($extends['rel_inheritance_cxn'] as $extend) {
                ddump($extend);
                if ($cxnObject->extends == '') {
                    $cxnObject->extends = [];
                }
                $parent = new Construction($extend['idConstruction']);
                $cxnObject->extends[] = $parent->getEntry();
                //$ceceRelations = $er->listCERelations($extend['idEntity'], $idEntity, 'rel_inheritance_cxn')->asQuery()->getResult();
                $ceceRelations = $er->listCERelations($extend['idEntity'], $idEntity, 'rel_inheritance_cxn');
                ddump($ceceRelations);
                foreach ($ces as $ce) {
                    $ceEntry = $ce['entry'];
                    $ceObject = $cxnObject->attributes->$ceEntry;
                    $ceObject->extends = [];
                    $ceIdEntity = $ce['idEntity'];
                    foreach ($ceceRelations as $ceceRelation) {
                        ddump('=== ceceRelation');
                        ddump($ceceRelation);
                        if ($ceceRelation['subCE'] == $ceIdEntity) {
                            $ceObject->extends[] = $ceceRelation['superCE'];
                        }
                    }
                }
            }
        }

        ddump('===========6 ============= inhibits');
        $inhibits = $this->listDirectRelations();
        ddump($inhibits);
        if (is_array($inhibits['rel_inhibits'])) {
            foreach ($inhibits['rel_inhibits'] as $inhibit) {
                ddump($inhibit);
                if ($cxnObject->inhibits == '') {
                    $cxnObject->inhibits = [];
                }
                $inhibited = new Construction($inhibit['idConstruction']);
                $cxnObject->inhibits[] = $inhibited->getEntry();
            }
        }

        return $cxnObject;
    }

    public function save($data)
    {
        $languages = Base::languages();
        $language = $languages[$data->idLanguage];
        $data->entry = 'cxn_' . $language . '_' . mb_strtolower(str_replace('cxn_', '', $data->name));
        $data->abstract = $data->abstract ?: false;
        $transaction = $this->beginTransaction();
        try {
            $entry = new Entry();
            if ($this->isPersistent()) {
                if ($this->getEntry() != $data->entry) {
                    $entity = new Entity($this->getIdEntity());
//                    Base::updateTimeLine($this->getEntry(), $data->entry);
                    $entity->setAlias($data->entry);
                    $entity->save();
                    $entry->updateEntry($this->getEntry(), $data->entry, $data->name);
                    $entry->setIdEntity($entity->getId());
                }
            } else {
                $entity = new Entity();
                $entity->setAlias($data->entry);
                $entity->setType('CX');
                $entity->save();
                $entry = new Entry();
                $entry->newEntry($data->entry, $entity->getId(), $data->name);
                $this->setIdEntity($entity->getId());
            }
            $this->setData($data);
            $this->setActive(true);
//            Base::entityTimelineSave($this->getIdEntity());
            parent::save();
            Timeline::addTimeline("construction",$this->getId(),"S");
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateEntry($newEntry)
    {
        $transaction = $this->beginTransaction();
        try {
//            Base::updateTimeLine($this->getEntry(), $newEntry);
            Timeline::addTimeline("construction",$this->getId(),"S");
            $entity = new Entity($this->getIdEntity());
            $entity->setAlias($newEntry);
            $entity->save();
            $entry = new Entry();
            $entry->updateEntry($this->getEntry(), $newEntry);
            $this->setEntry($newEntry);
            parent::save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function delete()
    {
        $transaction = $this->beginTransaction();
        try {
            $idEntity = $this->getIdEntity();
            // remove entry
            $entry = new Entry();
            $entry->deleteEntry($this->getEntry());
            // remove frame-relations
            Base::deleteAllEntityRelation($idEntity);
//            Base::entityTimelineDelete($this->getIdEntity());
            // remove this frame
            Timeline::addTimeline("construction",$this->getId(),"D");
            parent::delete();
            // remove entity
            $entity = new Entity($idEntity);
            $entity->delete();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    public function createFromData($data)
    {
        $this->setPersistent(false);
        $this->setEntry($data->entry);
        $this->setActive($data->active);
        $this->setIdEntity($data->idEntity);
        $this->setIdLanguage(Base::getIdLanguage($data->language));
        parent::save();
        Timeline::addTimeline("construction",$this->getId(),"S");
    }

    public function createRelationsFromData($data)
    {
        if ($data->evokes) {
            $frame = new Frame();
            foreach ($data->evokes as $frameEntry) {
                $frame->getByEntry($frameEntry);
                if ($frame->getIdEntity()) {
                    Base::createEntityRelation($this->getIdEntity(), 'rel_evokes', $frame->getIdEntity());
                }
            }
        }
        if ($data->relations) {
            $cxnRelated = new Construction();
            foreach ($data->relations as $relation) {
                $cxnRelated->getByEntry($relation[1]);
                if ($cxnRelated->getIdEntity()) {
                    Base::deleteEntityRelation($this->getIdEntity(), $relation[0], $cxnRelated->getIdEntity());
                    Base::createEntityRelation($this->getIdEntity(), $relation[0], $cxnRelated->getIdEntity());
                }
            }
        }
        if ($data->inverse) {
            $cxnRelated = new Construction();
            foreach ($data->inverse as $relation) {
                $cxnRelated->getByEntry($relation[1]);
                if ($cxnRelated->getIdEntity()) {
                    Base::deleteEntityRelation($cxnRelated->getIdEntity(), $relation[0], $this->getIdEntity());
                    Base::createEntityRelation($cxnRelated->getIdEntity(), $relation[0], $this->getIdEntity());
                }
            }
        }
    }
*/
}

