# Working with MWE Patterns and BNF Constructions

## A Practical Guide for Linguists and Developers

**Version:** 3.0
**Date:** December 2024

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Choosing the Right Method](#2-choosing-the-right-method)
3. [MWE Patterns (V2)](#3-mwe-patterns-v2)
4. [BNF Constructions (V3)](#4-bnf-constructions-v3)
5. [Database Storage](#5-database-storage)
6. [User Interface](#6-user-interface)
7. [Import/Export](#7-importexport)
8. [Examples and Templates](#8-examples-and-templates)
9. [Troubleshooting](#9-troubleshooting)

---

## 1. Introduction

Parser V3 provides two complementary methods for defining multi-word expressions and constructions:

| Method | MWE Patterns | BNF Constructions |
|--------|--------------|-------------------|
| **Purpose** | Fixed and variable sequences | Complex recursive patterns |
| **Notation** | JSON components | CFG-style notation |
| **Storage** | `parser_mwe` table | `parser_constructions` table |
| **Performance** | O(n) with indexing | O(n×states) with caching |
| **Best for** | Scale (1000s of patterns) | Complexity (optionals, alternatives) |

### When to Use Each

**Use MWE Patterns when:**
- The pattern is a fixed sequence (e.g., "café da manhã")
- The pattern has variable slots but fixed length (e.g., "[NOUN] de [NOUN]")
- You need to manage many similar patterns
- Performance at scale is critical

**Use BNF Constructions when:**
- The pattern has optional elements (e.g., "[very] {ADJ}")
- The pattern has alternatives (e.g., "(e | ou)")
- The pattern has repetition (e.g., "{ADJ}+")
- You need semantic value calculation (e.g., numbers → numeric value)

---

## 2. Choosing the Right Method

### Decision Flowchart

```
Does the pattern have...
│
├─ Optional elements [...]?
│   └─ YES → Use BNF Construction
│
├─ Alternatives (A | B | C)?
│   └─ YES → Use BNF Construction
│
├─ Repetition (A+ or A*)?
│   └─ YES → Use BNF Construction
│
├─ Semantic value calculation?
│   └─ YES → Use BNF Construction
│
├─ Fixed length, possibly with variable slots?
│   └─ YES → Use MWE Pattern
│
└─ Just a fixed word sequence?
    └─ YES → Use Simple MWE Pattern
```

### Conversion Examples

| Expression | Naive Approach | Better Approach |
|------------|----------------|-----------------|
| "café da manhã" | Simple MWE ✓ | Simple MWE ✓ |
| "[NOUN] de [NOUN]" | Variable MWE ✓ | Variable MWE ✓ |
| "por (causa \| meio \| falta) de" | 3 separate MWEs | 1 BNF Construction ✓ |
| "dois mil e quinhentos" | Many MWE combinations | 1 BNF Construction ✓ |
| "[very] {ADJ} {NOUN}" | Can't express | BNF Construction ✓ |

---

## 3. MWE Patterns (V2)

### 3.1 Simple Format (Fixed Sequences)

**Definition:**
```json
{
  "phrase": "café da manhã",
  "components": ["café", "da", "manhã"],
  "semanticType": "Head"
}
```

**Storage:**
- `componentFormat`: "simple"
- `anchorPosition`: 0 (first word)
- `anchorWord`: "café"

**Usage:**
- Import via JSON file
- Create via admin interface
- Edit individual components

### 3.2 Extended Format (Variable Slots)

**Definition:**
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

**Component Types:**

| Type | Code | Matches | Example |
|------|------|---------|---------|
| Word | `W` | Exact word (case-insensitive) | `{"type": "W", "value": "de"}` |
| Lemma | `L` | Lemma form | `{"type": "L", "value": "ser"}` |
| POS | `P` | Part-of-speech tag | `{"type": "P", "value": "NOUN"}` |
| CE | `C` | Phrasal CE label | `{"type": "C", "value": "Head"}` |
| Wildcard | `*` | Any token | `{"type": "*", "value": ""}` |

**Valid POS Tags (Universal Dependencies):**
```
NOUN  PROPN  PRON  VERB  ADJ  ADV  ADP  DET  NUM
AUX   CCONJ  SCONJ PART  INTJ SYM  PUNCT X
```

**Valid CE Labels (Croft's Flat Syntax):**
```
Head  Mod  Adm  Adp  Lnk  Clf  Idx  Conj
```

### 3.3 Anchor Strategy

The **anchor** is the first fixed word in the pattern, used for database indexing:

| Pattern | Anchor Position | Anchor Word | Detection Phase |
|---------|-----------------|-------------|-----------------|
| `café da manhã` | 0 | "café" | Phase 1 (fast) |
| `[NOUN] de [NOUN]` | 1 | "de" | Phase 1 (fast) |
| `[DET] [ADJ] [NOUN]` | null | null | Phase 2 (slower) |

**Best Practice:** Include at least one fixed word (`W` type) for fast indexing.

### 3.4 Creating MWE Patterns

**Via Artisan Command:**
```bash
php artisan parser:import-variable-patterns patterns.json --grammar=1
```

**Via API:**
```php
use App\Repositories\Parser\MWE;

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

---

## 4. BNF Constructions (V3)

### 4.1 Pattern Notation

**Elements:**

| Notation | Name | Description | Example |
|----------|------|-------------|---------|
| `word` | Literal | Fixed word | `mil`, `de` |
| `{POS}` | POS Slot | Match POS tag | `{NOUN}`, `{VERB}` |
| `{POS:constraint}` | Constrained Slot | POS with feature | `{VERB:inf}` |
| `{*}` | Wildcard | Any token | `{*}` |
| `[element]` | Optional | 0 or 1 times | `[e]` |
| `(A \| B)` | Alternatives | One of | `(e \| ou)` |
| `A+` | One or More | 1+ times | `{ADJ}+` |
| `A*` | Zero or More | 0+ times | `{ADV}*` |
| `(A B)` | Group | Sequence | `(de a)` |

### 4.2 Pattern Examples

**Simple Sequence:**
```
a fim de
```

**With POS Slot:**
```
{NOUN} de {NOUN}
```

**With Optional:**
```
{NUM} [e] {NUM}
```
Matches: "dois três" and "dois e três"

**With Alternatives:**
```
(por causa | por meio | apesar) de
```
Matches: "por causa de", "por meio de", "apesar de"

**With Repetition:**
```
{ADV}* {ADJ}+ {NOUN}
```
Matches: "big dog", "very big dog", "very very big large dog"

**Complex Number Pattern:**
```
[{NUM_UNIT}] mil [,] [{NUM_HUNDRED}] [e {NUM_TEN}] [e {NUM_UNIT}]
```

### 4.3 Constraints

Constraints filter slots by morphological features:

| Constraint | Description | Example |
|------------|-------------|---------|
| `{VERB:inf}` | Infinitive | "comprar" |
| `{VERB:fin}` | Finite | "comprou" |
| `{VERB:part}` | Participle | "comprado" |
| `{VERB:ger}` | Gerund | "comprando" |
| `{NOUN:sing}` | Singular | "livro" |
| `{NOUN:plur}` | Plural | "livros" |

### 4.4 Semantic Actions

BNF constructions can include semantic interpretation rules:

```json
{
  "name": "portuguese_cardinal",
  "pattern": "[{NUM_UNIT}] mil [{NUM_HUNDRED}] [e {NUM_UNIT}]",
  "semantics": {
    "type": "number",
    "calculation": {
      "slots": {
        "0": {"multiply": 1000, "default": 1},
        "2": {"add": true, "default": 0},
        "4": {"add": true, "default": 0}
      }
    }
  }
}
```

**Available Actions:**
- `multiply`: Multiply slot value
- `add`: Add slot value
- `default`: Use if slot not matched
- `lookup`: Use lookup table
- `format`: Output format string

### 4.5 Creating BNF Constructions

**Via Artisan Command:**
```bash
php artisan parser:import-constructions constructions.json --grammar=1
```

**Via API:**
```php
use App\Services\Parser\ConstructionService;

$service = app(ConstructionService::class);
$id = $service->compileAndStore(
    idGrammarGraph: 1,
    name: 'portuguese_cardinal',
    pattern: '[{NUM_UNIT}] mil [{NUM_HUNDRED}] [e {NUM_UNIT}]',
    metadata: [
        'semanticType' => 'Head',
        'description' => 'Portuguese cardinal numbers (thousands)',
        'semantics' => [
            'type' => 'number',
            'calculation' => [...]
        ]
    ]
);
```

---

## 5. Database Storage

### 5.1 MWE Patterns Table (`parser_mwe`)

| Column | Type | Description |
|--------|------|-------------|
| `idMWE` | INT | Primary key |
| `idGrammarGraph` | INT | Grammar reference |
| `phrase` | VARCHAR(255) | Display name |
| `components` | JSON | Component array |
| `componentFormat` | ENUM | "simple" or "extended" |
| `anchorPosition` | TINYINT | Position of anchor word |
| `anchorWord` | VARCHAR(100) | Anchor word (lowercase) |
| `semanticType` | ENUM | PhrasalCE value |
| `length` | INT | Generated: component count |
| `firstWord` | VARCHAR(100) | Generated: first word |

**Indexes:**
- `idx_mwe_first_word`: For simple format lookup
- `idx_mwe_anchor`: For extended format lookup
- `idx_mwe_phrase`: For duplicate detection

### 5.2 Constructions Table (`parser_constructions`)

| Column | Type | Description |
|--------|------|-------------|
| `idConstruction` | INT | Primary key |
| `idGrammarGraph` | INT | Grammar reference |
| `name` | VARCHAR(100) | Unique identifier |
| `pattern` | TEXT | BNF pattern string |
| `description` | TEXT | Human description |
| `compiledGraph` | JSON | Pre-compiled graph |
| `semanticType` | VARCHAR(20) | PhrasalCE value |
| `semantics` | JSON | Interpretation rules |
| `priority` | TINYINT | Matching order |
| `enabled` | BOOLEAN | Active flag |

**Indexes:**
- `idx_construction_name`: Unique name lookup
- `idx_construction_type`: By semantic type
- `idx_construction_priority`: By priority (DESC)

### 5.3 Compiled Graph Structure

```json
{
  "nodes": {
    "n0": {"type": "START"},
    "n1": {"type": "SLOT", "pos": "NUM_UNIT"},
    "n2": {"type": "LITERAL", "value": "mil"},
    "n3": {"type": "END"}
  },
  "edges": [
    {"from": "n0", "to": "n1"},
    {"from": "n0", "to": "n2", "bypass": true},
    {"from": "n1", "to": "n2"},
    {"from": "n2", "to": "n3"}
  ],
  "checksum": "abc123..."
}
```

---

## 6. User Interface

### 6.1 MWE Management

**List View:**
- Filter by grammar, format, semantic type
- Search by phrase
- Sort by phrase, length, type

**Edit View:**
- Phrase input
- Component builder (drag-and-drop)
- Type selector per component
- Preview matching

### 6.2 Construction Management

**List View:**
- Filter by grammar, enabled, type
- Search by name, pattern
- Priority ordering

**Edit View:**
- Name input
- Pattern editor with syntax highlighting
- Graph visualization (DOT/Graphviz)
- Semantic rules editor (JSON)
- Test against sample sentences

### 6.3 Testing Interface

**Test Panel:**
1. Enter sentence
2. Select methods to test (MWE, BNF, both)
3. View matches with:
   - Matched span
   - Component/slot values
   - Semantic result
   - Match source

---

## 7. Import/Export

### 7.1 MWE JSON Format

```json
{
  "grammar": 1,
  "version": "2.0",
  "patterns": [
    {
      "phrase": "café da manhã",
      "components": ["café", "da", "manhã"],
      "semanticType": "Head",
      "description": "Breakfast"
    },
    {
      "phrase": "[NOUN] de [NOUN]",
      "components": [
        {"type": "P", "value": "NOUN"},
        {"type": "W", "value": "de"},
        {"type": "P", "value": "NOUN"}
      ],
      "semanticType": "Head",
      "description": "Noun compound with de"
    }
  ]
}
```

### 7.2 Construction JSON Format

```json
{
  "grammar": 1,
  "version": "3.0",
  "constructions": [
    {
      "name": "portuguese_cardinal_thousands",
      "pattern": "[{NUM_UNIT}] mil [{NUM_HUNDRED}] [e {NUM_UNIT}]",
      "description": "Portuguese cardinal numbers in thousands",
      "semanticType": "Head",
      "priority": 10,
      "semantics": {
        "type": "number",
        "calculation": {
          "method": "portuguese_number",
          "slots": {
            "thousands_prefix": {"index": 0, "multiply": 1000, "default": 1},
            "hundreds": {"index": 2, "add": true, "default": 0},
            "units": {"index": 4, "add": true, "default": 0}
          }
        }
      }
    }
  ]
}
```

### 7.3 Import Commands

```bash
# Import MWE patterns
php artisan parser:import-variable-patterns patterns.json \
    --grammar=1 \
    --dry-run \
    --update

# Import constructions
php artisan parser:import-constructions constructions.json \
    --grammar=1 \
    --dry-run \
    --recompile

# Export all patterns for a grammar
php artisan parser:export-patterns --grammar=1 --output=export.json
```

---

## 8. Examples and Templates

### 8.1 Common MWE Patterns (Portuguese)

**Compound Nouns:**
```json
[
  {"phrase": "[NOUN] de [NOUN]", "semanticType": "Head"},
  {"phrase": "[NOUN] da [NOUN]", "semanticType": "Head"},
  {"phrase": "[NOUN] do [NOUN]", "semanticType": "Head"}
]
```

**Complex Prepositions:**
```json
[
  {"phrase": "por causa de", "semanticType": "Adp"},
  {"phrase": "por meio de", "semanticType": "Adp"},
  {"phrase": "a fim de", "semanticType": "Lnk"},
  {"phrase": "apesar de", "semanticType": "Adp"}
]
```

**Verb Phrases:**
```json
[
  {"phrase": "[L:ser] que [VERB]", "semanticType": "Head"},
  {"phrase": "[L:ter] que [VERB:inf]", "semanticType": "Head"}
]
```

### 8.2 Common BNF Constructions

**Portuguese Cardinal Numbers (complete):**
```
Pattern:
  [{UNITS}] (milhão | milhões) [,]
  [{UNITS}] mil [,]
  [{HUNDREDS}]
  [e {TENS}]
  [e {UNITS}]

Where:
  UNITS = um | dois | três | quatro | cinco | seis | sete | oito | nove
  TENS = dez | vinte | trinta | quarenta | cinquenta | sessenta | setenta | oitenta | noventa
  HUNDREDS = cem | cento | duzentos | trezentos | ...
```

**Portuguese Dates:**
```
{NUM} de (janeiro | fevereiro | março | abril | maio | junho | julho | agosto | setembro | outubro | novembro | dezembro) [de {NUM}]
```

**Time Expressions:**
```
{NUM} [e] [{NUM}] [(hora | horas | h)]
```

**Addresses:**
```
(Rua | Avenida | Av | Praça | Alameda) {PROPN}+ [, {NUM}]
```

### 8.3 Template: Creating a New Number System

```json
{
  "name": "my_language_cardinal",
  "pattern": "YOUR_PATTERN_HERE",
  "semanticType": "Head",
  "semantics": {
    "type": "number",
    "outputFeatures": {
      "NumType": "Card"
    },
    "calculation": {
      "method": "custom",
      "slots": {
        "slot_name": {
          "index": 0,
          "operation": "multiply|add|lookup",
          "operand": 1000,
          "default": 0,
          "lookupTable": "table_name"
        }
      },
      "formula": "slot1 * 1000 + slot2"
    }
  }
}
```

---

## 9. Troubleshooting

### 9.1 MWE Pattern Issues

**Pattern not matching:**
1. Check component types are correct (W, L, P, C, *)
2. Verify POS tags match UD tagset (case-sensitive)
3. Test with verbose mode: `--show-mwe-candidates`
4. Check anchor word exists in sentence

**Performance issues:**
1. Count fully variable patterns (no anchor)
2. Add anchor words where possible
3. Check index usage in database

**Duplicate detection:**
1. Phrase must be unique per grammar
2. Use `--update` flag to modify existing

### 9.2 BNF Construction Issues

**Pattern compilation error:**
1. Check bracket matching `[]`, `()`, `{}`
2. Verify POS tags are valid
3. Use pattern validator: `php artisan parser:validate-pattern`

**Pattern not matching:**
1. View compiled graph: `php artisan parser:show-graph --construction=name`
2. Check backtracking limit not exceeded
3. Test with simpler subpattern first

**Semantic calculation wrong:**
1. Verify slot indices match pattern
2. Check default values
3. Test calculation formula separately

### 9.3 Integration Issues

**Overlapping matches:**
1. Adjust priorities (higher = processed first)
2. Check enabled flags
3. Review match resolution rules

**Missing matches:**
1. Verify grammar ID is correct
2. Check enabled flag for constructions
3. Review processing order (BNF → Variable MWE → Simple MWE)

### 9.4 Debug Commands

```bash
# Test MWE detection
php artisan parser:test-transcription sentences.txt --grammar=1 -v --show-mwe-candidates

# Test construction matching
php artisan parser:test-construction "dois mil e quinhentos" --grammar=1 -v

# Show compiled graph
php artisan parser:show-graph --construction=portuguese_cardinal --format=dot

# Validate pattern syntax
php artisan parser:validate-pattern "[{NUM}] mil [{NUM}]"

# List all patterns for grammar
php artisan parser:list-patterns --grammar=1 --format=table
```

---

## Quick Reference Card

### MWE Component Types
| Type | Code | Example |
|------|------|---------|
| Word | `W` | `{"type":"W","value":"de"}` |
| Lemma | `L` | `{"type":"L","value":"ser"}` |
| POS | `P` | `{"type":"P","value":"NOUN"}` |
| CE | `C` | `{"type":"C","value":"Head"}` |
| Wildcard | `*` | `{"type":"*","value":""}` |

### BNF Notation
| Notation | Meaning |
|----------|---------|
| `word` | Literal word |
| `{POS}` | POS slot |
| `{POS:constraint}` | Constrained slot |
| `[element]` | Optional (0-1) |
| `(A \| B)` | Alternative |
| `A+` | One or more |
| `A*` | Zero or more |

### Semantic Types (PhrasalCE)
| Type | Description |
|------|-------------|
| `Head` | Core element (nouns, verbs) |
| `Mod` | Modifier (adjectives, articles) |
| `Adp` | Adposition (prepositions) |
| `Lnk` | Linker (conjunctions, particles) |
| `Conj` | Coordinator |

---

**Document Authors:** Claude Code with FrameNet Brasil team
**Last Updated:** December 2024
