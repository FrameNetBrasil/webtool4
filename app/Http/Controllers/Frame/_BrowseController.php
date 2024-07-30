<?php

namespace App\Http\Controllers\Frame;

use App\Data\ComboBox\QData;
use App\Data\Frame\SearchData;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FE\BrowseController as FEController;
use App\Http\Controllers\LU\BrowseController as LUController;
use App\Repositories\Frame;
use App\Repositories\SemanticType;
use App\Repositories\ViewFrame;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class _BrowseController extends Controller
{
    /*
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
        debug($search);
        return view("Frame.gridEdit", [
            'search' => $search
        ]);
    }

    #[Get(path: '/frame/listForSelect')]
    public function listForSelect(QData $data)
    {
        return Frame::listForSelect($data->q)->all();
    }

    #[Post(path: '/frame/listForTree')]
    public function listForTree(SearchData $search)
    {
        debug($search);
        $result = [];
        if ($search->idFramalDomain != 0) {
            //$icon = 'material-icons-outlined wt-tree-icon wt-icon-domain';
            $frames = ViewFrame::listByFilter($search)->all();
            foreach ($frames as $row) {
                $node = [];
                $node['id'] = 'f' . $row->idFrame;
                $node['type'] = 'frame';
                $node['name'] = [$row->name, $row->description];
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                $node['children'] = [];
                $result[] = $node;
            }
            return $result;
        } else {
            if ($search->idFramalType != 0) {
                //$icon = 'material-icons-outlined wt-tree-icon wt-icon-domain';
                $frames = ViewFrame::listByFilter($search)->all();
                foreach ($frames as $row) {
                    $node = [];
                    $node['id'] = 'f' . $row->idFrame;
                    $node['type'] = 'frame';
                    $node['name'] = [$row->name, $row->description];
                    $node['state'] = 'closed';
                    $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                    $node['children'] = [];
                    $result[] = $node;
                }
                return $result;
            } else {
                if ($search->idFrame != 0) {
                    $resultFE = FEController::listForTreeByFrame($search->idFrame);
                    $resultLU = LUController::listForTreeByFrame($search->idFrame);
                    return array_merge($resultFE, $resultLU);
                } else {
                    $icon = 'material-icons-outlined wt-tree-icon wt-icon-domain';
                    if (($search->frame == '') && ($search->fe == '') && ($search->lu == '')) {
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
                        if (($search->fe == '') && ($search->lu == '')) {
                            $frames = ViewFrame::listByFilter($search)->all();
                            foreach ($frames as $row) {
                                $node = [];
                                $node['id'] = 'f' . $row->idFrame;
                                $node['type'] = 'frame';
                                $node['name'] = [$row->name, $row->description];
                                $node['state'] = 'closed';
                                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-frame';
                                $node['children'] = [];
                                $result[] = $node;
                            }
                        } else {
                            if ($search->fe != '') {
                                $result = FEController::listForTreeByName($search->fe);
                                $icon = "material-icons wt-tree-icon wt-icon-fe-core";
                            } else if ($search->lu != '') {
                                $result = LUController::listForTreeByName($search->lu);
                                $icon = 'material-icons-outlined wt-tree-icon wt-icon-lu';
                            }
                        }
                    }
                }
            }
            $total = count($result);
            return [
                'total' => $total,
                'rows' => $result,
                'footer' => [
                    [
                        'type' => 'frame',
                        'name' => ["{$total} record(s)", ''],
                        'iconCls' => $icon
                    ]
                ]
            ];

        }

    }
    */
}
