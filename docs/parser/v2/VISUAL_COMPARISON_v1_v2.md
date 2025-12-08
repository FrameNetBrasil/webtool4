# Visual Comparison: Framework v1.0 vs v2.0
## Side-by-Side Examples Illustrating the Conceptual Shift

---

## Overview of the Change

### Framework v1.0 (OLD)
**Biological mapping:** Nitrogen bases (A/T/G/C) ←→ Word types (E/V/A/F)
**Focus:** Linear sequence elements (the "alphabet")

### Framework v2.0 (NEW)
**Biological mapping:** Amino acids/Peptides/Polypeptides ←→ Phrasal/Clausal/Sentential CEs
**Focus:** Hierarchical assembly process (the "building process")

---

## Example 1: Simple Portuguese Sentence

### Sentence: "Tomei café da manhã"
*Translation: "I drank breakfast"*

---

#### OLD Framework (v1.0)

```
WORD TYPE CLASSIFICATION:
┌──────────┬──────┬──────┬──────┬──────────┐
│ Word     │ tomei│ café │  da  │  manhã   │
├──────────┼──────┼──────┼──────┼──────────┤
│ Type     │  V   │  E   │  F   │   E      │
└──────────┴──────┴──────┴──────┴──────────┘

MWE ASSEMBLY:
café^da^manhã → aggregated as single E node

FINAL STRUCTURE:
[V:tomei] ──OBJ──> [E:café_da_manhã]

Two nodes: V and E
Dependency: V governs E as object
```

**What we knew:**
- tomei is eventive (V)
- café_da_manhã is entity (E)
- V predicts E as object

**What we didn't capture:**
- No explicit stages of assembly
- No representation of intermediate structures
- No clear linguistic function labels
- Missing: HOW did café+da+manhã become a unit?

---

#### NEW Framework (v2.0)

```
STAGE 1: TRANSCRIPTION (Building Amino Acids)
┌────────────────────────────────────────────────────────┐
│ Phrasal CE Classification:                             │
├──────────────┬─────────┬──────────────────────────────┤
│ Word         │ CE Type │ Features                      │
├──────────────┼─────────┼──────────────────────────────┤
│ tomei        │ Head    │ VerbForm=Fin, Person=1       │
│ café^da^manhã│ Head    │ Gender=Masc, Number=Sing     │
└──────────────┴─────────┴──────────────────────────────┘

BIOLOGICAL PARALLEL:
tomei:         Amino acid with catalytic properties
café_da_manhã: Amino acid with binding properties

Output: Two "amino acids" ready for peptide formation


STAGE 2: TRANSLATION (Building Peptides)
┌────────────────────────────────────────────────────────┐
│ Clausal CE Transformation:                             │
├──────────────┬─────────┬──────────────────────────────┤
│ Phrasal CE   │ → Clausal CE  │ Reason                 │
├──────────────┼───────────────┼──────────────────────────┤
│ tomei:Head   │ → Pred        │ VerbForm=Fin (finite)  │
│ café:Head    │ → Arg         │ Noun in object position│
└──────────────┴───────────────┴──────────────────────────┘

DEPENDENCY FORMATION (Peptide Bond):
┌────────────────────────────────────────────────────────┐
│ [Pred:tomei] ←─ predicate position                     │
│      |                                                 │
│      └──[OBJ]──> [Arg:café_da_manhã]                  │
│                                                        │
│ Feature compatibility check:                           │
│   tomei expects: Entity (NOUN)                        │
│   café_da_manhã provides: NOUN ✓                      │
│   Compatibility: HIGH → bond forms                     │
└────────────────────────────────────────────────────────┘

BIOLOGICAL PARALLEL:
Two amino acids form peptide bond:
Met ──peptide_bond──> Pro
(Pred) ──dependency──> (Arg)

Output: One functional "peptide" (simple clause)


STAGE 3: FOLDING (Integrating Polypeptides)
┌────────────────────────────────────────────────────────┐
│ Sentential CE Assignment:                              │
│                                                        │
│ Single clause → Single polypeptide → [Main]           │
│                                                        │
│       [Main: tomei café da manhã]                      │
│                                                        │
│ Root: tomei (Pred)                                     │
│   └─ café_da_manhã (Arg - object)                     │
└────────────────────────────────────────────────────────┘

BIOLOGICAL PARALLEL:
Simple protein = single polypeptide chain
Like insulin (51 residues, 1 chain)

Output: Complete functional sentence
```

**What we NOW capture:**
✓ Three explicit stages of assembly
✓ Phrasal CEs (amino acids) → Clausal CEs (peptides) → Sentential CEs (polypeptides)
✓ Feature-driven bonding at each stage
✓ Clear linguistic function labels (Pred, Arg)
✓ Process of assembly, not just final product

---

## Example 2: Spanish Agreement-Heavy Phrase

### Phrase: "las tres hermanas grandes"
*Translation: "the three big sisters"*

---

#### OLD Framework (v1.0)

```
WORD TYPE CLASSIFICATION:
┌────────┬──────┬──────┬──────────┬─────────┐
│ Word   │ las  │ tres │ hermanas │ grandes │
├────────┼──────┼──────┼──────────┼─────────┤
│ Type   │  F   │  A   │    E     │    A    │
└────────┴──────┴──────┴──────────┴─────────┘

STRUCTURE:
[F:las] → [E:hermanas]
[A:tres] → [E:hermanas]
[A:grandes] → [E:hermanas]

All modifiers point to head noun (E)
```

**What we missed:**
- How do features drive the bonding?
- Why do all these words form a cohesive unit?
- What makes Spanish different from English?

---

#### NEW Framework (v2.0)

```
STAGE 1: TRANSCRIPTION (Amino Acids with Properties)
┌────────────────────────────────────────────────────────────┐
│ Phrasal CE Classification with Chemical Properties:        │
├──────────┬─────────┬─────────────────────────────────────┤
│ Word     │ CE Type │ Features (= Chemical Properties)     │
├──────────┼─────────┼─────────────────────────────────────┤
│ las      │ Mod     │ Gender=Fem, Number=Plur, Def=Def    │
│ tres     │ Mod     │ Number=Plur                         │
│ hermanas │ Head    │ Gender=Fem, Number=Plur             │
│ grandes  │ Mod     │ Number=Plur                         │
└──────────┴─────────┴─────────────────────────────────────┘

Each word is an "amino acid" with specific properties


STAGE 2: TRANSLATION (Forming Peptide with Multiple Bonds)
┌────────────────────────────────────────────────────────────┐
│ FEATURE-DRIVEN BONDING (Hydrogen Bond Network):            │
│                                                            │
│ las → hermanas:                                            │
│   Gender:  Fem = Fem   ✓ [H-bond 1] strength: 0.3        │
│   Number:  Plur = Plur ✓ [H-bond 2] strength: 0.3        │
│   Total compatibility: 1.6                                │
│                                                            │
│ tres → hermanas:                                           │
│   Number:  Plur = Plur ✓ [H-bond 3] strength: 0.3        │
│   Total compatibility: 1.3                                │
│                                                            │
│ grandes → hermanas:                                        │
│   Number:  Plur = Plur ✓ [H-bond 4] strength: 0.3        │
│   Total compatibility: 1.3                                │
│                                                            │
│ NETWORK EFFECT: 4 H-bonds stabilize the structure         │
│ Result: Strong, cohesive [Arg] peptide                    │
└────────────────────────────────────────────────────────────┘

BIOLOGICAL PARALLEL:
Protein stabilized by extensive H-bond network:
        las
         |  (H-bonds)
    tres-hermanas-grandes
         |
    (multiple weak bonds create strong structure)

CLAUSAL CE: [Arg: las tres hermanas grandes]
            Single functional unit ready for integration


STAGE 3: FOLDING
If part of sentence "Las tres hermanas grandes llegaron":
[Main: las tres hermanas grandes llegaron]
[Arg] + [Pred] → complete clause → single polypeptide
```

**KEY INSIGHT (NEW):**
Spanish uses AGREEMENT (like H-bonds) as primary bonding strategy
- Multiple weak bonds (Gender, Number)
- Create strong network effect
- Cooperative stabilization

Compare to English "the three big sisters":
- Minimal agreement (just Number on verb)
- Position-heavy (word order determines function)
- Like hydrophobic effect (position matters more than bonds)

---

## Example 3: Complex Sentence with Relative Clause

### Sentence: "O menino que eu vi chegou cedo"
*Translation: "The boy that I saw arrived early"*

---

#### OLD Framework (v1.0)

```
WORD TYPE CLASSIFICATION:
┌──────┬────────┬──────┬──────┬──────┬────────┬──────┐
│  o   │ menino │ que  │  eu  │  vi  │ chegou │ cedo │
├──────┼────────┼──────┼──────┼──────┼────────┼──────┤
│  F   │   E    │  F   │  E   │  V   │   V    │  A   │
└──────┴────────┴──────┴──────┴──────┴────────┴──────┘

DEPENDENCY STRUCTURE:
o → menino
menino → chegou (SUBJECT) [crosses que, eu, vi]
que → vi (OBJECT)
eu → vi (SUBJECT)
cedo → chegou (MODIFIER)

Non-projective: menino → chegou crosses intervening words
```

**What we knew:**
- Parse has crossing edges
- menino is subject of chegou despite distance

**What wasn't clear:**
- Why does this crossing happen?
- What linguistic structure causes it?
- How does it relate to biology?

---

#### NEW Framework (v2.0)

```
STAGE 1: TRANSCRIPTION (Amino Acids)
┌──────────┬─────────┬────────────────────────────────┐
│ Word     │ CE Type │ Features                        │
├──────────┼─────────┼────────────────────────────────┤
│ o        │ Mod     │ Definite=Def, Gender=Masc      │
│ menino   │ Head    │ Gender=Masc, Number=Sing       │
│ que      │ Head    │ PronType=Rel                   │
│ eu       │ Head    │ Person=1, Number=Sing          │
│ vi       │ Head    │ VerbForm=Fin, Tense=Past       │
│ chegou   │ Head    │ VerbForm=Fin, Tense=Past       │
│ cedo     │ Head    │ (adverb)                       │
└──────────┴─────────┴────────────────────────────────┘

Seven "amino acids" ready for peptide formation


STAGE 2: TRANSLATION (Forming Two Peptides)
┌────────────────────────────────────────────────────────┐
│ Main Clause Peptide:                                   │
│   [Arg: o menino] ← o (Mod) + menino (Head)           │
│   [Pred: chegou]                                       │
│   [FPM: cedo]                                          │
│                                                        │
│ Relative Clause Peptide:                              │
│   [Rel: que eu vi] ← que (Head), eu (Head), vi (Head) │
│                                                        │
│ Local dependencies established:                        │
│   o → menino (determiner)                             │
│   eu → vi (subject)                                    │
│   que → vi (object)                                    │
└────────────────────────────────────────────────────────┘

Two separate "peptides" ready for folding


STAGE 3: FOLDING (Creating Disulfide Bridge)
┌────────────────────────────────────────────────────────┐
│ Sentential Structure:                                  │
│   [Main: o menino chegou cedo]                         │
│   [Sub: que eu vi]  (relative clause)                  │
│                                                        │
│ DISULFIDE BRIDGE (Long-distance dependency):           │
│                                                        │
│   Linear sequence:                                     │
│   o₁ menino₂ que₃ eu₄ vi₅ chegou₆ cedo₇              │
│                                                        │
│   Dependency:                                          │
│   menino₂ ────────────────────────> chegou₆           │
│            (subject)                                   │
│            CROSSES: que₃, eu₄, vi₅                     │
│                                                        │
│   Visual:                                              │
│        menino₂                                         │
│          |                                             │
│          |  ←─ relative clause (que eu vi)            │
│          |     intervenes in linear order             │
│          |                                             │
│          └────────────────────> chegou₆                │
│                                                        │
└────────────────────────────────────────────────────────┘

BIOLOGICAL PARALLEL (DISULFIDE BRIDGE):
┌────────────────────────────────────────────────────────┐
│ Protein sequence:                                      │
│   ...Cys₁₅─Ala₁₆─Gly₁₇─Val₁₈─...─Leu₇₆─Pro₇₇─Cys₇₈...│
│                                                        │
│   Linear: Cys₁₅ and Cys₇₈ far apart                   │
│   3D structure:                                        │
│                                                        │
│        Cys₁₅                                           │
│          |                                             │
│          S                                             │
│          |  ←─ disulfide bridge                       │
│          S     (crosses intervening residues)         │
│          |                                             │
│        Cys₇₈                                           │
│                                                        │
│ Same mechanism:                                        │
│   - Linear sequence: separated                         │
│   - Final structure: directly connected                │
│   - Connection crosses intervening units               │
│   - Essential for stability/function                   │
└────────────────────────────────────────────────────────┘

Final Parse Graph:
         chegou⁶ [Main:Pred] (ROOT)
         /      \
    menino²    cedo⁷
       |
      o¹   que³ [Sub:Rel]
             \
            vi⁵
            /
          eu⁴

Non-projective edge: menino² → chegou⁶
This is the "disulfide bridge"
```

**KEY INSIGHT (NEW):**
- Relative clauses create DISULFIDE-LIKE BRIDGES
- Linear order: menino and chegou separated
- Dependency structure: direct connection
- Crosses intervening words (que, eu, vi)
- Exactly analogous to Cys-S-S-Cys bridges in proteins
- Essential for complex sentence structure
- Happens in STAGE 3 (Folding), not earlier

---

## Example 4: Single vs. Multiple Polypeptides

### Understanding Variable Clause Numbers

---

#### OLD Framework (v1.0)

```
No explicit representation of clause structure
Parse trees showed all dependencies equally
No distinction between simple and complex sentences
at the structural level
```

---

#### NEW Framework (v2.0)

```
EXAMPLE A: Simple Sentence = ONE Polypeptide
───────────────────────────────────────────────

Sentence: "Tomei café"

Clausal structure: ONE clause
[Pred: tomei] + [Arg: café] → single functional unit

Sentential CE: [Main: tomei café]

Biological parallel:
┌────────────────────────────────────────┐
│ Single-chain protein (like INSULIN)    │
│                                        │
│ H₂N─[Met─Ala─Gly─...─Leu]─COOH       │
│     (51 amino acids, 1 chain)          │
│                                        │
│ Simple structure                        │
│ Direct function                         │
│ No multi-chain assembly needed         │
└────────────────────────────────────────┘


EXAMPLE B: Complex Sentence = MULTIPLE Polypeptides
──────────────────────────────────────────────────

Sentence: "O menino chegou porque estava cansado"
         (The boy arrived because he-was tired)

Clausal structure: TWO clauses
Main: [Pred: chegou] + [Arg: o menino]
Sub:  [Pred: estava] + [Arg: cansado]

Sentential CEs: 
[Main: o menino chegou]
[Sub: porque estava cansado]

Biological parallel:
┌────────────────────────────────────────┐
│ Multi-chain protein (like HEMOGLOBIN)  │
│                                        │
│ Chain A: α₁─[sequence]─COOH           │
│ Chain B: α₂─[sequence]─COOH           │
│ Chain C: β₁─[sequence]─COOH           │
│ Chain D: β₂─[sequence]─COOH           │
│                                        │
│ Four chains must fold together         │
│ Complex assembly process                │
│ Inter-chain interactions critical       │
└────────────────────────────────────────┘


KEY INSIGHT:
┌────────────────────────────────────────────────────┐
│ Not all sentences have multiple clauses            │
│ Not all proteins have multiple chains              │
│                                                    │
│ BUT: All follow the SAME ASSEMBLY PROCESS          │
│                                                    │
│ Simple sentence:                                   │
│   Stage 1: Build amino acids (words)              │
│   Stage 2: Form peptide (single clause)           │
│   Stage 3: Fold (finalize structure)              │
│                                                    │
│ Complex sentence:                                  │
│   Stage 1: Build amino acids (words)              │
│   Stage 2: Form peptides (multiple clauses)       │
│   Stage 3: Fold together (integrate clauses)      │
│                                                    │
│ FOCUS ON PROCESS, NOT PRODUCT!                    │
└────────────────────────────────────────────────────┘
```

---

## Example 5: Cross-Linguistic Variation

### Different Languages = Different Folding Strategies

---

#### Spanish (Agreement-Heavy)

```
Sentence: "Las tres hermanas grandes llegaron"
         (The three big sisters arrived)

FEATURE PROFILE:
┌────────────────────────────────────────────┐
│ Dominant feature: AGREEMENT                │
│   - Gender (Fem throughout)                │
│   - Number (Plur throughout)               │
│   - 5-6 agreement bonds                    │
│                                            │
│ Bonding strategy: H-bond network           │
│   - Multiple weak bonds                    │
│   - Cooperative effect                     │
│   - High redundancy                        │
└────────────────────────────────────────────┘

BIOLOGICAL PARALLEL:
Proteins stabilized by H-bond networks
Example: β-sheets with extensive H-bonding
Many weak interactions → strong structure
```

---

#### Russian (Case-Heavy)

```
Sentence: "Мальчик видит девочку"
         (Boy sees girl)
         
FEATURE PROFILE:
┌────────────────────────────────────────────┐
│ Dominant feature: CASE                     │
│   - Мальчик: Nominative (subject)          │
│   - девочку: Accusative (object)           │
│   - Strong ionic bonds                     │
│                                            │
│ Bonding strategy: Charge-based             │
│   - Few strong bonds                       │
│   - Direct position determination          │
│   - Word order flexible                    │
└────────────────────────────────────────────┘

BIOLOGICAL PARALLEL:
Proteins stabilized by ionic bonds (salt bridges)
Example: Charged residues (Lys, Asp, Glu, Arg)
Few strong interactions determine structure
Position less constrained
```

---

#### English (Position-Heavy)

```
Sentence: "The big dog saw a cat"

FEATURE PROFILE:
┌────────────────────────────────────────────┐
│ Dominant feature: POSITION                 │
│   - Minimal case marking                   │
│   - Minimal agreement                      │
│   - Word order determines function         │
│                                            │
│ Bonding strategy: Hydrophobic effect       │
│   - Position in sequence critical          │
│   - Few specific interaction types         │
│   - Overall architecture dominant          │
└────────────────────────────────────────────┘

BIOLOGICAL PARALLEL:
Proteins stabilized by hydrophobic effect
Example: Hydrophobic core formation
Position in sequence determines structure
Fewer specific bonds, more global effects
```

---

## Terminology Comparison Table

| Concept | OLD (v1.0) | NEW (v2.0) | Notes |
|---------|-----------|-----------|-------|
| Basic unit | Word type (E/V/A/F) | Phrasal CE (Head/Mod/etc.) | More linguistically grounded |
| Noun | E (entity) | Head → Arg | Shows transformation across stages |
| Verb | V (eventive) | Head → Pred | Captures predicative function |
| Adjective | A (attribute) | Mod (phrasal) | Modifier role explicit |
| Function word | F | Various (Adm/Lnk/etc.) | Differentiated by function |
| MWE | Aggregated E/V/A | Head (MWE) → Arg/Pred | Same assembly mechanism |
| Phrase | Implicit | Clausal CE (Arg/Pred/FPM) | Explicit functional labels |
| Clause | Implicit | Sentential CE (Main/Sub) | Explicit hierarchy |
| Dependency | Link | Peptide bond | Biological mechanism clear |
| Crossing edge | Non-projective | Disulfide bridge | Biological parallel explicit |
| Feature | Annotation | Chemical property | Drives assembly actively |

---

## Visual Summary: What Changed

```
┌─────────────────────────────────────────────────────────┐
│                     OLD (v1.0)                          │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Words → Type (E/V/A/F) → Parse Tree                   │
│                                                         │
│  Focus: WHAT type is each word?                        │
│  Missing: HOW do structures assemble?                  │
│                                                         │
└─────────────────────────────────────────────────────────┘
                         ↓↓↓
                   TRANSFORMATION
                         ↓↓↓
┌─────────────────────────────────────────────────────────┐
│                     NEW (v2.0)                          │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Stage 1: Words → Phrasal CEs (amino acids)            │
│           Features = chemical properties                │
│           MWE assembly like secondary structures        │
│                                                         │
│  Stage 2: Phrasal CEs → Clausal CEs (peptides)         │
│           Feature compatibility = bonding energy        │
│           Agreement = H-bonds, Case = ionic bonds       │
│                                                         │
│  Stage 3: Clausal CEs → Sentential (polypeptides)      │
│           Clause integration = folding                  │
│           Long-distance deps = disulfide bridges        │
│                                                         │
│  Focus: HOW do structures assemble at each level?       │
│  Captures: Process of hierarchical composition          │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

## Benefits of New Framework

### 1. Theoretical Clarity
- Grounded in Croft's established linguistic theory
- Three levels map to three biological stages
- Process over product focus

### 2. Biological Accuracy
- Amino acids/Peptides/Polypeptides parallel is natural
- Features as chemical properties makes sense
- Assembly stages match protein synthesis

### 3. Computational Explicitness
- Three clear processing stages
- Intermediate representations visible
- Easy to debug and validate

### 4. Cross-Linguistic Power
- Explains language variation systematically
- Same mechanism, different feature profiles
- Like protein families with different folding strategies

### 5. Empirical Testability
- Makes specific predictions about processing
- Stage boundaries should be observable
- Feature effects should be measurable

---

## Conclusion

The shift from E/V/A/F word types to Phrasal/Clausal/Sentential CEs represents a fundamental reconceptualization:

**OLD:** Static classification of word types
**NEW:** Dynamic process of hierarchical assembly

**OLD:** Focus on the "alphabet" (bases → word types)
**NEW:** Focus on the "building process" (amino acids → peptides → proteins)

**OLD:** Parse tree as product
**NEW:** Three-stage assembly as process

This new framework:
- ✅ Captures HOW structures form, not just WHAT they are
- ✅ Makes biological parallels explicit and accurate
- ✅ Provides richer linguistic representation
- ✅ Enables cross-linguistic comparison
- ✅ Supports empirical validation

**Most importantly:** It shifts focus from PRODUCT (final parse) to PROCESS (how parsing happens), which is where the real insight lies.

---

**Document Type:** Visual Comparison Guide  
**Version:** 2.0  
**Date:** December 2024  
**Status:** Complete  
**Related:** All v2.0 documents
