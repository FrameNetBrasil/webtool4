# Implementation Guide: Three-Stage CE Classification System
## Practical PHP Code Examples for Framework v2.0

---

## Overview

This guide provides concrete PHP implementation patterns for the revised transdisciplinary parsing framework that uses Croft's Construction Element (CE) labels at three levels.

**Key principle:** Each stage has clear inputs, operations, and outputs that correspond to biological assembly processes.

---

## Stage 1: Transcription (Building Phrasal CEs)

### Goal: Transform words → Phrasal CEs with features

**Biological parallel:** Building amino acids with chemical properties

### Core Service Structure

```php
<?php

namespace App\Services\Parser;

class TranscriptionService
{
    private UDParserService $udParser;
    private MWEService $mweService;
    private FeatureExtractor $featureExtractor;
    
    public function __construct(
        UDParserService $udParser,
        MWEService $mweService,
        FeatureExtractor $featureExtractor
    ) {
        $this->udParser = $udParser;
        $this->mweService = $mweService;
        $this->featureExtractor = $featureExtractor;
    }
    
    /**
     * Stage 1: Build phrasal CEs (amino acids)
     * 
     * @param string $sentence Raw sentence
     * @param Grammar $grammar MWE definitions
     * @return array<PhrasalCE> Array of phrasal CEs with features
     */
    public function buildPhrasalCEs(string $sentence, Grammar $grammar): array
    {
        // Step 1: Parse with Universal Dependencies
        $udTokens = $this->udParser->parse($sentence);
        
        // Step 2: Extract features and classify phrasal CEs
        $nodes = [];
        foreach ($udTokens as $index => $token) {
            $node = $this->createPhrasalCENode($token, $index);
            $nodes[] = $node;
        }
        
        // Step 3: Detect and assemble MWEs
        $nodes = $this->mweService->detectAndAssemble($nodes, $grammar);
        
        // Step 4: Garbage collect sub-threshold nodes
        $nodes = $this->garbageCollect($nodes);
        
        // Step 5: Validate and finalize
        $this->validatePhrasalCEs($nodes);
        
        return $nodes;
    }
    
    private function createPhrasalCENode($token, int $index): PhrasalCENode
    {
        // Extract all UD features
        $features = $this->featureExtractor->extractAll($token);
        
        // Classify phrasal CE type
        $ceType = $this->classifyPhrasalCE($token, $features);
        
        // Create node
        return new PhrasalCENode([
            'word' => $token->form,
            'lemma' => $token->lemma,
            'pos' => $token->pos,
            'phrasal_ce' => $ceType,
            'features' => $features,
            'index' => $index,
            'activation' => 1, // Single word = full activation
            'threshold' => 1,
        ]);
    }
    
    private function classifyPhrasalCE($token, array $features): string
    {
        /**
         * Classify phrasal CE type based on POS and features
         * 
         * Phrasal CE types (Croft):
         * - Head: Core of phrase (noun, verb, adj, adv)
         * - Mod: Modifier (det, adj modifying noun)
         * - Adm: Adposition marker (prep, postposition)
         * - Adp: Adposition phrase (object of preposition)
         * - Lnk: Linker (conjunction, complementizer)
         * - Clf: Classifier
         * - Idx: Index (demonstrative, pronoun)
         * - Conj: Conjunction
         */
        
        $pos = $token->pos;
        
        // Nouns and verbs are typically Heads
        if (in_array($pos, ['NOUN', 'PROPN', 'PRON'])) {
            return PhrasalCE::HEAD;
        }
        
        if ($pos === 'VERB') {
            return PhrasalCE::HEAD;
        }
        
        // Adjectives and adverbs can be Heads or Mods
        if ($pos === 'ADJ') {
            // If predicative: Head, if attributive: Mod
            return isset($features['case']) 
                ? PhrasalCE::HEAD  // Predicative
                : PhrasalCE::MOD;  // Attributive
        }
        
        if ($pos === 'ADV') {
            return PhrasalCE::HEAD;
        }
        
        // Determiners are Modifiers
        if ($pos === 'DET') {
            return PhrasalCE::MOD;
        }
        
        // Numerals are Modifiers
        if ($pos === 'NUM') {
            return PhrasalCE::MOD;
        }
        
        // Adpositions
        if (in_array($pos, ['ADP', 'PREP'])) {
            return PhrasalCE::ADM;
        }
        
        // Conjunctions
        if (in_array($pos, ['CCONJ', 'SCONJ'])) {
            return PhrasalCE::LNK;
        }
        
        // Default to Head
        return PhrasalCE::HEAD;
    }
    
    private function garbageCollect(array $nodes): array
    {
        // Remove nodes that didn't reach their activation threshold
        return array_filter($nodes, function($node) {
            return $node->activation >= $node->threshold;
        });
    }
    
    private function validatePhrasalCEs(array $nodes): void
    {
        foreach ($nodes as $node) {
            if (empty($node->phrasal_ce)) {
                throw new ParsingException("Node {$node->word} has no phrasal CE type");
            }
            if (empty($node->features)) {
                throw new ParsingException("Node {$node->word} has no features");
            }
        }
    }
}
```

### Data Structures

```php
<?php

namespace App\Models\Parser;

class PhrasalCE
{
    // Phrasal CE types (Croft's classification)
    const HEAD = 'Head';  // Core of phrase
    const MOD = 'Mod';    // Modifier
    const ADM = 'Adm';    // Adposition marker
    const ADP = 'Adp';    // Adposition phrase
    const LNK = 'Lnk';    // Linker
    const CLF = 'Clf';    // Classifier
    const IDX = 'Idx';    // Index
    const CONJ = 'Conj';  // Conjunction
}

class PhrasalCENode
{
    public string $word;
    public string $lemma;
    public string $pos;
    public string $phrasal_ce;  // Head, Mod, Adm, etc.
    public array $features;      // All UD features
    public int $index;
    public float $activation;
    public float $threshold;
    public bool $is_mwe;
    public ?array $mwe_components;
    
    public function __construct(array $data)
    {
        $this->word = $data['word'];
        $this->lemma = $data['lemma'];
        $this->pos = $data['pos'];
        $this->phrasal_ce = $data['phrasal_ce'];
        $this->features = $data['features'];
        $this->index = $data['index'];
        $this->activation = $data['activation'] ?? 1.0;
        $this->threshold = $data['threshold'] ?? 1.0;
        $this->is_mwe = $data['is_mwe'] ?? false;
        $this->mwe_components = $data['mwe_components'] ?? null;
    }
    
    public function getFeature(string $name): mixed
    {
        return $this->features[$name] ?? null;
    }
    
    public function hasFeature(string $name): bool
    {
        return isset($this->features[$name]);
    }
}
```

### Example: Stage 1 Output

```php
// Input: "las tres hermanas grandes"

// Output: Array of PhrasalCENodes
[
    PhrasalCENode {
        word: "las",
        pos: "DET",
        phrasal_ce: "Mod",
        features: [
            'Gender' => 'Fem',
            'Number' => 'Plur',
            'Definite' => 'Def',
            'PronType' => 'Art'
        ]
    },
    PhrasalCENode {
        word: "tres",
        pos: "NUM",
        phrasal_ce: "Mod",
        features: [
            'Number' => 'Plur',
            'NumType' => 'Card'
        ]
    },
    PhrasalCENode {
        word: "hermanas",
        pos: "NOUN",
        phrasal_ce: "Head",
        features: [
            'Gender' => 'Fem',
            'Number' => 'Plur'
        ]
    },
    PhrasalCENode {
        word: "grandes",
        pos: "ADJ",
        phrasal_ce: "Mod",
        features: [
            'Number' => 'Plur'
        ]
    }
]
```

---

## Stage 2: Translation (Building Clausal CEs)

### Goal: Transform Phrasal CEs → Clausal CEs with dependencies

**Biological parallel:** Forming peptide chains through bonds

### Core Service Structure

```php
<?php

namespace App\Services\Parser;

class TranslationService
{
    private FeatureCompatibilityService $compatibility;
    private DependencyBuilder $depBuilder;
    
    public function __construct(
        FeatureCompatibilityService $compatibility,
        DependencyBuilder $depBuilder
    ) {
        $this->compatibility = $compatibility;
        $this->depBuilder = $depBuilder;
    }
    
    /**
     * Stage 2: Build clausal CEs (peptides)
     * 
     * @param array<PhrasalCENode> $phrasalCEs
     * @return array<ClausalCE> Array of clausal CEs with dependencies
     */
    public function buildClausals(array $phrasalCEs): array
    {
        // Step 1: Transform phrasal CEs → clausal CE candidates
        $clausalCandidates = $this->transformToClausalCEs($phrasalCEs);
        
        // Step 2: Establish dependencies via feature compatibility
        $dependencies = $this->buildDependencies($clausalCandidates);
        
        // Step 3: Group into phrasal units
        $clausalCEs = $this->groupIntoPhrases($clausalCandidates, $dependencies);
        
        // Step 4: Validate
        $this->validateClausalCEs($clausalCEs);
        
        return $clausalCEs;
    }
    
    private function transformToClausalCEs(array $phrasalCEs): array
    {
        $clausal = [];
        
        foreach ($phrasalCEs as $phrasal) {
            $clausalType = $this->assignClausalCE($phrasal);
            
            $clausal[] = new ClausalCENode([
                'phrasal_node' => $phrasal,
                'clausal_ce' => $clausalType,
                'features' => $phrasal->features, // Inherit features
            ]);
        }
        
        return $clausal;
    }
    
    private function assignClausalCE(PhrasalCENode $phrasal): string
    {
        /**
         * Transform phrasal CE → clausal CE type
         * 
         * Clausal CE types (Croft):
         * - Pred: Predicate (verb phrase)
         * - Arg: Argument (noun phrase)
         * - Rel: Relative clause
         * - FPM: Flagged phrase modifier (adverb, PP)
         * - ICE: Intra-clausal element
         * - Cue: Discourse cue
         * - Voc: Vocative
         */
        
        // Finite verb Head → Pred
        if ($phrasal->phrasal_ce === PhrasalCE::HEAD && 
            $phrasal->pos === 'VERB' &&
            $phrasal->getFeature('VerbForm') === 'Fin') {
            return ClausalCE::PRED;
        }
        
        // Noun Head → Arg (will determine subj/obj later)
        if ($phrasal->phrasal_ce === PhrasalCE::HEAD &&
            in_array($phrasal->pos, ['NOUN', 'PROPN', 'PRON'])) {
            return ClausalCE::ARG;
        }
        
        // Adverb Head → FPM
        if ($phrasal->phrasal_ce === PhrasalCE::HEAD &&
            $phrasal->pos === 'ADV') {
            return ClausalCE::FPM;
        }
        
        // Adposition marker → part of FPM
        if ($phrasal->phrasal_ce === PhrasalCE::ADM) {
            return ClausalCE::FPM;
        }
        
        // Subordinating conjunction → introduces Rel or other subordinate
        if ($phrasal->phrasal_ce === PhrasalCE::LNK &&
            $phrasal->pos === 'SCONJ') {
            return ClausalCE::REL;
        }
        
        // Default: ICE (intra-clausal element)
        return ClausalCE::ICE;
    }
    
    private function buildDependencies(array $clausalNodes): array
    {
        $dependencies = [];
        
        // Find all Pred nodes (potential governors)
        $preds = array_filter($clausalNodes, function($n) {
            return $n->clausal_ce === ClausalCE::PRED;
        });
        
        foreach ($preds as $pred) {
            // Find arguments compatible with this predicate
            foreach ($clausalNodes as $candidate) {
                if ($candidate === $pred) continue;
                
                if ($candidate->clausal_ce === ClausalCE::ARG) {
                    // Check feature compatibility
                    $compat = $this->compatibility->calculate(
                        $pred->phrasal_node,
                        $candidate->phrasal_node,
                        'object'
                    );
                    
                    if ($compat > 0.5) { // Threshold
                        $dependencies[] = new Dependency(
                            $pred,
                            $candidate,
                            'OBJ',
                            $compat
                        );
                    }
                }
                
                if ($candidate->clausal_ce === ClausalCE::FPM) {
                    // Modifiers generally attach to predicates
                    $dependencies[] = new Dependency(
                        $pred,
                        $candidate,
                        'ADV',
                        0.8 // Default strength
                    );
                }
            }
        }
        
        return $dependencies;
    }
    
    private function groupIntoPhrases(
        array $clausalNodes, 
        array $dependencies
    ): array {
        // Group nodes that form coherent phrases
        $phrases = [];
        
        // Group by clausal CE type and connectivity
        foreach ($clausalNodes as $node) {
            $phrase = $this->findOrCreatePhrase($node, $dependencies, $phrases);
            $phrase->addNode($node);
        }
        
        return $phrases;
    }
}
```

### Data Structures

```php
<?php

namespace App\Models\Parser;

class ClausalCE
{
    // Clausal CE types (Croft's classification)
    const PRED = 'Pred';  // Predicate
    const ARG = 'Arg';    // Argument
    const REL = 'Rel';    // Relative clause
    const FPM = 'FPM';    // Flagged phrase modifier
    const ICE = 'ICE';    // Intra-clausal element
    const CUE = 'Cue';    // Discourse cue
    const VOC = 'Voc';    // Vocative
}

class ClausalCENode
{
    public PhrasalCENode $phrasal_node;  // Source phrasal CE
    public string $clausal_ce;            // Pred, Arg, Rel, FPM, etc.
    public array $features;               // Inherited + derived
    public array $dependencies;           // Links to other clausal CEs
    
    public function __construct(array $data)
    {
        $this->phrasal_node = $data['phrasal_node'];
        $this->clausal_ce = $data['clausal_ce'];
        $this->features = $data['features'];
        $this->dependencies = [];
    }
    
    public function addDependency(Dependency $dep): void
    {
        $this->dependencies[] = $dep;
    }
}

class Dependency
{
    public ClausalCENode $governor;
    public ClausalCENode $dependent;
    public string $relation;  // OBJ, SUBJ, ADV, etc.
    public float $strength;    // Compatibility score
    
    public function __construct(
        ClausalCENode $governor,
        ClausalCENode $dependent,
        string $relation,
        float $strength
    ) {
        $this->governor = $governor;
        $this->dependent = $dependent;
        $this->relation = $relation;
        $this->strength = $strength;
    }
}
```

### Feature Compatibility Service

```php
<?php

namespace App\Services\Parser;

class FeatureCompatibilityService
{
    /**
     * Calculate compatibility score between two nodes
     * Like calculating peptide bond formation energy
     * 
     * @return float Compatibility score (0.0 - 2.0+)
     */
    public function calculate(
        PhrasalCENode $node1,
        PhrasalCENode $node2,
        string $relationType
    ): float {
        $score = 1.0; // Baseline
        
        // Agreement features (hydrogen bonds)
        $score += $this->checkAgreement($node1, $node2);
        
        // Case features (ionic bonds)
        $score += $this->checkCase($node2, $relationType);
        
        // Definiteness (hydrophobic effect)
        $score += $this->checkDefiniteness($node1, $node2);
        
        return $score;
    }
    
    private function checkAgreement(
        PhrasalCENode $node1,
        PhrasalCENode $node2
    ): float {
        $bonus = 0.0;
        
        // Gender agreement (H-bond)
        if ($this->featuresMatch($node1, $node2, 'Gender')) {
            $bonus += 0.3;
        }
        
        // Number agreement (H-bond)
        if ($this->featuresMatch($node1, $node2, 'Number')) {
            $bonus += 0.3;
        }
        
        // Person agreement (H-bond)
        if ($this->featuresMatch($node1, $node2, 'Person')) {
            $bonus += 0.2;
        }
        
        return $bonus;
    }
    
    private function checkCase(
        PhrasalCENode $node,
        string $relationType
    ): float {
        $case = $node->getFeature('Case');
        
        // Strong ionic bonds for case-marked languages
        if ($relationType === 'subject' && $case === 'Nom') {
            return 0.5;
        }
        if ($relationType === 'object' && $case === 'Acc') {
            return 0.5;
        }
        if ($relationType === 'indirect_object' && $case === 'Dat') {
            return 0.5;
        }
        
        return 0.0;
    }
    
    private function checkDefiniteness(
        PhrasalCENode $node1,
        PhrasalCENode $node2
    ): float {
        // Definite entities prefer certain positions (like hydrophobic effect)
        $def1 = $node1->getFeature('Definite');
        $def2 = $node2->getFeature('Definite');
        
        if ($def1 === 'Def' || $def2 === 'Def') {
            return 0.1; // Small bonus for definiteness
        }
        
        return 0.0;
    }
    
    private function featuresMatch(
        PhrasalCENode $node1,
        PhrasalCENode $node2,
        string $feature
    ): bool {
        $val1 = $node1->getFeature($feature);
        $val2 = $node2->getFeature($feature);
        
        if ($val1 === null || $val2 === null) {
            return false;
        }
        
        return $val1 === $val2;
    }
}
```

### Example: Stage 2 Output

```php
// Input: Phrasal CEs for "tomei café da manhã"

// Output: Clausal CEs with dependencies
[
    ClausalCENode {
        phrasal_node: PhrasalCENode("tomei"),
        clausal_ce: "Pred",
        dependencies: [
            Dependency {
                governor: this,
                dependent: ClausalCENode("café_da_manhã"),
                relation: "OBJ",
                strength: 0.9
            }
        ]
    },
    ClausalCENode {
        phrasal_node: PhrasalCENode("café_da_manhã"),
        clausal_ce: "Arg",
        dependencies: []
    }
]
```

---

## Stage 3: Folding (Building Sentential Structure)

### Goal: Integrate Clausal CEs → Complete sentence structure

**Biological parallel:** Folding polypeptides into functional protein

### Core Service Structure

```php
<?php

namespace App\Services\Parser;

class FoldingService
{
    private ClauseIdentifier $clauseIdentifier;
    private LongDistanceLinker $longDistLinker;
    
    public function __construct(
        ClauseIdentifier $clauseIdentifier,
        LongDistanceLinker $longDistLinker
    ) {
        $this->clauseIdentifier = $clauseIdentifier;
        $this->longDistLinker = $longDistLinker;
    }
    
    /**
     * Stage 3: Fold sentence (integrate polypeptides)
     * 
     * @param array<ClausalCENode> $clausalCEs
     * @return ParseGraph Complete parse graph
     */
    public function foldSentence(array $clausalCEs): ParseGraph
    {
        // Step 1: Identify clauses
        $clauses = $this->clauseIdentifier->identify($clausalCEs);
        
        // Step 2: Assign sentential CE labels
        $sententialCEs = $this->assignSententialLabels($clauses);
        
        // Step 3: Establish long-distance dependencies
        $longDistDeps = $this->longDistLinker->build($sententialCEs);
        
        // Step 4: Create final parse graph
        $graph = $this->assembleFinalGraph($sententialCEs, $longDistDeps);
        
        // Step 5: Validate
        $this->validateGraph($graph);
        
        return $graph;
    }
    
    private function assignSententialLabels(array $clauses): array
    {
        $sentential = [];
        
        foreach ($clauses as $clause) {
            $label = $this->determineSententialCE($clause);
            
            $sentential[] = new SententialCENode([
                'clausal_ces' => $clause->nodes,
                'sentential_ce' => $label,
                'is_main' => $label === SententialCE::MAIN,
            ]);
        }
        
        return $sentential;
    }
    
    private function determineSententialCE(Clause $clause): string
    {
        /**
         * Assign sentential CE label
         * 
         * Sentential CE types:
         * - Main: Main clause
         * - Sub: Subordinate clause
         * - Coord: Coordinated clause
         */
        
        // If has finite predicate and no subordinator → Main
        if ($clause->hasFinitePredicate() && !$clause->hasSubordinator()) {
            return SententialCE::MAIN;
        }
        
        // If has subordinating conjunction → Sub
        if ($clause->hasSubordinator()) {
            return SententialCE::SUB;
        }
        
        // If has coordinating conjunction → Coord
        if ($clause->hasCoordinator()) {
            return SententialCE::COORD;
        }
        
        // Default to Main
        return SententialCE::MAIN;
    }
    
    private function assembleFinalGraph(
        array $sententialCEs,
        array $longDistDeps
    ): ParseGraph {
        $graph = new ParseGraph();
        
        // Add all nodes
        foreach ($sententialCEs as $sentCE) {
            foreach ($sentCE->clausal_ces as $clausalNode) {
                $graph->addNode($clausalNode);
                
                // Add local dependencies from Stage 2
                foreach ($clausalNode->dependencies as $dep) {
                    $graph->addEdge($dep);
                }
            }
        }
        
        // Add long-distance dependencies from Stage 3
        foreach ($longDistDeps as $dep) {
            $graph->addEdge($dep);
            $graph->markNonProjective($dep); // Flag disulfide bridges
        }
        
        // Identify root
        $graph->setRoot($this->findRoot($sententialCEs));
        
        return $graph;
    }
    
    private function findRoot(array $sententialCEs): ClausalCENode
    {
        // Root is typically the main predicate
        foreach ($sententialCEs as $sentCE) {
            if ($sentCE->is_main) {
                foreach ($sentCE->clausal_ces as $node) {
                    if ($node->clausal_ce === ClausalCE::PRED) {
                        return $node;
                    }
                }
            }
        }
        
        throw new ParsingException("No root found in sentence");
    }
    
    private function validateGraph(ParseGraph $graph): void
    {
        // All nodes must be connected
        if (!$graph->isFullyConnected()) {
            throw new ParsingException("Graph is not fully connected");
        }
        
        // Must have exactly one root
        if ($graph->getRoots()->count() !== 1) {
            throw new ParsingException("Graph must have exactly one root");
        }
    }
}
```

### Long-Distance Dependency Handler

```php
<?php

namespace App\Services\Parser;

class LongDistanceLinker
{
    /**
     * Build long-distance dependencies (disulfide bridges)
     * 
     * Examples:
     * - Relative clauses
     * - Wh-movement
     * - Topicalization
     */
    public function build(array $sententialCEs): array
    {
        $longDistDeps = [];
        
        // Handle relative clauses
        $longDistDeps = array_merge(
            $longDistDeps,
            $this->handleRelativeClauses($sententialCEs)
        );
        
        // Handle wh-movement
        $longDistDeps = array_merge(
            $longDistDeps,
            $this->handleWhMovement($sententialCEs)
        );
        
        return $longDistDeps;
    }
    
    private function handleRelativeClauses(array $sententialCEs): array
    {
        $deps = [];
        
        // Find relative clauses (Sub with Rel CE)
        $relativeClauses = $this->findRelativeClauses($sententialCEs);
        
        foreach ($relativeClauses as $relClause) {
            // Find antecedent (noun in main clause)
            $antecedent = $this->findAntecedent($relClause, $sententialCEs);
            
            if ($antecedent) {
                // Create disulfide-like bridge
                $deps[] = new Dependency(
                    $antecedent,
                    $relClause->getPredicate(),
                    'RELCL',
                    1.0
                );
                
                // Mark as non-projective if crosses intervening material
                if ($this->crosses($antecedent, $relClause)) {
                    end($deps)->non_projective = true;
                }
            }
        }
        
        return $deps;
    }
    
    private function crosses(
        ClausalCENode $node1,
        SententialCENode $clause
    ): bool {
        // Check if dependency crosses intervening words
        $start = $node1->phrasal_node->index;
        $end = $clause->getFirstNode()->phrasal_node->index;
        
        if ($start < $end) {
            // Check if any words between start and end
            $between = $end - $start - 1;
            return $between > 0;
        }
        
        return false;
    }
}
```

### Data Structures

```php
<?php

namespace App\Models\Parser;

class SententialCE
{
    // Sentential CE types
    const MAIN = 'Main';   // Main clause
    const SUB = 'Sub';     // Subordinate clause
    const COORD = 'Coord'; // Coordinated clause
}

class SententialCENode
{
    public array $clausal_ces;      // Array of ClausalCENodes
    public string $sentential_ce;   // Main, Sub, or Coord
    public bool $is_main;
    
    public function __construct(array $data)
    {
        $this->clausal_ces = $data['clausal_ces'];
        $this->sentential_ce = $data['sentential_ce'];
        $this->is_main = $data['is_main'];
    }
    
    public function getPredicate(): ?ClausalCENode
    {
        foreach ($this->clausal_ces as $node) {
            if ($node->clausal_ce === ClausalCE::PRED) {
                return $node;
            }
        }
        return null;
    }
}

class ParseGraph
{
    private array $nodes = [];
    private array $edges = [];
    private ?ClausalCENode $root = null;
    private array $nonProjectiveEdges = [];
    
    public function addNode(ClausalCENode $node): void
    {
        $this->nodes[] = $node;
    }
    
    public function addEdge(Dependency $edge): void
    {
        $this->edges[] = $edge;
    }
    
    public function setRoot(ClausalCENode $root): void
    {
        $this->root = $root;
    }
    
    public function markNonProjective(Dependency $edge): void
    {
        $this->nonProjectiveEdges[] = $edge;
    }
    
    public function isFullyConnected(): bool
    {
        // Check that all nodes are reachable from root
        if (!$this->root) return false;
        
        $visited = [];
        $this->dfs($this->root, $visited);
        
        return count($visited) === count($this->nodes);
    }
    
    private function dfs(ClausalCENode $node, array &$visited): void
    {
        $visited[] = $node;
        
        foreach ($this->edges as $edge) {
            if ($edge->governor === $node && !in_array($edge->dependent, $visited)) {
                $this->dfs($edge->dependent, $visited);
            }
        }
    }
}
```

---

## Complete Pipeline: ParserService Orchestration

```php
<?php

namespace App\Services\Parser;

class ParserService
{
    private TranscriptionService $transcription;
    private TranslationService $translation;
    private FoldingService $folding;
    private ParseGraphVisualizer $visualizer;
    
    public function __construct(
        TranscriptionService $transcription,
        TranslationService $translation,
        FoldingService $folding,
        ParseGraphVisualizer $visualizer
    ) {
        $this->transcription = $transcription;
        $this->translation = $translation;
        $this->folding = $folding;
        $this->visualizer = $visualizer;
    }
    
    /**
     * Complete three-stage parsing pipeline
     * 
     * @param string $sentence Input sentence
     * @param Grammar $grammar MWE definitions
     * @return ParsingResult Complete parse with all CE levels
     */
    public function parse(string $sentence, Grammar $grammar): ParsingResult
    {
        $startTime = microtime(true);
        
        // STAGE 1: TRANSCRIPTION (Build amino acids)
        $stage1Start = microtime(true);
        $phrasalCEs = $this->transcription->buildPhrasalCEs($sentence, $grammar);
        $stage1Time = microtime(true) - $stage1Start;
        
        // STAGE 2: TRANSLATION (Form peptides)
        $stage2Start = microtime(true);
        $clausalCEs = $this->translation->buildClausals($phrasalCEs);
        $stage2Time = microtime(true) - $stage2Start;
        
        // STAGE 3: FOLDING (Integrate polypeptides)
        $stage3Start = microtime(true);
        $parseGraph = $this->folding->foldSentence($clausalCEs);
        $stage3Time = microtime(true) - $stage3Start;
        
        $totalTime = microtime(true) - $startTime;
        
        // Generate visualization
        $visualization = $this->visualizer->render($parseGraph);
        
        return new ParsingResult([
            'sentence' => $sentence,
            'phrasal_ces' => $phrasalCEs,
            'clausal_ces' => $clausalCEs,
            'parse_graph' => $parseGraph,
            'visualization' => $visualization,
            'timing' => [
                'stage1' => $stage1Time,
                'stage2' => $stage2Time,
                'stage3' => $stage3Time,
                'total' => $totalTime,
            ],
            'statistics' => $this->computeStatistics($parseGraph),
        ]);
    }
    
    private function computeStatistics(ParseGraph $graph): array
    {
        return [
            'num_nodes' => count($graph->getNodes()),
            'num_edges' => count($graph->getEdges()),
            'num_non_projective' => count($graph->getNonProjectiveEdges()),
            'avg_dependency_length' => $graph->getAvgDependencyLength(),
            'max_dependency_length' => $graph->getMaxDependencyLength(),
        ];
    }
}

class ParsingResult
{
    public string $sentence;
    public array $phrasal_ces;
    public array $clausal_ces;
    public ParseGraph $parse_graph;
    public string $visualization;
    public array $timing;
    public array $statistics;
    
    public function __construct(array $data)
    {
        $this->sentence = $data['sentence'];
        $this->phrasal_ces = $data['phrasal_ces'];
        $this->clausal_ces = $data['clausal_ces'];
        $this->parse_graph = $data['parse_graph'];
        $this->visualization = $data['visualization'];
        $this->timing = $data['timing'];
        $this->statistics = $data['statistics'];
    }
    
    public function toArray(): array
    {
        return [
            'sentence' => $this->sentence,
            'phrasal_level' => $this->serializePhrasalCEs(),
            'clausal_level' => $this->serializeClausalCEs(),
            'sentential_level' => $this->serializeSententialStructure(),
            'timing' => $this->timing,
            'statistics' => $this->statistics,
        ];
    }
}
```

---

## Testing Examples

### Test 1: Simple Sentence

```php
$sentence = "Tomei café";
$result = $parser->parse($sentence, $grammar);

// Expected: Stage 1 (Phrasal CEs)
assert($result->phrasal_ces[0]->word === "Tomei");
assert($result->phrasal_ces[0]->phrasal_ce === PhrasalCE::HEAD);
assert($result->phrasal_ces[1]->word === "café");
assert($result->phrasal_ces[1]->phrasal_ce === PhrasalCE::HEAD);

// Expected: Stage 2 (Clausal CEs)
assert($result->clausal_ces[0]->clausal_ce === ClausalCE::PRED);
assert($result->clausal_ces[1]->clausal_ce === ClausalCE::ARG);

// Expected: Stage 3 (Sentential)
assert($result->parse_graph->getRoot()->word === "Tomei");
assert(count($result->parse_graph->getNodes()) === 2);
assert(!$result->parse_graph->hasNonProjectiveEdges());
```

### Test 2: Agreement-Heavy (Spanish)

```php
$sentence = "Las tres hermanas grandes llegaron";
$result = $parser->parse($sentence, $grammar);

// Check agreement bonds (H-bonds)
$hermanas = $result->findNode("hermanas");
$las = $result->findNode("las");
$compat = $compatibility->calculate($las->phrasal_node, $hermanas->phrasal_node, 'mod');
assert($compat > 1.5); // Gender + Number agreement

$tres = $result->findNode("tres");
$compat2 = $compatibility->calculate($tres->phrasal_node, $hermanas->phrasal_node, 'mod');
assert($compat2 > 1.2); // Number agreement

// Result should be single Arg CE
$args = $result->getClausalCEs(ClausalCE::ARG);
assert(count($args) === 1);
assert($args[0]->contains("las", "tres", "hermanas", "grandes"));
```

### Test 3: Long-Distance Dependency (Relative Clause)

```php
$sentence = "O menino que eu vi chegou";
$result = $parser->parse($sentence, $grammar);

// Check for non-projective edge (disulfide bridge)
assert($result->parse_graph->hasNonProjectiveEdges());

$nonProj = $result->parse_graph->getNonProjectiveEdges()[0];
assert($nonProj->governor->word === "menino");
assert($nonProj->dependent->word === "chegou");
assert($nonProj->crosses("que", "eu", "vi")); // Crosses intervening words

// Check sentential CEs
$sentCEs = $result->getSententialCEs();
assert(count($sentCEs) === 2); // Main + Sub (relative)
assert($sentCEs[0]->sentential_ce === SententialCE::MAIN);
assert($sentCEs[1]->sentential_ce === SententialCE::SUB);
```

---

## Summary

This implementation provides:

1. **Three-stage pipeline** with clear separation of concerns
2. **CE classification** at all three levels (phrasal, clausal, sentential)
3. **Feature-driven assembly** using compatibility scoring
4. **Long-distance dependencies** handled in Stage 3 (folding)
5. **Comprehensive data structures** for all CE types
6. **Validation** at each stage
7. **Performance metrics** and timing information

**Next steps:**
1. Implement the core services (TranscriptionService, TranslationService, FoldingService)
2. Create database migrations for CE columns
3. Update visualization to show three CE levels
4. Write comprehensive tests
5. Validate with cross-linguistic data

---

**Document Type:** Implementation Guide  
**Version:** 2.0  
**Date:** December 2024  
**Status:** Code Examples Ready for Implementation  
**Related:** REVISED_transdisciplinary_framework.md, MIGRATION_GUIDE_v2.md
