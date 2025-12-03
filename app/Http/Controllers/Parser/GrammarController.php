<?php

namespace App\Http\Controllers\Parser;

use App\Data\Parser\GrammarGraphData;
use App\Data\Parser\GrammarNodeData;
use App\Data\Parser\MWEData;
use App\Http\Controllers\Controller;
use App\Repositories\Parser\GrammarGraph;
use App\Repositories\Parser\MWE;
use App\Services\Parser\GrammarGraphService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class GrammarController extends Controller
{
    public function __construct(
        private GrammarGraphService $grammarService
    )
    {
    }

    /**
     * Browse nodes in grammar 1
     */
    #[Get(path: '/parser/grammar')]
    public function index()
    {
        $grammar = GrammarGraph::byId(1);
        $nodes = GrammarGraph::getNodes(1, 300);

        return view('Parser.Grammar.browse', [
            'grammar' => $grammar,
            'data' => $nodes,
        ]);
    }

    /**
     * View grammar graph details
     */
    #[Get(path: '/parser/grammar/{id}')]
    public function view(int $id)
    {
        $grammar = $this->grammarService->getGrammarStructure($id);
        $errors = $this->grammarService->validateGrammar($id);

        return view('Parser.grammarView', [
            'grammar' => $grammar,
            'errors' => $errors,
        ]);
    }

    /**
     * Show create grammar form
     */
    #[Get(path: '/parser/grammar/create')]
    public function create()
    {
        return view('Parser.grammarCreate');
    }

    /**
     * Store new grammar graph
     */
    #[Post(path: '/parser/grammar/create')]
    public function store(GrammarGraphData $data)
    {
        try {
            $idGrammar = GrammarGraph::create([
                'name' => $data->name,
                'language' => $data->language,
                'description' => $data->description,
            ]);

            return redirect('/parser/grammar/' . $idGrammar)
                ->with('success', 'Grammar graph created successfully');
        } catch (\Exception $e) {
            logger()->error('Grammar creation error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create grammar: ' . $e->getMessage());
        }
    }

    /**
     * List MWEs for a grammar
     */
    #[Get(path: '/parser/grammar/{id}/mwes')]
    public function listMWEs(int $id)
    {
        $grammar = GrammarGraph::byId($id);
        $mwes = MWE::listByGrammar($id);

        return view('Parser.mweList', [
            'grammar' => $grammar,
            'mwes' => $mwes,
        ]);
    }

    /**
     * Add MWE to grammar (HTMX endpoint)
     */
    #[Post(path: '/parser/grammar/{id}/mwes')]
    public function addMWE(int $id, MWEData $data)
    {
        try {
            $idMWE = MWE::create([
                'idGrammarGraph' => $id,
                'phrase' => $data->phrase,
                'components' => $data->components,
                'semanticType' => $data->semanticType,
            ]);

            $mwe = MWE::byId($idMWE);

            return view('Parser.mweListItem', [
                'mwe' => $mwe,
            ]);
        } catch (\Exception $e) {
            logger()->error('MWE creation error: ' . $e->getMessage());

            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get filtered grammar tables (HTMX endpoint)
     */
    #[Get(path: '/parser/grammar/{id}/tables')]
    public function tables(int $id)
    {
        $grammar = GrammarGraph::getWithStructure($id);
        $filter = request()->get('filter', '');

        // Filter nodes if filter is provided
        $filteredNodes = [];
        $filteredMwes = [];

        if (!empty($filter)) {
            $filteredNodes = array_values(array_filter($grammar->nodes, function ($node) use ($filter) {
                return stripos($node->label, $filter) !== false;
            }));

            $filteredMwes = array_values(array_filter($grammar->mwes, function ($mwe) use ($filter) {
                return stripos($mwe->phrase, $filter) !== false;
            }));
        }

        return view('Parser.grammarTables', [
            'nodes' => $filteredNodes,
            'mwes' => $filteredMwes,
            'filter' => $filter,
        ]);
    }

    /**
     * Get grammar graph visualization (HTMX endpoint)
     */
    #[Get(path: '/parser/grammar/{id}/visualization')]
    public function visualization(int $id)
    {
        $grammar = GrammarGraph::getWithStructure($id);
        $filter = request()->get('filter', '');

        // Prepare JointJS graph data format (nodes/links)
        $nodeColors = config('parser.visualization.nodeColors');
        $edgeColors = config('parser.visualization.edgeColors');

        // Filter nodes if filter is provided
        $filteredNodes = $grammar->nodes;
        $filteredNodeIds = [];

        if (!empty($filter)) {
            // First, find nodes matching the filter
            $matchingNodes = array_filter($grammar->nodes, function ($node) use ($filter) {
                return stripos($node->label, $filter) !== false;
            });
            $matchingNodeIds = array_map(fn($node) => $node->idGrammarNode, $matchingNodes);

            // Find all edges connected to matching nodes (either as source or target)
            $connectedEdges = array_filter($grammar->edges, function ($edge) use ($matchingNodeIds) {
                return in_array($edge->idSourceNode, $matchingNodeIds) ||
                    in_array($edge->idTargetNode, $matchingNodeIds);
            });

            // Collect all node IDs that are connected via these edges
            $connectedNodeIds = $matchingNodeIds;
            foreach ($connectedEdges as $edge) {
                $connectedNodeIds[] = $edge->idSourceNode;
                $connectedNodeIds[] = $edge->idTargetNode;
            }
            $connectedNodeIds = array_unique($connectedNodeIds);

            // Filter nodes to include matching nodes and their connected neighbors
            $filteredNodes = array_values(array_filter($grammar->nodes, function ($node) use ($connectedNodeIds) {
                return in_array($node->idGrammarNode, $connectedNodeIds);
            }));
            $filteredNodeIds = $connectedNodeIds;

            // Filter edges to only include those where BOTH source AND target are in the filtered set
            $filteredEdges = array_values(array_filter($grammar->edges, function ($edge) use ($filteredNodeIds) {
                return in_array($edge->idSourceNode, $filteredNodeIds) &&
                    in_array($edge->idTargetNode, $filteredNodeIds);
            }));
        } else {
            $filteredEdges = $grammar->edges;
        }

        // Build graph structure for JointJS grapherComponent
        $nodes = [];
        $links = [];

        // Create nodes array indexed by node ID
        foreach ($filteredNodes as $node) {
            $nodes[$node->idGrammarNode] = [
                'type' => 'grammar',
                'name' => $node->label,
                'grammarType' => $node->type,
                'threshold' => $node->threshold,
                'idColor' => $nodeColors[$node->type] ?? '#999',
            ];
        }

        // Create links array (nested: source -> target -> relation data)
        foreach ($filteredEdges as $edge) {
            $links[$edge->idSourceNode][$edge->idTargetNode] = [
                'type' => 'grammar',
                'relationEntry' => $edge->linkType,
                'weight' => $edge->weight,
                'color' => $edgeColors[$edge->linkType] ?? '#999',
            ];
        }

        $graph = [
            'nodes' => $nodes,
            'links' => $links,
        ];

        // Calculate statistics
        $stats = [
            'totalNodes' => count($filteredNodes),
            'totalEdges' => count($filteredEdges),
            'avgDegree' => count($filteredNodes) > 0 ? (count($filteredEdges) * 2) / count($filteredNodes) : 0,
            'nodesByType' => [],
            'unfilteredTotalNodes' => count($grammar->nodes),
            'unfilteredTotalEdges' => count($grammar->edges),
        ];

        foreach ($filteredNodes as $node) {
            $type = $node->type;
            $stats['nodesByType'][$type] = ($stats['nodesByType'][$type] ?? 0) + 1;
        }

        return view('Parser.grammarGraph', [
            'idGrammarGraph' => $id,
            'graph' => $graph,
            'stats' => $stats,
            'grammar' => $grammar,
            'filter' => $filter,
        ]);
    }

    /**
     * Show create node form (HTMX partial)
     */
    #[Get(path: '/parser/grammar/node/create')]
    public function createNode()
    {
        return view('Parser.Grammar.nodeForm', [
            'idGrammarGraph' => 1,
            'node' => null,
        ]);
    }

    /**
     * Store new grammar node
     */
    #[Post(path: '/parser/grammar/node')]
    public function storeNode(GrammarNodeData $data)
    {
        try {
            $nodeId = GrammarGraph::createNode([
                'idGrammarGraph' => $data->idGrammarGraph,
                'label' => $data->label,
                'type' => $data->type,
                'threshold' => $data->threshold,
                'idLemma' => $data->idLemma,
            ]);

            return redirect('/parser/grammar')
                ->with('success', 'Node created successfully');
        } catch (\Exception $e) {
            logger()->error('Node creation error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create node: ' . $e->getMessage());
        }
    }

    /**
     * Show edit node form
     */
    #[Get(path: '/parser/grammar/node/{id}/edit')]
    public function editNode(int $id)
    {
        // Verify node belongs to grammar 1
        if (!GrammarGraph::nodeExistsInGrammar($id, 1)) {
            abort(404, 'Node not found in grammar 1');
        }

        $node = GrammarGraph::getNodeById($id);
        $grammar = GrammarGraph::byId(1);

        return view('Parser.Grammar.nodeEdit', [
            'grammar' => $grammar,
            'node' => $node,
        ]);
    }

    /**
     * Update grammar node
     */
    #[Post(path: '/parser/grammar/node/{id}/update')]
    public function updateNode(int $id, GrammarNodeData $data)
    {
        try {
            // Verify node belongs to grammar 1
            if (!GrammarGraph::nodeExistsInGrammar($id, 1)) {
                abort(404, 'Node not found in grammar 1');
            }

            GrammarGraph::updateNode($id, [
                'label' => $data->label,
                'type' => $data->type,
                'threshold' => $data->threshold,
                'idLemma' => $data->idLemma,
            ]);

            return redirect('/parser/grammar')
                ->with('success', 'Node updated successfully');
        } catch (\Exception $e) {
            logger()->error('Node update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update node: ' . $e->getMessage());
        }
    }

    /**
     * Delete grammar node
     */
    #[Delete(path: '/parser/grammar/node/{id}/delete')]
    public function deleteNode(int $id)
    {
        try {
            // Verify node belongs to grammar 1
            if (!GrammarGraph::nodeExistsInGrammar($id, 1)) {
                $this->renderNotify("error", "Node not found in grammar 1");
            } else {
                GrammarGraph::deleteNode($id);
                return $this->clientRedirect("/parser/grammar");
            }

        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
