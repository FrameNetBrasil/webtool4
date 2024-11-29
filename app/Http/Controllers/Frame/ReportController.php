<?php

namespace App\Http\Controllers\Frame;

use App\Data\ComboBox\QData;
use App\Data\Frame\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Services\AppService;
use App\Services\ReportFrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class ReportController extends Controller
{
    #[Post(path: '/report/frame/grid')]
    public function grid(SearchData $search)
    {
        return view("Frame.Report.grid", [
            'search' => $search,
        ]);
    }

    #[Get(path: '/report/frame/data')]
    public function data(SearchData $search)
    {
        $rows = [];
        $frameIcon = view('components.icon.frame')->render();
        $frames = Criteria::byFilterLanguage("view_frame",
                ['name', "startswith", $search->frame])
            ->orderBy('name')
            ->all();
        foreach ($frames as $frame) {
            $n = [];
            $n['id'] = $frame->idFrame;
            $n['idFrame'] = $frame->idFrame;
            $n['type'] = 'frame';
            $n['text'] = $frameIcon . $frame->name;
            $n['state'] = 'open';
            $rows[] = $n;
        }
        return $rows;
    }

    #[Get(path: '/report/frame/{idFrame?}/{lang?}')]
    public function report(int|string $idFrame = '', string $lang = '', ?string $fragment = null)
    {
        $search = session('searchFrame') ?? SearchData::from();
        if ($idFrame == '') {
            return view("Frame.Report.main", [
                'search' => $search,
                'idFrame' => null
            ]);
        } else {
            $data = ReportFrameService::report($idFrame, $lang);
            $data['search'] = $search;
            $data['idFrame'] = $idFrame;
            return view("Frame.Report.report", $data);
        }
    }

    #[Get(path: '/frame/list/forSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        return ['results' => Criteria::byFilterLanguage("view_frame",["name","startswith",$name])->orderby("name")->all()];
    }

    #[Get(path: '/frame/listScenario/forSelect')]
    public function listScenarioForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        return ['results' => Criteria::table("view_relation as r")
            ->join("view_frame as f","r.idEntity1","=","f.idEntity")
            ->join("semantictype as st","r.idEntity2","=","st.idEntity")
            ->where("f.idLanguage","=", AppService::getCurrentIdLanguage())
            ->where("st.entry","=","sty_ft_scenario")
            ->where("f.name","startswith",$name)
            ->select("f.idFrame","f.idEntity","f.name")
            ->orderby("f.name")
            ->all()];
    }

}
