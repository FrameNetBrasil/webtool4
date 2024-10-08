<?php
namespace App\Repositories;

use Orkester\Persistence\Repository;

class ViewSubCorpusLU extends Repository {

    public function listByLU($idLU, $idLanguage = '')
    {
        $criteria = $this->getCriteria()->select('idSubCorpus, name, count(annotationsets.idAnnotationSet) as quant');
        $criteria->where("idLU = {$idLU}");
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
        $criteria = $this->getCriteria()->select('name, lu.name luName, lu.frame.entries.name frameName')->orderBy('name');
        $criteria->where("idSubCorpus = {$idSubCorpus}");
        Base::entryLanguage($criteria,'lu.frame');
        $result = $criteria->asQuery()->getResult();
        return $result[0]['frameName'] . '.' . $result[0]['luName'] . '  [' . $result[0]['name'] . ']';
    }

}

