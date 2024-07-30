<?php

namespace App\Http\Controllers;

use App\Data\Frame\SearchData;
use App\Http\Controllers\FE\BrowseController as FEController;
use App\Http\Controllers\LU\BrowseController as LUController;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\SemanticType;
use App\Repositories\ViewFrame;
use App\Repositories\ViewLU;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class SandboxController extends Controller
{
    #[Get(path: '/sandbox/tree')]
    public function tree()
    {
        $search = session('searchFrame') ?? SearchData::from([]);
        return view("Sandbox.tree", [
            'search' => $search,
        ]);
    }

    #[Post(path: '/sandbox/tree/grid')]
    public function grid(SearchData $search)
    {
//        $search = session('searchFrame') ?? $search;
        debug("starting ================");
        debug($search);
        //session('searchFrame', $search);
        $display = 'domainTableContainer';
        if ($search->lu != '') {
            $lus = $this->listLUSearch($search->lu);
            return view("Sandbox.grids", [
                'search' => $search,
                'currentFrame' => $search->lu . '*',
                'lus' => $lus,
            ]);
        } else {
            $groups = $this->listGroup($search->byGroup);
            debug($search->idFramalDomain);
            debug($search->idFramalType);
            if (!(is_null($search->idFramalDomain) && is_null($search->idFramalType))) {
                $display = 'frameTableContainer';
                debug('===============');
            }
            if ($search->frame == '') {
                if ($search->byGroup == 'domain') {
                    $search->idFramalDomain ??= array_key_first($groups);
                    $currentGroup = $groups[$search->idFramalDomain]['name'];
                    $group = 'Domains';
                } else {
                    $search->idFramalType ??= array_key_first($groups);
                    $currentGroup = $groups[$search->idFramalType]['name'];
                    $group = 'Types';
                }
            }
            if ($search->idFrame != 0) {
                $display = 'feluTableContainer';
//                $search->idFramalDomain = null;
//                $search->idFramalType = null;
            }
            $frames = $this->listFrame($search);
            //debug($frames);
            if (!empty($frames)) {
                $frame = Frame::getById(array_key_first($frames));
                $fes = $this->listFE($frame->idFrame);
                $lus = $this->listLU($frame->idFrame);
            } else {
                $fes = $lus = [];
            }
            debug($search,$display);
            return view("Sandbox.grids", [
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
    }

    public function listGroup(string $group)
    {
        $result = [];
        if ($group == 'domain') {
            $groups = SemanticType::listFrameDomain()->all();
        } else {
            $groups = SemanticType::listFrameType()->all();
        }
        foreach ($groups as $row) {
            $result[$row->idSemanticType] = [
                'id' => $row->idSemanticType,
                'idDomain' => $row->idSemanticType,
                'type' => $group,
                'name' => $row->name,
                'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-domain'
            ];
        }
        return $result;
    }

    public function listFrame(SearchData $search)
    {
        $result = [];
        $frames = ViewFrame::listByFilter($search)->all();
        foreach ($frames as $row) {
            $result[$row->idFrame] = [
                'id' => 'f' . $row->idFrame,
                'idFrame' => $row->idFrame,
                'type' => 'frame',
                'name' => [$row->name, $row->description],
                'iconCls' => 'material-icons-outlined wt-icon wt-icon-frame',
            ];
        }
        return $result;
    }

    public function listFE(int $idFrame)
    {
        $icon = config('webtool.fe.icon.tree');
        $coreness = config('webtool.fe.coreness');
        $fes = FrameElement::listByFrame($idFrame)->getResult();
        $orderedFe = [];
        foreach ($icon as $i => $j) {
            foreach ($fes as $fe) {
                if ($fe->coreType == $i) {
                    $orderedFe[] = $fe;
                }
            }
        }
        $result = [];
        foreach ($orderedFe as $fe) {
            $result[$fe->idFrameElement] = [
                'id' => 'e' . $fe->idFrameElement,
                'idFrameElement' => $fe->idFrameElement,
                'type' => 'fe',
                'name' => [$fe->name, $fe->description],
                'idColor' => $fe->idColor,
                'iconCls' => $icon[$fe->coreType],
                'coreness' => $coreness[$fe->coreType],
            ];
        }
        return $result;
    }

    public function listLU(int $idFrame)
    {
        $result = [];
        $lus = ViewLU::listByFrame($idFrame, AppService::getCurrentIdLanguage())->all();
        foreach ($lus as $lu) {
            $result[$lu->idLU] = [
                'id' => 'l' . $lu->idLU,
                'idLU' => $lu->idLU,
                'type' => 'lu',
                'name' => [$lu->name, $lu->senseDescription],
                'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-lu',
            ];
        }
        return $result;
    }

    public function listLUSearch(string $lu)
    {
        $result = [];
        $lus = ViewLU::listByFilter((object)[
            'lu' => $lu,
            'idLanguage' => AppService::getCurrentIdLanguage()
        ])->all();
        foreach ($lus as $lu) {
            $result[$lu->idLU] = [
                'id' => 'l' . $lu->idLU,
                'idLU' => $lu->idLU,
                'type' => 'lu',
                'name' => [$lu->name, $lu->senseDescription],
                'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-lu',
            ];
        }
        return $result;
    }

    #[Get(path: '/sandbox/tree/domain/{idDomain}')]
    public function getFramesByDomain(int $idDomain)
    {
        $search = SearchData::from(['idFramalDomain' => $idDomain]);
        $groups = $this->listGroup('domain');
        $frames = $this->listFrame($search);
        if (!empty($frames)) {
            $frame = Frame::getById(array_key_first($frames));
            $fes = $this->listFE($frame->idFrame);
            $lus = $this->listLU($frame->idFrame);
        } else {
            $fes = $lus = [];
        }
        return view("Sandbox.treeFrame", [
            'currentGroup' => $groups[$idDomain]['name'],
            'frames' => $frames,
            'fes' => $fes,
            'lus' => $lus,
        ]);
    }

    #[Get(path: '/sandbox/tree/type/{idType}')]
    public function getFramesByType(int $idType)
    {
        $search = SearchData::from(['idFramalType' => $idType]);
        $groups = $this->listGroup('type');
        $frames = $this->listFrame($search);
        if (!empty($frames)) {
            $frame = Frame::getById(array_key_first($frames));
            $fes = $this->listFE($frame->idFrame);
            $lus = $this->listLU($frame->idFrame);
        } else {
            $fes = $lus = [];
        }
        return view("Sandbox.treeFrame", [
            'currentGroup' => $groups[$idType]['name'],
            'frames' => $frames,
            'fes' => $fes,
            'lus' => $lus,
        ]);
    }

    #[Get(path: '/sandbox/tree/frame/{idFrame}')]
    public function getFELU(int $idFrame)
    {
        $search = SearchData::from(['idFrame' => $idFrame]);
        $fes = $this->listFE($search->idFrame);
        $lus = $this->listLU($search->idFrame);
        $frame = Frame::getById($idFrame);
        return view("Sandbox.treeFELU", [
            'currentFrame' => $frame->name,
            'fes' => $fes,
            'lus' => $lus
        ]);
    }

}
