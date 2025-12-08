# REVISED Transdisciplinary Parsing Framework
## Croft's Construction Elements as Biological Building Blocks

**Date:** December 2024  
**Status:** Revised Conceptual Framework v2.0

---

## Core Conceptual Shift

### OLD FRAMEWORK (v1.0)
```
Word types (E/V/A/F) ←→ Nitrogen bases (A/T/G/C)
Problem: This analogy focused on the linear sequence elements,
         missing the hierarchical assembly process
```

### NEW FRAMEWORK (v2.0)
```
Phrasal CEs    ←→ Amino Acids      (basic building blocks)
Clausal CEs    ←→ Peptides         (short chains)
Sentential CEs ←→ Polypeptides     (long chains)
Sentence       ←→ Folded Protein   (functional structure)
```

**Key insight:** The parallel is in the PROCESS of hierarchical assembly, not in the product. Morphological features drive combination at each level, just as chemical properties drive molecular bonding.

---

## Executive Summary: The New Mapping

### Three Levels = Three Biological Units

| **Linguistic Level** | **Croft's CEs** | **Biological Unit** | **Combination Process** |
|----------------------|-----------------|---------------------|-------------------------|
| **Phrasal** | Head, Mod, Adm, Adp, Lnk, Clf, Idx, Conj | **Amino Acids** | Features drive word-to-word bonding |
| **Clausal** | Pred, Arg, Rel, FPM, ICE, Cue, Voc | **Peptides** | Local phrase assembly into functional units |
| **Sentential** | Main, Sub, Coord | **Polypeptides** | Clause integration into sentence structure |

### The Assembly Process

```
STAGE 1: TRANSCRIPTION
Input:  Linear word sequence
Process: Classify words, assemble MWEs, extract features
Output: Phrasal CEs (= amino acids with properties)

STAGE 2: TRANSLATION  
Input:  Phrasal CEs (amino acids)
Process: Combine into local structures using feature compatibility
Output: Clausal CEs (= peptides/short chains)

STAGE 3: FOLDING
Input:  Clausal CEs (peptides) 
Process: Integrate into global structure, long-distance dependencies
Output: Sentential CEs (= polypeptides forming protein)
```

---

## Detailed Mapping: Phrasal Level

### Phrasal CEs as Amino Acids

**In Biology:** 
- 20 standard amino acids (Ala, Gly, Leu, etc.)
- Each has distinct chemical properties (hydrophobic, charged, polar)
- Properties determine bonding patterns

**In Linguistics:**
- ~8-10 phrasal CE types (Head, Mod, Adm, Adp, Lnk, Clf, Idx, Conj)
- Each has distinct linguistic properties (captured in UD features)
- Properties determine linking patterns

### Example: Building a Nominal Phrase

```
Spanish: "las tres hermanas grandes"
        (the three sisters big)

PHRASAL LEVEL ANNOTATION (Croft):
┌────────────────────────────────────────────────────┐
│ las     tres    hermanas  grandes                  │
│ Mod  +  Mod  +  Head   +  Mod                      │
└────────────────────────────────────────────────────┘

BIOLOGICAL PARALLEL:
Each word is like an amino acid with specific properties:

las:      [Mod CE]
          Features: Gender=Fem, Number=Plur, Definite=Def
          Like: Hydrophilic amino acid (seeks surface position)
          
tres:     [Mod CE]  
          Features: Number=Plur
          Like: Small neutral amino acid (fits modifier position)
          
hermanas: [Head CE]
          Features: Gender=Fem, Number=Plur
          Like: Catalytic amino acid (active site/nucleus)
          
grandes:  [Mod CE]
          Features: Number=Plur (gender underspecified)
          Like: Polar amino acid (forms H-bonds)

FEATURE-DRIVEN BONDING:
┌──────────────────────────────────────────────┐
│ las → hermanas:                              │
│   Gender:  Fem = Fem  ✓ [H-bond]           │
│   Number:  Plur = Plur ✓ [H-bond]          │
│   Strength: STRONG (2 agreement bonds)       │
│                                              │
│ tres → hermanas:                             │
│   Number:  Plur = Plur ✓ [H-bond]          │
│   Strength: MEDIUM (1 agreement bond)        │
│                                              │
│ grandes → hermanas:                          │
│   Number:  Plur = Plur ✓ [H-bond]          │
│   Strength: MEDIUM (1 agreement bond)        │
└──────────────────────────────────────────────┘

OUTPUT: Stable phrasal unit (like a stable amino acid cluster)
        Ready to function as a building block for clausal level
```

### Key Insight: Features = Chemical Properties

```
┌──────────────────┬─────────────────────┬──────────────────┐
│ UD Feature       │ Chemical Property   │ Bonding Effect   │
├──────────────────┼─────────────────────┼──────────────────┤
│ Gender (Fem/Masc)│ Polar groups       │ H-bonding        │
│ Number (Sing/Pl) │ Charge state       │ Ionic attraction │
│ Case (Nom/Acc)   │ Strong charge      │ Position lock    │
│ Definite (Def)   │ Hydrophobic        │ Info structure   │
│ Person (1/2/3)   │ Identity tag       │ Agreement        │
└──────────────────┴─────────────────────┴──────────────────┘
```

**Critical principle:** Features don't just annotate—they actively drive the assembly process at the phrasal level, determining which words can bond into stable units.

---

## Detailed Mapping: Clausal Level

### Clausal CEs as Peptides

**In Biology:**
- Peptides are short chains of amino acids (typically 2-50)
- Form through peptide bonds between amino acids
- Have local structure (turns, short helices)
- Represent functional sub-units

**In Linguistics:**
- Clausal CEs are functional units built from phrasal CEs
- Form through feature-compatible dependencies
- Have local structure (argument frames, modification)
- Represent semantic roles

### Clausal CE Types (Croft)

| **CE Label** | **Function** | **Biological Parallel** |
|--------------|-------------|------------------------|
| **Pred** | Predicate (verb phrase) | Catalytic peptide (active site) |
| **Arg** | Argument (noun phrase) | Substrate-binding peptide |
| **Rel** | Relative clause | Signal peptide (attachment point) |
| **FPM** | Flagged phrase modifier | Allosteric peptide (modulator) |
| **ICE** | Intra-clausal element | Linker peptide |
| **Cue** | Discourse cue | Recognition peptide |
| **Voc** | Vocative | Targeting peptide |

### Example: Simple Clause

```
Portuguese: "Tomei café da manhã cedo"
           (I-drank coffee of-the morning early)

STAGE 1 OUTPUT (Phrasal CEs = Amino Acids):
┌────────────────────────────────────────────┐
│ tomei          café^da^manhã        cedo   │
│ [Head]         [Head]               [Head] │
│ Features:      Features:            Features: │
│ VerbForm=Fin   Gender=Masc         (adverb)│
│ Person=1       Number=Sing                  │
│ Tense=Past     Definite=Def                 │
└────────────────────────────────────────────┘

STAGE 2: TRANSLATION TO CLAUSAL CEs (= Forming Peptides)
┌────────────────────────────────────────────┐
│ Assembly process:                           │
│                                            │
│ 1. tomei [Head → Pred]                     │
│    Why: VerbForm=Fin makes it predicative │
│    Like: Catalytic residue becomes active  │
│          site of peptide                   │
│                                            │
│ 2. café_da_manhã [Head → Arg]              │
│    Why: Entity type, compatible with Pred  │
│    Like: Substrate-binding residue         │
│          positions near active site        │
│                                            │
│ 3. cedo [Head → FPM]                       │
│    Why: Adverbial modifier of predicate    │
│    Like: Allosteric residue that modulates │
│          activity                          │
└────────────────────────────────────────────┘

CLAUSAL LEVEL STRUCTURE (= Peptide):
┌────────────────────────────────────────────┐
│        [Pred: tomei]                        │
│            /      \                         │
│           /        \                        │
│   [Arg: café_da_manhã]  [FPM: cedo]        │
│                                            │
│ This is ONE PEPTIDE with three parts:      │
│ - Active site (Pred)                       │
│ - Substrate binding (Arg)                  │
│ - Modulator (FPM)                          │
└────────────────────────────────────────────┘

CROFT ANNOTATION:
"tomei + café^da^manhã + cedo ."
Labels: Pred + Arg + FPM
```

### Feature Propagation in Peptide Formation

```
Just as peptides maintain chemical properties from their
constituent amino acids, clausal CEs inherit features from
their phrasal components:

[Arg: café_da_manhã]
  Inherits from phrasal Head:
  - Gender=Masc
  - Number=Sing  
  - Definite=Def
  
These features persist and can:
1. Control agreement with other clausal elements
2. Determine compatibility with predicate
3. Influence information structure
```

---

## Detailed Mapping: Sentential Level

### Sentential CEs as Polypeptides

**In Biology:**
- Polypeptides are long amino acid chains (50+ residues)
- Multiple polypeptides may combine to form a protein
- Long-distance interactions (disulfide bridges)
- Can have complex topologies (domains, folds)

**In Linguistics:**
- Sentential CEs are clause-level units
- Multiple clauses may combine to form a complex sentence
- Long-distance dependencies (relative clauses, wh-movement)
- Can have complex structures (embedding, coordination)

### Sentential CE Types (Croft)

| **CE Label** | **Function** | **Example** |
|--------------|-------------|------------|
| **Main** | Main clause | "The boy arrived" |
| **Sub** | Subordinate clause | "because he was late" |
| **Coord** | Coordinated clause | "and the girl left" |

### Key Insight: Single vs. Multiple Polypeptides

**IMPORTANT:** Many sentences have just ONE clausal CE → ONE polypeptide!

```
Simple sentence:
"Tomei café da manhã"
Clausal structure: ONE clause → [Pred + Arg]
Sentential: [Main]

Biological parallel:
Single polypeptide protein (like insulin, which is one chain)
   H₂N─[sequence]─COOH
   Simple structure, direct function

Complex sentence:
"O menino que eu vi chegou"
Clausal structure: TWO clauses → [Main] + [Rel]
Sentential: [Main: o menino chegou] + [Rel: que eu vi]

Biological parallel:
Multi-polypeptide protein (like hemoglobin, four chains)
   Chain A + Chain B + Chain C + Chain D
   Must fold together for function
```

**Focus on PROCESS not PRODUCT:** The key is understanding HOW clauses combine (coordination, subordination, embedding), not counting how many there are.

---

## STAGE 1 Revised: Transcription (Building Amino Acids)

### Process Overview

```
INPUT: Raw word sequence
       "las tres hermanas grandes"

OPERATIONS:
1. Extract morphological features (UD parser)
2. Classify phrasal CE type based on:
   - POS tag
   - Morphological features  
   - Syntactic context
3. Assemble MWEs through prefix hierarchy
4. Assign features to each phrasal CE

OUTPUT: Phrasal CEs = "Amino acids" ready for assembly
       [las:Mod] [tres:Mod] [hermanas:Head] [grandes:Mod]
       Each with complete feature bundle
```

### MWEs as Secondary Structure Precursors

```
Example: "café^da^manhã" (breakfast)

This is NOT building a peptide yet.
This is recognizing that certain amino acids naturally
cluster into stable units BEFORE peptide bond formation.

Biological parallel:
In protein folding, some sequences naturally form
secondary structures (α-helix, β-sheet) as soon as
they're synthesized—before the full chain folds.

Similarly:
"café da manhã" immediately clusters into a single
phrasal Head CE before linking with other elements.

Prefix hierarchy mechanism:
café     → 1/3 activation
café da  → 2/3 activation  
café da manhã → 3/3 STABLE ✓

Result: ONE phrasal CE [café_da_manhã:Head]
        Not three separate CEs
        
Features: Gender=Masc, Number=Sing, Definite=Def
         (computed from complete MWE, not components)
```

### Feature Extraction as Property Determination

```python
def classify_phrasal_ce(word, ud_features, context):
    """
    Determine phrasal CE type and extract features.
    Like determining amino acid type and properties.
    """
    
    # Base classification
    if ud_features.get('VerbForm') == 'Fin':
        ce_type = 'Head'  # Will become Pred at clausal level
    elif word.pos == 'DET':
        ce_type = 'Mod'
    elif word.pos == 'ADJ':
        ce_type = 'Mod'
    elif word.pos == 'NOUN':
        ce_type = 'Head'
    # ... etc
    
    # Extract chemical properties (features)
    properties = {
        'gender': ud_features.get('Gender'),
        'number': ud_features.get('Number'),
        'case': ud_features.get('Case'),
        'definite': ud_features.get('Definite'),
        'person': ud_features.get('Person'),
        'tense': ud_features.get('Tense'),
        # ... all UD features
    }
    
    return PhrasalCE(
        word=word,
        ce_type=ce_type,
        features=properties
    )
```

---

## STAGE 2 Revised: Translation (Building Peptides)

### Process Overview

```
INPUT: Phrasal CEs (amino acids with properties)
       [tomei:Head] [café_da_manhã:Head] [cedo:Head]

OPERATIONS:
1. Group phrasal CEs into functional units
2. Assign clausal CE labels (Pred, Arg, FPM, etc.)
3. Establish local dependencies using feature compatibility
4. Form peptide-like structures

OUTPUT: Clausal CEs = "Peptides" ready for sentence integration
       [Pred:tomei] [Arg:café_da_manhã] [FPM:cedo]
       With established dependency links
```

### Feature-Driven Assembly (Peptide Bond Formation)

```
MECHANISM: Feature compatibility determines bonding

Example: [Pred:tomei] + [Arg:café_da_manhã]

Compatibility check (like peptide bond formation energy):
┌─────────────────────────────────────────────────┐
│ tomei expects:                                   │
│   Object slot: needs entity (NOUN-headed)       │
│   Features: compatible case, number, etc.       │
│                                                  │
│ café_da_manhã provides:                          │
│   Type: Entity (NOUN) ✓                         │
│   Case: Accusative (if marked) ✓                │
│   Number: Singular ✓                            │
│                                                  │
│ Compatibility score: HIGH                        │
│ → Form dependency: tomei ──[OBJ]──> café_da_manhã │
└─────────────────────────────────────────────────┘

RESULT: Clausal CEs linked into peptide structure
        [Pred] ←→ [Arg]
        Like: Amino acids bonded into peptide
              Lys─Gly─Pro
```

### Agreement as Hydrogen Bonding

```
Spanish example shows multiple weak bonds:

"las tres hermanas grandes"
→ [las:Mod] [tres:Mod] [hermanas:Head] [grandes:Mod]

Bonds forming the NP "peptide":
┌──────────────────────────────────────┐
│ las → hermanas:                      │
│   Gender: Fem = Fem [H-bond 1]      │
│   Number: Plur = Plur [H-bond 2]    │
│                                      │
│ tres → hermanas:                     │
│   Number: Plur = Plur [H-bond 3]    │
│                                      │
│ grandes → hermanas:                  │
│   Number: Plur = Plur [H-bond 4]    │
└──────────────────────────────────────┘

Total: 4 hydrogen bonds stabilizing the structure
Result: Strong, stable Arg peptide
        [Arg: las tres hermanas grandes]
```

### Clausal CE Type Assignment

```
TRANSFORMATION: Phrasal CE → Clausal CE

Rules (simplified):
1. Finite verb Head → Pred
   Example: tomei [VerbForm=Fin] → [Pred:tomei]
   
2. Nominal Head → Arg (when object) or Arg (when subject)
   Example: café_da_manhã → [Arg:café_da_manhã]
   
3. Adverbial Head → FPM
   Example: cedo → [FPM:cedo]
   
4. Relative pronoun + clause → Rel
   Example: que eu vi → [Rel:que eu vi]

Like: Amino acid residue → functional position in peptide
      Glycine → turn inducer
      Proline → helix breaker
      Leucine → hydrophobic core
```

---

## STAGE 3 Revised: Folding (Integrating Polypeptides)

### Process Overview

```
INPUT: Clausal CEs (peptides)
       [Pred:tomei] [Arg:café_da_manhã] [FPM:cedo]

OPERATIONS:
1. Identify sentential CE labels (Main, Sub, Coord)
2. Integrate clauses into sentence structure
3. Establish long-distance dependencies
4. Resolve crossing edges (non-projective structures)

OUTPUT: Complete sentence = "Folded protein"
        Sentential structure with all elements integrated
```

### Simple Sentence: Single Polypeptide

```
"Tomei café da manhã cedo"

CLAUSAL LEVEL (Peptides):
[Pred:tomei] + [Arg:café_da_manhã] + [FPM:cedo]

SENTENTIAL LEVEL (Polypeptide → Protein):
This is ONE clause → ONE polypeptide → ONE Main CE

        [Main: tomei café da manhã cedo]
                    |
            (single polypeptide)

Folding operation:
- Identify root: tomei (finite verb)
- Attach arguments: café_da_manhã (object)
- Attach modifiers: cedo (adverb)
- No subordination
- No long-distance dependencies

RESULT: Simple, compact "protein" with one functional unit
        Like insulin (single polypeptide, ~50 residues)
```

### Complex Sentence: Multiple Polypeptides

```
"O menino que eu vi chegou cedo"
(The boy that I saw arrived early)

CLAUSAL LEVEL (Peptides):
Main clause: [Pred:chegou] + [Arg:o menino] + [FPM:cedo]
Relative:    [Rel:que eu vi]

SENTENTIAL LEVEL (Polypeptides → Protein):
Two clauses → Two polypeptides → Main + Sub structure

Structure:
┌─────────────────────────────────────────┐
│ [Main: o menino chegou cedo]            │
│          |                               │
│    [Rel: que eu vi]                     │
│          ↑                               │
│   (attaches to "menino")                │
└─────────────────────────────────────────┘

Like hemoglobin:
- Multiple polypeptide chains
- Must fold together
- Long-distance interactions connect chains
- Final structure is functional unit
```

### Long-Distance Dependencies = Disulfide Bridges

```
CRITICAL PARALLEL: Non-projective structures

In protein:
...Cys₁₅─Ala─Gly─Val─...─Ser─Leu─Cys₇₈...
     |                              |
     └──────── S─S bridge ──────────┘
     
Linear sequence: Cys₁₅ and Cys₇₈ far apart
3D structure: Brought together by folding

In relative clause:
O₁ menino₂ que₃ eu₄ vi₅ chegou₆ cedo₇

Linear: menino₂ and chegou₆ separated
Dependency: Direct link crosses intervening words

      menino₂ ─────────────→ chegou₆
                (subject)
      
Crosses: que₃, eu₄, vi₅

┌────────────────────────────────────────┐
│ This is EXACTLY like a disulfide bridge: │
│                                         │
│ 1. Linear sequence separated            │
│ 2. 3D/dependency structure connected    │
│ 3. Connection crosses intervening units │
│ 4. Essential for final function         │
└────────────────────────────────────────┘
```

### Sentential CE Labels

```
Types:
- [Main]: Main clause polypeptide
- [Sub]: Subordinate clause polypeptide  
- [Coord]: Coordinated clause polypeptide

Example 1: Simple
"Tomei café"
→ [Main: tomei café]
   Single polypeptide

Example 2: Subordination
"Tomei café porque estava com fome"
(I drank coffee because I was hungry)
→ [Main: tomei café] + [Sub: porque estava com fome]
   Two polypeptides, hierarchical relationship

Example 3: Coordination
"Tomei café e comi pão"
(I drank coffee and ate bread)
→ [Main: tomei café] + [Coord: e comi pão]
   Two polypeptides, parallel relationship

Like protein complexes:
- Some proteins are single chain (Main only)
- Some have domains connected by linkers (Main + Sub)
- Some have multiple independent subunits (Main + Coord)
```

---

## Cross-Linguistic Variation: Different Folding Strategies

### Principle: Same Process, Different Emphasis

Just as different protein families emphasize different bonding strategies (hydrophobic core vs. salt bridges vs. H-bond networks), different languages emphasize different features for structure building.

### Case-Heavy Languages (Russian, Finnish, Latin)

```
Russian: "Мальчик видит девочку"
         (Boy sees girl)

Phrasal CEs (amino acids):
мальчик: [Head] Case=Nom → strong ionic bond to subject position
видит:   [Head] VerbForm=Fin → Pred position
девочку: [Head] Case=Acc → strong ionic bond to object position

Feature dominance:
- Case = primary bonding agent (like ionic bonds)
- Agreement secondary
- Word order flexible

Biological parallel:
Proteins stabilized mainly by ionic bonds (salt bridges)
Can tolerate variation in other interaction types
Structure determined by charge distribution
```

### Agreement-Heavy Languages (Spanish, French, Italian)

```
Spanish: "Las tres hermanas grandes llegaron"
         (The three sisters big arrived)

Phrasal CEs (amino acids):
las:      [Mod] Gender=Fem, Number=Plur
tres:     [Mod] Number=Plur
hermanas: [Head] Gender=Fem, Number=Plur
grandes:  [Mod] Number=Plur  
llegaron: [Head] Number=Plur

Feature dominance:
- Gender/Number = primary bonding (like H-bonds)
- Multiple weak bonds create strong network
- Rich agreement percolation

Biological parallel:
Proteins stabilized by extensive H-bond networks
Many weak interactions create strong structure
Cooperative bonding effect
```

### Position-Heavy Languages (English, Mandarin)

```
English: "The big dog saw a cat"

Phrasal CEs (amino acids):
the:  [Mod] Definite=Def
big:  [Mod]
dog:  [Head]
saw:  [Head]
a:    [Mod] Definite=Indef
cat:  [Head]

Feature dominance:
- Word order = primary structure determinant
- Definiteness = secondary feature (info structure)
- Minimal agreement

Biological parallel:
Proteins stabilized mainly by hydrophobic effect
Position in sequence determines position in structure
Fewer specific interaction types
Relies on overall architecture
```

---

## Implementation Roadmap: Revised

### Phase 1: Core Three-Stage Architecture (2-3 weeks)

**Goal:** Establish three-stage processing pipeline with CE labeling

```php
// New service structure

class TranscriptionService {
    /**
     * STAGE 1: Build phrasal CEs (amino acids)
     * 
     * Input: Raw words
     * Output: Phrasal CEs with features
     */
    public function buildPhrasalCEs($sentence) {
        // 1. Extract UD features
        $udParse = $this->udParser->parse($sentence);
        
        // 2. Classify phrasal CE types
        $phrasalCEs = [];
        foreach ($udParse as $token) {
            $ce = $this->classifyPhrasalCE($token);
            $phrasalCEs[] = $ce;
        }
        
        // 3. Assemble MWEs
        $phrasalCEs = $this->assembleMWEs($phrasalCEs);
        
        // 4. Garbage collect sub-threshold units
        $phrasalCEs = $this->garbageCollect($phrasalCEs);
        
        return $phrasalCEs;
    }
    
    private function classifyPhrasalCE($token) {
        // Map POS + features → CE type (Head, Mod, Adm, etc.)
        if ($token->pos === 'VERB' && $token->verbForm === 'Fin') {
            return new PhrasalCE('Head', $token, $this->extractFeatures($token));
        }
        // ... other classifications
    }
}

class TranslationService {
    /**
     * STAGE 2: Build clausal CEs (peptides)
     * 
     * Input: Phrasal CEs
     * Output: Clausal CEs with dependencies
     */
    public function buildClausals($phrasalCEs) {
        // 1. Assign clausal CE labels
        $clausalCEs = [];
        foreach ($phrasalCEs as $phrasal) {
            $clausal = $this->assignClausaLabel($phrasal);
            $clausalCEs[] = $clausal;
        }
        
        // 2. Establish dependencies via feature compatibility
        $dependencies = $this->buildDependencies($clausalCEs);
        
        // 3. Group into phrases
        $phrases = $this->groupIntoPhrases($clausalCEs, $dependencies);
        
        return $phrases;
    }
    
    private function assignClausaLabel($phrasal) {
        // Transform phrasal CE → clausal CE
        if ($phrasal->type === 'Head' && $phrasal->features['verbForm'] === 'Fin') {
            return new ClausaICE('Pred', $phrasal);
        }
        // ... other transformations
    }
}

class FoldingService {
    /**
     * STAGE 3: Build sentential structure (protein)
     * 
     * Input: Clausal CEs (peptides)
     * Output: Complete parse graph with sentential CEs
     */
    public function foldSentence($clausalCEs) {
        // 1. Identify main clause
        $mainClause = $this->identifyMainClause($clausalCEs);
        
        // 2. Assign sentential labels
        $sententialCEs = $this->assignSententialLabels($clausalCEs);
        
        // 3. Establish long-distance dependencies
        $longDistDeps = $this->buildLongDistanceDeps($sententialCEs);
        
        // 4. Create final parse graph
        $parseGraph = $this->assembleFinalGraph($sententialCEs, $longDistDeps);
        
        return $parseGraph;
    }
}
```

**Success criteria:**
- All three stages execute sequentially
- CE labels assigned at each level
- Stage outputs are visible and debuggable

### Phase 2: Feature-Driven Assembly (3-4 weeks)

**Goal:** Implement feature compatibility checking and feature-driven linking

```php
class FeatureCompatibilityService {
    /**
     * Calculate bonding strength between two phrasal CEs
     * Like calculating peptide bond formation energy
     */
    public function calculateCompatibility($ce1, $ce2, $relationType) {
        $score = 1.0;  // baseline
        
        // Agreement features (hydrogen bonds)
        $score += $this->checkAgreement($ce1, $ce2);
        
        // Case features (ionic bonds)
        $score += $this->checkCase($ce2, $relationType);
        
        // Definiteness (hydrophobic effect)
        $score += $this->checkDefiniteness($ce1, $ce2);
        
        return $score;
    }
    
    private function checkAgreement($ce1, $ce2) {
        $bonus = 0.0;
        
        // Gender agreement
        if ($this->featuresMatch($ce1, $ce2, 'gender')) {
            $bonus += 0.3;  // H-bond strength
        }
        
        // Number agreement  
        if ($this->featuresMatch($ce1, $ce2, 'number')) {
            $bonus += 0.3;  // H-bond strength
        }
        
        // Person agreement
        if ($this->featuresMatch($ce1, $ce2, 'person')) {
            $bonus += 0.2;
        }
        
        return $bonus;
    }
    
    private function checkCase($ce, $relationType) {
        // Case provides strong bonding (ionic)
        if ($relationType === 'subject' && $ce->features['case'] === 'Nom') {
            return 0.5;  // Strong ionic bond
        }
        if ($relationType === 'object' && $ce->features['case'] === 'Acc') {
            return 0.5;  // Strong ionic bond
        }
        return 0.0;
    }
}
```

### Phase 3: CE Visualization (2 weeks)

**Goal:** Visualize the three levels of CEs in the parse output

```javascript
// Update parser_graph.js

function renderThreeLevelCEs(parseData) {
    // Render phrasal CEs (amino acids)
    const phrasalLayer = renderPhrasalLayer(parseData.phrasal);
    
    // Render clausal CEs (peptides)
    const clausalLayer = renderClausalLayer(parseData.clausal);
    
    // Render sentential CEs (polypeptides)
    const sententialLayer = renderSententialLayer(parseData.sentential);
    
    // Show feature-driven bonds
    renderFeatureBonds(parseData.dependencies);
    
    // Highlight long-distance dependencies (disulfide bridges)
    highlightNonProjective(parseData.longDistance);
}
```

### Phase 4: Multi-Language Validation (3-4 weeks)

**Goal:** Test framework across typologically different languages

```
Test languages:
- Portuguese (baseline)
- Spanish (agreement-heavy)
- Russian (case-heavy)
- English (position-heavy)
- Mandarin (analytic, position-heavy)

For each:
1. Annotate test corpus with Croft's CEs
2. Run parser
3. Compare output to gold standard
4. Analyze feature usage patterns
5. Tune feature weights per language
```

---

## Theoretical Implications

### Why This Mapping is Superior

**OLD (E/V/A/F as bases):**
- Focused on linear sequence elements
- Missed hierarchical assembly
- No clear biological stages
- Word types somewhat arbitrary

**NEW (CEs as amino acids/peptides/polypeptides):**
- Captures hierarchical assembly process ✓
- Maps naturally to three biological stages ✓
- CE types grounded in linguistic function ✓
- Features as chemical properties makes sense ✓
- Explains cross-linguistic variation ✓

### Process Over Product

```
KEY INSIGHT: The parallel is not in WHAT the structures are,
            but in HOW they assemble.

Both systems:
1. Start with linear sequence (DNA codons / words)
2. Build local structures with properties (amino acids / phrasal CEs)
3. Combine into functional units (peptides / clausal CEs)
4. Integrate into global structure (proteins / sentences)
5. Use local properties to drive global assembly

The NUMBER of units at each level varies:
- Some proteins: 1 polypeptide
- Some sentences: 1 main clause

But the PROCESS is universal:
- Feature-driven assembly
- Hierarchical composition  
- Local-to-global information flow
```

### Handling "Single Polypeptide" Sentences

```
Don't worry that many sentences have just one Main CE!

Biological precedent:
Many functional proteins are single polypeptides:
- Insulin: 51 amino acids, 1 chain
- Lysozyme: 129 amino acids, 1 chain  
- Myoglobin: 153 amino acids, 1 chain

They still go through:
1. Translation (peptide bond formation)
2. Folding (3D structure formation)

Similarly, simple sentences still go through:
1. Translation (phrasal → clausal CEs)
2. Folding (clausal → sentential integration)

Complex sentences are like multi-chain proteins:
- Hemoglobin: 4 chains that must assemble
- Complex sentence: multiple clauses that must integrate

Both simple and complex follow the same PROCESS.
```

---

## Research Questions (Updated)

### Theoretical

1. **Is there a finite set of phrasal CE types across languages?**
   - Like: Are there exactly 20 "amino acids" or is it open-ended?
   - Test: Survey typologically diverse languages
   
2. **What determines phrasal → clausal CE transformation rules?**
   - Like: What makes an amino acid catalytic vs. structural?
   - Test: Corpus analysis of CE transformations

3. **Can we define a "folding energy" for sentences?**
   - Measure: Sum of feature compatibility scores
   - Predict: Easier sentences have lower "folding energy"
   - Test: Compare with processing time data

### Computational

4. **What is optimal feature compatibility function per language?**
   - Method: Learn weights from annotated corpora
   - Compare: Hand-tuned vs. learned weights
   
5. **How do we handle feature conflicts?**
   - Example: Gender clash in coordination
   - Biological analog: Misfolding and chaperones
   - Solution: Repair mechanisms?

6. **Can neural networks learn CE classifications?**
   - Train: Transformer to predict CE labels from features
   - Advantage: Handle ambiguity and context
   - Compare: With rule-based classifier

### Empirical

7. **Does three-stage model match human processing?**
   - Test: Eye-tracking during reading
   - Predict: Stage boundaries show processing cost
   
8. **Do different languages show different feature profiles?**
   - Measure: Feature importance scores
   - Expect: Case-heavy vs. agreement-heavy vs. position-heavy
   - Validate: Matches linguistic typology

9. **Does framework generalize to other languages?**
   - Test: Low-resource languages
   - Measure: Zero-shot parsing accuracy
   - Success: Universal feature framework should transfer

---

## Conclusion

### Summary of Revised Framework

This framework establishes a precise transdisciplinary parallel:

```
PHRASAL CEs (Head, Mod, etc.)
  ↓
  ├─ Function: Basic building blocks
  ├─ Properties: Morphological features
  └─ Parallel: Amino acids with chemical properties

CLAUSAL CEs (Pred, Arg, Rel, etc.)  
  ↓
  ├─ Function: Local functional units
  ├─ Assembly: Feature-driven bonding
  └─ Parallel: Peptides with local structure

SENTENTIAL CEs (Main, Sub, Coord)
  ↓
  ├─ Function: Global integrated structure
  ├─ Assembly: Long-distance dependencies
  └─ Parallel: Polypeptides/proteins with 3D structure
```

**Core principles:**

1. **Features drive assembly** at every level
2. **Hierarchical composition** from local to global
3. **Process over product** - focus on HOW, not WHAT
4. **Universal mechanism** with language-specific variation
5. **Testable predictions** for computational and psycholinguistic research

### Next Steps

**Immediate (This Week):**
1. Review this revised framework
2. Update existing code to use CE terminology
3. Design database schema for three CE levels
4. Create test cases for each stage

**Short-term (Next Month):**
1. Implement three-stage pipeline with CE labeling
2. Add feature extraction and compatibility checking
3. Test with Portuguese corpus
4. Validate CE assignments against Croft's annotations

**Medium-term (Next Quarter):**
1. Extend to Spanish and English
2. Implement visualization of three CE levels
3. Conduct cross-linguistic feature analysis
4. Write up theoretical framework paper

---

**Document Version:** 2.0 (REVISED)  
**Date:** December 2024  
**Status:** Ready for Implementation  
**Key Change:** Mapped Croft's CE labels (not E/V/A/F) to biological units

