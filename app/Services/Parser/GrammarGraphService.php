<?php

namespace App\Services\Parser;

use App\Repositories\Parser\GrammarGraph;
use App\Repositories\Parser\MWE;

class GrammarGraphService
{
    /**
     * Get MWEs that start with a specific word
     */
    public function getMWEsStartingWith(int $idGrammarGraph, string $word): array
    {
        return MWE::getStartingWith($idGrammarGraph, $word);
    }

    /**
     * Get predicted types from a focus node
     */
    public function getPredictedTypes(object $focusNode, int $idGrammarGraph): array
    {
        $predictions = GrammarGraph::getPredictedTypes($idGrammarGraph, $focusNode->type);

        // Filter by minimum weight
        $minWeight = config('parser.prediction.minWeight', 0.1);

        return array_filter($predictions, function ($prediction) use ($minWeight) {
            return $prediction->weight >= $minWeight;
        });
    }

    /**
     * Check if a link can be created between source and target
     */
    public function canLink(object $sourceNode, object $targetNode, int $idGrammarGraph): bool
    {
        // Get predictions from source node
        $predictions = $this->getPredictedTypes($sourceNode, $idGrammarGraph);

        // Check if target type is in predictions
        foreach ($predictions as $prediction) {
            if ($prediction->type === $targetNode->type) {
                return true;
            }

            // Also check if prediction is for specific word (F type)
            if ($prediction->label === $targetNode->label) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get word type from label (for Portuguese words)
     */
    public function getWordType(string $word, int $idGrammarGraph): string
    {
        // Check if it's a fixed word
        $nodes = GrammarGraph::getNodes($idGrammarGraph);

        foreach ($nodes as $node) {
            if ($node->type === 'F' && strtolower($node->label) === strtolower($word)) {
                return $node->type;
            }
        }

        // Default to E (entity) for unknown words
        // In a real implementation, this would use POS tagging or a lexicon
        return 'E';
    }

    /**
     * Build a basic grammar graph from rules
     */
    public function buildGrammarFromRules(string $name, string $language, array $rules): int
    {
        $idGrammarGraph = GrammarGraph::create([
            'name' => $name,
            'language' => $language,
        ]);

        // Create base type nodes
        $nodeMap = [];

        // Create E, V, A type nodes
        foreach (['E', 'V', 'A'] as $type) {
            $nodeMap[$type] = GrammarGraph::createNode([
                'idGrammarGraph' => $idGrammarGraph,
                'label' => $type,
                'type' => $type,
                'threshold' => 1,
            ]);
        }

        // Create edges based on rules
        foreach ($rules as $rule) {
            if (isset($rule['from']) && isset($rule['to']) && isset($rule['weight'])) {
                $sourceId = $nodeMap[$rule['from']] ?? null;
                $targetId = $nodeMap[$rule['to']] ?? null;

                if ($sourceId && $targetId) {
                    GrammarGraph::createEdge([
                        'idGrammarGraph' => $idGrammarGraph,
                        'idSourceNode' => $sourceId,
                        'idTargetNode' => $targetId,
                        'edgeType' => 'prediction',
                        'weight' => $rule['weight'],
                    ]);
                }
            }
        }

        return $idGrammarGraph;
    }

    /**
     * Add function word to grammar
     */
    public function addFunctionWord(int $idGrammarGraph, string $word): int
    {
        return GrammarGraph::createNode([
            'idGrammarGraph' => $idGrammarGraph,
            'label' => "F_{$word}",
            'type' => 'F',
            'threshold' => 1,
        ]);
    }

    /**
     * Get complete grammar structure
     */
    public function getGrammarStructure(int $idGrammarGraph): object
    {
        return GrammarGraph::getWithStructure($idGrammarGraph);
    }

    /**
     * Validate grammar graph completeness
     */
    public function validateGrammar(int $idGrammarGraph): array
    {
        $errors = [];
        $grammar = GrammarGraph::getWithStructure($idGrammarGraph);

        // Check for base types
        $types = ['E', 'V', 'A'];
        $foundTypes = [];

        foreach ($grammar->nodes as $node) {
            if (in_array($node->type, $types)) {
                $foundTypes[] = $node->type;
            }
        }

        foreach ($types as $type) {
            if (! in_array($type, $foundTypes)) {
                $errors[] = "Missing base type node: {$type}";
            }
        }

        // Check for edges
        if (empty($grammar->edges)) {
            $errors[] = 'Grammar has no edges';
        }

        // Check for orphan nodes (nodes with no edges)
        $nodesWithEdges = [];

        foreach ($grammar->edges as $edge) {
            $nodesWithEdges[$edge->idSourceNode] = true;
            $nodesWithEdges[$edge->idTargetNode] = true;
        }

        foreach ($grammar->nodes as $node) {
            if (! isset($nodesWithEdges[$node->idGrammarNode])) {
                $errors[] = "Orphan node: {$node->label}";
            }
        }

        return $errors;
    }
}
