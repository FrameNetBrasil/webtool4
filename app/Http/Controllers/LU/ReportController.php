<?php

namespace App\Http\Controllers\LU;

use App\Data\Frame\SearchData;
use App\Data\LU\ReportData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\FrameElement;
use App\Repositories\Language;
use App\Repositories\LU;
use App\Repositories\ViewLU;
use App\Services\AppService;
use App\Services\ReportLUService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class ReportController extends Controller
{
    #[Get(path: '/report/lu/content/{idLU?}')]
    public function reportContent(int|string $idLU)
    {
        $lu = LU::byId($idLU);
        $data = ReportLUService::FERealizations($idLU);
        $data['lu'] = $lu;
        $data['language'] = Criteria::byId("language","idLanguage", $lu->idLanguage);
        if (!is_null($lu->incorporatedFE)) {
            $data['incorporatedFE'] = FrameElement::byId($lu->incorporatedFE);
        }
        return view("LU.Report.report", $data);
    }
    #[Get(path: '/report/lu/{idLU?}')]
    public function report(int|string $idLU = '')
    {
        $search = session('searchLU') ?? SearchData::from();
        if (($idLU == 'list') || ($idLU == '')) {
            return view("LU.Report.main", [
                'search' => $search
            ]);
        } else {
            $lu = LU::byId($idLU);
            $search->lu = $lu->name;
            return view("LU.Report.main", [
                'search' => $search,
                'idLU' => $idLU
            ]);
        }
    }

    #[Post(path: '/report/lu/grid')]
    public function grid(SearchData $search)
    {
        $lus = [];
        if ($search->lu != '') {
            $lus = self::listLUSearch($search->lu);
        }
        return view("LU.Report.grid", [
            'search' => $search,
            'currentSearch' => $search->lu . '*',
            'lus' => $lus,
        ]);
    }

    public static function listLUSearch(string $lu)
    {
        $result = [];
        $lus = Criteria::byFilter("view_lu",[
            ['name',"startswith",$lu],
            ['idLanguage',"=",AppService::getCurrentIdLanguage()]
        ])->all();
        foreach ($lus as $lu) {
            $result[$lu->idLU] = [
                'id' => 'l' . $lu->idLU,
                'idLU' => $lu->idLU,
                'type' => 'lu',
                'name' => [$lu->name, $lu->senseDescription],
                'frameName' => $lu->frameName,
            ];
        }
        return $result;
    }

    #[Post(path: '/report/lu/sentences')]
    public function sentences(ReportData $reportData)
    {
        return ReportLUService::getSentences($reportData);
    }

}
