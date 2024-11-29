<?php

namespace App\Http\Controllers\Construction;

use App\Data\ComboBox\QData;
use App\Data\Construction\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Construction;
use App\Services\AppService;
use App\Services\ReportConstructionService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class ReportController extends Controller
{
    #[Post(path: '/report/cxn/grid')]
    public function grid(SearchData $search)
    {
        return view("Construction.Report.grid", [
            'search' => $search,
        ]);
    }

    #[Get(path: '/report/cxn/data')]
    public function data(SearchData $search)
    {
        $languageIcon = view('components.icon.language')->render();
        $cxnIcon = view('components.icon.construction')->render();
        $tree = [];
        if ($search->cxn != '') {
            $cxn = Construction::listTree($search->cxn);
        } else if ($search->idLanguage == '') {

        }
            if ($search->idLanguage == '') {
                $domains = SemanticType::listDomains();
                foreach ($domains as $row) {
                    $count = Criteria::table("semantictype")
                        ->where("idDomain", $row->idDomain)
                        ->count();
                    $n = [];
                    $n['id'] = 'd' . $row->idDomain;
                    $n['idDomain'] = $row->idDomain;
                    $n['type'] = 'domain';
                    $n['text'] = $domainIcon . $row->name;
                    $n['state'] = ($count > 0) ? 'closed' : 'open';
                    $n['children'] = [];
                    $tree[] = $n;
                }
                return $tree;
            }
            if ($search->idDomain > 0) {
                $semanticTypes = SemanticType::listRootByDomain($search->idDomain);
            } else if ($search->idSemanticType > 0) {
                $semanticTypes = SemanticType::listChildren($search->idSemanticType);
            }
        }
        foreach ($semanticTypes as $semanticType) {
            $n = [];
            $n['id'] = 't' . $semanticType->idEntity;
            $n['idSemanticType'] = $semanticType->idSemanticType;
            $n['type'] = 'semanticType';
            $n['text'] = $stIcon . $semanticType->name;
            $n['state'] = ($semanticType->n > 0) ? 'closed' : 'open';
            $n['children'] = [];
            $tree[] = $n;
        }
        return $tree;
    }

    #[Get(path: '/report/cxn/{idConstruction?}/{lang?}')]
    public function report(int|string $idConstruction = '', string $lang = '')
    {
        $search = session('searchConstruction') ?? SearchData::from();
        $data = ReportConstructionService::report($idConstruction, $lang);
        $data['search'] = $search;
        $data['idConstruction'] = $idConstruction;
        $data['data'] = $data;
        return view("Construction.Report.report", $data);
    }

    #[Get(path: '/construction/list/forSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        return ['results' => Criteria::byFilterLanguage("view_construction", ["name", "startswith", $name])->orderby("name")->all()];
    }

}
