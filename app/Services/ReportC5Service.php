<?php

namespace App\Services;

use App\Database\Criteria;
use App\Repositories\Concept;
use App\Repositories\Frame;
use App\Repositories\SemanticType;

class ReportC5Service
{

    public static function report(int|string $idConcept, string $lang = ''): array
    {
        $report = [];
        if ($lang != '') {
            $language = Criteria::byId("language", "language", $lang);
            $idLanguage = $language->idLanguage;
            AppService::setCurrentLanguage($idLanguage);
        } else {
            $idLanguage = AppService::getCurrentIdLanguage();
        }
        if (is_numeric($idConcept)) {
            $concept = Concept::byId($idConcept);
        } else {
            $concept = Criteria::table("view_concept")
                ->where("name", $idConcept)
                ->where("idLanguage", $idLanguage)
                ->first();
        }
        $report['concept'] = $concept;
        return $report;
    }

}
