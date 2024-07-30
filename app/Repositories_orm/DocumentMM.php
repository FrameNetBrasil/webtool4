<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class DocumentMM extends Repository
{
    public static function map(ClassMap $classMap): void
    {

        $classMap->table('documentmm')
            ->attribute('idDocumentMM', key: Key::PRIMARY)
            ->attribute('name', reference: 'document.name')
            ->attribute('title')
            ->attribute('originalFile')
            ->attribute('sha1Name')
            ->attribute('audioPath')
            ->attribute('videoPath')
            ->attribute('videoWidth', type: Type::INTEGER)
            ->attribute('videoHeight', type: Type::INTEGER)
            ->attribute('alignPath')
            ->attribute('flickr30k')
            ->attribute('enabled')
            ->attribute('url')
            ->attribute('idDocument', type: Type::INTEGER, key: Key::FOREIGN)
            ->attribute('idLanguage', type: Type::INTEGER, key: Key::FOREIGN)
            ->associationOne('document', model: 'Document', key: 'idDocument:idDocument')
            ->associationMany('sentenceMM', model: 'SentenceMM', keys: 'idDocumentMM:idDocumentMM');
    }

    public static function getByIdDocument(int $idDocument)
    {
        return (object)self::first([
            ["idDocument","=",$idDocument],
        ]);
    }

    public function create($data)
    {
        $this->beginTransaction();
        try {
            $id = $this->saveData($data);
            Timeline::addTimeline("documentmm", $id, "C");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    public function saveData($data): ?int
    {
        $document = new Document();
        $document->setData($data);
        $document->save($data);
        $data->idDocument = $document->getId();
        $this->setData($data);
        parent::save();
        Timeline::addTimeline("documentmm", $this->getId(), "S");
        return $this->getId();
    }

    public function saveMM()
    {
        parent::save();
        Timeline::addTimeline("documentmm", $this->getId(), "S");
    }

    public function saveMMData($data)
    {
        $this->setData($data);
        parent::save();
        Timeline::addTimeline("documentmm", $this->getId(), "S");
    }

    public function listByFilter($filter)
    {
        $subcriteria = $this->getCriteria()->select('idDocument');
        $idDocument = array_column($subcriteria->asQuery()->getResult(), 'idDocument');
        $document = new Document();
        $criteria = $document->listByFilter($filter);
        $criteria->addCriteria('idDocument', 'IN', $idDocument);
        return $criteria;
    }

    public function listByCorpus($idCorpus)
    {
        $document = new Document();
        $criteria = $document->listByCorpus($idCorpus);
        $result = $criteria->asQuery()->getResult();
        $idDocument = array_column($result, 'idDocument');
        $documentsMM = [];
        foreach ($result as $row) {
            $documentsMM[$row['idDocument']] = $row;
        }
        $criteria = $this->getCriteria()->select('idDocumentMM, idDocument, flickr30k');
        $criteria->addCriteria('idDocument', 'IN', $idDocument);
        $result = $criteria->asQuery()->getResult();
        foreach ($result as $row) {
            $documentsMM[$row['idDocument']]['idDocumentMM'] = $row['idDocumentMM'];
            $documentsMM[$row['idDocument']]['flickr30k'] = $row['flickr30k'];
        }
        return $documentsMM;
    }

    public function listCorpusImageByFilter($filter)
    {
        $subcriteria = $this->getCriteria()->select('idDocument');
        $subcriteria->addCriteria('flickr30k', '=', '1');
        $idDocument = array_column($subcriteria->asQuery()->getResult(), 'idDocument');
        $document = new Document();
        $criteria = $document->getCriteria()->select('corpus.idCorpus, corpus.entries.name as name')->orderBy('corpus.entries.name');
        $criteria->addCriteria('corpus.entries.idLanguage', '=', \Manager::getSession()->idLanguage);
        $criteria->addCriteria('idDocument', 'IN', $idDocument);
        return $criteria;
    }

    public function listForLookup($name)
    {
        $criteria = $this->getCriteria()->select('document.idDocument,document.entries.name as name')->orderBy('document.entries.name');
        Base::entryLanguage($criteria, 'document');
        if ($name != '*') {
            $name = (strlen($name) > 1) ? $name : 'none';
            $criteria->where("upper(document.entries.name) LIKE upper('{$name}%')");
        }
        return $criteria;
    }

    public function listWordMM()
    {
        $wordMM = new WordMM();
        $criteria = $wordMM->getCriteria()
            ->select('*')
            ->where("idDocumentMM = {$this->idDocumentMM}")
            ->where('idSentenceMM is null')
            ->orderBy('startTime');
        return $criteria->asQuery()->getResult();
    }


    public function listSentenceMM()
    {
        $document = new Document();
        $document->getById($this->getIdDocument());
        $sentences = $document->listSentence()->chunkResult('idSentence', 'text');
        $idSentences = array_keys($sentences);
        if (!empty($idSentences)) {
            $listIdSentence = implode(',', $idSentences);
            ddump($listIdSentence);
        } else {
            $listIdSentence = '0';
        }
        $cmd = <<<HERE

select distinct smm.idSentenceMM, smm.startTimestamp, smm.endTimestamp, smm.idSentence, smm.startTime, smm.idImageMM, smm.idOriginMM
FROM sentencemm smm
where (smm.idSentence IN ({$listIdSentence}))
order by smm.startTime

HERE;
        $result = $this->getDb()->getQueryCommand($cmd)->getResult();
        foreach ($result as $i => $row) {
            $result[$i]['text'] = $sentences[$row['idSentence']];
        }
        return $result;
    }

    public function listObjectFrames()
    {
        $criteria = $this->getCriteria();
        $criteria->select("objectmm.objectframes.frameNumber,
        objectmm.objectframes.x,
        objectmm.objectframes.y,
        objectmm.objectframes.width,
        objectmm.objectframes.height");
        $criteria->where("idDocumentMM = {$this->getId()}");
        $criteria->orderBy('objectmm.objectframes.frameNumber');
        return $criteria->asQuery()->treeResult('frameNumber', 'x,y,width,height');
    }

    public function getObjects()
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $viewFrameElement = new ViewFrameElement();
        $lu = new LU();
        $criteria = $this->getCriteria();
        $criteria->select("objectmm.idObjectMM,
        objectmm.startFrame, objectmm.endFrame,
        objectmm.startTime, objectmm.endTime,
        objectmm.name, objectmm.status, objectmm.origin,
        objectmm.idLU, '' as lu, objectmm.idFrameElement, '' as idFrame, '' as frame, '' as idFE, '' as fe, '' as color");
        $criteria->where("idDocumentMM = {$this->getId()}");
        //$criteria->where("annotationmm.objectmm.status = 1");
        $criteria->orderBy('objectmm.startTime,objectmm.endTime');
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
            if ($object['idLU']) {
                $lu->getById($object['idLU']);
                //$object['lu'] = $lu->getName();
                $object['lu'] = $lu->getFullName();
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

    public function getObjectsInInterval($startTime, $endTime)
    {
        $objectMM = new ObjectMM();
        $criteria = $objectMM->getCriteria()
            ->select('idObjectMM')
            ->where('idDocumentMM', '=', $this->getIdDocumentMM())
            ->where('startTime', '>=', $startTime)
            ->where('endTime', '<=', $endTime);
        return array_column($criteria->asQuery()->getResult(), 'idObjectMM');
    }

    public function addSentence($data)
    {
        $sentenceMMData = null;
        $transaction = $this->beginTransaction();
        try {
            $document = new Document();
            $document->getById($this->getIdDocument());
            $paragraph = $document->createParagraph(1);
            $idLanguage = $this->getIdLanguage();
            $sentence = $document->createSentence($paragraph, 1, $data->text, $idLanguage);
            $sentenceMM = new SentenceMM();
            $sentenceMM->setStartTime((float)$data->startTimestamp);
            $sentenceMM->setEndTime((float)$data->endTimestamp);
            $sentenceMM->setStartTimestamp($data->startTimestamp);
            $sentenceMM->setEndTimestamp($data->endTimestamp);
            $sentenceMM->setIdSentence($sentence->getIdSentence());
            $sentenceMM->save();
            $sentenceMMData = $sentenceMM->getData();
            $sentenceMMData->text = $data->text;
            $transaction->commit();
        } catch (\EModelException $e) {
            $transaction->rollback();
        }
        return $sentenceMMData;
    }

    public function addCCSentence($data)
    {
        $sentenceMMData = null;
        $transaction = $this->beginTransaction();
        try {
            $document = new Document();
            $document->getById($this->getIdDocument());
            $paragraph = $document->createParagraph(1);
            $idLanguage = $this->getIdLanguage();
            $sentence = $document->createSentence($paragraph, 1, $data->text, $idLanguage);
            $sentenceMM = new SentenceMM();
            $sentenceMM->setStartTime((float)$data->startTimestamp);
            $sentenceMM->setEndTime((float)$data->endTimestamp);
            $sentenceMM->setStartTimestamp($data->startTimestamp);
            $sentenceMM->setEndTimestamp($data->endTimestamp);
            $sentenceMM->setOrigin(1);
            $sentenceMM->setIdDocumentMM($this->getIdDocumentMM());
            $sentenceMM->setIdSentence($sentence->getIdSentence());
            $sentenceMM->save();
            $sentenceMMData = $sentenceMM->getData();
            $sentenceMMData->text = $data->text;
            $transaction->commit();
        } catch (\EModelException $e) {
            $transaction->rollback();
        }
        return $sentenceMMData;
    }

    public function buildSentenceFromWords($words)
    {
        $sentenceMMData = null;
        if (!empty($words)) {
            $transaction = $this->beginTransaction();
            try {
                $wordMM = new WordMM();
                $listWords = $wordMM->getCriteria()
                    ->select('*')
                    ->where("idWordMM", "IN", $words)
                    ->orderBy('idWordMM')
                    ->asQuery()
                    ->getResult();
                $startTime = $endTime = '';
                $text = '';
                foreach ($listWords as $word) {
                    if ($startTime == '') {
                        $startTime = $word['startTimestamp'];
                    }
                    $text .= $word['word'] . ' ';
                    $endTime = $word['endTimestamp'];
                }
                $document = new Document();
                $document->getById($this->getIdDocument());
                $paragraph = $document->createParagraph(1);
                $idLanguage = $this->getIdLanguage();
                $text = ucfirst($text) . '.';
                $sentence = $document->createSentence($paragraph, 1, $text, $idLanguage);
                $sentenceMM = new SentenceMM();
                $sentenceMM->setStartTime($startTime);
                $sentenceMM->setEndTime($endTime);
                $sentenceMM->setStartTimestamp($startTime);
                $sentenceMM->setEndTimestamp($endTime);
                $sentenceMM->setIdSentence($sentence->getIdSentence());
                $sentenceMM->setIdDocumentMM($this->getIdDocumentMM());
                $sentenceMM->setOrigin(0);
                $sentenceMM->save();
                $sentenceMMData = $sentenceMM->getData();
                $sentenceMMData->text = $text;
                $updateCriteria = $wordMM->getUpdateCriteria();
                $updateCriteria->addColumnAttribute('idSentenceMM');
                $updateCriteria
                    ->where("idWordMM", "IN", $words)
                    ->update([$sentenceMM->getIdSentenceMM()]);
                $transaction->commit();
            } catch (\EModelException $e) {
                $transaction->rollback();
            }
        }
        return $sentenceMMData;
    }

    public function clearAllCCSentences()
    {
        $transaction = $this->beginTransaction();
        try {
            $sentenceMM = new SentenceMM();
            $ccSentences = $sentenceMM->listByFilter((object)[
                'idDocumentMM' => $this->getIdDocumentMM(),
                'origin' => 1
            ])->asQuery()->getResult();
            $listIdSentence = [];
            foreach ($ccSentences as $ccSentence) {
                $listIdSentence[] = $ccSentence['idSentence'];
            }
            if (count($listIdSentence) > 0) {
                $inListIdSentence = implode(',', $listIdSentence);

                $cmd = <<<HERE
DELETE FROM sentencemm
where (idSentence IN ({$inListIdSentence}))
and (origin = 1);

HERE;
                $this->getDb()->executeCommand($cmd);

                $sentence = new Sentence();
                $t2 = $sentence->beginTransaction();

                foreach ($listIdSentence as $idSentence) {
                    $sentence = $sentence->getById($idSentence);
                    $sentence->delete();
                }
                $t2->commit();
            }

            $transaction->commit();
        } catch (\EModelException $e) {
            $transaction->rollback();
        }

    }

    public function clearAllSentences()
    {
        $transaction = $this->beginTransaction();
        try {
            $cmd = <<<HERE

UPDATE WordMM set idSentenceMM = null WHERE (idDocumentMM = {$this->getIdDocumentMM()})

HERE;
            $this->getDb()->executeCommand($cmd);

            $document = new Document();
            $document->getById($this->getIdDocument());

            $t2 = $document->beginTransaction();

            $idSubCorpus = $document->getRelatedSubCorpusMultimodal();
            if ($idSubCorpus != '') {
                $cmd = <<<HERE

delete from label where idLayer in (
select idLayer from layer l where l.idAnnotationset in (
select idAnnotationSet from AnnotationSet where idSubCorpus = {$idSubCorpus}
));
delete from layer where l.idAnnotationset in (
select idAnnotationSet from AnnotationSet where idSubCorpus = {$idSubCorpus}
));
DELETE from AnnotationSet where idSubCorpus = {$idSubCorpus};

HERE;
                $document->getDb()->executeCommand($cmd);
            }

            $sentences = $document->listSentence()->chunkResult('idSentence', 'text');
            $idSentences = array_keys($sentences);
            if (!empty($idSentences)) {
                $listIdSentence = implode(',', $idSentences);
                ddump($listIdSentence);
            } else {
                $listIdSentence = '0';
            }
            $cmd = <<<HERE
DELETE FROM sentencemm
where (idSentence IN ({$listIdSentence}));

HERE;
            $this->getDb()->executeCommand($cmd);

            $document->deleteSentences();
            $t2->commit();

            $transaction->commit();
        } catch (\EModelException $e) {
            $transaction->rollback();
        }
    }

    public function clearSentences($idSentencesMM)
    {
        return;
        $transaction = $this->beginTransaction();
        try {
            $listIdSentenceMM = implode(',', $idSentencesMM);
            $cmd = <<<HERE

UPDATE WordMM set idSentenceMM = null WHERE (idSentenceMM IN ({$listIdSentenceMM}))

HERE;
            $this->getDb()->executeCommand($cmd);

            $sentenceMM = new SentenceMM();
            $sentences = $sentenceMM->getCriteria()
                ->select('idSentence')
                ->where('idSentenceMM', 'IN', $idSentencesMM)
                ->asQuery()
                ->getResult();

            $cmd = <<<HERE
DELETE FROM sentencemm
where (idSentenceMM IN ({$listIdSentenceMM}));

HERE;
            $this->getDb()->executeCommand($cmd);

            $sentence = new Sentence();
            $t2 = $sentence->beginTransaction();
            try {
                $idSentenceToDelete = [];
                foreach ($sentences as $row) {
                    $idSentenceToDelete[] = $row['idSentence'];
                }
                $listIdSentence = implode(',', $idSentenceToDelete);
                $cmd = <<<HERE
delete from label where idLayer in (
    select idLayer from layer l where l.idAnnotationset in (
      select idAnnotationSet from AnnotationSet where idSentence IN ({$listIdSentence})
));
delete from layer where l.idAnnotationset in (
   select idAnnotationSet from AnnotationSet  where idSentence IN ({$listIdSentence})
));
DELETE FROM annotationset
where (idSentence IN ({$listIdSentence}));
DELETE FROM sentence
where (idSentence IN ({$listIdSentence}));

HERE;
                $sentence->getDb()->executeCommand($cmd);
                $t2->commit();
            } catch (\EModelException $e) {
                $t2->rollback();
                throw new \EModelException('Delete fail.');
            }

            $transaction->commit();

        } catch (\EModelException $e) {
            $transaction->rollback();
        }
    }

    public function splitSentences($idSentencesMM)
    {
        if (!empty($idSentencesMM)) {
            $transaction = $this->beginTransaction();
            try {
                $listIdSentenceMM = implode(',', $idSentencesMM);
                $cmd = <<<HERE

UPDATE WordMM set idSentenceMM = null WHERE (idSentenceMM IN ({$listIdSentenceMM}))

HERE;
                $this->getDb()->executeCommand($cmd);

                $sentenceMM = new SentenceMM();
                $sentences = $sentenceMM->getCriteria()
                    ->select('idSentence')
                    ->where('idSentenceMM', 'IN', $idSentencesMM)
                    ->asQuery()
                    ->getResult();

                $cmd = <<<HERE
DELETE FROM sentencemm
where (idSentenceMM IN ({$listIdSentenceMM}));

HERE;
                $this->getDb()->executeCommand($cmd);

                $sentence = new Sentence();
                $t2 = $sentence->beginTransaction();
                try {
                    $idSentenceToDelete = [];
                    foreach ($sentences as $row) {
                        $idSentenceToDelete[] = $row['idSentence'];
                    }
                    $listIdSentence = implode(',', $idSentenceToDelete);
                    $cmd = <<<HERE
DELETE FROM annotationset
where (idSentence IN ({$listIdSentence}));
DELETE FROM sentence
where (idSentence IN ({$listIdSentence}));

HERE;
                    $sentence->getDb()->executeCommand($cmd);
                    $t2->commit();
                } catch (\EModelException $e) {
                    $t2->rollback();
                    throw new \EModelException('Delete fail.');
                }

                $transaction->commit();
            } catch (\EModelException $e) {
                $transaction->rollback();
            }
        }
    }

    public function clearAllCharonObjects()
    {
        $transaction = $this->beginTransaction();
        try {
            $cmd = <<<HERE
DELETE FROM objectframemm
where (idObjectMM IN (
    select idObjectMM
    from objectmm
    where (idDocumentMM = {$this->getIdDocumentMM()})
    and (origin = 1))
);
DELETE FROM objectmm
where (idDocumentMM = {$this->getIdDocumentMM()})
and (origin = 1);

HERE;
            $this->getDb()->executeCommand($cmd);
            $transaction->commit();
        } catch (\EModelException $e) {
            $transaction->rollback();
        }
    }

    public function addCharonObject($frameIndex, $label, $box)
    {
        $transaction = $this->beginTransaction();
        try {
            $objectMM = new ObjectMM();
            $data = (object)[
                'idDocumentMM' => $this->getIdDocumentMM(),
                'name' => $label,
                'status' => 0,
                'origin' => 1,
                'startFrame' => $frameIndex,
                'endFrame' => $frameIndex,
                'startTime' => ($frameIndex - 1) / 25,
                'endTime' => ($frameIndex - 1) / 25
            ];
            $objectMM->save($data);

            $objectFrameMM = new ObjectFrameMM();
            $x = $box[0];
            $y = $box[2];
            $x0 = $box[1];
            $y0 = $box[3];
            $width = abs($x0 - $x + 1);
            $height = abs($y0 - $y + 1);
            $data = (object)[
                'idObjectMM' => $objectMM->getIdObjectMM(),
                'frameNumber' => $frameIndex,
                'frameTime' => ($frameIndex - 1) / 25,
                'blocked' => 0,
                'x' => $x,
                'y' => $y,
                'height' => $height,
                'width' => $width
            ];
            $objectFrameMM->save($data);

            $transaction->commit();
        } catch (\EModelException $e) {
            $transaction->rollback();
        }
    }

    public function deleteFlickr30k()
    {
        $deleteCriteria = $this->getDeleteCriteria();
        $deleteCriteria->where('title', 'LIKE', "'flicker30k%'");
        $deleteCriteria->delete();
    }

    public function listImageSentenceMM(object $filter)
    {
        $document = new Document();
        $document->getById($this->idDocument);
        $sentences = collect($document->listSentence())->pluck('text','idSentence')->all();//->chunkResult('idSentence', 'text');
        $idSentences = array_keys($sentences);
        if (!empty($idSentences)) {
            $listIdSentence = implode(',', $idSentences);
        } else {
            $listIdSentence = '0';
        }
        $whereImage = ($filter->image ?? false) ? " and (imm.name LIKE '{$filter->image}%')" : "";
        $cmd = <<<HERE

select smm.idSentenceMM, imm.name as image, smm.idSentence, count(*) as n, count(am.idAnnotationMM) as i
FROM sentencemm smm
join imagemm imm on (smm.idImagemm = imm.idImagemm)
join objectsentencemm osmm on (smm.idSentencemm = osmm.idSentencemm)
join objectmm omm on (osmm.idObjectMM = omm.idObjectMM)
left join annotationmm am on (osmm.idObjectSentenceMM = am.idObjectSentenceMM)
where (smm.idSentence IN ({$listIdSentence}))
and (smm.idDocumentMM = {$this->getId()}) {$whereImage}
group by smm.idSentenceMM, imm.name, smm.idSentence
order by 1

HERE;
        $result = $this->query($cmd);
        foreach ($result as $i => $row) {
            $result[$i]['text'] = $sentences[$row['idSentence']];
            if ($result[$i]['i'] == 0) {
                $result[$i]['status'] = 'red';
            } else if ($result[$i]['i'] < $result[$i]['n']) {
                $result[$i]['status'] = 'yellow';
            } else {
                $result[$i]['status'] = 'green';
            }
        }
        return $result;
    }

/*
     public static function getById(int $id): object|array|null
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['idDocumentMM','=',$id]
        ];
        return parent::one($filters, ['*']);
    }

    public static function getByIdDocument(int $idDocument): object|array|null
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [
            ['document.corpus.entries.idLanguage', '=', $idLanguage],
            ['document.entries.idLanguage', '=', $idLanguage],
            ['idDocument','=',$idDocument]
        ];
        return parent::one($filters, ['idDocumentMM','idDocument','title','videoPath','videoHeight','videoWidth']);
    }

    public static function listByCorpusDocumentStatic(string $corpusName = '', string $documentName = ''): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        $filters[] = ['document.corpus.entries.idLanguage', '=', $idLanguage];
        if ($corpusName != '') {
            $filters[] = ['document.corpus.name', 'startswith', $corpusName];
        }
        $filters[] = ['document.entries.idLanguage', '=', $idLanguage];
        if ($documentName != '') {
            $filters[] = ['document.name', 'startswith', $documentName];
        }
        $filters[] = ['document.corpus.active','=',1];
        $filters[] = ['document.active','=',1];
        $filters[] = ['flickr30k','>',0];
        return self::list($filters, [
            'document.corpus.name as corpusName',
            'document.corpus.idCorpus',
            'document.name as documentName',
            'document.idDocument'
        ], [['document.corpus.name'],['document.name']]);
    }

    public static function listByCorpusDocumentDynamic(string $corpusName = '', string $documentName = ''): array
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $filters = [];
        $filters[] = ['document.corpus.entries.idLanguage', '=', $idLanguage];
        if ($corpusName != '') {
            $filters[] = ['document.corpus.name', 'startswith', $corpusName];
        }
        $filters[] = ['document.entries.idLanguage', '=', $idLanguage];
        if ($documentName != '') {
            $filters[] = ['document.name', 'startswith', $documentName];
        }
        $filters[] = ['document.corpus.active','=',1];
        $filters[] = ['document.active','=',1];
        $filters[] = ['videoPath','<>',""];
        return self::list($filters, [
            'document.corpus.name as corpusName',
            'document.corpus.idCorpus',
            'document.name as documentName',
            'document.idDocument',
            'idDocumentMM'
        ], [['document.corpus.name'],['document.name']]);
    }

    public static function listSentencesByDocument(int $idDocument, string $word = '', int $offset = 0, int $limit = 0): array
    {
        $filters = [];
        $filters[] = ['idDocument','=', $idDocument];
        if ($word != '') {
            $filters[] = ['sentenceMM.sentence.text','contains', $word];
        }
        $count = self::getCriteria()
            ->where($filters)
            ->select('count(sentenceMM.idSentence) as c')
            ->get()[0]['c'];
        $criteriaCount1 = ObjectSentenceMMModel::getCriteria()
            ->where('sentenceMM.documentMM.idDocument','=', $idDocument)
            ->groupBy('idSentenceMM')
            ->select(['idSentence','count(distinct idObjectMM) as quant']);
        $criteria = self::getCriteria()
            ->where($filters)
            ->select([
                'sentenceMM.idSentence',
                'sentenceMM.idSentenceMM',
                'sentenceMM.sentence.text',
                'c1.quant'
            ])
            ->joinSub($criteriaCount1, 'c1', 'sentenceMM.idSentenceMM', '=', 'c1.idSentenceMM')
            ->orderBy('sentenceMM.idSentence')
            ->offset($offset);
        if ($limit > 0) {
            $criteria->limit($limit);
        }
        $result = $criteria
            ->getResult()
            ->toArray();
        return [
            'result' => $result,
            'count' => $count
        ];
    }

 */
}
