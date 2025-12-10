<?php

namespace App\Http\Controllers\Parser;

use App\Http\Controllers\Controller;
use App\Repositories\Parser\Construction;
use App\Repositories\Parser\GrammarGraph;
use App\Services\Parser\ConstructionService;
use App\Services\Parser\PatternCompiler;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Illuminate\Http\Request;

#[Middleware(name: 'web')]
class ConstructionController extends Controller
{
    public function __construct(
        private ConstructionService $constructionService,
        private PatternCompiler $compiler
    ) {
    }

    /**
     * List all constructions for a grammar
     */
    #[Get(path: '/parser/construction')]
    public function index()
    {
        // Default to grammar 1 for now
        $idGrammarGraph = request()->get('grammar', 1);

        $constructions = Construction::listByGrammar($idGrammarGraph);
        $grammar = $this->getGrammarOrFail($idGrammarGraph);

        return view('Parser.Construction.index', [
            'constructions' => $constructions,
            'grammar' => $grammar,
            'stats' => $this->constructionService->getStats($idGrammarGraph),
        ]);
    }

    /**
     * Show create form
     */
    #[Get(path: '/parser/construction/create')]
    public function create()
    {
        $idGrammarGraph = request()->get('grammar', 1);
        $grammar = $this->getGrammarOrFail($idGrammarGraph);

        return view('Parser.Construction.create', [
            'grammar' => $grammar,
            'semanticTypes' => $this->getSemanticTypes(),
        ]);
    }

    /**
     * Import constructions from JSON (show form)
     */
    #[Get(path: '/parser/construction/import')]
    public function importForm()
    {
        $idGrammarGraph = request()->get('grammar', 1);
        $grammar = $this->getGrammarOrFail($idGrammarGraph);

        return view('Parser.Construction.import', [
            'grammar' => $grammar,
        ]);
    }

    /**
     * Store new construction
     */
    #[Post(path: '/parser/construction/create')]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idGrammarGraph' => 'required|integer',
            'name' => 'required|string|max:100',
            'pattern' => 'required|string',
            'description' => 'nullable|string',
            'semanticType' => 'required|string|max:20',
            'priority' => 'integer|min:-128|max:127',
            'enabled' => 'boolean',
        ]);

        try {
            $idConstruction = $this->constructionService->compileAndStore(
                idGrammarGraph: $validated['idGrammarGraph'],
                name: $validated['name'],
                pattern: $validated['pattern'],
                metadata: [
                    'description' => $validated['description'] ?? null,
                    'semanticType' => $validated['semanticType'],
                    'priority' => $validated['priority'] ?? 0,
                    'enabled' => $validated['enabled'] ?? true,
                ]
            );

            return redirect('/parser/construction/'.$idConstruction)
                ->with('success', 'Construction created successfully');
        } catch (\Exception $e) {
            logger()->error('Construction creation error: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create construction: '.$e->getMessage());
        }
    }

    /**
     * Show construction details
     */
    #[Get(path: '/parser/construction/{id}')]
    public function show(int $id)
    {
        $construction = Construction::byId($id);
        $grammar = $this->getGrammarOrFail($construction->idGrammarGraph);
        $compiledGraph = Construction::getCompiledGraph($construction);

        return view('Parser.Construction.show', [
            'construction' => $construction,
            'grammar' => $grammar,
            'compiledGraph' => $compiledGraph,
        ]);
    }

    /**
     * Show edit form
     */
    #[Get(path: '/parser/construction/{id}/edit')]
    public function edit(int $id)
    {
        $construction = Construction::byId($id);
        $grammar = $this->getGrammarOrFail($construction->idGrammarGraph);

        return view('Parser.Construction.edit', [
            'construction' => $construction,
            'grammar' => $grammar,
            'semanticTypes' => $this->getSemanticTypes(),
        ]);
    }

    /**
     * Update construction
     */
    #[Post(path: '/parser/construction/{id}/update')]
    public function update(int $id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'pattern' => 'required|string',
            'description' => 'nullable|string',
            'semanticType' => 'required|string|max:20',
            'priority' => 'integer|min:-128|max:127',
            'enabled' => 'boolean',
        ]);

        try {
            $construction = Construction::byId($id);

            // If pattern changed, recompile
            if ($validated['pattern'] !== $construction->pattern) {
                $validation = $this->compiler->validate($validated['pattern']);
                if (! $validation['valid']) {
                    throw new \Exception('Invalid pattern: '.implode(', ', $validation['errors']));
                }

                $compiledGraph = $this->compiler->compile($validated['pattern']);
                $validated['compiledGraph'] = $compiledGraph;
            }

            Construction::update($id, $validated);

            return redirect('/parser/construction/'.$id)
                ->with('success', 'Construction updated successfully');
        } catch (\Exception $e) {
            logger()->error('Construction update error: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update construction: '.$e->getMessage());
        }
    }

    /**
     * Delete construction
     */
    #[Delete(path: '/parser/construction/{id}/delete')]
    public function destroy(int $id)
    {
        try {
            $construction = Construction::byId($id);
            Construction::delete($id);

            return $this->clientRedirect('/parser/construction?grammar='.$construction->idGrammarGraph);
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    /**
     * Toggle construction enabled status (HTMX endpoint)
     */
    #[Post(path: '/parser/construction/{id}/toggle')]
    public function toggle(int $id)
    {
        try {
            $construction = Construction::byId($id);
            $newStatus = ! $construction->enabled;

            if ($newStatus) {
                Construction::enable($id);
            } else {
                Construction::disable($id);
            }

            return response()->json([
                'success' => true,
                'enabled' => $newStatus,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Test pattern against sample sentence (HTMX endpoint)
     */
    #[Post(path: '/parser/construction/test')]
    public function test(Request $request)
    {
        $validated = $request->validate([
            'pattern' => 'required|string',
            'sentence' => 'required|string',
        ]);

        try {
            // For now, return validation results
            // In production, this would parse the sentence and test the pattern
            $validation = $this->compiler->validate($validated['pattern']);

            return view('Parser.Construction.testResult', [
                'pattern' => $validated['pattern'],
                'sentence' => $validated['sentence'],
                'validation' => $validation,
            ]);
        } catch (\Exception $e) {
            return view('Parser.Construction.testResult', [
                'pattern' => $validated['pattern'],
                'sentence' => $validated['sentence'],
                'validation' => [
                    'valid' => false,
                    'errors' => [$e->getMessage()],
                ],
            ]);
        }
    }

    /**
     * View compiled graph visualization
     */
    #[Get(path: '/parser/construction/{id}/graph')]
    public function graph(int $id)
    {
        $construction = Construction::byId($id);
        $compiledGraph = Construction::getCompiledGraph($construction);

        // Convert to DOT format for visualization
        $dot = $this->compiler->toDot($compiledGraph);

        return view('Parser.Construction.graph', [
            'construction' => $construction,
            'compiledGraph' => $compiledGraph,
            'dot' => $dot,
        ]);
    }

    /**
     * Export constructions to JSON
     */
    #[Get(path: '/parser/construction/export')]
    public function export()
    {
        $idGrammarGraph = request()->get('grammar', 1);
        $constructions = Construction::listByGrammar($idGrammarGraph);

        $export = [];
        foreach ($constructions as $construction) {
            $export[] = [
                'name' => $construction->name,
                'pattern' => $construction->pattern,
                'description' => $construction->description,
                'semanticType' => $construction->semanticType,
                'priority' => $construction->priority,
                'enabled' => $construction->enabled,
            ];
        }

        return response()->json($export, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Import constructions from JSON (process)
     */
    #[Post(path: '/parser/construction/import')]
    public function import(Request $request)
    {
        $validated = $request->validate([
            'idGrammarGraph' => 'required|integer',
            'file' => 'required|file|mimes:json',
            'overwrite' => 'boolean',
        ]);

        try {
            $content = file_get_contents($validated['file']->getRealPath());
            $constructions = json_decode($content, true);

            if (! is_array($constructions)) {
                throw new \Exception('Invalid JSON format');
            }

            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($constructions as $data) {
                try {
                    $exists = Construction::exists($validated['idGrammarGraph'], $data['name']);

                    if ($exists && ! ($validated['overwrite'] ?? false)) {
                        $skipped++;
                        continue;
                    }

                    if ($exists) {
                        // Update existing
                        $existing = Construction::getByName($validated['idGrammarGraph'], $data['name']);
                        Construction::update($existing->idConstruction, $data);
                    } else {
                        // Create new
                        $this->constructionService->compileAndStore(
                            idGrammarGraph: $validated['idGrammarGraph'],
                            name: $data['name'],
                            pattern: $data['pattern'],
                            metadata: $data
                        );
                    }

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to import '{$data['name']}': ".$e->getMessage();
                }
            }

            $message = "Imported: {$imported}, Skipped: {$skipped}";
            if (! empty($errors)) {
                $message .= '. Errors: '.implode('; ', $errors);
            }

            return redirect('/parser/construction?grammar='.$validated['idGrammarGraph'])
                ->with('success', $message);
        } catch (\Exception $e) {
            logger()->error('Construction import error: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to import constructions: '.$e->getMessage());
        }
    }

    /**
     * Get grammar or fail with 404
     */
    private function getGrammarOrFail(int $idGrammarGraph): object
    {
        try {
            return GrammarGraph::byId($idGrammarGraph);
        } catch (\Exception $e) {
            abort(404, 'Grammar graph not found');
        }
    }

    /**
     * Get available semantic types
     */
    private function getSemanticTypes(): array
    {
        return [
            'Head' => 'Head (Main semantic contribution)',
            'Multiplier' => 'Multiplier (Multiplicative modifier)',
            'Additive' => 'Additive (Additive modifier)',
            'Ordinal' => 'Ordinal (Order/position)',
            'Temporal' => 'Temporal (Time expression)',
            'Spatial' => 'Spatial (Location/direction)',
            'Quantifier' => 'Quantifier (Amount/quantity)',
            'Classifier' => 'Classifier (Categorization)',
        ];
    }
}
