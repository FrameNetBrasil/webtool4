<?php

namespace App\Http\Controllers\C5;

use App\Data\C5\SearchData;
use App\Http\Controllers\Controller;
use App\Repositories\Concept;
use App\Services\ReportC5Service;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class ReportController extends Controller
{
    #[Post(path: '/report/c5/grid')]
    public function grid(SearchData $search)
    {
        return view("C5.Report.grid", [
            'search' => $search,
        ]);
    }
    #[Get(path: '/report/c5/data')]
    public function data(SearchData $search)
    {
        $c5Icon = view('components.icon.concept')->render();
        $tree = [];
        if ($search->concept != '') {
            $concepts = Concept::listTree($search->concept);
        } else {
            if ($search->id == '') {
                $types = Concept::listRoots();
                foreach ($types as $type) {
                    $n = [];
                    $n['id'] = 't' . $type->idTypeInstance;
                    $n['idTypeInstance'] = $type->idTypeInstance;
                    $n['type'] = 'type';
                    $n['text'] = $c5Icon . $type->name;
                    $n['state'] = 'closed';
                    $n['children'] = [];
                    $tree[] = $n;
                }
                return $tree;
            } else {
                if ($search->idTypeInstance != 0) {
                    $concepts = Concept::listTypeChildren($search->idTypeInstance);
                } else {
                    $concepts = Concept::listChildren($search->idConcept);
                }
            }
        }
        foreach ($concepts as $concept) {
            $n = [];
            $n['id'] = 'c' . $concept->idEntity;
            $n['idConcept'] = $concept->idConcept;
            $n['type'] = 'concept';
            $n['text'] = $c5Icon . $concept->name;
            $n['state'] = ($concept->n > 0) ? 'closed' : 'open';
            $n['children'] = [];
            $tree[] = $n;
        }
        return $tree;
    }

    #[Get(path: '/report/c5/content/{idConcept}/{lang?}')]
    public function reportContent(int|string $idConcept = '',string $lang = '')
    {
        $search = session('searchConcept') ?? SearchData::from();
        $data = ReportC5Service::report($idConcept, $lang);
        $data['search'] = $search;
        $data['idConcept'] = $idConcept;
        return view("C5.Report.report", $data);
    }
    #[Get(path: '/report/c5/{idConcept?}/{lang?}')]
    public function main(int|string $idConcept = '', string $lang = '')
    {
        $search = session('searchFrame') ?? SearchData::from();
        if ($idConcept == '') {
            return view("C5.Report.main", [
                'search' => $search,
                'idConcept' => null
            ]);
        } else {
            $data = ReportC5Service::report($idConcept, $lang);
            $data['search'] = $search;
            $data['idConcept'] = $idConcept;
            return view("C5.Report.main", $data);
        }
    }


//    #[Get(path: '/report/c5/{idConcept}/{lang?}')]
//    public function report(int|string $idConcept = '', string $lang = '')
//    {
//        $search = session('searchConcept') ?? SearchData::from();
//        $data = ReportC5Service::report($idConcept, $lang);
//        $data['search'] = $search;
//        $data['idConcept'] = $idConcept;
//        $data['data'] = $data;
//        return view("C5.Report.report", $data);
//    }




//    private function listForTree(SearchData $search)
//    {
//        $c5Icon = view('components.icon.concept')->render();
//        $tree = [];
//        if ($search->concept != '') {
//            $st = Criteria::table("view_concept")
//                ->select("idConcept", "idEntity", "name")
//                ->where("name", "startswith", $search->concept)
//                ->where('idLanguage', '=', AppService::getCurrentIdLanguage())
//                ->orderBy("name")->all();
//            foreach ($st as $row) {
//                $node = [];
//                $node['id'] = $row->idEntity;
//                $node['idConcept'] = $row->idConcept;
//                $node['type'] = 'concept';
//                $node['text'] = $c5Icon . $row->name;
//                $node['state'] = 'closed';
//                $node['children'] = $this->getChildren($row->idEntity);
//                $tree[] = $node;
//            }
//        } else {
//
//        }
//        return $tree;
//    }
//
//    private function getChildren(int $idEntity): array
//    {
//        $c5Icon = view('components.icon.concept')->render();
//        $children = [];
//        $st = Concept::listChildren($idEntity);
//        foreach ($st as $row) {
//            $n = [];
//            $n['id'] = $row->idEntity;
//            $n['idConcept'] = $row->idConcept;
//            $n['type'] = 'concept';
//            $n['text'] = $c5Icon . $row->name;
//            $n['state'] = 'closed';
//            $n['children'] = $this->getChildren($row->idEntity);;
//            $children[] = $n;
//        }
//        return $children;
//    }


}
