<?php

namespace App\Services\Parser;

use App\Repositories\Parser\MWE;
use App\Repositories\Parser\ParseEdge;
use App\Repositories\Parser\ParseNode;

class MWEService
{
    /**
     * Generate prefix hierarchy for an MWE
     */
    public function generatePrefixHierarchy(object $mwe): array
    {
        $components = MWE::getComponents($mwe);
        $prefixes = [];

        // Generate all prefixes (1-word, 2-word, ..., n-word)
        for ($i = 1; $i <= count($components); $i++) {
            $prefixComponents = array_slice($components, 0, $i);
            $prefixPhrase = implode(' ', $prefixComponents);

            $prefixes[] = (object) [
                'phrase' => $prefixPhrase,
                'components' => $prefixComponents,
                'threshold' => $i,
                'isComplete' => ($i === count($components)),
            ];
        }

        return $prefixes;
    }

    /**
     * Instantiate MWE prefix nodes when first word appears
     */
    public function instantiateMWENodes(
        string $firstWord,
        int $idParserGraph,
        int $idGrammarGraph,
        int $position
    ): array {
        $instantiatedNodes = [];
        $mwes = MWE::getStartingWith($idGrammarGraph, $firstWord);

        foreach ($mwes as $mwe) {
            $components = MWE::getComponents($mwe);

            // Generate all prefix nodes
            for ($i = 2; $i <= count($components); $i++) {
                $prefixComponents = array_slice($components, 0, $i);
                $prefixPhrase = implode(' ', $prefixComponents);

                // Create prefix node with threshold = i, activation = 1
                $idNode = ParseNode::create([
                    'idParserGraph' => $idParserGraph,
                    'label' => $prefixPhrase,
                    'type' => 'MWE',
                    'threshold' => $i,
                    'activation' => 1,
                    'isFocus' => false,
                    'positionInSentence' => $position,
                    'idMWE' => $mwe->idMWE,
                ]);

                $instantiatedNodes[] = ParseNode::byId($idNode);

                if (config('parser.logging.logMWE', false)) {
                    logger()->info('MWE: Instantiated prefix node', [
                        'phrase' => $prefixPhrase,
                        'threshold' => $i,
                        'idMWE' => $mwe->idMWE,
                    ]);
                }
            }
        }

        return $instantiatedNodes;
    }

    /**
     * Increment activation for MWE node
     */
    public function incrementActivation(object $mweNode, string $word): void
    {
        ParseNode::incrementActivation($mweNode->idParserNode);

        if (config('parser.logging.logMWE', false)) {
            logger()->info('MWE: Incremented activation', [
                'phrase' => $mweNode->label,
                'activation' => $mweNode->activation + 1,
                'threshold' => $mweNode->threshold,
                'word' => $word,
            ]);
        }
    }

    /**
     * Check if word matches expected next component
     */
    public function matchesNextComponent(object $mweNode, string $word): bool
    {
        if (! $mweNode->idMWE) {
            return false;
        }

        $mwe = MWE::byId($mweNode->idMWE);
        $components = MWE::getComponents($mwe);

        // Current activation indicates how many components we've seen
        $nextIndex = $mweNode->activation;

        if ($nextIndex >= count($components)) {
            return false;
        }

        return strtolower($components[$nextIndex]) === strtolower($word);
    }

    /**
     * Aggregate MWE when threshold is reached
     */
    public function aggregateMWE(object $mweNode, int $idParserGraph): void
    {
        // Mark as focus
        ParseNode::setFocus($mweNode->idParserNode, true);

        // Transfer all incoming links from first component to MWE node
        // Find the first word node at the same position
        $firstWordNodes = ParseNode::listByParseGraph($idParserGraph);

        foreach ($firstWordNodes as $node) {
            if ($node->positionInSentence === $mweNode->positionInSentence &&
                $node->type !== 'MWE' &&
                $node->idParserNode !== $mweNode->idParserNode) {

                // Transfer edges from first word to MWE
                $this->transferLinks($node, $mweNode, $idParserGraph);
                break;
            }
        }

        if (config('parser.logging.logMWE', false)) {
            logger()->info('MWE: Aggregated', [
                'phrase' => $mweNode->label,
                'activation' => $mweNode->activation,
                'threshold' => $mweNode->threshold,
            ]);
        }
    }

    /**
     * Transfer all links from one node to another
     */
    public function transferLinks(object $fromNode, object $toNode, int $idParserGraph): void
    {
        // Get all incoming edges to fromNode
        $incomingEdges = ParseEdge::listByTargetNode($fromNode->idParserNode);

        foreach ($incomingEdges as $edge) {
            // Create new edge to toNode if it doesn't exist
            ParseEdge::createIfNotExists([
                'idParserGraph' => $idParserGraph,
                'idSourceNode' => $edge->idSourceNode,
                'idTargetNode' => $toNode->idParserNode,
                'edgeType' => $edge->edgeType,
                'weight' => $edge->weight,
            ]);
        }

        // Get all outgoing edges from fromNode
        $outgoingEdges = ParseEdge::listBySourceNode($fromNode->idParserNode);

        foreach ($outgoingEdges as $edge) {
            // Create new edge from toNode if it doesn't exist
            ParseEdge::createIfNotExists([
                'idParserGraph' => $idParserGraph,
                'idSourceNode' => $toNode->idParserNode,
                'idTargetNode' => $edge->idTargetNode,
                'edgeType' => $edge->edgeType,
                'weight' => $edge->weight,
            ]);
        }

        if (config('parser.logging.logMWE', false)) {
            logger()->info('MWE: Transferred links', [
                'from' => $fromNode->label,
                'to' => $toNode->label,
                'incomingEdges' => count($incomingEdges),
                'outgoingEdges' => count($outgoingEdges),
            ]);
        }
    }

    /**
     * Get all active MWE prefixes for a parse graph
     */
    public function getActivePrefixes(int $idParserGraph): array
    {
        return ParseNode::getMWEPrefixes($idParserGraph);
    }

    /**
     * Check if MWE was interrupted
     */
    public function isInterrupted(object $mweNode, int $currentPosition): bool
    {
        // MWE is interrupted if current word position is not sequential
        $expectedPosition = $mweNode->positionInSentence + $mweNode->activation;

        return $currentPosition !== $expectedPosition;
    }

    /**
     * Handle competing MWEs with shared prefixes
     */
    public function resolveCompetition(array $mweNodes): ?object
    {
        if (empty($mweNodes)) {
            return null;
        }

        $strategy = config('parser.mwe.competitionStrategy', 'longest');

        switch ($strategy) {
            case 'longest':
                // Return MWE with highest threshold
                usort($mweNodes, function ($a, $b) {
                    return $b->threshold <=> $a->threshold;
                });

                return $mweNodes[0];

            case 'first':
                // Return first MWE
                return $mweNodes[0];

            case 'all':
                // Return all (for ambiguous parses)
                return $mweNodes;

            default:
                return $mweNodes[0];
        }
    }
}
