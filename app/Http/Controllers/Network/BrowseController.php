<?php

namespace App\Http\Controllers\Network;

use App\Data\ComboBox\QData;
use App\Data\Network\SearchData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FE\BrowseController as FEController;
use App\Http\Controllers\LU\BrowseController as LUController;
use App\Repositories\Frame;
use App\Repositories\Relation;
use App\Repositories\SemanticType;
use App\Repositories\ViewFrame;
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
    public function listForTree(SearchData $search)
    {
        debug($search);
        $result = [];
        if ($search->type != 'node') {
            if ($search->idFrame != 0) {
                $resultFrame = $this->listForTreeByFrame($search->idFrame);
                return $resultFrame;
            } else {
                if ($search->idFramalDomain != 0) {
                    //$icon = 'material-icons-outlined wt-tree-icon wt-icon-domain';
                    $frames = ViewFrame::listByFilter($search)->all();
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
                    $icon = 'material-icons-outlined wt-tree-icon wt-icon-domain';
                    if ($search->frame == '') {
                        $domains = SemanticType::listFrameDomain()->all();
                        foreach ($domains as $row) {
                            $node = [];
                            $node['id'] = 'd' . $row->idSemanticType;
                            $node['type'] = 'domain';
                            $node['name'] = [$row->name, $row->description ?? ''];
                            $node['state'] = 'closed';
                            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-domain';
                            $node['children'] = [];
                            $result[] = $node;
                        }
                    } else {
                        $icon = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                        $frames = ViewFrame::listByFilter($search)->all();
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
//        $total = count($result);
//        return [
//            'total' => $total,
//            'rows' => $result,
//            'footer' => [
//                [
//                    'type' => 'frame',
//                    'name' => ["{$total} record(s)", ''],
//                    'iconCls' => $icon
//                ]
//            ]
//        ];
        return $result;
    }

    public function listForTreeByFrame(int $idFrame): array
    {
        $result = [];
        $frameBase = Frame::getById($idFrame);
        $config = config('webtool.relations');
        $relations = Frame::listDirectRelations($idFrame)->all();
        foreach ($relations as $row) {
            $relationName = $config[$row->entry]['direct'];
            $frame = Frame::getById($row->idFrame);
            $domain = $this->getDomain($row->idFrame);
            $node = [];
            $node['id'] = $idFrame . '_' . $row->entry . '_' . $row->idFrame;
            $node['idFrame'] = 'f' . $row->idFrame;
            $node['idEntityRelation'] = $row->idEntityRelation;
            $node['type'] = 'relation';
            $node['name'] = [$row->name, $frame->description ?? '', $relationName, $row->entry, $domain];
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
        $relations = Frame::listInverseRelations($idFrame)->all();
        foreach ($relations as $row) {
            $relationName = $config[$row->entry]['inverse'];
            $frame = Frame::getById($row->idFrame);
            $domain = $this->getDomain($row->idFrame);
            $node = [];
            $node['id'] = $idFrame . '_' . $row->entry . '_' . $row->idFrame;
            $node['idFrame'] = 'f' . $row->idFrame;
            $node['idEntityRelation'] = $row->idEntityRelation;
            $node['type'] = 'relation';
            $node['name'] = [$row->name, $frame->description ?? '', $relationName, $row->entry, $domain];
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

    #[Get(path: '/network/ferelation/{idEntityRelation}')]
    public function gridRelationsFE(int $idEntityRelation)
    {
        $relation = Relation::getById($idEntityRelation);
        debug($relation);
        $config = config('webtool.relations');
        return view("Network.feRelationGrid",[
            'idEntityRelation' => $idEntityRelation,
            'frame' => Frame::getByIdEntity($relation->idEntity1),
            'relatedFrame' => Frame::getByIdEntity($relation->idEntity2),
            'relation' => (object)[
                'name' => $config[$relation->entry]['direct'],
                'entry' => $relation->entry
            ],
            'relations' => RelationService::listRelationsFE($idEntityRelation)
        ]);
    }
}
