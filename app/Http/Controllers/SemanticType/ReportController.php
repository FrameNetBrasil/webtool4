<?php

namespace App\Http\Controllers\SemanticType;

use App\Data\ComboBox\QData;
use App\Data\SemanticType\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frame\BrowseController;
use App\Repositories\SemanticType;
use App\Services\AppService;
use App\Services\ReportSTService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class ReportController extends Controller
{

//    #[Post(path: '/report/semanticType/grid')]
//    public function grid(SearchData $search)
//    {
//        $data = $this->listForTree($search);
//        $view = view("SemanticType.grid",[
//            'search' => $search,
//            'data' => $data,
//        ]);
//        return $view;
//    }

    #[Get(path: '/report/semanticType/{idSemanticType?}/{lang?}')]
    public function report(int|string $idSemanticType = '', string $lang = '')
    {
        $search = session('searchFrame') ?? SearchData::from();
        $data = $this->listForTree($search);
        if (($idSemanticType == 'list') || ($idSemanticType == '')) {
            return view("SemanticType.Report.main", [
                'search' => $search,
                'idSemanticType' => null,
                'data' => $data,
            ]);
        } else {
            $data = ReportSTService::report($idSemanticType, $lang);
            $data['search'] = $search;
            $data['idSemanticType'] = $idSemanticType;
            $data['data'] = $data;
            return view("SemanticType.Report.report", $data);
        }
    }

    private function listForTree(SearchData $search)
    {
        $domainIcon = view('components.icon.domain')->render();
        $stIcon = view('components.icon.semantictype')->render();
        $tree = [];
        if ($search->semanticType != '') {
            $st = Criteria::table("view_semanticType")
                ->select("idSemanticType", "idEntity", "name")
                ->where("name","startswith",'@'.$search->semanticType)
                ->where('idLanguage', '=', AppService::getCurrentIdLanguage())
                ->orderBy("name")->all();
            foreach ($st as $row) {
                $node = [];
                $node['id'] = 't' . $row->idEntity;
                $node['idSemanticType'] = $row->idSemanticType;
                $node['type'] = 'semanticType';
                $node['text'] = $stIcon . $row->name;
                $node['state'] = 'closed';
                $node['children'] = $this->getChildren($row->idEntity);
                $tree[] = $node;
            }
        } else {
            $domains = SemanticType::listDomains();
            foreach ($domains as $row) {
                $count = Criteria::table("semantictype")
                    ->where("idDomain",$row->idDomain)
                    ->count();
                if ($count > 0) {
                    $node = [];
                    $node['id'] = $row->idDomain;
                    $node['idDomain'] = $row->idDomain;
                    $node['type'] = 'domain';
                    $node['text'] = $domainIcon . $row->name;
                    $node['state'] = 'closed';
                    $roots = SemanticType::listRootByDomain($row->idDomain);
                    $children = [];
                    foreach ($roots as $root) {
                        $n = [];
                        $n['id'] = $root->idEntity;
                        $n['idSemanticType'] = $root->idSemanticType;
                        $n['type'] = 'semanticType';
                        $n['text'] = $stIcon . $root->name;
                        $n['state'] = 'closed';
                        $n['children'] = $this->getChildren($root->idEntity);
                        $children[] = $n;
                    }
                    $node['children'] = $children;
                    $tree[] = $node;
                }
            }
        }
        return $tree;
    }

    private function getChildren(int $idEntity): array
    {
        $stIcon = view('components.icon.semantictype')->render();
        $children = [];
        $st = SemanticType::listChildren($idEntity);
        foreach ($st as $row) {
            $n = [];
            $n['id'] = $row->idEntity;
            $n['idSemanticType'] = $row->idSemanticType;
            $n['type'] = 'semanticType';
            $n['text'] = $stIcon . $row->name;
            $n['state'] = 'closed';
            $n['children'] = $this->getChildren($row->idEntity);;
            $children[] = $n;
        }
        return $children;
    }


}
