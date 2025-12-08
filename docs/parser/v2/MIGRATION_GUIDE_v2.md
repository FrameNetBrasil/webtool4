# Migration Guide: From E/V/A/F to Croft CE Labels
## Quick Reference for Framework v2.0

---

## What Changed and Why

### OLD Framework (v1.0)
```
Biological:  Nitrogen bases (A/T/G/C) ←→ Word types (E/V/A/F)
Problem:     Focused on sequence elements, not assembly process
```

### NEW Framework (v2.0)
```
Biological:  Amino acids/Peptides/Polypeptides ←→ Phrasal/Clausal/Sentential CEs
Advantage:   Captures hierarchical assembly process naturally
```

---

## Core Mappings: At a Glance

### Three Levels = Three Biological Units

| Level | Croft's CEs | Biological Unit | What It Represents |
|-------|-------------|-----------------|-------------------|
| **Level 1: Phrasal** | Head, Mod, Adm, Adp, Lnk, Clf, Idx, Conj | **Amino Acids** | Individual words with chemical properties (features) |
| **Level 2: Clausal** | Pred, Arg, Rel, FPM, ICE, Cue, Voc | **Peptides** | Functional phrases built from words |
| **Level 3: Sentential** | Main, Sub, Coord | **Polypeptides** | Complete clauses that integrate into sentence |

### Three Stages = Three Assembly Processes

| Stage | Input | Process | Output | Biological Parallel |
|-------|-------|---------|--------|---------------------|
| **Transcription** | Raw words | Classify CEs, extract features, assemble MWEs | Phrasal CEs with features | Building amino acids with properties |
| **Translation** | Phrasal CEs | Group into phrases, establish local dependencies | Clausal CEs with links | Forming peptide chains through bonds |
| **Folding** | Clausal CEs | Integrate clauses, long-distance dependencies | Complete parse graph | Folding polypeptides into functional protein |

---

## Key Conceptual Shifts

### 1. From Word Types to Construction Elements

**OLD:**
```
E = Entity (noun-like)
V = Eventive (verb-like)  
A = Attribute (adjective-like)
F = Function (grammatical)
```

**NEW:**
```
PHRASAL LEVEL:
Head = Core of phrase (noun, verb, adj, adv stems)
Mod = Modifier (det, adj modifying noun)
Adm = Adposition marker (prepositions, postpositions)
... (other phrasal CE types)

CLAUSAL LEVEL:
Pred = Predicate (verb phrase)
Arg = Argument (noun phrase as subj/obj)
Rel = Relative clause
FPM = Flagged phrase modifier (adverbs, PPs)
... (other clausal CE types)

SENTENTIAL LEVEL:
Main = Main clause
Sub = Subordinate clause
Coord = Coordinate clause
```

**Why this is better:**
- CE labels are grounded in linguistic function
- Three-level hierarchy is explicit
- Maps naturally to biological assembly stages

### 2. From Product to Process

**OLD focus:**
- What type is this word? (E/V/A/F)
- What is the final parse tree?

**NEW focus:**
- How do words combine into phrases? (Phrasal CEs → Clausal CEs)
- How do phrases combine into clauses? (Clausal CEs → Sentential CEs)
- How do clauses integrate into sentences? (Folding process)

**Key insight:**
```
It's not about COUNTING polypeptides (clauses).
It's about UNDERSTANDING the assembly process.

Simple sentence = 1 clause = 1 polypeptide protein ✓
Complex sentence = multiple clauses = multi-chain protein ✓

Both follow the SAME ASSEMBLY PROCESS.
```

### 3. Features Drive Every Level

**What stays the same:**
Morphological features (Gender, Number, Case, etc.) are the "chemical properties" that drive structure formation.

**What's clearer now:**
- **Level 1 (Phrasal):** Features determine word-to-word bonding
  - Example: Gender=Fem + Number=Plur allows "las hermanas" to bond
  
- **Level 2 (Clausal):** Features determine phrase function
  - Example: VerbForm=Fin makes a Head into a Pred
  
- **Level 3 (Sentential):** Features enable long-distance connections
  - Example: Case=Nom connects subject across relative clause

---

## Implementation Changes Required

### Database Schema Updates

```sql
-- OLD: Just word type
ALTER TABLE parser_node ADD COLUMN word_type VARCHAR(1); -- E/V/A/F

-- NEW: CE labels at three levels
ALTER TABLE parser_node 
    ADD COLUMN phrasal_ce VARCHAR(20),  -- Head, Mod, Adm, etc.
    ADD COLUMN clausal_ce VARCHAR(20),  -- Pred, Arg, Rel, FPM, etc.
    ADD COLUMN sentential_ce VARCHAR(20); -- Main, Sub, Coord
    
-- Features stay the same
ALTER TABLE parser_node 
    ADD COLUMN features JSONB; -- All UD features
```

### Code Architecture Changes

```php
// OLD: Single classification
class ParserNode {
    public string $wordType; // E, V, A, or F
}

// NEW: Three-level CE classification
class ParserNode {
    // Three CE levels
    public string $phrasalCE;    // Head, Mod, etc.
    public ?string $clausalCE;   // Pred, Arg, etc. (assigned in Stage 2)
    public ?string $sententialCE; // Main, Sub, Coord (assigned in Stage 3)
    
    // Features (unchanged)
    public array $features; // UD features
}

// OLD: Single service
class ParserService {
    public function parse($sentence) {
        // Everything in one pass
    }
}

// NEW: Three-stage services
class TranscriptionService {
    public function buildPhrasalCEs($sentence): array;
}

class TranslationService {
    public function buildClausals($phrasalCEs): array;
}

class FoldingService {
    public function foldSentence($clausalCEs): ParseGraph;
}

class ParserService {
    public function parse($sentence) {
        $phrasal = $this->transcription->buildPhrasalCEs($sentence);
        $clausal = $this->translation->buildClausals($phrasal);
        $graph = $this->folding->foldSentence($clausal);
        return $graph;
    }
}
```

### Classification Logic Changes

```php
// OLD: Classify word type
function classifyWordType($token): string {
    if ($token->pos === 'NOUN') return 'E';
    if ($token->pos === 'VERB') return 'V';
    if ($token->pos === 'ADJ') return 'A';
    return 'F';
}

// NEW: Classify phrasal CE (Stage 1)
function classifyPhrasalCE($token): string {
    // More nuanced based on POS and features
    if ($token->pos === 'NOUN') return 'Head';
    if ($token->pos === 'VERB') return 'Head';
    if ($token->pos === 'DET') return 'Mod';
    if ($token->pos === 'ADJ') return 'Mod';
    if ($token->pos === 'ADP') return 'Adm';
    // ... more cases
}

// NEW: Transform to clausal CE (Stage 2)
function assignClausalCE($phrasal, $context): string {
    if ($phrasal->type === 'Head' && 
        $phrasal->features['verbForm'] === 'Fin') {
        return 'Pred'; // Finite verb Head → Predicate
    }
    if ($phrasal->type === 'Head' && 
        $phrasal->pos === 'NOUN' &&
        $context->role === 'argument') {
        return 'Arg'; // Noun Head in argument position → Arg
    }
    // ... more transformations
}

// NEW: Assign sentential CE (Stage 3)
function assignSententialCE($clausal, $context): string {
    if ($clausal->type === 'Pred' && $context->isMainClause) {
        return 'Main';
    }
    if ($clausal->type === 'Rel') {
        return 'Sub'; // Relative clause is subordinate
    }
    // ... more assignments
}
```

---

## Terminology Mapping

### Old → New

| Old Term | New Term | Notes |
|----------|----------|-------|
| "Word type" | "Phrasal CE" | More precise linguistic term |
| "E node" | "Head (noun)" | Head at phrasal level |
| "V node" | "Head (verb)" → "Pred" | Head at phrasal, becomes Pred at clausal |
| "A node" | "Mod (adj)" | Modifier at phrasal level |
| "F node" | Various (Adm, Lnk, etc.) | Split into specific CE types |
| "MWE node" | "Head (MWE)" | Still a Head, but composed |
| "Phrase" | "Clausal CE" | Pred, Arg, etc. |
| "Parse graph" | "Sentential structure" | Final folded structure |

---

## Examples: Side-by-Side Comparison

### Example 1: Simple Sentence

**Sentence:** "Tomei café da manhã" (I drank coffee-of-the-morning)

#### OLD Framework (v1.0)
```
Word types:
tomei: V
café: E
da: F
manhã: E

MWE: café^da^manhã → E (aggregated)

Final:
[tomei:V] → [café_da_manhã:E]
```

#### NEW Framework (v2.0)
```
STAGE 1 (Phrasal CEs = Amino Acids):
tomei:         Head [VerbForm=Fin, Person=1, Tense=Past]
café^da^manhã: Head [Gender=Masc, Number=Sing] (MWE)

STAGE 2 (Clausal CEs = Peptides):
tomei → Pred (finite verb Head becomes predicate)
café_da_manhã → Arg (noun Head in object position)

Dependency: [Pred:tomei] ──OBJ──> [Arg:café_da_manhã]

STAGE 3 (Sentential CEs = Polypeptide):
Main: [tomei café da manhã]
(Single clause = single polypeptide)

Final structure: 
Root: tomei [Main clause]
  └─ café_da_manhã [Object Arg]
```

### Example 2: Complex Sentence with Relative Clause

**Sentence:** "O menino que eu vi chegou" (The boy that I saw arrived)

#### OLD Framework (v1.0)
```
Word types:
o: F
menino: E
que: F
eu: E
vi: V
chegou: V

Parse tree with dependency relations
(non-projective: menino → chegou crosses intervening words)
```

#### NEW Framework (v2.0)
```
STAGE 1 (Phrasal CEs):
o:      Mod [Definite=Def]
menino: Head [Gender=Masc, Number=Sing]
que:    Head [relative pronoun]
eu:     Head [Person=1]
vi:     Head [VerbForm=Fin, Tense=Past]
chegou: Head [VerbForm=Fin, Tense=Past]

STAGE 2 (Clausal CEs):
Main clause:
  o menino → Arg
  chegou → Pred
  
Relative clause:
  que eu vi → Rel

Dependencies:
  o → menino (determiner)
  menino ← chegou (subject) [CROSSES que, eu, vi]
  que → vi (object)
  eu → vi (subject)

STAGE 3 (Sentential CEs):
Main: [o menino chegou]
Sub: [que eu vi] (relative clause modifying "menino")

Disulfide bridge parallel:
menino₂ ────────────> chegou₆
        (crosses 3,4,5)
        
Like: Cys₁₅ ─S─S─ Cys₇₈
      (distant in sequence, close in 3D)

Final structure:
Root: chegou [Main:Pred]
  └─ menino [Main:Arg]
      └─ o [Mod]
      └─ que eu vi [Sub:Rel]
```

---

## Testing Strategy

### Test Each Level Independently

#### Level 1 Tests: Phrasal CE Classification
```
Input:  "las tres hermanas grandes"
Expect: 
  las      → Mod [Gender=Fem, Number=Plur]
  tres     → Mod [Number=Plur]
  hermanas → Head [Gender=Fem, Number=Plur]
  grandes  → Mod [Number=Plur]
```

#### Level 2 Tests: Clausal CE Assignment
```
Input:  Phrasal CEs from above
Expect: 
  [las tres hermanas grandes] → Arg
  (all four bind into single Arg CE via agreement)
```

#### Level 3 Tests: Sentential Integration
```
Input:  [Pred: llegaron] + [Arg: las tres hermanas grandes]
Expect:
  Main clause with subject-verb agreement
  llegaron [Number=Plur] ✓ hermanas [Number=Plur]
```

### Cross-Linguistic Tests

Test the framework with:

1. **Agreement-heavy (Spanish):**
   ```
   "las tres hermanas grandes"
   → Multiple H-bonds via Gender/Number agreement
   ```

2. **Case-heavy (Russian):**
   ```
   "Мальчик видит девочку"
   → Strong ionic bonds via Nom/Acc case
   ```

3. **Position-heavy (English):**
   ```
   "The big dog saw a cat"
   → Minimal features, position determines structure
   ```

---

## FAQ: Common Questions

### Q1: Why is this better than E/V/A/F?

**A:** The CE framework:
- Is grounded in established linguistic theory (Croft)
- Has three levels that map naturally to biological stages
- Captures the assembly PROCESS, not just the final PRODUCT
- Provides richer classification (8+ phrasal types vs. 4 word types)
- Makes the hierarchical composition explicit

### Q2: Do all sentences need multiple clauses?

**A:** No! Many sentences have just one clause = one polypeptide.

```
Simple sentence: "Tomei café" → [Main] (one polypeptide)
Complex sentence: "Tomei café porque estava com fome" 
                  → [Main] + [Sub] (two polypeptides)
```

Just like proteins can be single-chain (insulin) or multi-chain (hemoglobin).

### Q3: What happens to MWEs?

**A:** MWEs remain important! They're still assembled in Stage 1 (Transcription) through the prefix hierarchy mechanism.

The difference:
- **OLD:** MWE becomes an "E" node
- **NEW:** MWE becomes a "Head" CE at phrasal level

```
"café da manhã" →
  Stage 1: Assembled into single Head [Gender=Masc, Number=Sing]
  Stage 2: Becomes Arg when functioning as object
  Stage 3: Part of Main clause structure
```

### Q4: Are features still important?

**A:** Yes! Even more so. Features now explicitly drive assembly at EVERY level:

- **Level 1:** Features enable MWE detection and word classification
- **Level 2:** Features drive phrasal bonding (agreement, case)
- **Level 3:** Features enable long-distance dependencies

### Q5: How does this affect the code?

**A:** Main changes:

1. Database: Add three CE columns (phrasal, clausal, sentential)
2. Services: Split into three stage-specific services
3. Classification: More nuanced CE type assignment
4. Visualization: Show three-level hierarchy

But the core mechanisms (activation, prefix hierarchy, feature extraction) remain the same!

### Q6: What about crossing edges / non-projective structures?

**A:** These are now understood as "disulfide bridges"—long-distance connections made during the Folding stage (Stage 3).

```
Relative clauses create disulfide-like bonds:
menino₂ ───────────→ chegou₆
        (crosses intervening words)

Just like:
Cys₁₅ ──S─S──→ Cys₇₈
      (connects distant parts)
```

### Q7: Do I need to change my annotation tool?

**A:** Yes, update it to output Croft's CE labels:

```
OLD output:
tomei: V
café: E

NEW output:
Phrasal level:
  tomei: Head
  café: Head
  
Clausal level:
  tomei: Pred
  café da manhã: Arg
  
Sentential level:
  Main clause: [tomei café da manhã]
```

---

## Implementation Checklist

### Phase 1: Update Data Model ✓
- [ ] Add three CE columns to parser_node table
- [ ] Update ParserNode class with three CE properties
- [ ] Create CE enum/constant classes (PhrasalCE, ClausalCE, SententialCE)

### Phase 2: Refactor Services ✓
- [ ] Create TranscriptionService (Stage 1)
- [ ] Create TranslationService (Stage 2)
- [ ] Create FoldingService (Stage 3)
- [ ] Refactor ParserService to orchestrate stages

### Phase 3: Update Classification Logic ✓
- [ ] Implement phrasal CE classifier
- [ ] Implement clausal CE transformer
- [ ] Implement sentential CE assigner
- [ ] Update MWE assembly to use phrasal CEs

### Phase 4: Update Visualization ✓
- [ ] Show three-level CE hierarchy in graph
- [ ] Color-code by CE level
- [ ] Highlight feature-driven bonds
- [ ] Mark long-distance dependencies (disulfide bridges)

### Phase 5: Update Documentation ✓
- [ ] Update README with new terminology
- [ ] Create CE reference guide
- [ ] Update API documentation
- [ ] Create migration notes for existing users

### Phase 6: Testing ✓
- [ ] Unit tests for each CE level
- [ ] Integration tests for three-stage pipeline
- [ ] Cross-linguistic validation tests
- [ ] Regression tests (ensure existing features still work)

---

## Summary

### What's the Same
- Three-stage processing (Transcription → Translation → Folding)
- MWE assembly via prefix hierarchy
- Feature extraction from Universal Dependencies
- Feature-driven linking mechanism
- Activation thresholds and garbage collection

### What's Different
- **Classification:** Phrasal/Clausal/Sentential CEs instead of E/V/A/F
- **Hierarchy:** Three explicit levels instead of implicit
- **Biological mapping:** Amino acids/Peptides/Polypeptides instead of bases
- **Focus:** Process (how things assemble) over product (final structures)
- **Theory:** Grounded in Croft's flat-syntax framework

### Why This Matters

This revision makes the framework:
1. **Theoretically stronger:** Grounded in established linguistic theory
2. **Biologically accurate:** Proper mapping to assembly stages
3. **Computationally explicit:** Three clear processing stages
4. **Cross-linguistically valid:** Works across language types
5. **Empirically testable:** Makes specific predictions about processing

---

**Document Type:** Migration Guide  
**Version:** 2.0  
**Date:** December 2024  
**Status:** Ready for Implementation  
**Related:** REVISED_transdisciplinary_framework.md
