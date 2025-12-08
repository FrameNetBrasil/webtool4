# Complex Sentences in Flat Syntax: Annotation Guide

## Part I: Theoretical Foundation

### 1.1 Complex Sentences Without Recursion

In flat syntax, **all clauses exist at the same syntactic level**. There is no recursive embedding where one clause is syntactically part of another. Instead:

- Clauses are separated by `#` boundaries
- Clauses are juxtaposed sequentially
- One clause may interrupt another (marked with `{  }`), but this is a linear interruption, not hierarchical embedding

**The representing asymmetry by recursion assumption** (which flat syntax rejects): the traditional assumption that asymmetric relations between entities must be represented by part-whole embedding relations.

**Flat syntax alternative:** Asymmetric semantic or pragmatic relations between events can be represented by:
- Sequential juxtaposition of clauses
- Word order patterns
- Morphosyntactic marking (subordinators, deranked predicates)
- CE labels (Main, Adv, Comp, Rel)

### 1.2 The Four-Way Classification

Complex sentences involve clauses that are classified at the **Sentential CE level** using four primary labels:

| CE Label | Description | Pragmatic/Semantic Characterization |
|----------|-------------|-------------------------------------|
| **Main** | Main clause | Pragmatically **asserted** event |
| **Adv** | Adverbial clause | Non-asserted event; temporal/causal/conditional relation to main event |
| **Comp** | Complement clause | Action/proposition serving as argument of predicate |
| **Rel** | Relative clause | Action/state serving as modifier of referent |

**Critical insight from Croft:** The morphosyntactic distinction between "main" and "subordinate" clauses is NOT a perfect indicator of syntactic embedding. It is better understood as indicating:

1. **Pragmatic distinction:** Asserted vs. non-asserted events
2. **Semantic relations:** Between events (temporal, causal, etc.) or between event and participant
3. **Morphosyntactic strategies:** Coordination markers, subordinators, deranked predicates

### 1.3 Deranked Predicates

**Deranked verb forms** are verb forms that overtly code their relation to the sentence. They include:

- **Converbs** (adverbial clause marking)
- **Participles** (relative clause marking)
- **Infinitives/Action nominals** (complement clause marking)
- **Gerunds** (various functions)

**Key principle:** Deranked predicates are **cues** to clause type, but not absolute determiners. The same form may appear in different clause types depending on context.

### 1.4 Why the Main/Subordinate Distinction is Fuzzy

**Evidence that challenges traditional embedding:**

1. **Coordinate deranking:** Some languages use subordinate morphology for coordinate meanings
2. **Insubordination:** Subordinate morphology used for main clause speech acts
3. **Speech act constructions:** Main clause patterns appearing in subordinate clauses
4. **Polyfunctionality:** Same subordinator for different clause types

**Annotation principle:** In flat syntax, we annotate based on **morphosyntactic form** and **pragmatic/semantic function**, not on assumed hierarchical structure.

---

## Part II: Main Clauses

### 2.1 Characteristics

**Main clauses** are characterized by:
- **Pragmatically asserted** events
- Fully inflected predicates (not deranked)
- Can stand alone as independent utterances
- CE label: **Main**

### 2.2 Coordination of Main Clauses

**Pattern:** Multiple main clauses juxtaposed, often with conjunction

**English examples:**
```
I + ate # and + I + left
She + was + exhausted # and + she + went + to bed
John + had + no money # but + he + went + into this expensive restaurant
```

**Annotation:**
- Each clause marked with `#`
- Conjunction (and, but, or) belongs to the **second clause** as a **Conj** CE
- Both clauses labeled **Main** at sentential CE level

**Semantic relations:** The same semantic relations found in adverbial subordination (temporal, causal, conditional, adversative) can be expressed through coordination:

| Semantic Relation | Subordinate Form | Coordinate Form |
|-------------------|------------------|-----------------|
| Temporal (anterior) | `He washed the car # before + driving to the party` | `He washed the car # and + drove to the party` |
| Causal | `She went to bed # because + she + was + exhausted` | `She + was + exhausted # and + she + went + to bed` |
| Means | `He got into the army # by + lying about his age` | `He lied about his age # and + got into the army` |
| Conditional | `If + you + do + that # the terrorists + have + won` | `You + do + that # and + the terrorists + have + won` |
| Adversative | `Although + John + had + no money # he + went into this restaurant` | `John + had + no money # but + he + went into this restaurant` |

**Key insight:** Coordination vs. subordination is NOT determined by semantic relations between events, but by pragmatic assertion status and morphosyntactic form.

### 2.3 Border Case: Coordinate Deranking

**Problem:** Some languages use deranked (subordinate-type) verb forms in coordinate constructions where both events are asserted.

**Japanese example (English translation):**
```
The old man worked at the mountain # and + the old woman tended the store
```
(In Japanese, first clause uses converb/-te form typical of subordination)

**Annotation guidance:**
- Annotate as **two Main clauses** if both events are pragmatically asserted
- The deranked form is a morphological strategy for coordination, not true subordination
- Mark both clauses with `#` and label both as **Main**

### 2.4 Border Case: Insubordination

**Problem:** Subordinate clause morphology used for main clause speech acts

**Examples:**

*Spanish Subjunctive for imperatives:*
```
Dígan-se-lo + Ustedes
tell:3SG.SUBJ-REFL-3SG.OBJ 2PL.FORMAL
"Tell them about it."
```

*English Gerund for prohibitive:*
```
No smoking
```

*Russian Infinitive for prohibitive:*
```
Ne + kurit'
NEG smoke-INF
"No smoking"
```

**Annotation guidance:**
- Annotate as **single Main clause** at sentential CE level
- The subordinate morphology is repurposed for speech act functions
- Do not create a phantom "matrix clause"

### 2.5 Border Case: Speech Act Constructions in Subordinate Clauses

**Problem:** Constructions typically restricted to main clauses appearing in syntactically subordinate clauses

**Examples of main clause inversion constructions:**

*Acceptable in certain subordinate contexts:*
```
I + knew # that + never before + have + prices + been + so high
I'm + leaving # because + here + comes + my bus
```

*Not acceptable in other subordinate contexts:*
```
*Nixon + regrets # that + never before + have + prices + been + so high
*I'm + leaving # if + here + comes + my bus
```

**Annotation guidance:**
- Annotate based on **morphosyntactic structure** (presence of subordinator)
- Label clause as **Adv** or **Comp** based on subordinator type
- The illocutionary force is pragmatic, not syntactic

**Theoretical implication:** Morphosyntactic "subordinate" structures are not absolute indicators of clause status, but rather strategies that typically (but not always) correlate with non-assertion.

---

## Part III: Adverbial Clauses

### 3.1 Characteristics

**Adverbial clauses** are characterized by:
- **Non-asserted events**
- Express temporal, causal, conditional, or other circumstantial relations to main event
- Often marked by subordinating conjunctions (when, because, if, although)
- Or by deranked predicates (converbs)
- CE label: **Adv**

### 3.2 Adverbial Subordinators

**English examples with subordinating conjunctions:**

*Temporal:*
```
after + I + ate # I + left
```

*Causal:*
```
She + went + to bed # because + she + was + exhausted
```

*Conditional:*
```
If + you + do + that # the terrorists + have + won
```

*Adversative:*
```
Although + John + had + no money # he + went + into this expensive restaurant
```

**Annotation:**
- Adverbial clause marked with `#` boundary
- Subordinator (after, because, if, although) belongs to the adverbial clause as a clausal CE
- Main clause labeled **Main**, adverbial clause labeled **Adv**
- Order of clauses can vary (Adv # Main or Main # Adv)

### 3.3 Converbs (Deranked Adverbial Predicates)

**Converbs** are non-finite verb forms specialized for adverbial clause functions.

**English examples:**

*Gerund converb:*
```
He + got + into the army # by + lying + about his age
```

*Participial converb:*
```
after + I + ate # I + left
```
(Note: "after eating" would use gerund form)

*Infinitive converb:*
```
he + bought + a computer # to + work
```

**Cross-linguistic note:** Many languages have dedicated converb forms that mark:
- Temporal relations (simultaneous, sequential)
- Manner
- Cause
- Condition

**Annotation guidance:**
- Converb clause is still a full clause at sentential CE level
- Mark as **Adv** 
- The converb form is the **Pred** CE at clausal level
- Morphological marking on the converb is glossed in the IMT line

### 3.4 Interruption Patterns

**Adverbial clauses rarely interrupt their matrix clause.** They typically occur:
- Sentence-initially: `# Adv # Main #`
- Sentence-finally: `# Main # Adv #`

**Rare interrupting cases:**

If interruption occurs, use `{  }` notation:
```
The man {when + he + arrived} immediately + left
```

**But such patterns are extremely rare cross-linguistically and in actual discourse.**

### 3.5 Multiple Adverbial Clauses

**Question:** When multiple adverbial clauses occur, are they recursively embedded?

**Flat syntax answer:** No. They are sequentially juxtaposed.

**Example:**
```
# After + I + woke up # when + the alarm + rang # I + made + coffee #
```

**Annotation:**
- Three separate clauses, all at sentential CE level
- First two labeled **Adv**
- Last labeled **Main**
- No hierarchical embedding of adverbial clauses

**Morphosyntactic evidence:** Languages do not distinguish between an adverbial clause subordinate to a main clause vs. an adverbial clause subordinate to another adverbial clause. The same converb or subordinator forms are used regardless of semantic scope.

---

## Part IV: Complement Clauses

### 4.1 Characteristics

**Complement clauses** are characterized by:
- Serve as **argument** of a complement-taking predicate (CTP)
- Denote actions or propositions
- Often marked by complementizers (that, whether, if)
- Or by deranked predicates (infinitives, action nominals)
- CE label: **Comp**

### 4.2 Types of Complement-Taking Predicates (CTPs)

| CTP Type | Examples | Typical Complement Form (English) |
|----------|----------|-----------------------------------|
| Utterance | say, tell, report | that-clause, direct quote |
| Propositional attitude | believe, think, know | that-clause |
| Perception | see, hear, feel | -ing clause, bare infinitive |
| Desiderative | want, wish, hope | to-infinitive |
| Manipulative | make, let, force, persuade | to-infinitive, bare infinitive |
| Modal | can, must, should | bare infinitive (grammaticalized) |
| Aspectual | begin, finish, continue | -ing clause, to-infinitive (grammaticalized) |
| Negative | not, never | (grammaticalized) |

### 4.3 Finite Complement Clauses

**Finite complements** have fully inflected predicates and complementizers.

**Examples:**

*Utterance CTP:*
```
I + told + her # that + I + bought + a bicycle
```

*Propositional attitude CTP:*
```
That + he + resigned # isn't + surprising
```
(Subject complement)

**Annotation:**
- Complement clause marked with `#` boundary
- Complementizer (that, whether, if) belongs to complement clause as clausal CE
- Matrix clause labeled **Main**, complement clause labeled **Comp**

**Alternative: Extraposition**
```
It + isn't + surprising # that + he + resigned
```
(No interruption with extraposed complement)

### 4.4 Infinitives and Action Nominals (Deranked Complement Predicates)

**Infinitives** are non-finite verb forms specialized for complement clause functions.

**English examples:**

*Desiderative CTP:*
```
She + wanted # to + leave
```

*Manipulative CTP:*
```
I + persuaded + him # to + apply
```

**Action nominals** (gerunds in English) also function as complements:
```
I + enjoyed # reading + that book
```

**Annotation guidance:**
- Infinitive/gerund clause is still a clause at sentential CE level (if it has clause-like properties)
- Mark as **Comp**
- The infinitive/gerund is the **Pred** CE at clausal level
- Morphological marking is glossed in IMT line

### 4.5 Interruption Patterns

**Complement clauses may interrupt matrix clauses** depending on word order:

| Matrix Order | Complement Position | Interruption? | Pattern |
|--------------|---------------------|---------------|---------|
| SVO | Subject complement | NO | `# Comp # V + Obj #` |
| SVO | Object complement | NO | `# Sbj + V # Comp #` |
| SOV | Subject complement | YES | `# Comp # Obj + V #` (rare) |
| SOV | Object complement | YES | `# Sbj {Comp} V #` |

**English examples:**

*No interruption (SVO object complement):*
```
I + told + her # that + I + bought + a bicycle
```

*Interruption (if English were SOV):*
```
I {that + I + bicycle + bought} her + told
```

**Mitigating factors:**
- **Preferred Argument Structure:** Transitive subjects (A role) often unexpressed, reducing interruption
- **Extraposition:** Many languages allow complement postposing
- **Direct report strategy:** Utterance complements often pre- or postposed

### 4.6 Complement Clauses vs. Complex Predicates

**Critical distinction:** When does a CTP + complement form two clauses vs. one clause with a complex predicate?

**Criteria for TWO CLAUSES (Main + Comp):**
- CTP denotes independent or partly independent event
- Separate or partly separate argument structures
- CTP has its own clausal CEs (arguments, other CPPs)
- Deranked but still clause-like complement

**Criteria for ONE CLAUSE (CPP + Pred):**
- CTP has grammaticalized to auxiliary or modal
- Fully shared argument structure
- CTP lacks other CEs
- Semantic contribution is modal, aspectual, or polarity

**Grammaticalization cline:**
```
Full CTP → Auxiliary/Modal → Affix
[Main # Comp] → [CPP + Pred] → [bound morpheme]
```

**English examples:**

*Two clauses:*
```
She + wanted # to + leave
I + told + her # that + I + bought + a bicycle
```

*One clause (complex predicate):*
```
She + might + lose
I + have + eaten
They + are + running
```

**Annotation of complex predicates:**
- Auxiliary/modal = **CPP** at clausal CE level
- Main verb = **Pred** at clausal CE level
- Both within single clause (no `#` boundary between them)

**Examples in table format:**

```
Analyzed Text: She + might + lose + the game
Clausal CEs:   Arg  CPP    Pred   Arg
Sentential CE: Main
```

```
Analyzed Text: She + wanted # to + leave
Clausal CEs:   Arg  Pred     Conj Pred
Sentential CE: Main   Comp
```

**Border cases:** The grammaticalization continuum means some CTPs are intermediate:

- **Perception verbs:** Often lose complement clause properties
- **Desideratives:** May grammaticalize to future/irrealis markers
- **Aspectual verbs:** Often grammaticalize to aspect auxiliaries

**Annotation guidance:** When in doubt, check for:
1. Independent argument structure → two clauses
2. Morphological reduction of CTP → complex predicate
3. Loss of other CEs around CTP → complex predicate

---

## Part V: Relative Clauses

### 5.1 Characteristics

**Relative clauses** are characterized by:
- Serve as **modifier** of a referring phrase
- Denote actions, states, or properties
- Often marked by relativizers (that, which, who)
- Or by deranked predicates (participles)
- CE label: **Rel**

**Functional role:** Despite modifying a phrase, relative clauses are annotated as **clauses at the sentential CE level** in flat syntax, not as embedded within phrases.

### 5.2 Externally-Headed Relative Clauses

**Most common cross-linguistically.** The head noun is an overt argument of the matrix clause, and the relative clause modifies it.

**English examples:**

*Non-interrupting (postposed):*
```
Bilbo + found + the ring # that + Gollum + had + lost
```

*Interrupting (subject relative):*
```
The tree {that + fell + on {my} house} had + died + last winter
The man {who's + picking + pears} comes + down + from the tree
```

**Annotation:**
- Relative clause marked with `#` boundary (or `{  }` if interrupting)
- Relativizer (that, which, who) belongs to relative clause as clausal CE
- Matrix clause labeled **Main**, relative clause labeled **Rel**
- External head is an argument of the matrix clause

### 5.3 Word Order and Interruption Patterns

**Relative clause position relative to head noun:**

| Language Order | Relative Clause Order | Subject Relative | Object Relative |
|----------------|-----------------------|------------------|-----------------|
| SVO | NRel (postposed) | **INTERRUPTS** | No interruption |
| SVO | RelN (preposed) | No interruption (rare) | **INTERRUPTS** |
| SOV | RelN (preposed) | No interruption | No interruption (if A unexpressed) |
| SOV | NRel (postposed) | **INTERRUPTS** | **INTERRUPTS** |

**English patterns (SVO + NRel):**

*Subject relative (interrupts):*
```
# The man {who + lives + next door} is + a teacher #
```

*Object relative (no interruption):*
```
# I + met + the man # who + lives + next door #
```

**Cross-linguistic note:**
- **SVO languages:** Preposed relatives (RelN) extremely rare
- **SOV languages:** Both orders found; interruption varies
- **Preferred Argument Structure:** Reduces interruption when A argument unexpressed

### 5.4 Extraposition of Relative Clauses

**English allows extraposition of relative clauses on subjects to avoid interruption:**

*Interrupting:*
```
The tree {that + fell + on {my} house} was + dead
```

*Extraposed:*
```
The tree + was + dead # that + fell + on {my} house
```

**Annotation of extraposition:**
- Same as non-interrupting relative clause
- Relative clause follows matrix clause with `#` boundary
- Labeled **Rel** at sentential CE level

### 5.5 Participles (Deranked Relative Predicates)

**Participles** are non-finite verb forms specialized for relative clause functions.

**English examples:**

*Present participle:*
```
The man {picking + pears} came + down
```

*Past participle:*
```
The house {built + by my father} was + destroyed
```

**Annotation guidance:**
- Participial relative clause is still a clause at sentential CE level
- Mark as **Rel** (or `{Rel}` if interrupting)
- The participle is the **Pred** CE at clausal level
- Morphological marking on participle glossed in IMT line

**Cross-linguistic variation:**
- Many languages use participles as primary relative clause strategy
- Some languages have multiple participle forms for different tenses/aspects
- Participles may grammaticalize to adjectives (see §5.7)

### 5.6 Infinitive 

```
The product {to + kill + ants}  is + on + the table
``` 

**Characteristics:**

- The infinitive modifies a noun (product)
- There is participant sharing: "product" is a participant in the killing event (as INSTRUMENT/MEANS)
- Can often be paraphrased with a full relative clause: "The product [that/which kills/is used to kill ants]"


### 5.7 Alternative Relative Clause Strategies

**Several strategies minimize or eliminate interruption:**

#### 5.7.1 Internally-Headed Relative Clauses

**The head noun is expressed inside the relative clause, not in the matrix clause.**

**Japanese-type example (English approximation):**
```
# I + ate [WHAT: rice + that I + bought] #
```

**Interruption patterns:**
- **SVO, subject head:** No interruption
- **SVO, object head:** No interruption  
- **SOV, subject head:** No interruption
- **SOV, object head:** No interruption (if A unexpressed)

**Result:** Internally-headed relatives minimize interruption, especially in SOV languages (where they are most common).

#### 5.7.2 Correlative Relative Clauses

**Relative clause and matrix clause juxtaposed, each with correlated demonstrative/pronoun.**

**Hindi-type example (English approximation):**
```
# Which + man + came # that man + is + my friend #
```

**Annotation:**
- Two clauses juxtaposed with `#`
- First clause labeled **Rel**
- Second clause labeled **Main**
- No interruption

#### 5.7.3 Adjoined Relative Clauses

**Relative clause functions like an adverbial clause rather than embedded modifier.**

**Annotation:**
- Similar to correlative
- Relative clause marked **Rel**
- Matrix clause marked **Main**
- No interruption

### 5.8 Property Concept "Relative Clauses"

**Problem:** In many languages, property concepts (adjectives) use relative clause morphology when modifying nouns.

**Characteristics:**
- Single word (possibly + relativizer/linker)
- Property concept predicate (tall, red, old)
- Relative clause verbal inflections
- No independent arguments (only the head referent role)

**Example pattern (schematic):**
```
tree [that-is-tall]
```

**Annotation guidance:**

**If truly clause-like:**
```
# tree {that + is + tall} #
Rel clause
```

**If grammaticalized to lexical modifier:**
```
tree + tall
Mod at phrasal CE level
```

**Criteria for lexical modifier analysis:**
- Single word or word + invariant relativizer
- No argument structure beyond head
- Grammaticalized relative marking
- Functions identically to underived adjectives

**Theoretical note:** Property concept words with relative clause morphology represent an intermediate stage in grammaticalization from clause to lexical modifier.

### 5.9 Prosody and Relative Clause Structure

**English prosodic pattern:** Relative clauses on subjects are consistently produced in the same intonation unit as their head, while relative clauses on objects are in separate intonation units.

**Example:**

*Subject relative (single intonation unit):*
```
7,78 [Meanwhile...] the man who's picking pears,
7,79 comes down from the tree.
```

*Object relative (separate intonation units):*
```
10,29 and put them in a couple of barrels,
10,30 that he's got down there.
```

**Theoretical implication:** The prosodic pattern for subject relatives suggests speakers may construe the external head + relative clause as a single unit, despite the morphosyntactic interruption pattern.

**Annotation note:** Flat syntax maintains morphosyntactic annotation regardless of prosodic grouping, but prosody may reflect alternative functional analysis.

---

## Part VI: Diagnostic Decision Tree for Annotation

### Step 1: How many clauses?

**Ask:** Is there more than one predicate element?

- **One predicate + auxiliaries/modals** → One clause (complex predicate: CPP + Pred)
- **Two+ independent predicates** → Multiple clauses → Proceed to Step 2

**Complex predicate check:**
- Shared argument structure?
- CTP grammaticalized to auxiliary?
- CTP lacks independent CEs?
→ If YES to all: One clause

### Step 2: What is the pragmatic status?

**Ask:** Are the events asserted or non-asserted?

- **Both/all asserted** → Coordinate main clauses (all **Main**)
- **Mixed assertion status** → Main + subordinate → Proceed to Step 3

**Assertion check:**
- Can it stand alone as independent utterance?
- Would it be acceptable response to "What happened?"
- Lacks subordinate morphology?
→ If YES: Asserted (Main clause)

### Step 3: What kind of subordinate clause?

**Ask:** What is the morphosyntactic marking and functional role?

| Marking | Function | Label |
|---------|----------|-------|
| Adverbial subordinator (when, because, if) | Circumstantial relation | **Adv** |
| Converb/gerund with circumstantial meaning | Circumstantial relation | **Adv** |
| Complementizer (that, whether, if) | Argument of CTP | **Comp** |
| Infinitive/action nominal | Argument of CTP | **Comp** |
| Relativizer (that, which, who) | Modifier of referent | **Rel** |
| Participle | Modifier of referent | **Rel** |

### Step 4: Does one clause interrupt another?

**Ask:** Does one clause's span interrupt the linear span of another clause?

- **No interruption** → Mark with `#` boundaries
- **Interruption** → Mark interrupting clause with `{  }`

**Common interruption patterns:**
- Subject relative clauses (SVO languages): `{Rel}`
- Complement clauses on objects (SOV languages): `{Comp}`
- Genitive phrases within adpositional phrases: `{Gen}`

### Step 5: Border case checks

**Insubordination check:**
- Subordinate morphology + speech act function + no matrix → **Main** clause

**Coordinate deranking check:**
- Converb/deranked form + both events asserted → Both **Main**

**Property concept check:**
- Single word + relative morphology + no arguments → Lexical **Mod**, not **Rel** clause

**Complex predicate check:**
- CTP + complement, but grammaticalized/shared arguments → **CPP + Pred** in one clause

### **Border Cases & Ambiguities with Infinitives**

#### **Case 1: Purpose vs. Relative - "Thing-for-purpose" Nouns**

Some nouns inherently encode purpose (tool nouns, instrument nouns):

**Ambiguous example**:
- "He bought a knife to cut bread"

**Two possible analyses**:

**Analysis A - Adverbial (purpose of buying)**:
- "He bought a knife [in order to cut bread with it]"
- Explains why he bought it
- The knife and cutting are separate considerations

**Analysis B - Relative (type of knife)**:
- "He bought a knife [that is used to cut bread]" = bread knife
- Identifies what kind of knife
- The knife's purpose is part of its identity

**How to decide?**

**Context + Intonation in Portuguese**:
- "Ele comprou uma faca para cortar pão" (slight pause before "para") → PURPOSE (adverbial)
- "Ele comprou uma faca-para-cortar-pão" (no pause, compound-like) → RELATIVE

**Semantic test**:
- If you're identifying the TYPE of object → **relative**
- If you're explaining the REASON for the action → **adverbial**

---

#### **Case 2: Portuguese "para + infinitive" Ambiguity**

Portuguese "para + infinitive" is notoriously ambiguous:

**Example**: "Comprei um livro para ler"

**Reading 1 - Adverbial (purpose)**:
- "I bought a book [in order to read (it/something)]"
- Reading activity is the purpose of buying

**Reading 2 - Relative (reading material)**:
- "I bought a book [that is to-be-read / for reading]"
- Book = reading material (participant sharing)

**Disambiguation cues**:

**Favors ADVERBIAL**:
- Object specified: "Comprei um livro para ler *nas férias*" (temporal adjunct)
- Different patient: "Comprei um livro para ler *para as crianças*" (read to children)

**Favors RELATIVE**:
- Generic/type reference: "Comprei *um livro para ler*" (a reading-book, not a picture book)
- Contrastive: "Comprei um livro *para ler*, não *para colorir*"

---

#### **Case 3: "Have/Ter + Object + Infinitive"**

**English**: "I have work to do"
**Portuguese**: "Tenho trabalho a fazer" / "Tenho trabalho para fazer"

**Analysis**: **RELATIVE CLAUSE**

**Evidence**:
✓ "trabalho" is modified (what kind of work? work-to-be-done)
✓ Participant sharing: "trabalho" = PATIENT of "fazer"
✓ Can expand: "trabalho [que deve ser feito]"
✓ Cannot move: *"A fazer, tenho trabalho" ✗

**Related constructions**:
- "Não tenho nada **a dizer**" = nothing [to say/that should be said]
- "Há muito **a fazer**" = much [to do/that must be done]
- "É fácil **de entender**" = easy [to understand] (relative modifying property adjective)

---


#### **Portuguese-Specific Patterns**

**1. "para" vs "a" vs "de" + infinitive**

- "para + inf" (most ambiguous):
    - Can be adverbial: "Vim para ajudar" (purpose)
    - Can be relative: "livro para ler" (reading book)

- "a + inf" (more restricted):
    - Usually relative: "trabalho a fazer", "nada a declarar"
    - Especially after quantifiers: "muito a fazer", "pouco a dizer"

- "de + inf" (different pattern):
    - After adjectives: "fácil de fazer", "difícil de entender"
    - These are relative-like (modifying the adjective's property)

**2. Position matters**

- Post-nominal infinitives → more likely **relative**:
    - "Tenho [um problema **a resolver**]" (noun + infinitive)

- Clause-final infinitives → more likely **adverbial**:
    - "[Ele veio aqui] **para ajudar**" (action + purpose)

---

#### **Final Diagnostic Flowchart**

```
Is the infinitive adjacent to a noun?
    ↓ YES → Does the noun participate in the infinitive event?
             ↓ YES → RELATIVE CLAUSE
             ↓ NO → Check if it's actually modifying the main verb...
    ↓ NO → Does it express why/for what purpose/result?
            ↓ YES → ADVERBIAL CLAUSE
            ↓ NO → Is it an argument of the main predicate?
                    ↓ YES → COMPLEMENT CLAUSE
```


## Part VII: Quick Reference Tables

### Morphosyntactic Markers by Clause Type

| Clause Type | Finite Marking | Deranked Marking | Conjunction/Relativizer |
|-------------|----------------|------------------|-------------------------|
| Main | Full inflection | (none) | Coordinating (and, but, or) |
| Adv | Subordinator | Converb | Adverbial subordinator (when, because, if, although) |
| Comp | Complementizer | Infinitive, action nominal | Complementizer (that, whether, if, for...to) |
| Rel | Relativizer | Participle | Relativizer (that, which, who, where, when) |

### Interruption Likelihood by Construction Type

| Construction | Interruption Frequency | Most Common Pattern |
|--------------|------------------------|---------------------|
| Adverbial clause | Extremely rare | Peripheral (sentence-initial/final) |
| Complement clause | Moderate (depends on word order) | SOV object complement interrupts |
| Relative clause | High in SVO languages | Subject relatives interrupt in SVO+NRel |
| Genitive phrase | Varies by language | English Gen+N with prepositions |

### Two Clauses vs. Complex Predicate

| Feature | Two Clauses (Main # Comp) | Complex Predicate (CPP + Pred) |
|---------|---------------------------|-------------------------------|
| Event independence | Separate or partly separate events | Single event with modal/aspectual/polarity modification |
| Argument structure | Separate or partly shared | Fully shared |
| CTP status | Lexical predicate | Grammaticalized auxiliary/modal |
| CTP has own CEs | Yes (may have own arguments) | No (only shared arguments) |
| Deranked form | Infinitive/that-clause | Bare infinitive/participial form |
| English examples | "want to leave", "said that..." | "might leave", "have eaten", "are running" |

---

## Part VIII: Cross-Linguistic Notes

### Languages with Extensive Converb Systems
- **Turkic, Mongolic, Caucasian languages:** Rich converb paradigms marking different temporal/causal relations
- **Annotation:** Each converb clause is **Adv** at sentential CE level

### Languages with Obligatory Relative Clause Marking on Adjectives
- **Many African, Asian languages:** Property concepts take participial/relative morphology
- **Annotation decision:** If single-word + no arguments → Lexical **Mod**; otherwise **Rel** clause

### SOV Languages with Non-Rigid Word Order
- **Many SOV languages:** Allow postposing of heavy constituents (including clauses)
- **Effect:** Reduces complement and relative clause interruption
- **Annotation:** Standard clause marking with `#`, no interruption `{  }`

### Languages with Correlative or Internally-Headed Relatives
- **Indo-Aryan, Tibeto-Burman, some Native American:** Non-interrupting relative clause strategies
- **Annotation:** Relative clause juxtaposed to main clause, labeled **Rel**

---

## Part IX: Practical Annotation Examples

### Example 1: Simple Coordination
```
Analyzed Text: I + ate # and + I + left .
Clausal CEs:   Arg Pred  Conj Arg Pred
Sentential CE: Main      Main
```

### Example 2: Adverbial Subordination
```
Analyzed Text: after + I + ate # I + left .
Clausal CEs:   Conj  Arg Pred  Arg Pred
Sentential CE: Adv            Main
```

### Example 3: Complement Clause (Finite)
```
Analyzed Text: I + told + her # that + I + bought + a bicycle .
Clausal CEs:   Arg Pred  Arg   Conj  Arg Pred    Arg
Sentential CE: Main            Comp
```

### Example 4: Complement Clause (Infinitive)
```
Analyzed Text: She + wanted # to + leave .
Clausal CEs:   Arg  Pred     Conj Pred
Sentential CE: Main         Comp
```

### Example 5: Complex Predicate (Not Two Clauses)
```
Analyzed Text: She + might + lose + the game .
Clausal CEs:   Arg  CPP    Pred   Arg
Sentential CE: Main
```

### Example 6: Relative Clause (Non-Interrupting)
```
Analyzed Text: Bilbo + found + the ring # that + Gollum + had + lost .
Clausal CEs:   Arg    Pred   Arg        Conj  Arg     CPP  Pred
Sentential CE: Main                     Rel
```

### Example 7: Relative Clause (Interrupting)
```
Analyzed Text: The tree {that + fell + on {my} house} had + died .
Phrasal CEs:   Mod Head  Head  Pred  Adp Head        CPP  Pred
Clausal CEs:   Arg       Conj  Pred  Arg      Gen    CPP  Pred
Sentential CE: Main      Rel
```

### Example 8: Multiple Subordinate Clauses
```
Analyzed Text: When + I + woke up # after + the alarm + rang # I + made + coffee .
Clausal CEs:   Conj  Arg Pred      Conj   Arg        Pred   Arg Pred   Arg
Sentential CE: Adv                 Adv                       Main
```

### Example 9: Extraposed Relative
```
Analyzed Text: The tree + was + dead # that + fell + on {my} house .
Phrasal CEs:   Mod Head  CPP   Head  Head  Pred  Adp Head
Clausal CEs:   Arg       CPP   Pred  Conj  Pred  Arg      Gen
Sentential CE: Main                  Rel
```

### Example 10: Insubordination (Subordinate Form, Main Function)
```
Analyzed Text: No smoking .
Phrasal CEs:   Mod Head
Clausal CEs:   (Pred)
Sentential CE: Main
```
(Gerund form typically subordinate, but functioning as prohibitive main clause)
