<?php

namespace App\Http\Controllers\Grapher;

use App\Data\Grapher\FrameData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Repositories\RelationType;
use App\Services\FrameService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Illuminate\Http\Request;

#[Middleware(name: 'web')]
class FrameController extends Controller
{
    #[Get(path: '/grapher/frame')]
    public function frame()
    {
        $relations = Criteria::byFilterLanguage("view_relationtype",[
            'rgEntry',"=",'rgp_frame_relations'
        ])->all();
        $dataRelations = [];
        $config = config('webtool.relations');
        foreach($relations as $relation) {
            $dataRelations[] = (object)[
                'idRelationType' => $relation->idRelationType,
                'name' => $config[$relation->entry]['direct'],
                'entry' => $relation->entry,
            ];
        }
        return view('Grapher.Frame.frame', [
            'relations' => $dataRelations
        ]);
    }

    #[Post(path: '/grapher/frame/graph/{idEntity?}')]
    public function frameGraph(FrameData $data, int $idEntity = null)
    {
        $nodes = session("graphNodes") ?? [];
        if (!is_null($data->idFrame)) {
            $frame = Frame::byId($data->idFrame);
            $nodes = [$frame->idEntity];
        }
        if (empty($data->frameRelation)) {
            $data->frameRelation = session('frameRelation') ?? [];
        }
        if (!is_null($idEntity)) {
            if ($idEntity == 0) {
                $nodes = [];
            } else {
                $nodes = [...$nodes, $idEntity];
            }
        }
        session([
            "graphNodes" => $nodes,
            "idRelationType" => $data->frameRelation
        ]);
        return view('Grapher.Frame.frameGraph', [
            'graph' => RelationService::listFrameRelationsForGraph($nodes, $data->frameRelation)
        ]);
    }

    #[Post(path: '/grapher/framefe/graph/{idEntityRelation}')]
    public function frameFeGraph(int $idEntityRelation = null)
    {
        $nodes = session("graphNodes") ?? [];
        $idRelationType = session('idRelationType');
        $graph = RelationService::listFrameRelationsForGraph($nodes, $idRelationType);
        $feGraph = RelationService::listFrameFERelationsForGraph($idEntityRelation);
        foreach($feGraph['nodes'] as $idNode => $node) {
            $graph['nodes'][$idNode] = $node;
        }
        foreach($feGraph['links'] as $idSource => $links) {
            foreach($links as $idTarget => $link) {
                $graph['links'][$idSource][$idTarget] = $link;
            }
        }
        data('graph', $graph);
        return $this->render('frameGraph');
    }

}
