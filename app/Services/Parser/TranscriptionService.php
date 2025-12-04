<?php

namespace App\Services\Parser;

use App\Repositories\Parser\ParseNode;

/**
 * Transcription Stage: Lexical Assembly
 *
 * Transforms UD tokens into stable lexical units with morphological features.
 * This is Stage 1 of the three-stage parsing framework (Transcription → Translation → Folding).
 *
 * Biological Analogy: DNA → mRNA (Transcription)
 * - Resolves word types (E/R/A/F)
 * - Assembles multi-word expressions (MWEs) via prefix activation
 * - Extracts and stores morphological features from UD
 * - Quality control: garbage collects incomplete units
 */
class TranscriptionService
{
    private GrammarGraphService $grammarService;

    private MWEService $mweService;

    private LemmaResolverService $lemmaResolver;

    public function __construct(
        GrammarGraphService $grammarService,
        MWEService $mweService,
        LemmaResolverService $lemmaResolver
    ) {
        $this->grammarService = $grammarService;
        $this->mweService = $mweService;
        $this->lemmaResolver = $lemmaResolver;
    }

    /**
     * Transcribe UD tokens into lexical units with features
     *
     * @param  array  $tokens  UD tokens from TrankitService
     * @param  int  $idParserGraph  Parse graph ID
     * @param  int  $idGrammarGraph  Grammar graph ID
     * @param  int  $idLanguage  Language ID
     * @return array Array of created node IDs
     */
    public function transcribe(
        array $tokens,
        int $idParserGraph,
        int $idGrammarGraph,
        int $idLanguage
    ): array {
        $createdNodes = [];

        if (config('parser.logging.logStages', false)) {
            logger()->info('Transcription Stage: Starting', [
                'idParserGraph' => $idParserGraph,
                'tokenCount' => count($tokens),
            ]);
        }

        // Process each token
        foreach ($tokens as $token) {
            $nodeId = $this->processToken(
                token: $token,
                idParserGraph: $idParserGraph,
                idGrammarGraph: $idGrammarGraph,
                idLanguage: $idLanguage
            );

            if ($nodeId) {
                $createdNodes[] = $nodeId;
            }

            // Check MWE prefixes for activation
            $this->checkMWEPrefixes(
                word: $token['word'],
                idParserGraph: $idParserGraph,
                position: $token['id']
            );
        }

        // Quality control: garbage collect incomplete MWEs
        if (config('parser.garbageCollection.enabled', true)) {
            $this->garbageCollectIncompleteMWEs($idParserGraph);
        }

        if (config('parser.logging.logStages', false)) {
            logger()->info('Transcription Stage: Complete', [
                'createdNodes' => count($createdNodes),
            ]);
        }

        return $createdNodes;
    }

    /**
     * Process a single UD token
     */
    private function processToken(
        array $token,
        int $idParserGraph,
        int $idGrammarGraph,
        int $idLanguage
    ): ?int {
        // Extract data from UD token
        $word = $token['word'];
        $lemma = $token['lemma'] ?? $word;
        $pos = $token['pos'] ?? 'X';
        $morphFeatures = $token['morph'] ?? [];
        $position = $token['id'];

        // Build feature bundle
        $features = $this->buildFeatureBundle($morphFeatures);

        if (config('parser.logging.logFeatures', false)) {
            logger()->info('Transcription: Extracted features', [
                'word' => $word,
                'features' => $features,
            ]);
        }

        // Classify word type (E/R/A/F)
        $wordType = $this->grammarService->getWordType($word, $pos, $idGrammarGraph);

        // Resolve lemma ID
        $idLemma = $this->lemmaResolver->getOrCreateLemma($lemma, $idLanguage, $pos);

        // Determine label (lemma for E/R/A, word for F)
        $label = ($wordType === 'F') ? $word : $lemma;

        // Create word node with features and stage
        $nodeData = [
            'idParserGraph' => $idParserGraph,
            'label' => $label,
            'idLemma' => $idLemma,
            'pos' => $pos,
            'type' => $wordType,
            'threshold' => 1,
            'activation' => 1,
            'isFocus' => true,
            'positionInSentence' => $position,
            'features' => json_encode($features),
            'stage' => 'transcription',
        ];

        $idWordNode = ParseNode::create($nodeData);

        // If word starts any MWE, instantiate prefix nodes
        $this->mweService->instantiateMWENodes(
            firstWord: $word,
            idParserGraph: $idParserGraph,
            idGrammarGraph: $idGrammarGraph,
            position: $position
        );

        return $idWordNode;
    }

    /**
     * Build feature bundle from UD morphological features
     *
     * Creates structure: {lexical: {...}, derived: {...}}
     */
    private function buildFeatureBundle(array $morphFeatures): array
    {
        $features = [
            'lexical' => [],
            'derived' => [],
        ];

        // Extract only the features we're tracking
        $extractedFeatures = config('parser.features.extractedFeatures', []);

        foreach ($morphFeatures as $featureName => $featureValue) {
            if (in_array($featureName, $extractedFeatures)) {
                $features['lexical'][$featureName] = $featureValue;
            }
        }

        return $features;
    }

    /**
     * Check MWE prefixes for activation
     *
     * Reuses existing MWEService logic
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

                    // If threshold reached, aggregate MWE
                    if (ParseNode::hasReachedThreshold($updatedPrefix)) {
                        $this->mweService->aggregateMWE($updatedPrefix, $idParserGraph);

                        if (config('parser.logging.logStages', false)) {
                            logger()->info('Transcription: MWE completed', [
                                'label' => $updatedPrefix->label,
                                'threshold' => $updatedPrefix->threshold,
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Garbage collect incomplete MWE prefix nodes
     *
     * Only removes MWE nodes that didn't reach threshold
     * Regular word nodes are kept
     */
    private function garbageCollectIncompleteMWEs(int $idParserGraph): void
    {
        $incompleteMWEs = ParseNode::listBy([
            'idParserGraph' => $idParserGraph,
            'type' => 'MWE',
            'stage' => 'transcription',
        ]);

        $removedCount = 0;

        foreach ($incompleteMWEs as $node) {
            // Only remove if activation < threshold (incomplete)
            if ($node->activation < $node->threshold) {
                // Don't keep incomplete MWEs unless debugging
                if (! config('parser.garbageCollection.keepIncompleteMWE', false)) {
                    ParseNode::delete($node->idParserNode);
                    $removedCount++;
                }
            }
        }

        if ($removedCount > 0 && config('parser.logging.logStages', false)) {
            logger()->info('Transcription: Garbage collection', [
                'removedIncompleteMWEs' => $removedCount,
            ]);
        }
    }

    /**
     * Get feature bundle from node
     *
     * Helper method to decode features JSON
     */
    public function getNodeFeatures(object $node): array
    {
        if (empty($node->features)) {
            return ['lexical' => [], 'derived' => []];
        }

        return json_decode($node->features, true) ?? ['lexical' => [], 'derived' => []];
    }

    /**
     * Extract lexical features only
     */
    public function getLexicalFeatures(object $node): array
    {
        $features = $this->getNodeFeatures($node);

        return $features['lexical'] ?? [];
    }

    /**
     * Extract derived features only
     */
    public function getDerivedFeatures(object $node): array
    {
        $features = $this->getNodeFeatures($node);

        return $features['derived'] ?? [];
    }
}
