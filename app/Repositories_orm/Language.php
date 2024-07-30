<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Map\ClassMap;
use Orkester\Persistence\Repository;

class Language extends Repository {
    public static function map(ClassMap $classMap): void
    {
        $classMap->table('language')
            ->attribute('idLanguage', key: Key::PRIMARY)
            ->attribute('language')
            ->attribute('description');
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idLanguage');
        if ($filter->idLanguage){
            $criteria->where("idLanguage = {$filter->idLanguage}");
        }
        if ($filter->language){
            $criteria->where("language LIKE '{$filter->language}%'");
        }
        return $criteria;
    }

    public function listForSelection(){
        $criteria = $this->getCriteria()
            ->select(['idLanguage','language','description',"concat(language,' - ',description) as ldescription"])
            ->where("idLanguage","<>",0)
            ->orderBy('language');
        return $criteria;
    }

    public function getByLanguage($language){
        $criteria = $this->getCriteria()->select('*')->where("language = '{$language}'");
        $this->retrieveFromCriteria();

    }

}
