# Variable MWE Patterns - Multi-Word Expressions with Generic Components

**Version:** 2.0
**Date:** December 2024
**Status:** Implemented

---

## Table of Contents

1. [Overview](#overview)
2. [Motivation](#motivation)
3. [Component Types](#component-types)
4. [Pattern Formats](#pattern-formats)
5. [Detection Algorithm](#detection-algorithm)
6. [Database Schema](#database-schema)
7. [Implementation Guide](#implementation-guide)
8. [Examples](#examples)
9. [API Reference](#api-reference)
10. [Testing](#testing)

---

## Overview

The Variable MWE (Multi-Word Expression) system extends traditional MWE detection to support **generic patterns with variable components**. Instead of matching only fixed word sequences like "café da manhã", the system can now match patterns like "[NOUN] de [NOUN]" where components can be:

- **POS tags** (from Universal Dependencies parser)
- **CE labels** (Phrasal Construction Elements from Croft's flat syntax)
- **Lemmas** (base forms of words)
- **Wildcards** (match any token)
- **Fixed words** (traditional MWE components)

This enables the parser to recognize entire **classes of expressions** rather than individual instances.

### Key Features

- **Backward compatible** with existing simple (fixed-word) MWE patterns
- **Two-phase detection** for optimal performance (anchored + fully variable)
- **Type-aware matching** for each component
- **Anchor-based indexing** for efficient lookup
- **Seamless integration** with existing parser stages

---

## Motivation

### The Problem

Traditional MWE detection required explicitly listing every multi-word expression:

```
café da manhã
bolo de chocolate
copo de água
garrafa de vinho
...
```

This approach has limitations:
- **Scalability:** Thousands of similar expressions to maintain
- **Coverage:** New expressions not in database are missed
- **Linguistic insight:** Pattern is implicit, not explicit

### The Solution

Variable patterns capture the underlying linguistic structure:

```json
{
  "phrase": "[NOUN] de [NOUN]",
  "components": [
    {"type": "P", "value": "NOUN"},
    {"type": "W", "value": "de"},
    {"type": "P", "value": "NOUN"}
  ],
  "semanticType": "Head"
}
```

This single pattern matches all "[NOUN] de [NOUN]" constructions, recognizing the productive pattern in Portuguese.

### Benefits

1. **Linguistic precision:** Explicit representation of productive patterns
2. **Better coverage:** Captures all instances matching the pattern
3. **Maintainability:** One pattern instead of hundreds of instances
4. **Theoretical grounding:** Based on Construction Grammar and dependency parsing

---

## Component Types

Each component in a variable pattern has a **type** that determines how it matches tokens.

### Type System

| Type Code | Name | Matches Against | Example |
|-----------|------|-----------------|---------|
| `W` | Word | `token.word` (case-insensitive) | `{"type": "W", "value": "de"}` |
| `L` | Lemma | `token.lemma` (case-insensitive) | `{"type": "L", "value": "ser"}` |
| `P` | POS | `token.pos` (UDPOS tag) | `{"type": "P", "value": "NOUN"}` |
| `C` | CE | `token.phrasalCE` (Phrasal CE label) | `{"type": "C", "value": "Head"}` |
| `*` | Wildcard | Any token | `{"type": "*", "value": ""}` |

### Detailed Descriptions

#### Word (W) - Fixed Word Match

Matches the exact word form (case-insensitive).

```json
{"type": "W", "value": "de"}
```

**Use case:** Fixed grammatical words in otherwise variable patterns.

**Example:** The "de" in "[NOUN] de [NOUN]" is always "de".

#### Lemma (L) - Lemma Match

Matches the base form of a word, allowing inflected variants.

```json
{"type": "L", "value": "ser"}
```

**Matches:** ser, é, são, foi, eram, etc.

**Use case:** Patterns where different inflections of the same verb/noun are acceptable.

**Example:** `[L:ser] que [VERB]` matches "é que disse", "foi que fez", etc.

#### POS (P) - Part-of-Speech Match

Matches any word with the specified UDPOS tag.

```json
{"type": "P", "value": "NOUN"}
```

**Valid POS tags:** NOUN, VERB, ADJ, ADV, DET, ADP, PRON, NUM, CONJ, PART, AUX, etc. (Universal Dependencies tagset)

**Use case:** Productive patterns where word class matters, not specific words.

**Example:** `[DET] [ADJ] [NOUN]` matches "o grande livro", "uma pequena casa", etc.

#### CE (C) - Construction Element Match

Matches tokens with the specified Phrasal CE label (from Croft's flat syntax).

```json
{"type": "C", "value": "Head"}
```

**Valid CE labels:** Head, Mod, Adm, Adp, Lnk, Clf, Idx, Conj

**Use case:** High-level patterns based on phrasal structure.

**Example:** `[Head] de [Head]` matches any two phrasal heads connected by "de".

#### Wildcard (*) - Match Any Token

Matches any single token.

```json
{"type": "*", "value": ""}
```

**Use case:** Patterns with variable elements in specific positions.

**Example:** `por [*] de` matches "por causa de", "por falta de", "por meio de", etc.

---

## Pattern Formats

The system supports two formats for backward compatibility and flexibility.

### Simple Format (Legacy)

Array of strings representing fixed words:

```json
["café", "da", "manhã"]
```

**Properties:**
- `componentFormat`: "simple"
- `anchorPosition`: 0 (first word)
- `anchorWord`: lowercased first word ("café")

**Database representation:**
```json
{
  "components": ["café", "da", "manhã"],
  "componentFormat": "simple"
}
```

### Extended Format (New)

Array of objects with type and value:

```json
[
  {"type": "P", "value": "NOUN"},
  {"type": "W", "value": "de"},
  {"type": "P", "value": "NOUN"}
]
```

**Properties:**
- `componentFormat`: "extended"
- `anchorPosition`: Position of first fixed word (or null if fully variable)
- `anchorWord`: The fixed word used for indexing (or null if fully variable)

**Database representation:**
```json
{
  "components": "[{\"type\":\"P\",\"value\":\"NOUN\"},{\"type\":\"W\",\"value\":\"de\"},{\"type\":\"P\",\"value\":\"NOUN\"}]",
  "componentFormat": "extended",
  "anchorPosition": 1,
  "anchorWord": "de"
}
```

### Format Detection

The system automatically detects format:

```php
public static function detectComponentFormat(array $components): string
{
    if (empty($components)) {
        return 'simple';
    }

    // Check first element
    return is_array($components[0]) ? 'extended' : 'simple';
}
```

---

## Detection Algorithm

The detection algorithm uses a **two-phase approach** for optimal performance.

### Two-Phase Detection

#### Phase 1: Anchored Patterns (Fast)

Patterns with at least one fixed word are indexed by that word for quick lookup.

```
For each token in sentence:
    1. Lookup simple MWEs starting with token.word (firstWord index)
    2. Lookup extended MWEs anchored by token.word (anchorWord index)
    3. For each matching MWE:
        - Calculate pattern start position using anchorPosition
        - Try to match all components from start position
        - Validate complete matches
```

**Performance:** O(n × m) where n = sentence length, m = avg MWEs per anchor word

#### Phase 2: Fully Variable Patterns (Slower)

Patterns with no fixed words (all POS/CE/wildcards) must be checked at every position.

```
For each fully variable pattern:
    For each position in sentence:
        - Try to match all components from this position
        - If complete match, validate and add to detected
```

**Performance:** O(n × p) where n = sentence length, p = number of fully variable patterns

### Anchor Calculation

The anchor is the **first fixed word** in the pattern, used for efficient indexing.

```php
public static function calculateAnchor(array $components): array
{
    foreach ($components as $position => $component) {
        if ($component['type'] === 'W') {
            return [
                'position' => $position,
                'word' => strtolower($component['value']),
            ];
        }
    }

    // No fixed word - fully variable pattern
    return ['position' => null, 'word' => null];
}
```

**Examples:**

| Pattern | Anchor Position | Anchor Word |
|---------|-----------------|-------------|
| `[NOUN] de [NOUN]` | 1 | "de" |
| `por [*] de` | 0 | "por" |
| `[DET] [ADJ] [NOUN]` | null | null (fully variable) |
| `[VERB] que [VERB]` | 1 | "que" |

### Component Matching

Type-aware matching for each component:

```php
public static function componentMatchesToken(array $component, PhrasalCENode $token): bool
{
    $type = MWEComponentType::from($component['type']);
    return $type->matchesToken($component['value'], $token);
}
```

Implemented in `MWEComponentType` enum:

```php
public function matchesToken(string $value, PhrasalCENode $token): bool
{
    return match ($this) {
        self::WORD => strtolower($token->word) === strtolower($value),
        self::LEMMA => strtolower($token->lemma) === strtolower($value),
        self::POS => $token->pos === $value,
        self::CE => $token->phrasalCE->value === $value,
        self::WILDCARD => true,
    };
}
```

### Pattern Matching Process

The `tryMatchMWE()` method handles the complete matching logic:

```php
private function tryMatchMWE(object $mwe, array $nodesByPosition, int $anchorPosition): ?array
{
    // 1. Get normalized components
    $components = MWE::getParsedComponents($mwe);

    // 2. Calculate pattern start position based on anchor
    $anchorOffset = $mwe->anchorPosition ?? 0;
    $patternStartPosition = $anchorPosition - $anchorOffset;

    // 3. Validate bounds
    if ($patternStartPosition < 0 ||
        $patternStartPosition + count($components) > count($nodesByPosition)) {
        return null;
    }

    // 4. Match each component sequentially
    $activation = 0;
    $matchedWords = [];
    foreach ($components as $i => $component) {
        $node = $nodesByPosition[$patternStartPosition + $i];
        if (MWE::componentMatchesToken($component, $node)) {
            $activation++;
            $matchedWords[] = $node->word;
        } else {
            break; // Sequence interrupted
        }
    }

    // 5. Check if fully matched
    return [
        'complete' => ($activation === count($components)),
        'phrase' => $mwe->phrase,
        'matchedWords' => $matchedWords,
        // ... other metadata
    ];
}
```

---

## Activation Mechanism (Prefix Hierarchy)

The MWE detection system implements a **prefix hierarchy activation mechanism** as described in the theoretical framework. This mechanism treats MWE recognition as analogous to **secondary structure formation** in protein folding.

### Biological Analogy

Just as certain amino acid sequences naturally form stable secondary structures (α-helix, β-sheet) during protein synthesis—**before** the full chain folds—MWEs are recognized and clustered into single phrasal CEs **before** clausal assembly.

**Example:** "café da manhã" (breakfast)

This is NOT building a clause (peptide) yet. This is recognizing that certain words naturally cluster into a stable unit BEFORE clause formation.

### Activation Threshold Model

Each MWE has a **threshold** equal to the number of components it contains. As the parser encounters each token in sequence, it increments an **activation counter**:

```
MWE Pattern: ["café", "da", "manhã"]
Threshold: 3

Token sequence matching:
café     → activation = 1/3  (prefix match, incomplete)
café da  → activation = 2/3  (prefix match, incomplete)
café da manhã → activation = 3/3  COMPLETE ✓ (threshold reached)
```

### Implementation Details

#### Candidate Tracking

The system tracks both **incomplete candidates** and **complete matches**:

```php
$candidate = [
    'idMWE' => $mwe->idMWE,
    'phrase' => 'café^da^manhã',
    'components' => ['café', 'da', 'manhã'],
    'threshold' => 3,              // Total components needed
    'activation' => 2,             // Current matches (2/3)
    'matchedWords' => ['café', 'da'],  // Words matched so far
    'complete' => false,           // Not yet complete
];
```

#### Activation Process

**Step 1: Initialize**
```php
$candidate['activation'] = 0;
$candidate['threshold'] = count($components);
```

**Step 2: Sequential Matching**
```php
foreach ($components as $i => $component) {
    $node = $nodesByPosition[$patternStartPosition + $i];

    if (MWE::componentMatchesToken($component, $node)) {
        $candidate['activation']++;  // Increment activation
        $candidate['matchedWords'][] = $node->word;
    } else {
        break;  // Sequence interrupted - stop activation
    }
}
```

**Step 3: Threshold Check**
```php
if ($candidate['activation'] >= $threshold) {
    // MWE fully activated - becomes stable unit
    $candidate['complete'] = true;
} else {
    // Prefix match only - remains candidate (may be garbage collected)
    $candidate['complete'] = false;
}
```

### Prefix Matching vs. Complete Matching

The system distinguishes between:

1. **Prefix matches (candidates):** Partial activation that doesn't reach threshold
2. **Complete matches (detected):** Full activation that reaches threshold

**Example with variable pattern:**

```
Pattern: [NOUN] de [NOUN]
Threshold: 3

Sentence: "Comprei um copo de água gelada."

Position 3: "copo"
  Component 0 [NOUN]: copo ✓ → activation = 1/3
  Component 1 [W:de]: de ✓ → activation = 2/3
  Component 2 [NOUN]: água ✓ → activation = 3/3 COMPLETE ✓

Result: MWE detected "copo de água"
```

**Interrupted sequence:**

```
Sentence: "Comprei um copo verde de plástico."

Position 3: "copo"
  Component 0 [NOUN]: copo ✓ → activation = 1/3
  Component 1 [W:de]: verde ✗ → activation stops at 1/3

Result: Prefix match only (candidate), NOT completed
        "copo" alone is not recognized as MWE
```

### Garbage Collection

Candidates that don't reach threshold are **not stored** as MWEs. This implements implicit garbage collection:

```php
// Only complete matches are assembled into nodes
if ($candidate['activation'] >= $threshold) {
    if ($this->validateMWECandidate($candidate, $allNodes)) {
        $detected[] = $candidate;  // Will be assembled
    }
} else {
    // Prefix match - tracked as candidate but not assembled
    $candidates[] = $candidate;  // For debugging only
}
```

### MWE Assembly Result

When an MWE reaches threshold and passes validation:

**Before assembly:**
```
[café:Head] [da:Adp] [manhã:Head]
Three separate phrasal CEs
```

**After assembly:**
```
[café_da_manhã:Head]
ONE phrasal CE with combined features:
  - Gender=Masc (from "café")
  - Number=Sing
  - Definite=Def (from "da")
```

The assembled MWE becomes a **stable secondary structure** that enters Stage 2 (Translation) as a single unit, not as three separate elements.

### Activation with Variable Components

Variable components follow the same activation logic:

```
Pattern: [NOUN] de [NOUN]
Threshold: 3

Matching "livro de história":
  livro [NOUN] ✓ → activation = 1/3
  de [W:de] ✓ → activation = 2/3
  história [NOUN] ✓ → activation = 3/3 COMPLETE ✓
```

The activation counter increments when `componentMatchesToken()` returns `true` for each type:

- **Word (W):** `token.word === value` (case-insensitive)
- **Lemma (L):** `token.lemma === value` (case-insensitive)
- **POS (P):** `token.pos === value`
- **CE (C):** `token.phrasalCE === value`
- **Wildcard (*):** Always `true`

### Theoretical Grounding

This activation mechanism directly implements the **secondary structure precursor** concept from the theoretical framework:

> "MWEs immediately cluster into single phrasal Head CEs before linking with other elements—just as certain amino acid sequences naturally form secondary structures (α-helix, β-sheet) as soon as they're synthesized, before the full chain folds."

The **prefix hierarchy** ensures that:
1. MWE detection is **incremental** (builds up component by component)
2. MWE formation is **deterministic** (reaches threshold or doesn't)
3. MWE assembly is **atomic** (all components or none)
4. MWE becomes **stable unit** ready for clause-level assembly

This positions MWEs correctly as **Stage 1 (Transcription) outputs** that become inputs to **Stage 2 (Translation)**, maintaining the three-stage architecture's biological parallels.

---

## Database Schema

### Table: `parser_mwe`

Extended schema to support variable components:

```sql
CREATE TABLE parser_mwe (
    idMWE INT PRIMARY KEY AUTO_INCREMENT,
    idGrammarGraph INT NOT NULL,
    phrase VARCHAR(255) NOT NULL,
    components LONGTEXT NOT NULL,  -- JSON array
    componentsLemma LONGTEXT,      -- JSON array (legacy)

    -- NEW: Variable component support
    componentFormat ENUM('simple', 'extended') DEFAULT 'simple',
    anchorPosition TINYINT NULL,   -- Position of first fixed word (0-indexed)
    anchorWord VARCHAR(100) NULL,  -- Lowercased anchor word for indexing

    -- Semantic classification
    semanticType ENUM('E','V','A','F','R',  -- Legacy v1 types
                      'Head','Mod','Adm','Adp','Lnk','Clf','Idx','Conj') -- v2 Phrasal CEs
                      NOT NULL,

    -- Metadata
    length INT GENERATED ALWAYS AS (JSON_LENGTH(components)) STORED,
    firstWord VARCHAR(100) GENERATED ALWAYS AS
        (LOWER(JSON_UNQUOTE(JSON_EXTRACT(components, '$[0]')))) STORED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Indexes
    INDEX idx_mwe_first_word (idGrammarGraph, firstWord),
    INDEX idx_mwe_anchor (idGrammarGraph, anchorWord),  -- NEW
    INDEX idx_mwe_phrase (idGrammarGraph, phrase),

    FOREIGN KEY (idGrammarGraph) REFERENCES parser_grammar_graph(idGrammarGraph)
        ON DELETE CASCADE
);
```

### Key Columns

**`componentFormat`**
- Values: `'simple'` or `'extended'`
- Determines how to parse the `components` JSON
- Default: `'simple'` (backward compatibility)

**`anchorPosition`**
- Position (0-indexed) of the first fixed word in the pattern
- `NULL` for fully variable patterns
- Used to calculate pattern start position during matching
- Example: For `[NOUN] de [NOUN]`, anchorPosition = 1

**`anchorWord`**
- Lowercased fixed word used for database indexing
- `NULL` for fully variable patterns
- Enables fast Phase 1 lookup
- Example: For `[NOUN] de [NOUN]`, anchorWord = "de"

**`semanticType`**
- Expanded enum including both legacy (E/V/A/F/R) and v2 Phrasal CE values
- Maps to Croft's Phrasal Construction Elements
- Most MWEs are `'Head'` (noun-like) or `'Adp'` (adposition-like)

### Indexes

**`idx_mwe_first_word`** (existing)
- Used for simple format MWEs
- Lookup: `WHERE idGrammarGraph = ? AND firstWord = ?`

**`idx_mwe_anchor`** (new)
- Used for extended format MWEs with anchor words
- Lookup: `WHERE idGrammarGraph = ? AND anchorWord = ?`

**`idx_mwe_phrase`** (existing)
- Used for duplicate detection during import
- Lookup: `WHERE idGrammarGraph = ? AND phrase = ?`

---

## Implementation Guide

### Creating Variable Patterns

#### Option 1: Import from JSON

Use the `ImportVariablePatternsCommand` to bulk-import patterns:

```bash
php artisan parser:import-variable-patterns patterns.json --grammar=1
```

**JSON format:**

```json
{
  "patterns": [
    {
      "phrase": "[NOUN] de [NOUN]",
      "components": [
        {"type": "P", "value": "NOUN"},
        {"type": "W", "value": "de"},
        {"type": "P", "value": "NOUN"}
      ],
      "semanticType": "Head",
      "description": "Noun + de + Noun pattern"
    },
    {
      "phrase": "por [*] de",
      "components": [
        {"type": "W", "value": "por"},
        {"type": "*", "value": ""},
        {"type": "W", "value": "de"}
      ],
      "semanticType": "Adp",
      "description": "Function word pattern with wildcard"
    }
  ]
}
```

**Command options:**

- `--dry-run`: Show what would be imported without making changes
- `--update`: Update existing patterns instead of skipping duplicates
- `-v`: Verbose output showing each pattern processed

#### Option 2: Programmatic Creation

Use the `MWE::createExtended()` method:

```php
use App\Repositories\Parser\MWE;

$idMWE = MWE::createExtended([
    'idGrammarGraph' => 1,
    'phrase' => '[VERB] que [VERB]',
    'components' => [
        ['type' => 'P', 'value' => 'VERB'],
        ['type' => 'W', 'value' => 'que'],
        ['type' => 'P', 'value' => 'VERB'],
    ],
    'semanticType' => 'Head',
]);
```

The method automatically:
- Validates components using `ValidMWEComponents` rule
- Detects format (`extended`)
- Calculates anchor position and word
- Stores to database

### Repository Methods

#### Lookup Methods

```php
// Get simple-format MWEs starting with a word
$mwes = MWE::getStartingWith($idGrammarGraph, 'café');

// Get extended-format MWEs anchored by a word
$mwes = MWE::getByAnchorWord($idGrammarGraph, 'de');

// Get fully variable patterns (no anchor)
$mwes = MWE::getFullyVariable($idGrammarGraph);

// Get all MWEs for a grammar
$mwes = MWE::listByGrammar($idGrammarGraph);

// Get specific MWE by phrase
$mwe = MWE::getByPhrase($idGrammarGraph, '[NOUN] de [NOUN]');
```

#### Component Processing

```php
// Get normalized components (works for both formats)
$components = MWE::getParsedComponents($mwe);
// Returns: [['type' => 'P', 'value' => 'NOUN'], ...]

// Check if component matches token
$matches = MWE::componentMatchesToken($component, $token);

// Calculate anchor from components
$anchor = MWE::calculateAnchor($components);
// Returns: ['position' => 1, 'word' => 'de']

// Detect format
$format = MWE::detectComponentFormat($components);
// Returns: 'simple' or 'extended'
```

### Validation

The `ValidMWEComponents` rule validates component arrays:

```php
use App\Rules\ValidMWEComponents;

$validator = Validator::make(
    ['components' => $components],
    ['components' => ['required', 'array', new ValidMWEComponents]]
);
```

**Validation rules:**

1. Must be an array with at least 2 components
2. Format consistency (all strings OR all arrays)
3. Each extended component must have `type` and `value` (except wildcards)
4. Valid type codes: W, L, P, C, *
5. POS values must be valid UDPOS tags
6. CE values must be valid PhrasalCE labels

### Using in Parser Commands

All parser commands support variable MWEs via the `--grammar` option:

```bash
# Test transcription with MWE detection
php artisan parser:test-transcription sentences.txt --grammar=1

# Test translation with MWE detection
php artisan parser:test-translation sentences.txt --grammar=1

# Register CE annotations with MWE detection
php artisan parser:register-ce-annotations 123 --grammar=1
```

The detection is automatic - both simple and extended patterns are processed transparently.

---

## Examples

### Example 1: Noun + Preposition + Noun

**Pattern:** Portuguese compound nouns

```json
{
  "phrase": "[NOUN] de [NOUN]",
  "components": [
    {"type": "P", "value": "NOUN"},
    {"type": "W", "value": "de"},
    {"type": "P", "value": "NOUN"}
  ],
  "semanticType": "Head"
}
```

**Matches:**
- copo de água (glass of water)
- bolo de chocolate (chocolate cake)
- sala de aula (classroom)
- fim de semana (weekend)

**Anchor:** position=1, word="de"

**Detection:** Phase 1 (anchored by "de")

### Example 2: Fully Variable Noun Phrase

**Pattern:** Determiner + Adjective + Noun

```json
{
  "phrase": "[DET] [ADJ] [NOUN]",
  "components": [
    {"type": "P", "value": "DET"},
    {"type": "P", "value": "ADJ"},
    {"type": "P", "value": "NOUN"}
  ],
  "semanticType": "Head"
}
```

**Matches:**
- o grande livro (the big book)
- uma pequena casa (a small house)
- os bons alunos (the good students)

**Anchor:** position=null, word=null (fully variable)

**Detection:** Phase 2 (checked at every position)

### Example 3: Wildcard Pattern

**Pattern:** "por X de" construction

```json
{
  "phrase": "por [*] de",
  "components": [
    {"type": "W", "value": "por"},
    {"type": "*", "value": ""},
    {"type": "W", "value": "de"}
  ],
  "semanticType": "Adp"
}
```

**Matches:**
- por causa de (because of)
- por meio de (by means of)
- por falta de (for lack of)
- por volta de (around)

**Anchor:** position=0, word="por"

**Detection:** Phase 1 (anchored by "por")

### Example 4: CE-Based Pattern

**Pattern:** Two phrasal heads connected by "de"

```json
{
  "phrase": "[Head] de [Head]",
  "components": [
    {"type": "C", "value": "Head"},
    {"type": "W", "value": "de"},
    {"type": "C", "value": "Head"}
  ],
  "semanticType": "Head"
}
```

**Matches any two phrasal heads connected by "de":**
- café da manhã (already a Head MWE + "da" + Head)
- cidade de São Paulo (city of São Paulo)

**Anchor:** position=1, word="de"

**Detection:** Phase 1 (anchored by "de")

### Example 5: Lemma-Based Pattern

**Pattern:** "ser que VERB" construction

```json
{
  "phrase": "[ser] que [VERB]",
  "components": [
    {"type": "L", "value": "ser"},
    {"type": "W", "value": "que"},
    {"type": "P", "value": "VERB"}
  ],
  "semanticType": "Head"
}
```

**Matches:**
- é que disse (it is that [he] said)
- foi que fez (it was that [he] did)
- era que pensava (it was that [he] thought)

**Anchor:** position=1, word="que"

**Detection:** Phase 1 (anchored by "que")

---

## API Reference

### MWE Repository Methods

#### `MWE::createExtended(array $data): int`

Create a new MWE with extended format support.

**Parameters:**
```php
[
    'idGrammarGraph' => int,
    'phrase' => string,
    'components' => array,
    'semanticType' => string,
]
```

**Returns:** `idMWE`

**Example:**
```php
$id = MWE::createExtended([
    'idGrammarGraph' => 1,
    'phrase' => '[NOUN] de [NOUN]',
    'components' => [
        ['type' => 'P', 'value' => 'NOUN'],
        ['type' => 'W', 'value' => 'de'],
        ['type' => 'P', 'value' => 'NOUN'],
    ],
    'semanticType' => 'Head',
]);
```

#### `MWE::getParsedComponents(object $mwe): array`

Get normalized components array, handling both simple and extended formats.

**Parameters:** MWE object from database

**Returns:** Array of components in extended format

**Example:**
```php
$components = MWE::getParsedComponents($mwe);
// [
//     ['type' => 'P', 'value' => 'NOUN'],
//     ['type' => 'W', 'value' => 'de'],
//     ['type' => 'P', 'value' => 'NOUN'],
// ]
```

#### `MWE::componentMatchesToken(array $component, PhrasalCENode $token): bool`

Check if a component matches a token.

**Parameters:**
- `$component`: Component array with 'type' and 'value'
- `$token`: PhrasalCENode to match against

**Returns:** `true` if matches, `false` otherwise

**Example:**
```php
$component = ['type' => 'P', 'value' => 'NOUN'];
$matches = MWE::componentMatchesToken($component, $token);
```

#### `MWE::getByAnchorWord(int $idGrammarGraph, string $anchorWord): array`

Get extended-format MWEs anchored by a specific word.

**Returns:** Array of MWE objects

#### `MWE::getFullyVariable(int $idGrammarGraph): array`

Get patterns with no fixed word anchor.

**Returns:** Array of MWE objects

#### `MWE::calculateAnchor(array $components): array`

Calculate anchor position and word from components.

**Returns:**
```php
[
    'position' => int|null,  // 0-indexed position or null
    'word' => string|null,   // Lowercased word or null
]
```

#### `MWE::detectComponentFormat(array $components): string`

Detect format from components array.

**Returns:** `'simple'` or `'extended'`

### MWEComponentType Enum

```php
enum MWEComponentType: string
{
    case WORD = 'W';
    case LEMMA = 'L';
    case POS = 'P';
    case CE = 'C';
    case WILDCARD = '*';

    public function matchesToken(string $value, PhrasalCENode $token): bool;
    public function isFixed(): bool;
    public function validateValue(string $value): bool;
    public static function validPOSTags(): array;
    public static function validCELabels(): array;
}
```

### ValidMWEComponents Rule

Validation rule for component arrays.

**Usage:**
```php
use App\Rules\ValidMWEComponents;

$rules = [
    'components' => ['required', 'array', new ValidMWEComponents],
];
```

**Validates:**
- Minimum 2 components
- Format consistency
- Valid type codes
- Non-empty values (except wildcards)
- Valid POS tags and CE labels

---

## Testing

### Unit Tests

Test component matching logic:

```bash
php artisan test --filter=MWEComponentTypeTest
```

### Feature Tests

Test MWE detection in real sentences:

```bash
# Create test file
echo "Tomei café da manhã." > test.txt
echo "Comprei um copo de água." >> test.txt
echo "Vi o grande livro." >> test.txt

# Test with simple patterns
php artisan parser:test-transcription test.txt --grammar=1 -v

# Import variable patterns
php artisan parser:import-variable-patterns patterns.json --grammar=1

# Test with variable patterns
php artisan parser:test-transcription test.txt --grammar=1 -v
```

### Sample Test Patterns

File: `test_patterns.json`

```json
{
  "patterns": [
    {
      "phrase": "[NOUN] de [NOUN]",
      "components": [
        {"type": "P", "value": "NOUN"},
        {"type": "W", "value": "de"},
        {"type": "P", "value": "NOUN"}
      ],
      "semanticType": "Head",
      "description": "Noun + de + Noun"
    },
    {
      "phrase": "[DET] [ADJ] [NOUN]",
      "components": [
        {"type": "P", "value": "DET"},
        {"type": "P", "value": "ADJ"},
        {"type": "P", "value": "NOUN"}
      ],
      "semanticType": "Head",
      "description": "Determiner + Adjective + Noun"
    }
  ]
}
```

### Performance Testing

Test with large pattern sets:

```bash
# Generate test sentences
php artisan parser:test-transcription large_corpus.txt \
    --grammar=1 \
    --limit=1000 \
    --format=json \
    --output=results.json

# Analyze statistics
cat results.json | jq '.statistics'
```

### Validation Testing

Test pattern validation:

```bash
# Test with invalid patterns (should fail)
php artisan parser:import-variable-patterns invalid.json --grammar=1 --dry-run
```

---

## Best Practices

### 1. Pattern Design

**DO:**
- Use the most specific component type that captures the pattern
- Include at least one fixed word for anchoring when possible
- Use meaningful phrase names that reflect the pattern
- Add descriptions for documentation

**DON'T:**
- Create overly broad patterns that match unintended sequences
- Use wildcards without bounding fixed words
- Duplicate patterns with different semanticTypes

### 2. Performance Optimization

**Anchored vs. Fully Variable:**
- Prefer anchored patterns (with at least one fixed word)
- Use fully variable patterns only when linguistically necessary
- Monitor Phase 2 detection cost with many fully variable patterns

**Indexing:**
- Choose anchor words that are:
  - Grammatically stable (prepositions, conjunctions)
  - Less ambiguous
  - Relatively frequent (but not too frequent like "de")

### 3. Semantic Types

**Use Phrasal CE values (v2) for new patterns:**
- `Head`: Noun-like compound expressions
- `Mod`: Modifier-like expressions
- `Adp`: Adposition-like function words
- `Lnk`: Linking particles

**Legacy values (E/V/A/F) are maintained for backward compatibility.**

### 4. Testing Strategy

1. **Unit test** component matching logic
2. **Feature test** with real sentences
3. **Performance test** with large corpora
4. **Validation test** edge cases and error conditions

### 5. Documentation

Always document:
- **Pattern purpose:** What linguistic phenomenon it captures
- **Examples:** At least 3-5 instances that match
- **Limitations:** Cases it should NOT match
- **Version:** When pattern was added/modified

---

## Troubleshooting

### Pattern Not Matching

**Check:**

1. **Component types:** Are you using the correct type codes (W/L/P/C/*)?
2. **POS tags:** UDPOS tags are case-sensitive (NOUN, not Noun)
3. **Anchor calculation:** Run with `-v` to see which patterns are being tried
4. **Format:** Ensure components are arrays with 'type' and 'value'

**Debug:**
```bash
php artisan parser:test-transcription test.txt --grammar=1 -v --show-mwe-candidates
```

### Performance Issues

**Symptoms:** Slow parsing with many patterns

**Solutions:**

1. Check number of fully variable patterns:
   ```sql
   SELECT COUNT(*) FROM parser_mwe
   WHERE idGrammarGraph = 1 AND anchorWord IS NULL;
   ```

2. Add anchors to fully variable patterns if possible

3. Profile detection time:
   ```bash
   php artisan parser:test-transcription large.txt --grammar=1 --limit=100
   ```

### Validation Errors

**Common errors:**

1. **Invalid POS tag:** Check against Universal Dependencies tagset
2. **Invalid CE label:** Check against PhrasalCE enum
3. **Empty value:** Components must have non-empty values (except wildcards)
4. **Missing type:** All extended components need 'type' field

**Solution:** Use `--dry-run` to validate before importing:
```bash
php artisan parser:import-variable-patterns patterns.json --grammar=1 --dry-run -v
```

---

## Related Documentation

- [MIGRATION_GUIDE_v2.md](MIGRATION_GUIDE_v2.md) - Framework migration from E/V/A/F to CE labels
- [REVISED_transdisciplinary_framework.md](REVISED_transdisciplinary_framework.md) - Theoretical foundation
- [Universal Dependencies](https://universaldependencies.org/) - POS tagset and features
- [Croft's Flat Syntax](../flat_syntax/) - Construction Element theory

---

## Changelog

### Version 2.0 (December 2024)

- Initial implementation of variable MWE patterns
- Support for POS, CE, Lemma, and Wildcard component types
- Two-phase detection algorithm
- Anchor-based indexing for performance
- Import command for JSON pattern files
- Backward compatibility with simple format
- Extended semanticType enum with Phrasal CE values

---

**Document Authors:** Claude Code with FrameNet Brasil team
**Maintainers:** FrameNet Brasil team
**Last Updated:** December 9, 2024
