<?php

namespace App\Services\Parser;

use App\Data\Parser\ParseInputData;
use App\Data\Parser\ParseOutputData;
use App\Repositories\Parser\ParseEdge;
use App\Repositories\Parser\ParseGraph;
use App\Repositories\Parser\ParseNode;
use Illuminate\Support\Facades\DB;

class ParserService
{
    private GrammarGraphService $grammarService;

    private MWEService $mweService;

    private FocusQueueService $queueService;

    public function __construct(
        GrammarGraphService $grammarService,
        MWEService $mweService
    ) {
        $this->grammarService = $grammarService;
        $this->mweService = $mweService;
    }

    /**
     * Parse a sentence using the graph-based predictive parser
     */
    public function parse(ParseInputData $input): ParseOutputData
    {
        $startTime = microtime(true);

        try {
            return DB::transaction(function () use ($input, $startTime) {
                // Initialize focus queue with strategy
                $this->queueService = new FocusQueueService($input->queueStrategy);

                // Create parse graph
                $idParserGraph = ParseGraph::create([
                    'sentence' => $input->sentence,
                    'idGrammarGraph' => $input->idGrammarGraph,
                    'status' => 'parsing',
                ]);

                // Tokenize sentence
                $words = $this->tokenize($input->sentence);

                // Process each word
                foreach ($words as $position => $word) {
                    $this->processWord($word, $idParserGraph, $input->idGrammarGraph, $position + 1);

                    // Check timeout
                    if ((microtime(true) - $startTime) > config('parser.performance.maxParseTime', 30)) {
                        throw new \Exception('Parse timeout exceeded');
                    }
                }

                // Garbage collection
                if (config('parser.garbageCollection.enabled', true)) {
                    $this->garbageCollect($idParserGraph);
                }

                // Validate parse
                $isValid = $this->validateParse($idParserGraph);

                // Update status
                if ($isValid) {
                    ParseGraph::markComplete($idParserGraph);
                } else {
                    ParseGraph::markFailed($idParserGraph, 'Parse validation failed');
                }

                // Return result
                return $this->buildOutput($idParserGraph);
            });
        } catch (\Exception $e) {
            logger()->error('Parser error: '.$e->getMessage());

            throw new \Exception('Parse failed: '.$e->getMessage());
        }
    }

    /**
     * Process a single word
     */
    private function processWord(string $word, int $idParserGraph, int $idGrammarGraph, int $position): void
    {
        if (config('parser.logging.logSteps', false)) {
            logger()->info('Parser: Processing word', [
                'word' => $word,
                'position' => $position,
            ]);
        }

        // Step 1: Create word node
        $wordType = $this->grammarService->getWordType($word, $idGrammarGraph);

        $idWordNode = ParseNode::create([
            'idParserGraph' => $idParserGraph,
            'label' => $word,
            'type' => $wordType,
            'threshold' => 1,
            'activation' => 1,
            'isFocus' => true,
            'positionInSentence' => $position,
        ]);

        $wordNode = ParseNode::byId($idWordNode);

        // Step 2: If word is first in any MWE, instantiate prefix nodes
        $this->mweService->instantiateMWENodes($word, $idParserGraph, $idGrammarGraph, $position);

        // Step 3: Check existing MWE prefix nodes for matches
        $this->checkMWEPrefixes($word, $idParserGraph, $position);

        // Step 4: Check against current focus nodes
        $matched = $this->checkFociPredictions($wordNode, $idParserGraph, $idGrammarGraph);

        // Step 5: If no match, add word as new waiting focus
        if (! $matched) {
            $this->queueService->enqueue($wordNode);
        }
    }

    /**
     * Check MWE prefixes for activation
     */
    private function checkMWEPrefixes(string $word, int $idParserGraph, int $position): void
    {
        $mwePrefixes = $this->mweService->getActivePrefixes($idParserGraph);

        foreach ($mwePrefixes as $prefix) {
            // Check if word matches next expected component
            if ($this->mweService->matchesNextComponent($prefix, $word)) {
                // Check if not interrupted
                if (! $this->mweService->isInterrupted($prefix, $position)) {
                    // Increment activation
                    $this->mweService->incrementActivation($prefix, $word);

                    // Reload node to get updated activation
                    $updatedPrefix = ParseNode::byId($prefix->idParserNode);

                    // If threshold reached, aggregate
                    if (ParseNode::hasReachedThreshold($updatedPrefix)) {
                        $this->mweService->aggregateMWE($updatedPrefix, $idParserGraph);

                        // Add to focus queue
                        $this->queueService->enqueue($updatedPrefix);
                    }
                }
            }
        }
    }

    /**
     * Check if word matches predictions from focus nodes
     */
    private function checkFociPredictions(object $wordNode, int $idParserGraph, int $idGrammarGraph): bool
    {
        $matched = false;
        $foci = $this->queueService->getActiveFoci();

        foreach ($foci as $focus) {
            if ($this->grammarService->canLink($focus, $wordNode, $idGrammarGraph)) {
                // Create edge
                ParseEdge::create([
                    'idParserGraph' => $idParserGraph,
                    'idSourceNode' => $focus->idParserNode,
                    'idTargetNode' => $wordNode->idParserNode,
                    'linkType' => 'dependency',
                ]);

                // Remove focus from queue
                $this->queueService->removeFromQueue($focus);

                $matched = true;

                // Recursive linking
                if (config('parser.prediction.recursiveLinking', true)) {
                    $this->recursiveLinking($wordNode, $idParserGraph, $idGrammarGraph);
                }

                break;
            }
        }

        return $matched;
    }

    /**
     * Attempt recursive linking of waiting foci
     */
    private function recursiveLinking(object $newNode, int $idParserGraph, int $idGrammarGraph): void
    {
        $waitingFoci = $this->queueService->getActiveFoci();

        foreach ($waitingFoci as $focus) {
            if ($this->grammarService->canLink($newNode, $focus, $idGrammarGraph)) {
                // Create edge
                ParseEdge::create([
                    'idParserGraph' => $idParserGraph,
                    'idSourceNode' => $newNode->idParserNode,
                    'idTargetNode' => $focus->idParserNode,
                    'linkType' => 'dependency',
                ]);

                // Remove focus from queue
                $this->queueService->removeFromQueue($focus);

                // Continue recursion
                $this->recursiveLinking($focus, $idParserGraph, $idGrammarGraph);
            }
        }
    }

    /**
     * Remove nodes that didn't reach threshold
     */
    private function garbageCollect(int $idParserGraph): void
    {
        $garbageNodes = ParseGraph::getGarbageNodes($idParserGraph);

        foreach ($garbageNodes as $node) {
            // Check if should keep incomplete MWEs for debugging
            if ($node->type === 'MWE' && config('parser.garbageCollection.keepIncompleteMWE', false)) {
                continue;
            }

            // Delete edges involving this node
            ParseEdge::deleteByNode($node->idParserNode);

            // Delete node
            ParseNode::delete($node->idParserNode);
        }

        if (config('parser.logging.logSteps', false)) {
            logger()->info('Parser: Garbage collection', [
                'removed' => count($garbageNodes),
            ]);
        }
    }

    /**
     * Validate that parse is successful
     */
    private function validateParse(int $idParserGraph): bool
    {
        if (! config('parser.validation.requireConnected', true)) {
            return true;
        }

        $nodeCount = ParseGraph::countNodes($idParserGraph);
        $edgeCount = ParseGraph::countEdges($idParserGraph);

        // Check minimum edge ratio
        $minRatio = config('parser.validation.minEdgeRatio', 0.9);
        $requiredEdges = ($nodeCount - 1) * $minRatio;

        return $edgeCount >= $requiredEdges;
    }

    /**
     * Tokenize sentence into words
     */
    private function tokenize(string $sentence): array
    {
        // Simple whitespace tokenization
        // In production, use a proper tokenizer
        $words = preg_split('/\s+/', trim($sentence));

        // Normalize to lowercase
        return array_map('strtolower', $words);
    }

    /**
     * Build output data
     */
    private function buildOutput(int $idParserGraph): ParseOutputData
    {
        $parseGraph = ParseGraph::getComplete($idParserGraph);
        $stats = ParseGraph::byIdWithStats($idParserGraph);

        return new ParseOutputData(
            idParserGraph: $parseGraph->idParserGraph,
            sentence: $parseGraph->sentence,
            status: $parseGraph->status,
            nodes: $parseGraph->nodes,
            edges: $parseGraph->edges,
            nodeCount: $stats->nodeCount ?? 0,
            edgeCount: $stats->linkCount ?? 0,
            focusNodeCount: $stats->focusNodeCount ?? 0,
            mweNodeCount: $stats->mweNodeCount ?? 0,
            isValid: $parseGraph->status === 'complete',
            errorMessage: $parseGraph->errorMessage ?? null,
        );
    }

    /**
     * Get parse result by ID
     */
    public function getParseResult(int $idParserGraph): ParseOutputData
    {
        return $this->buildOutput($idParserGraph);
    }
}
