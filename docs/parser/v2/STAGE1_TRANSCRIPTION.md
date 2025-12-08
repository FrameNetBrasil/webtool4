# Stage 1: Transcription - Implementation Guide

## Overview

Stage 1 (Transcription) is the first stage of the three-stage parsing pipeline. It transforms raw words from Universal Dependencies (UD) parsing into **Phrasal Construction Elements (PhrasalCEs)** with morphological features.

### Biological Analogy
```
DNA → mRNA (Transcription)
```
Just as biological transcription converts DNA into mRNA, this stage converts raw tokens into structured lexical units with "chemical properties" (features).

## Architecture

### Input
- Raw sentence text
- Trankit/UD parser output with tokens containing:
  - `word`: Surface form
  - `lemma`: Dictionary form
  - `pos`: Universal POS tag
  - `morph`: Morphological features (Gender, Number, Tense, etc.)
  - `rel`/`deprel`: Dependency relation
  - `parent`/`head`: Head token index

### Output
- Array of `PhrasalCENode` objects, each containing:
  - Word and lemma
  - POS tag
  - **PhrasalCE classification** (Head, Mod, Adp, Lnk, etc.)
  - Feature bundle (lexical + derived)
  - Dependency information

## Key Components

### 1. PhrasalCE Enum (`app/Enums/Parser/PhrasalCE.php`)

Defines the eight phrasal-level CE labels based on Croft's flat syntax:

| Label | Name | Description | Examples |
|-------|------|-------------|----------|
| `Head` | Head | Core element of a phrase | nouns, verbs, pronouns |
| `Mod` | Modifier | Specifies/describes the head | articles, adjectives, numerals |
| `Adm` | Admodifier | Modifies modifiers | "very", "quite", "extremely" |
| `Adp` | Adposition | Marks relations | prepositions, postpositions |
| `Lnk` | Linker | Connects elements | subordinators, linking particles |
| `Clf` | Classifier | Categorizes referents | numeral classifiers |
| `Idx` | Index | Agreement markers | (rare as separate words) |
| `Conj` | Conjunction | Coordinates elements | "and", "or", "but" |

#### Classification Logic

The `fromPOS()` method classifies tokens using **POS-only** strategy. UD dependency relations (`deprel`) are intentionally not used because they become unreliable with null instantiation (ellipsis) and other complex phenomena. The parser v2 was designed to avoid reliance on erratic UD relations.

```php
/**
 * Note: v2 uses only POS-based classification. UD deprel is intentionally
 * not used because it becomes unreliable with null instantiation (ellipsis).
 */
public static function fromPOS(string $pos, array $features = []): self
{
    return match ($pos) {
        'NOUN', 'PROPN', 'PRON' => self::HEAD,
        'VERB' => self::HEAD,
        'ADV' => self::HEAD,
        'ADJ' => isset($features['VerbForm']) ? self::HEAD : self::MOD,
        'DET' => self::MOD,
        'NUM' => self::MOD,
        'ADP' => self::ADP,
        'CCONJ' => self::CONJ,
        'SCONJ' => self::LNK,
        'PART' => self::LNK,
        'AUX' => self::HEAD,
        'INTJ' => self::HEAD,
        'SYM', 'PUNCT', 'X' => self::HEAD,
        default => self::HEAD,
    };
}
```

### 2. PhrasalCENode Model (`app/Models/Parser/PhrasalCENode.php`)

Represents a single token with its phrasal CE classification.

#### Constructor Properties
```php
public function __construct(
    public string $word,           // Surface form: "comprou"
    public string $lemma,          // Dictionary form: "comprar"
    public string $pos,            // UD POS: "VERB"
    public PhrasalCE $phrasalCE,   // CE label: PhrasalCE::HEAD
    public array $features,        // {lexical: {...}, derived: {...}}
    public int $index,             // Position in sentence: 3
    public float $activation = 1.0,
    public float $threshold = 1.0,
    public bool $isMWE = false,
    public ?int $idLemma = null,
    public ?int $idParserNode = null,
    public ?string $deprel = null, // UD relation: "root"
    public ?int $head = null,      // Head index: 0
) {}
```

#### Factory Method
```php
public static function fromUDToken(array $token, ?int $idLemma = null): self
```

Creates a PhrasalCENode from a UD token, handling both Trankit format (`rel`/`parent`) and standard UD format (`deprel`/`head`).

#### Feature Bundle Structure
```php
[
    'lexical' => [
        'Gender' => 'Masc',
        'Number' => 'Sing',
        'Tense' => 'Past',
        'VerbForm' => 'Fin',
        // ... from UD morph
    ],
    'derived' => [
        // Added during later processing
    ]
]
```

### 3. Test Command (`app/Console/Commands/ParserV2/TestTranscriptionCommand.php`)

Interactive command for testing and debugging Stage 1.

#### Usage
```bash
# Basic usage (UD parsing only)
php artisan parser:test-transcription <input-file>

# With grammar graph integration (MWE detection)
php artisan parser:test-transcription sentences.txt \
    --grammar=1 \
    --show-mwe-candidates

# Full options
php artisan parser:test-transcription sentences.txt \
    --language=pt \
    --grammar=1 \
    --format=table \
    --verbose-features \
    --show-mwe-candidates \
    --limit=10 \
    --output=results.json
```

#### Options
| Option | Description | Default |
|--------|-------------|---------|
| `--language` | Language code (pt, en) | pt |
| `--grammar` | Grammar graph ID for MWE detection | none |
| `--format` | Output format (table, json, csv) | table |
| `--verbose-features` | Show morphological features | false |
| `--show-mwe-candidates` | Show incomplete MWE matches | false |
| `--limit` | Max sentences to process | none |
| `--skip` | Skip first N sentences | 0 |
| `--output` | Save results to file | none |

### 4. Grammar Graph Integration

When a grammar graph ID is provided via `--grammar`, the command enables MWE detection.

#### MWE Detection
Multi-Word Expressions are detected using a prefix activation mechanism:
1. When a word matches the first component of an MWE, a candidate is created
2. Subsequent words increment the activation counter if they match
3. When activation reaches threshold (component count), the MWE is complete
4. Complete MWEs are assembled into single `PhrasalCENode` with `isMWE=true`
5. The POS tag for the MWE is looked up from `view_lemma_pos` in the lexicon database

```
Example: "a fim de" (MWE meaning "in order to")

Sentence: "Ele estudou a fim de passar."

Word:        a      fim     de
Activation:  1      2       3
Threshold:   3      3       3
Result:      → MWE complete → assembled as "a^fim^de"
```

## Data Flow

```
┌─────────────────┐
│  Input File     │
│  (sentences)    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Trankit/UD     │
│  Parser         │
└────────┬────────┘
         │
         ▼ (tokens with POS, morph)
┌─────────────────┐
│  PhrasalCE      │
│  Classification │
│  fromPOS()      │
│  (POS-only)     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  PhrasalCENode  │
│  Creation       │
└────────┬────────┘
         │
         ▼
┌─────────────────┐     ┌─────────────────┐
│  Grammar Graph  │────▶│  MWE Detection  │
│  (optional)     │     │  (prefix match) │
└────────┬────────┘     └────────┬────────┘
         │                       │
         │              ┌────────▼────────┐
         │              │  POS Lookup     │
         │              │  view_lemma_pos │
         │              └────────┬────────┘
         │                       │
         ▼              ┌────────▼────────┐
┌─────────────────┐     │  MWE Assembly   │
│  Output         │◀────│  (if complete)  │
│  (table/json)   │     └─────────────────┘
└─────────────────┘
```

## Example Output

### Input
```
O menino comprou um livro.
```

### Trankit Parse
```json
[
  {"id": 1, "word": "O", "lemma": "o", "pos": "DET", "rel": "det", "parent": 2},
  {"id": 2, "word": "menino", "lemma": "menino", "pos": "NOUN", "rel": "nsubj", "parent": 3},
  {"id": 3, "word": "comprou", "lemma": "comprar", "pos": "VERB", "rel": "root", "parent": 0},
  {"id": 4, "word": "um", "lemma": "um", "pos": "DET", "rel": "det", "parent": 5},
  {"id": 5, "word": "livro", "lemma": "livro", "pos": "NOUN", "rel": "obj", "parent": 3},
  {"id": 6, "word": ".", "lemma": ".", "pos": "PUNCT", "rel": "punct", "parent": 3}
]
```

### Stage 1 Output
```
+---+---------+---------+-------+-----------+--------+------+
| # | Word    | Lemma   | POS   | PhrasalCE | DepRel | Head |
+---+---------+---------+-------+-----------+--------+------+
| 1 | O       | o       | DET   | Mod       | det    | 2    |
| 2 | menino  | menino  | NOUN  | Head      | nsubj  | 3    |
| 3 | comprou | comprar | VERB  | Head      | root   | 0    |
| 4 | um      | um      | DET   | Mod       | det    | 5    |
| 5 | livro   | livro   | NOUN  | Head      | obj    | 3    |
| 6 | .       | .       | PUNCT | Head      | punct  | 3    |
+---+---------+---------+-------+-----------+--------+------+

Analysis: O[Mod] + menino[Head] + comprou[Head] + um[Mod] + livro[Head] + .[Head]
```

### Annotation Interpretation
```
Sentence:    O        menino   comprou   um       livro    .
PhrasalCE:   Mod      Head     Head      Mod      Head     Head
             ↓        ↓        ↓         ↓        ↓        ↓
Function:    article  subject  predicate article  object   punct
```

### Example with Grammar Graph (--grammar option)

When using `--grammar=1`, the output includes MWE detection:

```
+---+---------+---------+-------+-----------+--------+------+-----+
| # | Word    | Lemma   | POS   | PhrasalCE | DepRel | Head | MWE |
+---+---------+---------+-------+-----------+--------+------+-----+
| 1 | O       | o       | DET   | Mod       | det    | 2    | -   |
| 2 | menino  | menino  | NOUN  | Head      | nsubj  | 3    | -   |
| 3 | comprou | comprar | VERB  | Head      | root   | 0    | -   |
| 4 | um      | um      | DET   | Mod       | det    | 5    | -   |
| 5 | livro   | livro   | NOUN  | Head      | obj    | 3    | -   |
| 6 | .       | .       | PUNCT | Head      | punct  | 3    | -   |
+---+---------+---------+-------+-----------+--------+------+-----+
```

### Example with MWE Detection

Input: "Ele estudou a fim de passar no exame."

If the grammar contains the MWE "a fim de", and the lexicon has `a^fim^de` with POS `SCONJ`:
```
+---+------------+----------+-------+-----------+--------+------+-----+
| # | Word       | Lemma    | POS   | PhrasalCE | DepRel | Head | MWE |
+---+------------+----------+-------+-----------+--------+------+-----+
| 1 | Ele        | ele      | PRON  | Head      | nsubj  | 2    | -   |
| 2 | estudou    | estudar  | VERB  | Head      | root   | 0    | -   |
| 3 | a^fim^de   | a^fim^de | SCONJ | Lnk       | mark   | 5    | ✓   |
| 4 | passar     | passar   | VERB  | Head      | advcl  | 2    | -   |
| ...
+---+------------+----------+-------+-----------+--------+------+-----+

Detected MWEs:
  • a fim de (words 3-5)

Analysis: Ele[Head] + estudou[Head] + a^fim^de[Lnk/MWE] + passar[Head] + ...
```

Note: The POS for MWEs is looked up from `view_lemma_pos` using the space-separated
name format (e.g., `a não ser que`). The PhrasalCE is then derived from this POS tag.

## Classification Rules Summary

### Always Head
- `NOUN`, `PROPN`, `PRON` - Nominal heads
- `VERB` - Verbal heads
- `ADV` - Adverbial heads (at phrasal level)
- `AUX` - Auxiliary heads (become CPP at clausal level)
- `INTJ` - Interjection heads

### Always Mod
- `DET` - Determiners
- `NUM` - Numerals

### Context-Dependent
- `ADJ` - Head if participial (`VerbForm` feature), Mod otherwise
- `SCONJ` - Lnk (linker/subordinator)
- `CCONJ` - Conj (coordinator)
- `ADP` - Adp (adposition)
- `PART` - Lnk (linker)

## Files Reference

| File                                                           | Purpose                              |
| -------------------------------------------------------------- | ------------------------------------ |
| `app/Enums/Parser/PhrasalCE.php`                               | CE label definitions and POS-based classification |
| `app/Models/Parser/PhrasalCENode.php`                          | Token representation with CE         |
| `app/Console/Commands/ParserV2/TestTranscriptionCommand.php`   | Test command                         |
| `app/Console/Commands/ParserV2/test_sentences.txt`             | Sample sentences                     |
| `app/Repositories/Parser/MWE.php`                              | MWE database access and POS lookup   |
| `docs/flat_syntax/ce_labels.md`                                | Comprehensive CE label documentation |

## Next Steps: Stage 2 (Translation)

After Stage 1 produces PhrasalCENodes, Stage 2 (Translation) will:
1. Group PhrasalCEs into phrases
2. Assign **Clausal CEs** (Pred, Arg, CPP, Gen, FPM, Conj)
3. Create local dependencies based on feature compatibility

See `STAGE2_TRANSLATION.md` for details.
