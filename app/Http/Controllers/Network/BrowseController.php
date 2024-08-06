<?php

namespace App\Http\Controllers\Network;

use App\Data\ComboBox\QData;
use App\Data\Network\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FE\BrowseController as FEController;
use App\Http\Controllers\LU\BrowseController as LUController;
use App\Repositories\Frame;
use App\Repositories\Relation;
use App\Repositories\SemanticType;
use App\Repositories\ViewFrame;
use App\Services\AppService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/network')]
    public function browse()
    {
        $search = session('searchNetwork') ?? SearchData::from();
        return view("Network.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/network/grid')]
    public function grid(SearchData $search)
    {
        debug($search);
        return view("Network.grids", [
            'search' => $search
        ]);
    }

    #[Post(path: '/network/listForTree')]
    public function listForTree(SearchData $search): array
    {
        debug($search);
        $result = [];
        if ($search->type != 'node') {
            if ($search->idFrame != 0) {
                $resultFrame = $this->listForTreeByFrame($search->idFrame);
                return $resultFrame;
            } else {
                if ($search->idFramalDomain != 0) {
                    $frames = Criteria::byFilterLanguage("view_frame", [
                        ["name", "startswith", $search->frame]
                    ])->all();
                    foreach ($frames as $row) {
                        $domain = $this->getDomain($row->idFrame);
                        $node = [];
                        $node['id'] = $row->idFrame;
                        $node['type'] = 'frame';
                        $node['name'] = [$row->name, $row->description ?? '', '', '', $domain];
                        $node['state'] = 'closed';
                        $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                        $node['children'] = [];
                        $result[] = $node;
                    }
                    return $result;
                } else {
                    if ($search->frame != '') {
                        $frames = Criteria::byFilterLanguage("view_frame", [
                            ["name", "startswith", $search->frame]
                        ])->all();
                        foreach ($frames as $row) {
                            $domain = $this->getDomain($row->idFrame);
                            $node = [];
                            $node['id'] = $row->idFrame;
                            $node['idFrame'] = 'f' . $row->idFrame;
                            $node['type'] = 'frame';
                            $node['name'] = [$row->name, $row->description ?? '', '', '', $domain];
                            $node['state'] = 'closed';
                            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                            $node['children'] = [];
                            $result[] = $node;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function listForTreeByFrame(int $idFrame): array
    {
        $result = [];
        $frameBase = Frame::byId($idFrame);
        $idLanguage = AppService::getCurrentIdLanguage();
        $config = config('webtool.relations');
        $relations = Criteria::table("view_frame_relation")
            ->where("f1IdFrame", $idFrame)
            ->where("idLanguage", $idLanguage)
            ->orderBy("relationType")
            ->all();
        foreach ($relations as $row) {
            $relationName = $config[$row->relationType]['direct'];
            $frame = Frame::byId($row->f2IdFrame);
            $domain = $this->getDomain($frame->idFrame);
            $node = [];
            $node['id'] = $idFrame . '_' . $row->relationType . '_' . $frame->idFrame;
            $node['idFrame'] = 'f' . $frame->idFrame;
            $node['idEntityRelation'] = $row->idEntityRelation;
            $node['type'] = 'relation';
            $node['name'] = [$frame->name, $frame->description ?? '', $relationName, $row->relationType, $domain];
            $node['frame'] = $frameBase->name;
            $node['state'] = 'closed';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-relation';
            $node['children'] = [];
            $result[] = $node;
        }
        $direct = [
            'id' => 'n' . $idFrame . '_' . uniqid() . '_' . 'direct',
            'type' => 'node',
            'name' => 'direct',
            'state' => 'closed',
            'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-relation',
            'children' => empty($result) ? null : $result
        ];
        $result = [];

        $relations = Criteria::table("view_frame_relation")
            ->where("f2IdFrame", $idFrame)
            ->where("idLanguage", $idLanguage)
            ->orderBy("relationType")
            ->all();
        foreach ($relations as $row) {
            $relationName = $config[$row->relationType]['inverse'];
            $frame = Frame::byId($row->f1IdFrame);
            $domain = $this->getDomain($frame->idFrame);
            $node = [];
            $node['id'] = $idFrame . '_' . $row->relationType . '_' . $frame->idFrame;
            $node['idFrame'] = 'f' . $frame->idFrame;
            $node['idEntityRelation'] = $row->idEntityRelation;
            $node['type'] = 'relation';
            $node['name'] = [$frame->name, $frame->description ?? '', $relationName, $row->relationType, $domain];
            $node['frame'] = $frameBase->name;
            $node['state'] = 'closed';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-relation';
            $node['children'] = [];
            $result[] = $node;
        }
        $inverse = [
            'id' => 'n' . $idFrame . '_' . uniqid() . '_' . 'inverse',
            'type' => 'node',
            'name' => 'inverse',
            'state' => 'closed',
            'iconCls' => 'material-icons-outlined wt-tree-icon wt-icon-relation',
            'children' => empty($result) ? null : $result
        ];
        return [$direct, $inverse];
    }

    public function getDomain(int $idFrame): string
    {
        $labels = Frame::getClassificationLabels($idFrame);
        $domain = '';
        if (isset($labels['rel_framal_domain'])) {
            foreach ($labels['rel_framal_domain'] as $label) {
                $domain .= $label . ' ';
            }
        }
        return $domain;
    }

//    #[Get(path: '/network/ferelation/{idEntityRelation}')]
//    public function gridRelationsFE(int $idEntityRelation)
//    {
//        $relation = Relation::getById($idEntityRelation);
//        debug($relation);
//        $config = config('webtool.relations');
//        return view("Network.feRelationGrid",[
//            'idEntityRelation' => $idEntityRelation,
//            'frame' => Frame::getByIdEntity($relation->idEntity1),
//            'relatedFrame' => Frame::getByIdEntity($relation->idEntity2),
//            'relation' => (object)[
//                'name' => $config[$relation->entry]['direct'],
//                'entry' => $relation->entry
//            ],
//            'relations' => RelationService::listRelationsFE($idEntityRelation)
//        ]);
//    }
}
