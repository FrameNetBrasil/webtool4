<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class ViewSubCorpusCxn extends Repository {

    public function listByCxn($idConstruction, $idLanguage = '')
    {
        $criteria = $this->getCriteria()->select('idSubCorpus, name, count(annotationsets.idAnnotationSet) as quant');
        $criteria->where("idConstruction = {$idConstruction}");
        $criteria->groupBy('idSubCorpus,name');
        $criteria->orderBy('name');
        return $criteria;
    }

    public function getStats($idSubCorpus) {
        $criteria = $this->getCriteria()->select('annotationsets.entry, annotationsets.entries.name, count(*) as quant')
            ->where("idSubCorpus = {$idSubCorpus}" )
            ->groupBy('annotationsets.entry','annotationsets.entries.name');
        Base::entryLanguage($criteria,'annotationsets');
        $result = (object)$criteria->asQuery()->getResult();
        return $result;
    }

    public function getTitle($idSubCorpus, $idLanguage = '')
    {
        $criteria = $this->getCriteria()->select('name, construction.entries.name cxnName')->orderBy('construction.entries.name ');
        $criteria->where("idSubCorpus = {$idSubCorpus}");
        Base::entryLanguage($criteria,'construction');
        $result = $criteria->asQuery()->getResult();
        return $result[0]['cxnName'] . '  [' . $result[0]['name'] . ']';
    }

}

