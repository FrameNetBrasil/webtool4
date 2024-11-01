<?php

namespace App\Http\Controllers\C5;

use App\Data\C5\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Concept;
use App\Services\AppService;
use App\Services\ReportC5Service;
use App\Services\ReportSTService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class ReportController extends Controller
{

//    #[Get(path: '/report/c5/{idConcept?}/{lang?}')]
//    public function report(int|string $idConcept = '', string $lang = '')
//    {
//        $search = session('searchConcept') ?? SearchData::from();
//        if (($idConcept == 'list') || ($idConcept == '')) {
//            $data = $this->listForTree($search);
//            return view("C5.Report.main", [
//                'search' => $search,
//                'idConcept' => null,
//                'data' => $data,
//            ]);
//        } else {
//            $data = ReportC5Service::report($idConcept, $lang);
//            $data['search'] = $search;
//            $data['idConcept'] = $idConcept;
//            $data['data'] = $data;
//            return view("C5.Report.report", $data);
//        }
//    }

    #[Get(path: '/report/c5')]
    public function main(int|string $idConcept = '', string $lang = '')
    {
        $search = session('searchConcept') ?? SearchData::from();
        $data = [];
        return view("C5.Report.main", [
            'search' => $search,
            'idConcept' => null,
            'data' => $data,
        ]);
    }

    #[Get(path: '/report/c5/data')]
    public function data(SearchData $search)
    {
        debug("data");
        debug($search);
        $c5Icon = view('components.icon.concept')->render();
        $tree = [];
        if ($search->concept != '') {
            $concepts = Concept::listTree($search->concept);
        } else {
            if ($search->id == 0) {
                $concepts = Concept::listRoots();
            } else {
                $concepts = Concept::listChildren($search->id);
            }
        }
        foreach ($concepts as $concept) {
            $n = [];
            $n['id'] = $concept->idEntity;
            $n['idConcept'] = $concept->idConcept;
            $n['type'] = 'concept';
            $n['text'] = $c5Icon . $concept->name;
            $n['state'] = ($concept->n > 0) ? 'closed' : 'open';
            $n['children'] = [];
            $tree[] = $n;
        }
        return $tree;
    }

    #[Get(path: '/report/c5/{idConcept}/{lang?}')]
    public function report(int|string $idConcept = '', string $lang = '')
    {
        $search = session('searchConcept') ?? SearchData::from();
        $data = ReportC5Service::report($idConcept, $lang);
        $data['search'] = $search;
        $data['idConcept'] = $idConcept;
        $data['data'] = $data;
        return view("C5.Report.report", $data);
    }

    #[Post(path: '/report/c5/search')]
    public function search(SearchData $search)
    {
        return view("C5.Report.grid",[
            'search' => $search,
        ]);
    }


    private function listForTree(SearchData $search)
    {
        $c5Icon = view('components.icon.concept')->render();
        $tree = [];
        if ($search->concept != '') {
            $st = Criteria::table("view_concept")
                ->select("idConcept", "idEntity", "name")
                ->where("name", "startswith", $search->concept)
                ->where('idLanguage', '=', AppService::getCurrentIdLanguage())
                ->orderBy("name")->all();
            foreach ($st as $row) {
                $node = [];
                $node['id'] = $row->idEntity;
                $node['idConcept'] = $row->idConcept;
                $node['type'] = 'concept';
                $node['text'] = $c5Icon . $row->name;
                $node['state'] = 'closed';
                $node['children'] = $this->getChildren($row->idEntity);
                $tree[] = $node;
            }
        } else {

        }
        return $tree;
    }

    private function getChildren(int $idEntity): array
    {
        $c5Icon = view('components.icon.concept')->render();
        $children = [];
        $st = Concept::listChildren($idEntity);
        foreach ($st as $row) {
            $n = [];
            $n['id'] = $row->idEntity;
            $n['idConcept'] = $row->idConcept;
            $n['type'] = 'concept';
            $n['text'] = $c5Icon . $row->name;
            $n['state'] = 'closed';
            $n['children'] = $this->getChildren($row->idEntity);;
            $children[] = $n;
        }
        return $children;
    }


}
