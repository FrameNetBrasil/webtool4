<?php

namespace App\Services\Lexicon;

use App\Services\Trankit\TrankitService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LexiconPatternService
{
    protected TrankitService $trankit;

    public function __construct(TrankitService $trankit)
    {
        $this->trankit = $trankit;
        $this->trankit->init(config('udparser.trankit_url'));
    }

    /**
     * Store a lemma (SWE or MWE) and its full dependency tree pattern
     *
     * @param  string  $lemmaText  The lemma text
     * @param  string  $lemmaType  'SWE' or 'MWE'
     * @param  int|null  $idUDPOS  UPOS id for SWE, null for MWE
     * @param  array  $parsedPattern  The full parsed pattern from UD parser
     * @param  string  $patternType  Type of pattern (e.g., 'canonical', 'variant')
     * @return int The idLexicon
     */
    public function storeLemmaPattern(string $lemmaText, string $lemmaType, ?int $idUDPOS, array $parsedPattern, string $patternType = 'canonical'): int
    {
        return DB::transaction(function () use ($lemmaText, $lemmaType, $idUDPOS, $parsedPattern, $patternType) {
            // Insert or get lexicon entry
            $idLexicon = DB::table('lexicon')->insertGetId([
                'form' => $lemmaText,
                'idUDPOS' => $idUDPOS,
                'lemma_type' => $lemmaType,
                'frequency' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // For SWE, we don't need to store pattern (just the lexicon entry with UPOS)
            if ($lemmaType === 'SWE') {
                Log::info('Stored SWE lemma', [
                    'idLexicon' => $idLexicon,
                    'form' => $lemmaText,
                    'idUDPOS' => $idUDPOS,
                ]);

                return $idLexicon;
            }

            // For MWE, store the full dependency tree pattern
            $idLexiconPattern = DB::table('lexicon_pattern')->insertGetId([
                'idLexicon' => $idLexicon,
                'patternType' => $patternType,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Store all nodes in the pattern
            $nodeMapping = []; // pattern position -> idLexiconPatternNode
            foreach ($parsedPattern['nodes'] as $node) {
                $idLexiconPatternNode = DB::table('lexicon_pattern_node')->insertGetId([
                    'idLexiconPattern' => $idLexiconPattern,
                    'position' => $node['position'],
                    'idLexicon' => $node['idLexicon'] ?? null,
                    'idUDPOS' => $node['idUDPOS'] ?? null,
                    'isRoot' => $node['is_root'],
                    'isRequired' => $node['is_required'] ?? true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $nodeMapping[$node['position']] = $idLexiconPatternNode;
            }

            // Store ALL edges (complete dependency tree)
            foreach ($parsedPattern['edges'] as $edge) {
                DB::table('lexicon_pattern_edge')->insert([
                    'idLexiconPattern' => $idLexiconPattern,
                    'idNodeHead' => $nodeMapping[$edge['head_position']],
                    'idNodeDependent' => $nodeMapping[$edge['dependent_position']],
                    'idUDRelation' => $edge['idUDRelation'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Store constraints if any
            if (isset($parsedPattern['constraints'])) {
                foreach ($parsedPattern['constraints'] as $constraint) {
                    DB::table('lexicon_pattern_constraint')->insert([
                        'idLexiconPattern' => $idLexiconPattern,
                        'constraintType' => $constraint['type'],
                        'constraintValue' => $constraint['value'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            Log::info('Stored MWE pattern with full dependency tree', [
                'idLexicon' => $idLexicon,
                'idLexiconPattern' => $idLexiconPattern,
                'form' => $lemmaText,
                'nodes' => count($parsedPattern['nodes']),
                'edges' => count($parsedPattern['edges']),
            ]);

            return $idLexicon;
        });
    }

    /**
     * Parse lemma using Trankit parser and extract FULL dependency tree structure
     *
     * @param  string  $lemmaText  The lemma text to parse
     * @param  string  $lemmaType  'SWE' or 'MWE'
     * @param  int  $idLanguage  Language ID (1=Portuguese, 2=English)
     * @return array Pattern structure with complete tree
     */
    public function extractPatternFromLemma(string $lemmaText, string $lemmaType, int $idLanguage = 1): array
    {
        // Parse using Trankit
        $trankitOutput = $this->trankit->parseSentenceRawTokens($lemmaText, $idLanguage);

        // Convert Trankit format to internal format
        $tokens = $this->parseTrankitOutput($trankitOutput);

        // Extract FULL pattern structure (all nodes and edges)
        return $this->buildFullTreePattern($tokens, $lemmaType);
    }

    /**
     * Find all lemma occurrences (both SWE and MWE) in a parsed sentence
     *
     * @param  array  $sentenceTokens  Parsed sentence tokens
     * @param  string|null  $sentenceId  Optional sentence identifier
     * @param  string|null  $filterType  Filter by lemma type: 'SWE', 'MWE', or null for both
     * @return array Array of detected lemma occurrences
     */
    public function findLemmaOccurrences(array $sentenceTokens, ?string $sentenceId = null, ?string $filterType = null): array
    {
        $occurrences = [];

        // Get all patterns from database
        $patterns = $this->loadAllPatterns($filterType);

        foreach ($patterns as $pattern) {
            if ($pattern['lemma_type'] === 'SWE') {
                // Simple SWE matching
                $matches = $this->findSweMatches($sentenceTokens, $pattern);
                $occurrences = array_merge($occurrences, $matches);
            } else {
                // Complex MWE tree matching
                $matches = $this->findMweMatches($sentenceTokens, $pattern);
                $occurrences = array_merge($occurrences, $matches);
            }
        }

        return $occurrences;
    }

    /**
     * Find SWE matches in sentence
     *
     * @param  array  $sentenceTokens  Sentence tokens
     * @param  array  $pattern  Pattern data
     * @return array Matches
     */
    protected function findSweMatches(array $sentenceTokens, array $pattern): array
    {
        $matches = [];

        foreach ($sentenceTokens as $token) {
            $lemmaMatch = $this->getLexiconIdByForm($token['lemma']) == $pattern['idLexicon'];
            $uposMatch = $pattern['idUDPOS'] === null || $token['idUDPOS'] == $pattern['idUDPOS'];

            if ($lemmaMatch && $uposMatch) {
                $matches[] = [
                    'sentence_id' => $pattern['sentence_id'] ?? uniqid('sent_'),
                    'idLexicon' => $pattern['idLexicon'],
                    'idPattern' => null,
                    'lemma_text' => $pattern['form'],
                    'lemma_type' => 'SWE',
                    'token_indices' => [$token['id']],
                    'matched_nodes' => null,
                    'confidence' => 1.0,
                ];
            }
        }

        return $matches;
    }

    /**
     * Find MWE matches by matching the full dependency tree structure
     *
     * @param  array  $sentenceTokens  Sentence tokens
     * @param  array  $pattern  Pattern with nodes and edges
     * @return array Matches
     */
    protected function findMweMatches(array $sentenceTokens, array $pattern): array
    {
        $matches = [];

        // Find root node candidates
        $rootNode = $this->getRootNode($pattern['nodes']);
        if (! $rootNode) {
            return $matches;
        }

        $rootCandidates = $this->findNodeCandidates($sentenceTokens, $rootNode);

        foreach ($rootCandidates as $rootToken) {
            $match = $this->tryMatchFullTree($rootToken, $pattern, $sentenceTokens);

            if ($match !== null) {
                $matches[] = [
                    'sentence_id' => $pattern['sentence_id'] ?? uniqid('sent_'),
                    'idLexicon' => $pattern['idLexicon'],
                    'idLexiconPattern' => $pattern['idLexiconPattern'],
                    'lemma_text' => $pattern['form'],
                    'lemma_type' => 'MWE',
                    'token_indices' => $match['token_indices'],
                    'matched_nodes' => $match['node_mapping'],
                    'confidence' => $match['confidence'],
                ];
            }
        }

        return $matches;
    }

    /**
     * Try to match the complete dependency tree structure
     *
     * @param  array  $rootToken  Root candidate token
     * @param  array  $pattern  Full pattern with nodes and edges
     * @param  array  $sentenceTokens  All sentence tokens
     * @return array|null Match data or null
     */
    protected function tryMatchFullTree(array $rootToken, array $pattern, array $sentenceTokens): ?array
    {
        // Build sentence dependency graph for quick lookup
        $sentenceGraph = $this->buildDependencyGraph($sentenceTokens);

        // Try to map pattern nodes to sentence tokens
        $nodeMapping = []; // pattern position -> sentence token id
        $rootNode = $this->getRootNode($pattern['nodes']);
        $nodeMapping[$rootNode['position']] = $rootToken['id'];

        // Recursively match all nodes using BFS/DFS
        $toVisit = [$rootNode['position']];
        $visited = [];
        $requiredMatches = 0;
        $totalRequired = 0;

        while (! empty($toVisit)) {
            $currentPos = array_shift($toVisit);
            if (in_array($currentPos, $visited)) {
                continue;
            }
            $visited[] = $currentPos;

            $currentNode = $this->getNodeByPosition($pattern['nodes'], $currentPos);
            if ($currentNode['isRequired']) {
                $totalRequired++;
                if (isset($nodeMapping[$currentPos])) {
                    $requiredMatches++;
                }
            }

            // Find all edges where current node is the head
            $outgoingEdges = $this->getOutgoingEdges($pattern['edges'], $currentPos);

            foreach ($outgoingEdges as $edge) {
                $dependentNode = $this->getNodeByPosition($pattern['nodes'], $edge['dependent_position']);

                // Try to find matching token in sentence
                if (isset($nodeMapping[$currentPos])) {
                    $headTokenId = $nodeMapping[$currentPos];
                    $matchedDependent = $this->findMatchingDependent(
                        $headTokenId,
                        $dependentNode,
                        $edge['idUDRelation'],
                        $sentenceTokens,
                        $sentenceGraph
                    );

                    if ($matchedDependent !== null) {
                        $nodeMapping[$edge['dependent_position']] = $matchedDependent['id'];
                        $toVisit[] = $edge['dependent_position'];
                    } elseif ($dependentNode['isRequired']) {
                        // Required node not found
                        return null;
                    }
                }
            }
        }

        // Verify ALL edges match (complete tree structure)
        $allEdgesMatch = $this->verifyAllEdges($pattern['edges'], $nodeMapping, $sentenceGraph);
        if (! $allEdgesMatch) {
            return null;
        }

        $confidence = $totalRequired > 0 ? $requiredMatches / $totalRequired : 0;

        if ($confidence >= 0.8) {
            return [
                'token_indices' => array_values($nodeMapping),
                'node_mapping' => $nodeMapping,
                'confidence' => $confidence,
            ];
        }

        return null;
    }

    /**
     * Verify that ALL edges in the pattern are present in the sentence
     *
     * @param  array  $patternEdges  Pattern edges
     * @param  array  $nodeMapping  Pattern position -> sentence token id
     * @param  array  $sentenceGraph  Sentence dependency graph
     */
    protected function verifyAllEdges(array $patternEdges, array $nodeMapping, array $sentenceGraph): bool
    {
        foreach ($patternEdges as $edge) {
            $headPos = $edge['head_position'];
            $depPos = $edge['dependent_position'];

            // Check if both nodes are mapped
            if (! isset($nodeMapping[$headPos]) || ! isset($nodeMapping[$depPos])) {
                continue; // Skip if optional node
            }

            $headTokenId = $nodeMapping[$headPos];
            $depTokenId = $nodeMapping[$depPos];

            // Verify this edge exists in sentence with same relation
            $edgeExists = false;
            if (isset($sentenceGraph[$headTokenId])) {
                foreach ($sentenceGraph[$headTokenId] as $dep) {
                    if ($dep['id'] == $depTokenId && $dep['idUDRelation'] == $edge['idUDRelation']) {
                        $edgeExists = true;
                        break;
                    }
                }
            }

            if (! $edgeExists) {
                return false;
            }
        }

        return true;
    }

    /**
     * Build dependency graph from sentence tokens for quick lookup
     *
     * @param  array  $tokens  Sentence tokens
     * @return array Graph: token_id => [dependent tokens]
     */
    protected function buildDependencyGraph(array $tokens): array
    {
        $graph = [];

        foreach ($tokens as $token) {
            $headId = $token['head'];
            if ($headId > 0) {
                if (! isset($graph[$headId])) {
                    $graph[$headId] = [];
                }
                $graph[$headId][] = $token;
            }
        }

        return $graph;
    }

    /**
     * Find matching dependent token in sentence
     *
     * @param  int  $headTokenId  Head token id in sentence
     * @param  array  $patternNode  Pattern node to match
     * @param  int  $idUDRelation  Expected relation
     * @param  array  $sentenceTokens  All sentence tokens
     * @param  array  $sentenceGraph  Sentence dependency graph
     * @return array|null Matched token or null
     */
    protected function findMatchingDependent(int $headTokenId, array $patternNode, int $idUDRelation, array $sentenceTokens, array $sentenceGraph): ?array
    {
        if (! isset($sentenceGraph[$headTokenId])) {
            return null;
        }

        foreach ($sentenceGraph[$headTokenId] as $dependent) {
            // Check relation
            if ($dependent['idUDRelation'] != $idUDRelation) {
                continue;
            }

            // Check lemma if specified
            if ($patternNode['idLexicon'] !== null) {
                $depLexiconId = $this->getLexiconIdByForm($dependent['lemma']);
                if ($depLexiconId != $patternNode['idLexicon']) {
                    continue;
                }
            }

            // Check UPOS if specified
            if ($patternNode['idUDPOS'] !== null && $dependent['idUDPOS'] != $patternNode['idUDPOS']) {
                continue;
            }

            return $dependent;
        }

        return null;
    }

    /**
     * Get root node from pattern nodes
     *
     * @param  array  $nodes  Pattern nodes
     * @return array|null Root node
     */
    protected function getRootNode(array $nodes): ?array
    {
        foreach ($nodes as $node) {
            if ($node['isRoot']) {
                return $node;
            }
        }

        return null;
    }

    /**
     * Get node by position
     *
     * @param  array  $nodes  Pattern nodes
     * @param  int  $position  Position
     * @return array|null Node
     */
    protected function getNodeByPosition(array $nodes, int $position): ?array
    {
        foreach ($nodes as $node) {
            if ($node['position'] == $position) {
                return $node;
            }
        }

        return null;
    }

    /**
     * Get outgoing edges from a node
     *
     * @param  array  $edges  Pattern edges
     * @param  int  $headPosition  Head node position
     * @return array Outgoing edges
     */
    protected function getOutgoingEdges(array $edges, int $headPosition): array
    {
        return array_filter($edges, fn ($edge) => $edge['head_position'] == $headPosition);
    }

    /**
     * Find candidate tokens that match a pattern node
     *
     * @param  array  $sentenceTokens  Sentence tokens
     * @param  array  $patternNode  Pattern node
     * @return array Candidate tokens
     */
    protected function findNodeCandidates(array $sentenceTokens, array $patternNode): array
    {
        $candidates = [];

        foreach ($sentenceTokens as $token) {
            $lemmaMatch = true;
            $uposMatch = true;

            if ($patternNode['idLexicon'] !== null) {
                $tokenLexiconId = $this->getLexiconIdByForm($token['lemma']);
                $lemmaMatch = $tokenLexiconId == $patternNode['idLexicon'];
            }

            if ($patternNode['idUDPOS'] !== null) {
                $uposMatch = $token['idUDPOS'] == $patternNode['idUDPOS'];
            }

            if ($lemmaMatch && $uposMatch) {
                $candidates[] = $token;
            }
        }

        return $candidates;
    }

    /**
     * Store detected lemma occurrences in the database
     *
     * @param  array  $occurrences  Array of occurrences to store
     * @return int Number of stored occurrences
     */
    public function storeOccurrences(array $occurrences): int
    {
        $stored = 0;

        foreach ($occurrences as $occurrence) {
            DB::table('lexicon_occurrences')->insert([
                'sentence_id' => $occurrence['sentence_id'],
                'idLexicon' => $occurrence['idLexicon'],
                'idLexiconPattern' => $occurrence['idLexiconPattern'],
                'token_indices' => json_encode($occurrence['token_indices']),
                'matched_nodes' => json_encode($occurrence['matched_nodes']),
                'confidence' => $occurrence['confidence'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update lexicon frequency
            DB::table('lexicon')
                ->where('idLexicon', $occurrence['idLexicon'])
                ->increment('frequency');

            $stored++;
        }

        return $stored;
    }

    /**
     * Build FULL tree pattern from parsed tokens
     * Stores ALL nodes and ALL edges (complete dependency structure)
     *
     * @param  array  $tokens  Parsed tokens
     * @param  string  $lemmaType  'SWE' or 'MWE'
     * @return array Pattern structure with complete tree
     */
    protected function buildFullTreePattern(array $tokens, string $lemmaType): array
    {
        $pattern = [
            'nodes' => [],
            'edges' => [],
            'constraints' => [],
        ];

        // For SWE, just return basic info (no pattern needed)
        if ($lemmaType === 'SWE') {
            return $pattern;
        }

        // For MWE, extract complete tree structure
        $rootToken = $this->findRootToken($tokens);
        if (! $rootToken) {
            throw new \RuntimeException('No root token found in parsed MWE');
        }

        // Create nodes for all tokens
        foreach ($tokens as $index => $token) {
            $isRoot = ($token['id'] == $rootToken['id']);

            $pattern['nodes'][] = [
                'position' => $index,
                'idLexicon' => $this->getLexiconIdByForm($token['lemma']),
                'idUDPOS' => $this->getUDPOSIdByName($token['upos']),
                'is_root' => $isRoot,
                'is_required' => $this->isCoreElement($token),
            ];
        }

        // Create edges for ALL dependencies (complete tree)
        foreach ($tokens as $token) {
            if ($token['head'] == 0) {
                continue; // Skip root's head (which is 0)
            }

            $headPosition = $this->findTokenPosition($tokens, $token['head']);
            $depPosition = $this->findTokenPosition($tokens, $token['id']);

            if ($headPosition !== null && $depPosition !== null) {
                $pattern['edges'][] = [
                    'head_position' => $headPosition,
                    'dependent_position' => $depPosition,
                    'idUDRelation' => $this->getUDRelationIdByName($token['deprel']),
                ];
            }
        }

        // Add constraints for fixed expressions
        if ($this->isFixedExpression($tokens)) {
            $pattern['constraints'][] = [
                'type' => 'word_order',
                'value' => 'strict',
            ];
        }

        return $pattern;
    }

    /**
     * Find root token in parsed tokens
     *
     * @param  array  $tokens  Parsed tokens
     * @return array|null Root token
     */
    protected function findRootToken(array $tokens): ?array
    {
        foreach ($tokens as $token) {
            if ($token['deprel'] === 'root' || $token['head'] === 0) {
                return $token;
            }
        }

        // Fallback: first token
        return $tokens[0] ?? null;
    }

    /**
     * Find position of token by its id
     *
     * @param  array  $tokens  All tokens
     * @param  int  $tokenId  Token id
     * @return int|null Position (index)
     */
    protected function findTokenPosition(array $tokens, int $tokenId): ?int
    {
        foreach ($tokens as $index => $token) {
            if ($token['id'] == $tokenId) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Get lexicon id by form (lemma text)
     *
     * @param  string  $form  Lemma text
     */
    protected function getLexiconIdByForm(string $form): ?int
    {
        $result = DB::table('lexicon')
            ->where('form', $form)
            ->value('idLexicon');

        return $result;
    }

    /**
     * Get UDPOS id by POS name
     *
     * @param  string  $pos  POS name
     */
    protected function getUDPOSIdByName(string $pos): ?int
    {
        $result = DB::table('udpos')
            ->where('POS', $pos)
            ->value('idUDPOS');

        return $result;
    }

    /**
     * Get UDRelation id by relation name
     *
     * @param  string  $relation  Relation name
     */
    protected function getUDRelationIdByName(string $relation): ?int
    {
        $result = DB::table('udrelation')
            ->where('info', $relation)
            ->value('idUDRelation');

        return $result;
    }

    /**
     * Determine if a token is a core element
     *
     * @param  array  $token  Token data
     */
    protected function isCoreElement(array $token): bool
    {
        // Core dependency relations (not modifiers or adjuncts)
        $coreRels = ['nsubj', 'obj', 'iobj', 'csubj', 'ccomp', 'xcomp', 'obl', 'aux', 'cop', 'case', 'mark', 'fixed', 'flat', 'compound'];

        return in_array($token['deprel'], $coreRels);
    }

    /**
     * Determine if the expression is fixed (no variation allowed)
     *
     * @param  array  $tokens  Parsed tokens
     */
    protected function isFixedExpression(array $tokens): bool
    {
        // Check if any token has 'fixed' relation
        foreach ($tokens as $token) {
            if ($token['deprel'] === 'fixed') {
                return true;
            }
        }

        return false;
    }

    /**
     * Load all patterns from database with full tree structure
     *
     * @param  string|null  $filterType  Filter by lemma type
     * @return array Array of patterns with nodes and edges
     */
    protected function loadAllPatterns(?string $filterType = null): array
    {
        $query = DB::table('lexicon')
            ->select('lexicon.*');

        if ($filterType !== null) {
            $query->where('lexicon.lemma_type', $filterType);
        }

        $lexicons = $query->get();

        $result = [];
        foreach ($lexicons as $lexicon) {
            $lexiconArray = (array) $lexicon;

            // For SWE, no pattern needed
            if ($lexicon->lemma_type === 'SWE') {
                $result[] = $lexiconArray;

                continue;
            }

            // For MWE, load all patterns
            $patterns = DB::table('lexicon_pattern')
                ->where('idLexicon', $lexicon->idLexicon)
                ->get();

            foreach ($patterns as $pattern) {
                $patternArray = array_merge($lexiconArray, (array) $pattern);

                // Load all nodes
                $nodes = DB::table('lexicon_pattern_node')
                    ->where('idLexiconPattern', $pattern->idLexiconPattern)
                    ->orderBy('position')
                    ->get()
                    ->map(fn ($node) => (array) $node)
                    ->toArray();

                $patternArray['nodes'] = $nodes;

                // Load all edges (complete tree structure)
                $edges = DB::table('lexicon_pattern_edge')
                    ->join('lexicon_pattern_node as head', 'lexicon_pattern_edge.idNodeHead', '=', 'head.idLexiconPatternNode')
                    ->join('lexicon_pattern_node as dep', 'lexicon_pattern_edge.idNodeDependent', '=', 'dep.idLexiconPatternNode')
                    ->where('lexicon_pattern_edge.idLexiconPattern', $pattern->idLexiconPattern)
                    ->select(
                        'lexicon_pattern_edge.*',
                        'head.position as head_position',
                        'dep.position as dependent_position'
                    )
                    ->get()
                    ->map(fn ($edge) => (array) $edge)
                    ->toArray();

                $patternArray['edges'] = $edges;

                // Load constraints
                $constraints = DB::table('lexicon_pattern_constraint')
                    ->where('idLexiconPattern', $pattern->idLexiconPattern)
                    ->get()
                    ->map(fn ($con) => (array) $con)
                    ->toArray();

                $patternArray['constraints'] = $constraints;

                $result[] = $patternArray;
            }
        }

        return $result;
    }

    /**
     * Parse Trankit output into structured tokens with relation IDs
     *
     * @param  array  $trankitOutput  Trankit output array
     * @return array Array of token arrays with idUDPOS and idUDRelation
     */
    public function parseTrankitOutput(array $trankitOutput): array
    {
        $tokens = [];

        foreach ($trankitOutput as $node) {
            $upos = $node['pos'];
            $deprel = $node['rel'];

            $tokens[] = [
                'id' => (int) $node['id'],
                'form' => $node['word'],
                'lemma' => $node['lemma'] ?? $node['word'],
                'upos' => $upos,
                'idUDPOS' => $this->getUDPOSIdByName($upos),
                'xpos' => '_',
                'feats' => $node['morph'] ?? [],
                'head' => (int) $node['parent'],
                'deprel' => $deprel,
                'idUDRelation' => $this->getUDRelationIdByName($deprel),
                'deps' => '_',
                'misc' => '_',
            ];
        }

        return $tokens;
    }
}
