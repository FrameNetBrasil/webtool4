<?php

namespace App\Http\Controllers\Construction;

use App\Data\Construction\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/cxn')]
    public function browse()
    {
        $search = session('searchCxn') ?? SearchData::from();
        return view("Construction.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/cxn/grid')]
    public function grid(SearchData $search)
    {
        debug($search);
        $display = 'frameTableContainer';
        if ($search->lu != '') {
            $display = 'luTableContainer';
            $lus = self::listLUSearch($search->lu);
            return view("Construction.grids", [
                'search' => $search,
                'display' => $display,
                'currentFrame' => $search->lu . '*',
                'lus' => $lus,
            ]);
        } else {
            $groups = self::listGroup($search->byGroup);
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
                if ($search->byGroup == 'scenario') {
                    $search->idFrameScenario ??= array_key_first($groups);
                    $currentGroup = $groups[$search->idFrameScenario]['name'];
                    $group = 'Scenarios';
                    $display = 'domainTableContainer';
                }
            }
            $frames = self::listFrame($search);
            return view("Construction.grids", [
                'search' => $search,
                'display' => $display,
                'currentGroup' => $currentGroup ?? '',
                'currentFrame' => $frame?->name ?? '',
                'groupName' => $group ?? '',
                'groups' => $groups ?? [],
                'frames' => $frames,
            ]);
        }
    }

    public static function listGroup(string $group)
    {
        $result = [];
        if ($group == 'scenario') {
            $scenarios = Criteria::table("view_relation as r")
                ->join("view_frame as f","r.idEntity1","=","f.idEntity")
                ->join("semantictype as st","r.idEntity2","=","st.idEntity")
                ->where("f.idLanguage","=", AppService::getCurrentIdLanguage())
                ->where("st.entry","=","sty_ft_scenario")
                ->select("f.idFrame","f.idEntity","f.name")
                ->orderby("f.name")
                ->all();
            foreach ($scenarios as $row) {
                $result[$row->idFrame] = [
                    'id' => $row->idFrame,
                    'type' => 'scenario',
                    'name' => $row->name,
                    'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-domain'
                ];
            }
        } else {
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
        }
        return $result;
    }

    public static function listFrame(SearchData $search)
    {
        $result = [];
        if (!is_null($search->idFramalDomain)) {
            $frames = Criteria::table("view_frame as f")
                ->join("view_frame_classification as c", "f.idFrame", "=", "c.idFrame")
                ->where("f.idLanguage", AppService::getCurrentIdLanguage())
                ->where("c.idSemanticType", $search->idFramalDomain)
                ->select("f.idFrame", "f.name", "f.description")
                ->orderby("f.name")->all();
        } else if (!is_null($search->idFramalType)) {
            $frames = Criteria::table("view_frame as f")
                ->join("view_frame_classification as c", "f.idFrame", "=", "c.idFrame")
                ->where("f.idLanguage", AppService::getCurrentIdLanguage())
                ->where("c.idSemanticType", $search->idFramalType)
                ->select("f.idFrame", "f.name", "f.description")
                ->orderby("f.name")->all();
        } else if (!is_null($search->idFrameScenario)) {
            $frames = Frame::listScenarioFrames($search->idFrameScenario);
        } else {
            $frames = Criteria::byFilterLanguage("view_frame", ['name', "startswith", $search->frame])
                ->orderBy('name')->all();
        }

        //$frames = ViewFrame::listByFilter($search)->all();
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


}
