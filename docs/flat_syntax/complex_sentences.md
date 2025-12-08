# Complex Sentences in Flat Syntax: Annotation Guide

## Overview

In flat syntax, complex sentences are annotated **without recursion** - all clauses exist at the same syntactic level, separated by `#` boundaries. The key challenge is determining when you have multiple clauses vs. a single clause, and whether one clause interrupts another.

## Main Types of Complex Sentences

### 1. **Coordination**
**Pattern:** Two juxtaposed clauses, often with conjunction

**English Examples:**
```
I + ate # and + I + left
after + I + ate # I + left
```

**Classification:** Always two separate clauses marked with `#`, regardless of semantic relationship (temporal, causal, conditional, etc.)

**Note:** The conjunction (and, but, or) belongs to the second clause as a Conj CE

---

### 2. **Complement Clauses**
**Pattern:** Clause serving as argument of another clause's predicate

**English Examples:**
```
That + he + resigned # isn't + surprising
I + told + her # that + I + bought + a bicycle
```

**Classification:** Two clauses unless the complement-taking predicate (CTP) has grammaticalized into an auxiliary/modal

**Interruption cases:**
- **No interruption:** SVO with object complement: `# Sbj + V # Comp #`
- **Interruption:** SOV with object complement: `# Sbj {Comp} V #`

---

### 3. **Relative Clauses**
**Pattern:** Clause modifying a referring phrase

**English Examples:**

**Non-interrupting:**
```
Bilbo + found + the ring # that + Gollum + had + lost
```

**Interrupting:**
```
The tree {that + fell + on {my} house} had + died + last winter
The man {who's + picking + pears} comes + down + from the tree
```

**Classification:** Separate clause at sentential CE level (marked as Rel), even though functionally modifying a phrase

**Extraposition alternative:**
```
The tree + was + dead # that + fell + on {my} house
```

---

## Border Cases & Ambiguities

### 1. **Insubordination**
**Problem:** Subordinate clause morphology used for main clause speech acts

**Examples:**
- Spanish Subjunctive: `Dígan-se-lo Ustedes` ("Tell them about it")
- English Gerund: `No smoking`
- Russian Infinitive: `Ne kurit'` ("No smoking")

**Annotation guidance:** Annotate as **single clause** (Main) despite subordinate morphology

---

### 2. **Coordinate Deranking**
**Problem:** Coordinate meaning but subordinate morphology

**Japanese example (English translation):**
```
The old man worked at the mountain # and + the old woman tended the store
```
(First clause uses converb/-te form typical of subordination)

**Annotation guidance:** Annotate as **two clauses** based on semantic assertion of both events

---

### 3. **Speech Act Constructions in Subordinate Clauses**
**Problem:** Main clause inversion patterns appearing in syntactically subordinate clauses

**Examples:**
- ✗ `*Nixon regrets that never before have prices been so high`
- ✓ `I knew that never before have prices been so high`
- ✗ `*I'm leaving, if here comes my bus`
- ✓ `I'm leaving, because here comes my bus`

**Annotation guidance:** Annotate based on **morphosyntax** (subordinator = subordinate clause), not illocutionary force

---

### 4. **Complex Predicates**
**Problem:** When do multiple predicate-like elements form one clause vs. two?

**Examples:**
- Modal + main verb: `might + lose` → **One clause** (CPP + Pred)
- Full CTP + complement: `She + wanted # to + leave` → **Two clauses**

**Annotation criteria:**
- **One clause** if:
  - CTP has grammaticalized to auxiliary/modal
  - Shared argument structure
  - CTP lacks other CEs
- **Two clauses** if:
  - Independent events
  - Separate argument structures
  - CTP has own arguments

**Grammaticalization cline:**
```
Full clause → Auxiliary → Affix
[two clauses] → [CPP in one clause] → [bound morpheme]
```

---

### 5. **Property Concept "Relative Clauses"**
**Problem:** Property words with relative clause morphology

**Examples:**
- Languages where adjectives take relative clause marking
- Often single-word forms

**Annotation guidance:** Consider as **lexical modifiers** (Mod at phrasal CE level) if:
- Single word (+ possible relativizer)
- No independent arguments
- Functions like adjective

Otherwise annotate as full relative clause

---

### 6. **Discontinuous vs. Split Constructions**
**Problem:** Are interrupted parts one construction or two?

**Polish example:**
```
Piękny + mają + ogród. Dom + mają + kiepski
beautiful have garden house have crummy
"They have a beautiful garden. Their house is crummy."
```

**Annotation guidance:**
- If parts have **different discourse functions** → annotate as **separate phrases**
- If purely positional interruption → use `{  }` notation

---

## Key Diagnostic Questions for Annotation

1. **Are both events asserted?**
   - Yes → likely coordinate (two Main clauses)
   - No → likely subordinate (Main + Adv/Comp/Rel)

2. **Is there deranked predicate morphology?**
   - Converb/participle/infinitive → subordinate clause CE
   - Unless insubordination pattern

3. **Does the construction interrupt another?**
   - Use `{  }` notation for center-embedding
   - Check if word order alternative exists

4. **Complement-taking predicate or complex predicate?**
   - Independent event + separate arguments → two clauses
   - Grammaticalized auxiliary + shared arguments → one clause (CPP + Pred)

5. **Single-word "clause" or lexical dependent?**
   - Consider reanalysis as lexical Mod or CPP
   - Especially for possessives and property concepts

---

## Practical Annotation Strategy

**Default assumption:** When in doubt, annotate as **separate clauses** at sentential CE level, marked with `#`

**Use single-clause analysis** only when:
- Clear grammaticalization to auxiliary/modal
- Shared argument structure
- Or lexical-level phenomenon (single word functioning as modifier)

**Remember:** Flat syntax prioritizes **morphosyntactic form** over semantic/pragmatic relations for initial segmentation
