<?php

namespace App\Services;

use App\Database\Criteria;
use App\Repositories\Frame;
use App\Repositories\SemanticType;

class ReportSTService
{

    public static function report(int|string $idSemanticType, string $lang = ''): array
    {
        $report = [];
        if ($lang != '') {
            $language = Criteria::byId("language", "language", $lang);
            $idLanguage = $language->idLanguage;
            AppService::setCurrentLanguage($idLanguage);
        } else {
            $idLanguage = AppService::getCurrentIdLanguage();
        }
        if (is_numeric($idSemanticType)) {
            $semanticType = SemanticType::byId($idSemanticType);
        } else {
            $semanticType = Criteria::table("view_semantictype")
                ->where("name", $idSemanticType)
                ->where("idLanguage", $idLanguage)
                ->first();
        }
        $report['semanticType'] = $semanticType;
//        $report['fe'] = self::getFEData($frame, $idLanguage);
//        $report['fecoreset'] = self::getFECoreSet($frame);
//        $report['frame']->description = self::decorate($frame->description, $report['fe']['styles']);
//        $report['relations'] = self::getRelations($frame);
//        $report['classification'] = Frame::getClassificationLabels($idFrame);
//        $report['lus'] = self::getLUs($frame, $idLanguage);
        return $report;
    }

}
