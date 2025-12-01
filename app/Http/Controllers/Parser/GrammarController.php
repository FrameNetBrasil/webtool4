<?php

namespace App\Http\Controllers\Parser;

use App\Data\Parser\GrammarGraphData;
use App\Data\Parser\MWEData;
use App\Http\Controllers\Controller;
use App\Repositories\Parser\GrammarGraph;
use App\Repositories\Parser\MWE;
use App\Services\Parser\GrammarGraphService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class GrammarController extends Controller
{
    public function __construct(
        private GrammarGraphService $grammarService
    ) {}

    /**
     * List all grammar graphs
     */
    #[Get(path: '/parser/grammar')]
    public function index()
    {
        $grammars = GrammarGraph::list();

        return view('Parser.grammarList', [
            'grammars' => $grammars,
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

            return redirect('/parser/grammar/'.$idGrammar)
                ->with('success', 'Grammar graph created successfully');
        } catch (\Exception $e) {
            logger()->error('Grammar creation error: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create grammar: '.$e->getMessage());
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
            logger()->error('MWE creation error: '.$e->getMessage());

            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
