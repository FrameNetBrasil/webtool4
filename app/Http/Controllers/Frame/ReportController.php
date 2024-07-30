<?php

namespace App\Http\Controllers\Frame;

use App\Data\ComboBox\QData;
use App\Data\Frame\SearchData;
use App\Data\SearchFrameData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FE\FEController;
use App\Http\Controllers\LU\BrowseController as LUController;
use App\Http\Controllers\LU\ResourceController;
use App\Repositories\Frame;
use App\Repositories\SemanticType;
use App\Repositories\ViewFrame;
use App\Services\AppService;
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
        debug($data);
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
        //debug($frames);
        return view("Frame.Report.grid", [
            'search' => $search,
            'frames' => $frames,
        ]);
        /*
        if ($search->isEdit) {
            return $this->clientRedirect("/report/frame/{$search->idFrame}");
        } else {
            $display = 'frameTableContainer';
            $groups = BrowseController::listGroup($search->byGroup);
            if ($search->frame == '') {
                if ($search->byGroup == 'domain') {
                    $search->idFramalDomain ??= array_key_first($groups);
                    $currentGroup = $groups[$search->idFramalDomain]['name'];
                    $group = 'Domains';
                    $display = 'domainTableContainer';
                }
                if ($search->byGroup == 'type') {
                    $search->idFramalType ??= array_key_first($groups);
                    $currentGroup = $groups[$search->idFramalType]['name'];
                    $group = 'Types';
                    $display = 'domainTableContainer';
                }
            }
            if ($search->idFrame != 0) {
                $display = 'feluTableContainer';
                $search->idFramalDomain = null;
                $search->idFramalType = null;
            }
            $frames = BrowseController::listFrame($search);
            //debug($frames);
            if (!empty($frames)) {
                $frame = Frame::getById(array_key_first($frames));
                $fes = BrowseController::listFE($frame->idFrame);
                $lus = BrowseController::listLU($frame->idFrame);
            } else {
                $fes = $lus = [];
            }
            return view("Frame.Report.grids", [
                'search' => $search,
                'display' => $display,
                'currentGroup' => $currentGroup ?? '',
                'currentFrame' => $frame?->name ?? '',
                'group' => $group ?? '',
                'groups' => $groups ?? [],
                'frames' => $frames,
                'fes' => $fes,
                'lus' => $lus,
            ]);
        }
        */
    }

    #[Get(path: '/report/frame/listForSelect')]
    public function listForSelect(QData $data)
    {
        return Frame::listForSelect($data->q)->all();
    }

}
