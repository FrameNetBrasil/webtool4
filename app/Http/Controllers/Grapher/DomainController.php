<?php

namespace App\Http\Controllers\Grapher;

use App\Data\Grapher\DomainData;
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
class DomainController extends Controller
{
    #[Get(path: '/grapher/domain')]
    public function domain()
    {
        $relations = RelationType::listByFilter((object)[
            'group' => 'rgp_frame_relations'
        ])->all();
        $dataRelations = [];
        $config = config('webtool.relations');
        foreach($relations as $relation) {
            $dataRelations[] = [
                'value' => $relation->idRelationType,
                'entry' => $relation->entry,
                'name' => $config[$relation->entry]['direct'],
            ];
        }
        return view('Grapher.Domain.domain', [
            'relations' => $dataRelations
        ]);
    }

    #[Post(path: '/grapher/domain/graph/{idEntity?}')]
    public function domainGraph(DomainData $data,int $idEntity = null)
    {
        $nodes = session("graphNodes") ?? [];
        if (is_null($data->idSemanticType)) {
            $data->idSemanticType = 0;
        }
        if (empty($data->idRelationType)) {
            $data->idRelationType = session('idRelationType') ?? [];
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
            "idRelationType" => $data->idRelationType
        ]);
        return view('Grapher.Domain.domainGraph', [
            'graph' => RelationService::listDomainForGraph($data->idSemanticType, $data->idRelationType)
        ]);
    }

    #[Post(path: '/grapher/framefe/graph/{idEntityRelation}')]
    public function frameFeGraph(int $idEntityRelation = null)
    {
        ddump($this->data);
        $nodes = session("graphNodes") ?? [];
        $idRelationType = session('idRelationType');
        $this->data->graph = RelationService::listFrameRelationsForGraph($nodes, $idRelationType);
        $feGraph = RelationService::listFrameFERelationsForGraph($idEntityRelation);
        foreach($feGraph['nodes'] as $idNode => $node) {
            $this->data->graph['nodes'][$idNode] = $node;
        }
        foreach($feGraph['links'] as $idSource => $links) {
            foreach($links as $idTarget => $link) {
                $this->data->graph['links'][$idSource][$idTarget] = $link;
            }
        }
        ddump($this->data);
        return $this->render('frameGraph');
    }

}