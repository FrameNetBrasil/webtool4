<?php

namespace App\Services\Class;

use App\Database\Criteria;
use App\Services\AppService;

class ReportService
{
    public static function report(int|string $idClass, string $lang = ''): array
    {
        $report = [];
        if ($lang != '') {
            $language = Criteria::byId('language', 'language', $lang);
            $idLanguage = $language->idLanguage;
            AppService::setCurrentLanguage($idLanguage);
        } else {
            $idLanguage = AppService::getCurrentIdLanguage();
        }

        if (is_numeric($idClass)) {
            $class = Criteria::table('view_class')
                ->where('idClass', '=', $idClass)
                ->where('idLanguage', '=', $idLanguage)
                ->first();
        } else {
            $class = Criteria::table('view_class')
                ->where('name', '=', $idClass)
                ->where('idLanguage', '=', $idLanguage)
                ->first();
        }

        $report['class'] = $class;
        $report['frameElements'] = self::getFrameElementsUsingClass($class->idClass, $idLanguage);
        $report['stats'] = self::getStats($report['frameElements']);
        $report['relations'] = self::getRelations($class);

        return $report;
    }

    /**
     * Get all Frame Elements that use this Class as their semantic type
     * Grouped by Frame name
     */
    public static function getFrameElementsUsingClass(int $idClass, int $idLanguage): array
    {
        $fes = Criteria::table('frameelement as fe')
            ->join('view_frameelement as vfe', 'fe.idFrameElement', '=', 'vfe.idFrameElement')
            ->where('fe.idClass', '=', $idClass)
            ->where('vfe.idLanguage', '=', $idLanguage)
            ->select('fe.idFrameElement', 'fe.entry', 'fe.coreType', 'fe.idClass',
                     'vfe.name', 'vfe.description', 'vfe.frameName', 'vfe.idFrame', 'vfe.idColor')
            ->orderBy('vfe.frameName')
            ->orderBy('vfe.name')
            ->all();

        // Group by frame name
        $grouped = [];
        foreach ($fes as $fe) {
            $frameName = $fe->frameName;
            if (!isset($grouped[$frameName])) {
                $grouped[$frameName] = [
                    'idFrame' => $fe->idFrame,
                    'frameName' => $frameName,
                    'elements' => []
                ];
            }
            $grouped[$frameName]['elements'][] = $fe;
        }

        return $grouped;
    }

    /**
     * Calculate statistics for Class usage
     */
    public static function getStats(array $frameElements): array
    {
        $totalFEs = 0;
        $totalFrames = count($frameElements);

        foreach ($frameElements as $frame) {
            $totalFEs += count($frame['elements']);
        }

        return [
            'totalFEs' => $totalFEs,
            'totalFrames' => $totalFrames,
        ];
    }

    /**
     * Get entity relations for this Class
     * Note: Currently returns empty array as Class relations are primarily
     * through MicroFrame (MicroFrame → Class → FE) which are shown in the FE section
     */
    public static function getRelations($class): array
    {
        // Relations for Classes are primarily MicroFrame semantic type relations
        // which are already represented in the Frame Elements section
        return [];
    }
}
