# Parser V3 Implementation Plan

## Hybrid Architecture: MWE Patterns + BNF Constructions

**Version:** 3.0
**Date:** December 2024
**Status:** Planning Phase

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [Architecture Overview](#2-architecture-overview)
3. [Current State (V2) Summary](#3-current-state-v2-summary)
4. [V3 Extensions](#4-v3-extensions)
5. [BNF Implementation Details](#5-bnf-implementation-details)
6. [Database Schema Changes](#6-database-schema-changes)
7. [Integration with Three-Stage Pipeline](#7-integration-with-three-stage-pipeline)
8. [Implementation Phases](#8-implementation-phases)
9. [API Design](#9-api-design)
10. [Testing Strategy](#10-testing-strategy)
11. [Migration Path](#11-migration-path)

---

## 1. Executive Summary

Parser V3 introduces a **hybrid architecture** combining two complementary methods for detecting and assembling multi-word constructions:

| Method | Best For | Expressiveness | Performance |
|--------|----------|----------------|-------------|
| **MWE Patterns** (V2) | Fixed/variable sequences, scale | Flat sequences only | O(n×m) anchored |
| **BNF Constructions** (V3 New) | Complex recursive patterns | Full CFG (optionality, alternatives, repetition) | O(n×states) with caching |

### Key Decision: Hybrid Approach

Based on the comparative analysis, V3 will:

1. **Keep MWE Patterns as primary system** - handles 95% of cases efficiently
2. **Add BNF Constructions for complex patterns** - numbers, dates, addresses, nested structures
3. **Unified processing pipeline** - both methods feed into Stage 1 (Transcription)
4. **Compile-once, run-many** - BNF graphs stored pre-compiled in database

---

## 2. Architecture Overview

### 2.1 Layered Detection System

```
┌─────────────────────────────────────────────────────────────────┐
│  Stage 1 (Transcription) - Enhanced Detection Pipeline          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Layer 3: BNF Constructions (V3 NEW)                    │   │
│  │  - Complex recursive patterns (numbers, dates)          │   │
│  │  - Pre-compiled graphs stored in database               │   │
│  │  - Graph traversal with backtracking                    │   │
│  └─────────────────────────────────────────────────────────┘   │
│                          ↓                                      │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Layer 2: Variable MWE Patterns (V2)                    │   │
│  │  - [NOUN] de [NOUN] patterns                            │   │
│  │  - Anchor-based fast lookup                             │   │
│  │  - Two-phase detection (anchored + fully variable)      │   │
│  └─────────────────────────────────────────────────────────┘   │
│                          ↓                                      │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │  Layer 1: Simple MWE Patterns (V1 Legacy)               │   │
│  │  - Fixed word sequences ("café da manhã")               │   │
│  │  - First-word indexing                                  │   │
│  │  - Prefix activation mechanism                          │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
                          ↓
              Stage 2 (Translation) - Clausal Assembly
                          ↓
              Stage 3 (Folding) - Sentential Integration
```

### 2.2 Processing Flow

```
Input Tokens (from UD Parse)
         │
         ▼
┌─────────────────────────────────────┐
│  1. BNF Construction Detection      │
│     - Load compiled graphs          │
│     - Try matching at key positions │
│     - Mark matched spans            │
└───────────────┬─────────────────────┘
                │
         ▼ (unmarked tokens)
┌─────────────────────────────────────┐
│  2. Variable MWE Detection          │
│     - Phase 1: Anchored patterns    │
│     - Phase 2: Fully variable       │
│     - Mark matched spans            │
└───────────────┬─────────────────────┘
                │
         ▼ (unmarked tokens)
┌─────────────────────────────────────┐
│  3. Simple MWE Detection            │
│     - Prefix activation             │
│     - Threshold-based assembly      │
└───────────────┬─────────────────────┘
                │
         ▼
┌─────────────────────────────────────┐
│  4. Phrasal CE Assembly             │
│     - Merge all detected patterns   │
│     - Assign PhrasalCE labels       │
│     - Output PhrasalCENodes         │
└─────────────────────────────────────┘
```

---

## 3. Current State (V2) Summary

### 3.1 Implemented Components

| Component | File | Description |
|-----------|------|-------------|
| MWE Repository | `app/Repositories/Parser/MWE.php` | Database access, component matching |
| MWE Service | `app/Services/Parser/MWEService.php` | Prefix hierarchy, activation |
| MWE Component Types | `app/Enums/Parser/MWEComponentType.php` | W, L, P, C, * types |
| Transcription Service | `app/Services/Parser/TranscriptionService.php` | Stage 1 processing |
| Variable MWE Docs | `docs/parser/v2/VARIABLE_MWE_PATTERNS.md` | Complete documentation |

### 3.2 MWE Pattern Capabilities

**Supported:**
- Fixed word sequences (simple format)
- POS-based slots (P type)
- Lemma matching (L type)
- CE-based matching (C type)
- Wildcards (* type)
- Anchor-based indexing

**Not Supported:**
- Optional elements `[element]`
- Alternatives `(A | B | C)`
- Repetition `A+`, `A*`
- Nested patterns

### 3.3 Database Schema (Current)

```sql
CREATE TABLE parser_mwe (
    idMWE INT PRIMARY KEY AUTO_INCREMENT,
    idGrammarGraph INT NOT NULL,
    phrase VARCHAR(255) NOT NULL,
    components LONGTEXT NOT NULL,  -- JSON array
    componentFormat ENUM('simple', 'extended') DEFAULT 'simple',
    anchorPosition TINYINT NULL,
    anchorWord VARCHAR(100) NULL,
    semanticType ENUM('E','V','A','F','R','Head','Mod','Adm','Adp','Lnk','Clf','Idx','Conj'),
    -- ... indexes
);
```

---

## 4. V3 Extensions

### 4.1 New Capabilities

| Feature | Description | Example |
|---------|-------------|---------|
| **BNF Constructions** | Full CFG patterns with graph traversal | Portuguese numbers |
| **Optional Elements** | `[element]` syntax | `[{NUM_UNIT}] mil` |
| **Alternatives** | `(A \| B \| C)` syntax | `(e \| ou)` |
| **Repetition** | `A+`, `A*` syntax | `{ADJ}+` |
| **Constraints** | `{POS:constraint}` | `{VERB:inf}` |
| **Semantic Actions** | Value calculation from matches | Number → numeric value |
| **Pre-compilation** | Store compiled graphs in database | One-time cost |

### 4.2 Use Cases for BNF Constructions

| Construction Type | Pattern Example | Language |
|-------------------|-----------------|----------|
| **Cardinal Numbers** | `[{NUM_UNIT}] mil [,] [{NUM_HUNDRED}] [e {NUM_TEN}] [e {NUM_UNIT}]` | PT |
| **Ordinal Numbers** | `{NUM} [{SUF:o/a}]` | PT |
| **Dates** | `{NUM} de {MONTH} [de {YEAR}]` | PT |
| **Times** | `{NUM} [e] [{NUM}] [horas \| h]` | PT |
| **Addresses** | `{TYPE} {NAME} [, {NUM}]` | PT |
| **Complex Prepositions** | `{ADV} de [a \| o]` | PT |

### 4.3 When to Use Each Method

| Criterion | Use MWE Patterns | Use BNF Constructions |
|-----------|------------------|----------------------|
| Pattern type | Fixed/simple variable | Complex with recursion |
| Optionality | Not needed | Required |
| Alternatives | Create multiple patterns | Single pattern |
| Scale | Thousands of patterns | Tens of constructions |
| Semantic value | Classification only | Calculation needed |

---

## 5. BNF Implementation Details

### 5.1 Pattern Notation

Based on the analysis in `docs/bnf/bnf.md`:

```
# Literals
word            # Fixed word (case-insensitive)

# Slots
{POS}           # POS tag slot (NOUN, VERB, ADJ, etc.)
{POS:constraint}# Constrained slot (VERB:inf, NOUN:plural)
{*}             # Wildcard (any token)

# Optionality
[element]       # Optional element
[A B C]         # Optional sequence

# Alternatives
(A | B | C)     # One of the alternatives

# Repetition
A+              # One or more
A*              # Zero or more

# Grouping
(A B C)         # Sequence group
```

### 5.2 Graph Structure

```php
// Compiled graph structure
$graph = [
    'nodes' => [
        'n0' => ['type' => 'START'],
        'n1' => ['type' => 'SLOT', 'pos' => 'NUM_UNIT', 'constraint' => null],
        'n2' => ['type' => 'LITERAL', 'value' => 'mil'],
        'n3' => ['type' => 'OPTIONAL_START'],
        // ...
        'n9' => ['type' => 'END']
    ],
    'edges' => [
        ['from' => 'n0', 'to' => 'n1'],
        ['from' => 'n0', 'to' => 'n2'],  // bypass optional
        ['from' => 'n1', 'to' => 'n2'],
        // ...
    ]
];
```

### 5.3 Node Types

| Type | Description | Properties |
|------|-------------|------------|
| `START` | Entry point | - |
| `END` | Exit point | - |
| `LITERAL` | Fixed word | `value` |
| `SLOT` | POS slot | `pos`, `constraint` |
| `WILDCARD` | Any token | - |
| `OPTIONAL_START` | Begin optional | - |
| `OPTIONAL_END` | End optional | - |
| `ALT_START` | Begin alternatives | - |
| `ALT_END` | End alternatives | - |
| `REP_START` | Begin repetition | `min`, `max` |
| `REP_END` | End repetition | - |

### 5.4 Graph Conversion Rules

| Pattern Element | Graph Transformation |
|-----------------|---------------------|
| `A B C` (sequence) | `[A] → [B] → [C]` |
| `[A]` (optional) | Fork: `[start] → [A] → [end]` + `[start] → [end]` |
| `(A \| B)` (alternative) | Fork: `[start] → [A] → [end]` + `[start] → [B] → [end]` |
| `A+` (one or more) | `[A] → [check] → [A]` (loop) + `[check] → [end]` |
| `A*` (zero or more) | Like `A+` with initial bypass |

### 5.5 Matching Algorithm

```php
class BNFMatcher {
    /**
     * Match tokens against compiled graph
     * Uses backtracking for non-deterministic paths
     */
    public function match(array $graph, array $tokens, int $startPos): ?ConstructionMatch
    {
        $result = [
            'matched' => false,
            'slots' => [],
            'span' => [],
            'endPosition' => $startPos
        ];

        if ($this->traverse($graph, 'n0', $tokens, $startPos, $result)) {
            $result['matched'] = true;
            return new ConstructionMatch($result);
        }

        return null;
    }

    private function traverse(
        array $graph,
        string $nodeId,
        array $tokens,
        int $tokenIndex,
        array &$result
    ): bool {
        $node = $graph['nodes'][$nodeId];

        // END node: success if tokens consumed or optional
        if ($node['type'] === 'END') {
            $result['endPosition'] = $tokenIndex;
            return true;
        }

        // Match based on node type
        $consumed = $this->matchNode($node, $tokens, $tokenIndex, $result);

        if ($consumed === false) {
            return false;
        }

        // Try all outgoing edges (backtracking)
        $outEdges = $this->getOutgoingEdges($graph, $nodeId);

        foreach ($outEdges as $edge) {
            // Save state for backtracking
            $savedState = $this->saveState($result);

            if ($this->traverse($graph, $edge['to'], $tokens, $tokenIndex + $consumed, $result)) {
                return true;
            }

            // Restore state on failure
            $this->restoreState($result, $savedState);
        }

        return false;
    }
}
```

---

## 6. Database Schema Changes

### 6.1 New Table: `parser_constructions`

```sql
CREATE TABLE parser_constructions (
    idConstruction INT PRIMARY KEY AUTO_INCREMENT,
    idGrammarGraph INT NOT NULL,

    -- Pattern definition
    name VARCHAR(100) NOT NULL,
    pattern TEXT NOT NULL,
    description TEXT,

    -- Compiled graph (one-time compilation)
    compiledGraph JSON NOT NULL,

    -- Semantic interpretation
    semanticType VARCHAR(20) NOT NULL,  -- PhrasalCE value
    semantics JSON,                      -- Interpretation rules

    -- Matching configuration
    priority TINYINT DEFAULT 0,          -- Higher = try first
    enabled BOOLEAN DEFAULT TRUE,

    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes
    UNIQUE INDEX idx_construction_name (idGrammarGraph, name),
    INDEX idx_construction_type (idGrammarGraph, semanticType),
    INDEX idx_construction_priority (idGrammarGraph, priority DESC),

    FOREIGN KEY (idGrammarGraph) REFERENCES parser_grammar_graph(idGrammarGraph)
        ON DELETE CASCADE
);
```

### 6.2 Semantics JSON Structure

```json
{
    "type": "cardinal_number",
    "calculation": {
        "method": "portuguese_number",
        "slots": {
            "thousands": {"multiply": 1000},
            "hundreds": {"add": true},
            "tens": {"add": true},
            "units": {"add": true}
        }
    },
    "outputFeatures": {
        "NumType": "Card"
    }
}
```

### 6.3 Migration Script

```php
// database/migrations/xxxx_create_parser_constructions_table.php
public function up(): void
{
    Schema::create('parser_constructions', function (Blueprint $table) {
        $table->id('idConstruction');
        $table->foreignId('idGrammarGraph')
            ->constrained('parser_grammar_graph', 'idGrammarGraph')
            ->onDelete('cascade');

        $table->string('name', 100);
        $table->text('pattern');
        $table->text('description')->nullable();

        $table->json('compiledGraph');

        $table->string('semanticType', 20);
        $table->json('semantics')->nullable();

        $table->tinyInteger('priority')->default(0);
        $table->boolean('enabled')->default(true);

        $table->timestamps();

        $table->unique(['idGrammarGraph', 'name']);
        $table->index(['idGrammarGraph', 'semanticType']);
        $table->index(['idGrammarGraph', 'priority']);
    });
}
```

---

## 7. Integration with Three-Stage Pipeline

### 7.1 Stage 1 (Transcription) - Enhanced

```php
class TranscriptionService
{
    private MWEService $mweService;
    private ConstructionService $constructionService;  // NEW

    public function transcribe(array $tokens, int $idParserGraph, int $idGrammarGraph): array
    {
        // Convert tokens to PhrasalCENodes first
        $nodes = $this->createPhrasalNodes($tokens);

        // 1. BNF Construction Detection (NEW - highest priority)
        $constructionMatches = $this->constructionService->detectAll(
            $nodes, $idGrammarGraph
        );
        $nodes = $this->applyMatches($nodes, $constructionMatches, 'construction');

        // 2. Variable MWE Detection
        $variableMWEs = $this->mweService->detectVariableMWEs(
            $nodes, $idGrammarGraph
        );
        $nodes = $this->applyMatches($nodes, $variableMWEs, 'variable_mwe');

        // 3. Simple MWE Detection (legacy)
        $simpleMWEs = $this->mweService->detectSimpleMWEs(
            $nodes, $idGrammarGraph
        );
        $nodes = $this->applyMatches($nodes, $simpleMWEs, 'simple_mwe');

        // 4. Garbage collection
        $nodes = $this->garbageCollect($nodes);

        return $nodes;
    }
}
```

### 7.2 Match Application

```php
private function applyMatches(array $nodes, array $matches, string $source): array
{
    foreach ($matches as $match) {
        // Get span of matched nodes
        $startIdx = $match->startPosition;
        $endIdx = $match->endPosition;

        // Create merged node
        $mergedNode = $this->mergeNodes(
            array_slice($nodes, $startIdx, $endIdx - $startIdx + 1),
            $match
        );

        // Mark source for debugging
        $mergedNode->matchSource = $source;

        // Replace in array
        array_splice($nodes, $startIdx, $endIdx - $startIdx + 1, [$mergedNode]);

        // Adjust subsequent match positions
        // ...
    }

    return $nodes;
}
```

### 7.3 Output to Stage 2

Both MWE and Construction matches become single PhrasalCENodes:

```php
PhrasalCENode {
    word: "dois mil e quinhentos",
    lemma: "dois mil e quinhentos",
    pos: "NUM",           // From semantic rules
    phrasalCE: Head,      // From semanticType
    features: [
        'lexical' => ['NumType' => 'Card'],
        'derived' => ['numericValue' => 2500]  // From semantic calculation
    ],
    isMWE: true,
    mweComponents: ['dois', 'mil', 'e', 'quinhentos'],
    matchSource: 'construction'
}
```

---

## 8. Implementation Phases

### Phase 1: Core BNF Infrastructure (Week 1-2)

**Tasks:**

1. Create database migration for `parser_constructions`
2. Implement `PatternCompiler` class:
   - Pattern tokenization
   - Graph construction
   - Validation
3. Implement `ConstructionRepository`:
   - CRUD operations
   - Compiled graph caching
4. Create test suite for pattern compilation

**Deliverables:**
- `app/Services/Parser/PatternCompiler.php`
- `app/Repositories/Parser/Construction.php`
- `database/migrations/xxxx_create_parser_constructions_table.php`
- `tests/Feature/Parser/PatternCompilerTest.php`

### Phase 2: Matching Engine (Week 3-4)

**Tasks:**

1. Implement `BNFMatcher` class:
   - Graph traversal
   - Backtracking
   - State management
2. Implement `ConstructionService`:
   - Load constructions for grammar
   - Detect all matches in sentence
   - Handle overlapping matches
3. Add caching layer for compiled graphs
4. Create test suite for matching

**Deliverables:**
- `app/Services/Parser/BNFMatcher.php`
- `app/Services/Parser/ConstructionService.php`
- `tests/Feature/Parser/BNFMatcherTest.php`

### Phase 3: Semantic Actions (Week 5)

**Tasks:**

1. Implement semantic value calculation:
   - Number calculation (Portuguese)
   - Date parsing
   - Generic slot extraction
2. Create semantic action registry
3. Add feature derivation from semantics
4. Test semantic outputs

**Deliverables:**
- `app/Services/Parser/SemanticCalculator.php`
- `app/Services/Parser/SemanticActions/PortugueseNumberAction.php`
- `tests/Feature/Parser/SemanticCalculatorTest.php`

### Phase 4: Pipeline Integration (Week 6)

**Tasks:**

1. Modify `TranscriptionService` to include construction detection
2. Implement match priority resolution
3. Handle overlapping matches between layers
4. Update test commands with construction support
5. Performance optimization

**Deliverables:**
- Updated `app/Services/Parser/TranscriptionService.php`
- `app/Console/Commands/ParserV2/TestConstructionCommand.php`
- Integration tests

### Phase 5: Admin Interface (Week 7-8)

**Tasks:**

1. Create construction management views:
   - List constructions
   - Create/edit pattern
   - Test pattern against sentences
   - View compiled graph
2. Import/export functionality
3. Documentation UI

**Deliverables:**
- Blade views for construction management
- `app/Http/Controllers/Parser/ConstructionController.php`
- API endpoints

### Phase 6: Predefined Constructions (Week 9)

**Tasks:**

1. Implement Portuguese number construction
2. Implement Portuguese date construction
3. Implement Portuguese time construction
4. Create construction library JSON
5. Import command

**Deliverables:**
- `resources/data/constructions/pt_numbers.json`
- `resources/data/constructions/pt_dates.json`
- `app/Console/Commands/Parser/ImportConstructionsCommand.php`

---

## 9. API Design

### 9.1 Repository: `Construction`

```php
namespace App\Repositories\Parser;

class Construction
{
    // CRUD
    public static function byId(int $id): object;
    public static function listByGrammar(int $idGrammarGraph): array;
    public static function create(array $data): int;
    public static function update(int $id, array $data): void;
    public static function delete(int $id): void;

    // Queries
    public static function getEnabled(int $idGrammarGraph): array;
    public static function getByName(int $idGrammarGraph, string $name): ?object;

    // Compilation
    public static function compile(string $pattern): array;
    public static function getCompiledGraph(int $id): array;
}
```

### 9.2 Service: `PatternCompiler`

```php
namespace App\Services\Parser;

class PatternCompiler
{
    // Compilation
    public function compile(string $pattern): array;
    public function validate(string $pattern): ValidationResult;

    // Tokenization
    public function tokenize(string $pattern): array;

    // Graph building
    public function buildGraph(array $tokens): array;

    // Export
    public function toDot(array $graph): string;
    public function toJson(array $graph): string;
}
```

### 9.3 Service: `ConstructionService`

```php
namespace App\Services\Parser;

class ConstructionService
{
    // Detection
    public function detectAll(array $nodes, int $idGrammarGraph): array;
    public function detectConstruction(array $nodes, object $construction, int $startPos): ?ConstructionMatch;

    // Management
    public function compileAndStore(int $idGrammarGraph, string $name, string $pattern, array $metadata): int;
    public function recompile(int $idConstruction): void;

    // Testing
    public function testPattern(string $pattern, string $sentence): array;
}
```

### 9.4 Data Classes

```php
namespace App\Data\Parser;

class ConstructionMatch
{
    public int $idConstruction;
    public string $name;
    public int $startPosition;
    public int $endPosition;
    public array $matchedTokens;
    public array $slots;           // Named slot values
    public ?mixed $semanticValue;  // Calculated value
    public array $features;        // Derived features
}

class CompiledGraph
{
    public array $nodes;
    public array $edges;
    public string $pattern;
    public string $checksum;       // For cache invalidation
}
```

---

## 10. Testing Strategy

### 10.1 Unit Tests

**Pattern Compiler:**
```php
it('compiles sequence pattern', function () {
    $compiler = new PatternCompiler();
    $graph = $compiler->compile('a b c');

    expect($graph['nodes'])->toHaveCount(5);  // START + 3 literals + END
    expect($graph['edges'])->toHaveCount(4);
});

it('compiles optional pattern', function () {
    $compiler = new PatternCompiler();
    $graph = $compiler->compile('a [b] c');

    // Should have bypass edge for optional
    $bypassEdges = collect($graph['edges'])
        ->filter(fn($e) => $e['bypass'] ?? false);
    expect($bypassEdges)->toHaveCount(1);
});
```

**BNF Matcher:**
```php
it('matches simple pattern', function () {
    $matcher = new BNFMatcher();
    $graph = $compiler->compile('the {NOUN}');
    $tokens = [
        new PhrasalCENode(word: 'the', pos: 'DET'),
        new PhrasalCENode(word: 'cat', pos: 'NOUN'),
    ];

    $match = $matcher->match($graph, $tokens, 0);

    expect($match)->not->toBeNull();
    expect($match->endPosition)->toBe(1);
});

it('matches with optionals', function () {
    $graph = $compiler->compile('[very] {ADJ} {NOUN}');

    // Without optional
    $tokens1 = [tok('big', 'ADJ'), tok('dog', 'NOUN')];
    expect($matcher->match($graph, $tokens1, 0))->not->toBeNull();

    // With optional
    $tokens2 = [tok('very', 'ADV'), tok('big', 'ADJ'), tok('dog', 'NOUN')];
    expect($matcher->match($graph, $tokens2, 0))->not->toBeNull();
});
```

### 10.2 Feature Tests

**Portuguese Numbers:**
```php
it('parses portuguese cardinal numbers', function () {
    $service = app(ConstructionService::class);

    $testCases = [
        'dois mil' => 2000,
        'trezentos e quarenta e cinco' => 345,
        'dois mil, quatrocentos e vinte e dois' => 2422,
        'mil e quinhentos' => 1500,
    ];

    foreach ($testCases as $text => $expected) {
        $match = $service->detectConstruction(
            tokenize($text),
            getConstruction('portuguese_cardinal'),
            0
        );

        expect($match)->not->toBeNull();
        expect($match->semanticValue)->toBe($expected);
    }
});
```

### 10.3 Integration Tests

```php
it('integrates with transcription pipeline', function () {
    $service = app(TranscriptionService::class);

    $sentence = "Comprei dois mil e quinhentos livros.";
    $tokens = parseWithUD($sentence);

    $nodes = $service->transcribe($tokens, $graphId, $grammarId);

    // Should have merged the number into single node
    $numberNode = collect($nodes)->first(fn($n) => $n->matchSource === 'construction');

    expect($numberNode)->not->toBeNull();
    expect($numberNode->features['derived']['numericValue'])->toBe(2500);
});
```

---

## 11. Migration Path

### 11.1 Backward Compatibility

- All existing MWE patterns continue to work unchanged
- Simple format MWEs use existing detection
- Extended format MWEs use existing two-phase detection
- BNF constructions are additive (new layer)

### 11.2 Migration Steps

1. **Run migration** to create `parser_constructions` table
2. **Deploy code** with new services (disabled by default)
3. **Import predefined constructions** for Portuguese
4. **Enable construction detection** in config
5. **Monitor performance** and adjust priorities
6. **Gradually migrate** complex MWE patterns to constructions

### 11.3 Configuration

```php
// config/parser.php
return [
    'constructions' => [
        'enabled' => env('PARSER_CONSTRUCTIONS_ENABLED', true),
        'cacheEnabled' => true,
        'cacheTTL' => 3600,
        'maxBacktrackingDepth' => 100,
        'priority' => [
            'constructions' => 1,  // Run first
            'variable_mwe' => 2,
            'simple_mwe' => 3,
        ],
    ],
];
```

---

## Appendix A: Pattern Examples

### A.1 Portuguese Cardinal Numbers

```
Pattern: [{NUM_UNIT}] mil [,] [{NUM_HUNDRED}] [e [{NUM_TEN}]] [e [{NUM_UNIT}]]

Semantics:
{
    "type": "cardinal_number",
    "slots": {
        "thousands_prefix": { "extract": 0, "default": 1 },
        "hundreds": { "extract": 2, "default": 0 },
        "tens": { "extract": 4, "default": 0 },
        "units": { "extract": 6, "default": 0 }
    },
    "calculation": "thousands_prefix * 1000 + hundreds + tens + units"
}
```

### A.2 Portuguese Dates

```
Pattern: {NUM} de {MONTH} [de {YEAR}]

Semantics:
{
    "type": "date",
    "slots": {
        "day": { "extract": 0 },
        "month": { "extract": 2, "lookup": "month_to_number" },
        "year": { "extract": 4, "optional": true }
    },
    "output": "ISO date string"
}
```

### A.3 Complex Prepositions

```
Pattern: (por causa | por meio | por falta | apesar) de [a | o | as | os]

Semantics:
{
    "type": "complex_preposition",
    "semanticType": "Adp"
}
```

---

## Appendix B: Files to Create

| File | Purpose |
|------|---------|
| `app/Services/Parser/PatternCompiler.php` | Compile patterns to graphs |
| `app/Services/Parser/BNFMatcher.php` | Match graphs against tokens |
| `app/Services/Parser/ConstructionService.php` | Orchestrate detection |
| `app/Services/Parser/SemanticCalculator.php` | Calculate semantic values |
| `app/Repositories/Parser/Construction.php` | Database access |
| `app/Data/Parser/ConstructionMatch.php` | Match result DTO |
| `app/Data/Parser/CompiledGraph.php` | Graph structure DTO |
| `app/Console/Commands/Parser/TestConstructionCommand.php` | Test command |
| `app/Console/Commands/Parser/ImportConstructionsCommand.php` | Import command |
| `app/Console/Commands/Parser/CompileConstructionCommand.php` | Compile command |
| `database/migrations/xxxx_create_parser_constructions_table.php` | Schema |
| `resources/data/constructions/pt_numbers.json` | Number patterns |
| `resources/data/constructions/pt_dates.json` | Date patterns |
| `docs/parser/v3/MWE_BNF_GUIDE.md` | User documentation |

---

**Document Status:** Planning Complete
**Next Step:** Begin Phase 1 Implementation
**Estimated Duration:** 9 weeks
**Dependencies:** V2 must be stable (currently is)
