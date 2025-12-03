# Linguistic Parser with MWE Processing: Implementation Summary

## Core Concept

A predictive graph-based parser that processes sentences incrementally, building a dependency graph structure. The system uses an activation-based mechanism inspired by Relational Network Theory (Sidney Lamb) and parallels protein folding processes.

## Theoretical Foundation

### Primitives
- **4 word types**: 
  - **E** (Entities): nouns, proper nouns
  - **R** (Relational): verbs, actions
  - **A** (Attributes): adjectives, adverbs
  - **F** (Fixed): function words treated individually (pronouns, determiners, prepositions)

### Grammar Base Graph
A pre-defined graph structure containing:
- **Nodes**: Individual words {F₁, F₂, ...} and abstract types {E, R, A}, plus MWE nodes
- **Edges**: Valid transitions between nodes (defines grammatical rules)
- **MWE Hierarchy**: Each multi-word expression generates a complete prefix hierarchy

## Multi-Word Expression (MWE) Processing

### Key Innovation: Prefix Hierarchy
Every n-word MWE automatically generates all prefix nodes with incremental thresholds.

**Example: "café da manhã" (breakfast) generates:**
```
Nodes:
- "café" (threshold=1)
- "da" (threshold=1)
- "manhã" (threshold=1)
- "café da" (threshold=2)
- "café da manhã" (threshold=3)

Edges:
- "café" → "da" (sequential)
- "café" → "café da" (activate 2-gram)
- "da" → "café da" (increment 2-gram)
- "da" → "manhã" (sequential)
- "manhã" → "café da manhã" (increment 3-gram)
- "café da" → "café da manhã" (incorporate 2-gram)
```

### Activation Mechanism
1. **Node Properties**:
   - `threshold`: Required activation count (1 for single words, n for n-word MWEs)
   - `activation`: Current activation level
   - `is_focus`: Boolean (true only when activation >= threshold)

2. **Instantiation Rule**: MWE prefix nodes only instantiate when the FIRST word appears

3. **Incremental Activation**: Each subsequent matching word increments activation

4. **Aggregation**: When threshold reached, transfer all incoming links from first component to MWE node

## Parsing Algorithm

### Step-by-Step Process

```
For each word in sentence:
  1. Create word node (threshold=1, activation=1)
  
  2. If word is FIRST in any MWE:
     - Instantiate all prefix nodes for those MWEs
     - Set activation=1 for all
  
  3. Check existing MWE prefix nodes:
     - If word matches expected component: activation++
     - If activation >= threshold: aggregate and make focus
  
  4. Check against current focus nodes:
     - If word matches prediction: create link
     - Recursively check waiting foci for possible links
  
  5. If no match: add word as new waiting focus
  
After processing all words:
  6. Garbage collection: remove all nodes with activation < threshold
  
  7. Validate: check if all remaining nodes are connected
```

### Focus Queue Management
- **Data structure**: FIFO queue (or LIFO stack - to be tested)
- **Waiting foci**: Nodes that don't match current predictions but might link later
- **Recursive linking**: When new link forms, check if waiting foci can now connect

### Successful Parse Criteria
- All words connected in graph
- All focus nodes resolved
- No isolated nodes remain (after garbage collection)

## Implementation Requirements

### Laravel/PHP Structure

#### 1. Core Models

**Node Model**
```php
- id
- label (string): word or MWE phrase
- type (enum): E, R, A, F, MWE
- threshold (int): required activation count
- activation (int): current activation
- is_focus (bool)
- parse_graph_id (foreign key)
- created_at, updated_at
```

**Edge Model**
```php
- id
- source_node_id (foreign key)
- target_node_id (foreign key)
- graph_id (foreign key)
- edge_type (enum): sequential, activate, dependency
- created_at, updated_at
```

**GrammarGraph Model** (Base graph - pre-defined rules)
```php
- id
- name (string)
- language (string): 'pt', 'en', etc.
- nodes (relationship)
- edges (relationship)
- created_at, updated_at
```

**ParseGraph Model** (Instance created during parsing)
```php
- id
- sentence (text)
- grammar_graph_id (foreign key)
- status (enum): parsing, complete, failed
- nodes (relationship)
- edges (relationship)
- created_at, updated_at
```

**MWE Model**
```php
- id
- phrase (string): "café da manhã"
- components (json): ["café", "da", "manhã"]
- semantic_type (enum): E, R, A
- grammar_graph_id (foreign key)
- created_at, updated_at
```

#### 2. Core Services

**ParserService**
```php
class ParserService
{
    public function parse(string $sentence, GrammarGraph $grammar): ParseGraph
    public function processWord(string $word, ParseGraph $parseGraph): void
    public function checkFociPredictions(Node $wordNode, ParseGraph $parseGraph): bool
    public function recursiveLinking(Node $newNode, ParseGraph $parseGraph): void
    public function garbageCollect(ParseGraph $parseGraph): void
    public function validateParse(ParseGraph $parseGraph): bool
}
```

**MWEService**
```php
class MWEService
{
    public function generatePrefixHierarchy(MWE $mwe): array
    public function instantiateMWENodes(string $firstWord, ParseGraph $parseGraph): void
    public function incrementActivation(Node $mweNode, string $word): void
    public function aggregateMWE(Node $mweNode, ParseGraph $parseGraph): void
    public function transferLinks(Node $fromNode, Node $toNode, ParseGraph $parseGraph): void
}
```

**GrammarGraphService**
```php
class GrammarGraphService
{
    public function getMWEsStartingWith(string $word, GrammarGraph $grammar): Collection
    public function getPredictedTypes(Node $focusNode): array
    public function canLink(Node $source, Node $target): bool
    public function buildGrammarFromRules(array $rules): GrammarGraph
}
```

**FocusQueueService**
```php
class FocusQueueService
{
    private array $queue = [];
    
    public function enqueue(Node $node): void
    public function dequeue(): ?Node
    public function getActiveFoci(): Collection
    public function removeFromQueue(Node $node): void
    public function isEmpty(): bool
}
```

#### 3. API Endpoints

```php
POST /api/parse
{
    "sentence": "Tomei café da manhã cedo",
    "grammar_id": 1
}
Response: ParseGraph with all nodes and edges

GET /api/grammar/{id}
Response: GrammarGraph structure

POST /api/grammar
{
    "name": "Portuguese Basic",
    "language": "pt",
    "mwes": [...],
    "rules": [...]
}
Response: Created GrammarGraph

GET /api/parse/{id}/visualization
Response: JSON for graph visualization
```

#### 4. Console Commands

```php
php artisan parser:test-sentence "café da manhã" --grammar=1
php artisan grammar:import --file=grammar.json
php artisan grammar:add-mwe "café da manhã" --type=E
php artisan parser:batch-test --file=sentences.txt
```

### Database Schema

```sql
-- grammar_graphs
id, name, language, created_at, updated_at

-- mwes  
id, grammar_graph_id, phrase, components (JSON), semantic_type, created_at, updated_at

-- grammar_nodes
id, grammar_graph_id, label, type, threshold, created_at, updated_at

-- grammar_edges
id, grammar_graph_id, source_node_id, target_node_id, edge_type, created_at, updated_at

-- parse_graphs
id, sentence, grammar_graph_id, status, created_at, updated_at

-- parse_nodes
id, parse_graph_id, label, type, threshold, activation, is_focus, position_in_sentence, created_at, updated_at

-- parse_edges
id, parse_graph_id, source_node_id, target_node_id, created_at, updated_at
```

## Initial Test Cases (Portuguese)

### Simple Sentences
1. "Café está quente" (Coffee is hot)
   - café (E) → está (R) → quente (A)

2. "Tomei café" (I drank coffee)
   - Tomei (R) → café (E)

### MWE Tests
3. "Café da manhã" (Breakfast)
   - Should create: café_da_manhã node (threshold=3)
   - Garbage collect: café, da, manhã individual nodes

4. "Tomei café da manhã" (I had breakfast)
   - Should link: Tomei → café_da_manhã (aggregated MWE)

5. "Café da tarde" (Afternoon coffee/snack)
   - Test competing MWEs with shared prefixes

6. "Café quente da manhã" (Hot morning coffee - interrupted MWE)
   - café_da_manhã should fail (interrupted by "quente")
   - Should parse as: café ← quente, café → da → manhã

### Nested MWEs
7. "Mesa de café da manhã" (Breakfast table)
   - café_da_manhã completes first (becomes atomic)
   - Then mesa_de_café_da_manhã completes

### Focus Queue Tests
8. "O menino que eu vi comeu" (The boy that I saw ate)
   - Test multiple waiting foci
   - Test recursive linking when "comeu" arrives

## Open Questions for Testing

1. **Queue Strategy**: FIFO vs LIFO - which handles Portuguese word order better?

2. **Partial MWEs**: When "café da" appears without "manhã", should "café da" survive as 2-gram?

3. **Competing MWEs**: If "café da" is prefix of both "café da manhã" and "café da tarde", how to handle ambiguity?

4. **Performance**: For 5-word MWE creating 10+ prefix nodes - acceptable overhead?

5. **Edge Types**: Currently untyped - should we add types (dependency, modification, etc.) later?

6. **Prediction Granularity**: How specific should focus predictions be? Just type (E/R/A) or also specific words?

## Implementation Phases

### Phase 1: Core Infrastructure
- Models, migrations, basic relationships
- GrammarGraph creation and storage
- MWE definition and prefix generation

### Phase 2: Basic Parser
- Single-word parsing without MWEs
- Focus queue management
- Simple prediction matching

### Phase 3: MWE Processing
- MWE instantiation on first word
- Incremental activation
- Aggregation and link transfer
- Garbage collection

### Phase 4: Testing & Refinement
- Test suite with Portuguese examples
- Performance optimization
- Grammar expansion based on failed parses

### Phase 5: Visualization
- Web interface for graph display
- Step-by-step parsing animation
- Grammar graph editor

## Next Steps

1. Set up Laravel project with initial models
2. Create basic grammar graph for Portuguese (manual curation)
3. Implement core parsing algorithm
4. Test with simple sentences
5. Add MWE processing
6. Iterate based on test results
