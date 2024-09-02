<?php

namespace App\Http\Controllers\Frame;

use App\Data\ComboBox\QData;
use App\Data\Frame\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Services\ReportFrameService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class ReportController extends Controller
{
    #[Get(path: '/report/frame/content/{idFrame?}/{lang?}')]
    public function reportContent(int|string $idFrame = '', string $lang = '', ?string $fragment = null)
    {
        $data = ReportFrameService::report($idFrame, $lang);
        return view("Frame.Report.report", $data);
    }

    #[Get(path: '/report/frame/{idFrame?}/{lang?}')]
    public function report(int|string $idFrame = '', string $lang = '', ?string $fragment = null)
    {
        $search = session('searchFrame') ?? SearchData::from();
        if (($idFrame == 'list') || ($idFrame == '')) {
            return view("Frame.Report.main", [
                'search' => $search
            ]);
        } else {
            return view("Frame.Report.main", [
                'search' => $search,
                'idFrame' => $idFrame
            ]);
        }
    }


    #[Post(path: '/report/frame/grid')]
    public function grid(SearchData $search)
    {
        $frames = BrowseController::listFrame($search);
        return view("Frame.Report.grid", [
            'search' => $search,
            'frames' => $frames,
        ]);
    }

    #[Get(path: '/frame/list/forSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        return ['results' => Criteria::byFilterLanguage("view_frame",["name","startswith",$name])->orderby("name")->all()];
    }

}
