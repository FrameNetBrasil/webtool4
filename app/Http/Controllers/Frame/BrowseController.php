<?php

namespace App\Http\Controllers\Frame;

use App\Data\ComboBox\QData;
use App\Data\Frame\FrameData;
use App\Data\Frame\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Repositories\SemanticType;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/frame')]
    public function browse()
    {
        $search = session('searchFrame') ?? SearchData::from();
        return view("Frame.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/frame/grid')]
    public function grid(SearchData $search)
    {
        //$search = session('searchFrame') ?? $search;
//        debug("starting ================");
//        debug($search);
//        if ($search->isEdit) {
//            return $this->clientRedirect("/frame/{$search->idFrame}");
//        } else {
        //session('searchFrame', $search);
        $display = 'frameTableContainer';
        if ($search->lu != '') {
            $display = 'luTableContainer';
            $lus = self::listLUSearch($search->lu);
            return view("Frame.grids", [
                'search' => $search,
                'display' => $display,
                'currentFrame' => $search->lu . '*',
                'lus' => $lus,
            ]);
        } else {
            $groups = self::listGroup($search->byGroup);
//            debug($search->idFramalDomain);
//            debug($search->idFramalType);
//            if (!(is_null($search->idFramalDomain) && is_null($search->idFramalType))) {
//                $display = 'frameTableContainer';
//                debug('===============');
//            }
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
//            if ($search->idFrame != 0) {
//                $display = 'feluTableContainer';
//                $search->idFramalDomain = null;
//                $search->idFramalType = null;
//            }
            $frames = self::listFrame($search);
            //debug($frames);
//            if (!empty($frames)) {
//                $frame = Frame::byId(array_key_first($frames));
//                $fes = self::listFE($frame->idFrame);
//                $lus = self::listLU($frame->idFrame);
//            } else {
//                $fes = $lus = [];
//            }
//                debug([
//                    'search' => $search,
//                    'display' => $display,
//                    'currentGroup' => $currentGroup ?? '',
//                    'currentFrame' => $frame?->name ?? '',
//                    'group' => $group ?? '',
//                ]);
            return view("Frame.grids", [
                'search' => $search,
                'display' => $display,
                'currentGroup' => $currentGroup ?? '',
                'currentFrame' => $frame?->name ?? '',
                'group' => $group ?? '',
                'groups' => $groups ?? [],
                'frames' => $frames,
//                'fes' => $fes,
//                'lus' => $lus,
            ]);
//            }
        }
    }

    public static function listGroup(string $group)
    {
        $result = [];
        if ($group == 'domain') {
            $groups = SemanticType::listFrameDomain();
        } else {
            $groups = SemanticType::listFrameType();
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

    public static function listFrame(SearchData $search)
    {
        $result = [];
        //$frames = ViewFrame::listByFilter($search)->all();
        $frames = Criteria::byFilterLanguage("view_frame", ['name', "startswith", $search->frame])
            ->orderBy('name')->all();
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

    public static function listFE(int $idFrame)
    {
//        $icon = config('webtool.fe.icon');
//        $coreness = config('webtool.fe.coreness');
//        $fes = FrameElement::listByFrame($idFrame)->getResult();
//        $orderedFe = [];
//        foreach ($icon as $i => $j) {
//            foreach ($fes as $fe) {
//                if ($fe->coreType == $i) {
//                    $orderedFe[] = $fe;
//                }
//            }
//        }
        $fes = Criteria::byFilterLanguage("view_frameelement", ['idFrame', "=", $idFrame])
            ->orderBy('name')->all();
        $result = [];
        foreach ($fes as $fe) {
            $result[$fe->idFrameElement] = [
                'id' => 'e' . $fe->idFrameElement,
                'idFrameElement' => $fe->idFrameElement,
                'type' => 'fe',
                'name' => [$fe->name, $fe->description],
                'idColor' => $fe->idColor,
//                'iconCls' => $icon[$fe->coreType],
//                'coreness' => $coreness[$fe->coreType],
            ];
        }
        return $result;
    }

    public static function listLU(int $idFrame)
    {
        $result = [];
//        $lus = ViewLU::listByFrame($idFrame, AppService::getCurrentIdLanguage())->all();
        $lus = Criteria::byFilter("view_lu", ['idFrame', "=", $idFrame])
            ->orderBy('name')->all();
        foreach ($lus as $lu) {
            $result[$lu->idLU] = [
                'id' => 'l' . $lu->idLU,
                'idLU' => $lu->idLU,
                'type' => 'lu',
                'name' => [$lu->name, $lu->senseDescription],
//                'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-lu',
            ];
        }
        return $result;
    }

    public static function listLUSearch(string $lu)
    {
        $result = [];
        $lus = Criteria::byFilterLanguage("view_lu", ['name', "startswith", $lu], 'idLanguage')
            ->orderBy('name')->all();
//
//        $lus = ViewLU::listByFilter((object)[
//            'lu' => $lu,
//            'idLanguage' => AppService::getCurrentIdLanguage()
//        ])->all();
        foreach ($lus as $lu) {
//            debug($lu);
            $result[$lu->idLU] = [
                'id' => 'l' . $lu->idLU,
                'idLU' => $lu->idLU,
                'type' => 'lu',
                'name' => [$lu->name, $lu->senseDescription],
                'frameName' => $lu->frameName,
//                'iconCls' => 'material-icons-outlined wt-icon wt-icon-lu',
            ];
        }
        return $result;
    }

    #[Get(path: '/frame/list/forSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        return ['results' => Criteria::byFilterLanguage("view_frame",["name","startswith",$name])->orderby("name")->all()];
    }

}
