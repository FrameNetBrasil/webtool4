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

## **COMPLEX SENTENCES: A Didactic Summary**

### **1. FUNDAMENTAL CLASSIFICATION**

Complex sentences consist of two or more clauses joined together. They are classified based on **information packaging** (how the relationship between events is conceptualized) rather than just semantic relations.

### **2. MAIN TYPES OF COMPLEX SENTENCES**

#### **A. COORDINATE CLAUSE CONSTRUCTIONS**
**Information packaging**: Symmetric relationship (complex figure) - both clauses form an integrated whole

**Characteristics**:
- Neither clause is functionally dependent on the other
- Events conceptualized as parts of a single complex event
- Exhibits **tense iconicity**: clause order mirrors event order
- Requires finding a "common denominator" between events

**Examples**:
- **English**: "She was exhausted and (so) went to bed."
- **Portuguese**: "Ela estava exausta e (então) foi para a cama."

- **English**: "He washed the car and drove to the party."
- **Portuguese**: "Ele lavou o carro e foi para a festa."

**Classification rationale**: The two events cannot be reordered without changing the meaning. Compare: "He drove to the party and washed the car" describes a different situation.

---

#### **B. ADVERBIAL CLAUSE CONSTRUCTIONS**
**Information packaging**: Asymmetric relationship (figure-ground) - one clause is the figure (matrix/main clause), the other is the ground (dependent/subordinate clause)

**Characteristics**:
- Matrix clause = figure (the main event)
- Dependent clause = ground (provides temporal, causal, or other context)
- Clause order can be reversed without changing the basic meaning
- The dependent clause is **non-asserted** (pragmatically presupposed)

**Examples**:

**Temporal (anterior)**:
- **English**: "He washed the car before driving to the party."
- **Portuguese**: "Ele lavou o carro antes de ir para a festa."

**Temporal (posterior)**:
- **English**: "He drove to the party after washing the car."
- **Portuguese**: "Ele foi para a festa depois de lavar o carro."

**Causal**:
- **English**: "She went to bed because she was exhausted."
- **Portuguese**: "Ela foi para a cama porque estava exausta."

**Conditional**:
- **English**: "If you do that, the terrorists have won."
- **Portuguese**: "Se você fizer isso, os terroristas venceram."

**Concessive**:
- **English**: "Although John had no money, he went into this expensive restaurant."
- **Portuguese**: "Embora João não tivesse dinheiro, ele entrou neste restaurante caro."

**Classification rationale**: Can be reordered → "Because she was exhausted, she went to bed." The asymmetry remains regardless of order.

---

#### **C. COMPLEMENT CLAUSE CONSTRUCTIONS**
**Information packaging**: Event functions as an **argument** (rather than a predication)

**Characteristics**:
- The complement clause is a subordinate (non-asserted) clause
- Functions as argument of complement-taking predicates (CTPs)
- Represents a "mismatch" between semantic class (event) and function (reference)
- Can be more integrated than other complex sentences (may grammaticalize into single predicates)

**Main CTP types**:

**1. Utterance predicates**:
- **English**: "She said that she was buying the house."
- **Portuguese**: "Ela disse que estava comprando a casa."

**2. Propositional attitude (belief/knowledge)**:
- **English**: "Harry thinks that Janet ate the last doughnut."
- **Portuguese**: "Harry acha que Janet comeu a última rosquinha."

**3. Commentative (factive)**:
- **English**: "She regrets that she didn't vote early."
- **Portuguese**: "Ela se arrepende de não ter votado cedo."

**4. Evaluative (fear/hope/wish)**:
- **English**: "Pat fears that she won't pass the exam."
- **Portuguese**: "Pat teme que não passe no exame."

**Classification rationale**: These pass the non-assertion tests:
- Negation: "It's not the case that he said it's raining" ≠ "It's not raining"
- Question hedging: "He said it's raining, didn't he?" (✓) vs. "*He said it's raining, isn't it?" (✗)

---

#### **D. RELATIVE CLAUSE CONSTRUCTIONS**
**Information packaging**: Event functions as a **modifier** of a referent

**Characteristics**:
- Figure-ground construal (like adverbials)
- Pragmatically presupposed (ground)
- **Necessary participant sharing**: the head referent participates in both events
- The head is expressed in the matrix clause and either as pronoun or gap in the relative clause

**Main strategies**:

**1. Externally headed - Gap strategy**:
- **English**: "I found the key [I had lost __]."
- **Portuguese**: "Eu encontrei a chave [que __ perdi]."

**2. Externally headed - Pronoun retention**:
- **English**: (less common in standard English)
- **Portuguese**: "Os homens [que você deu os livros para eles] foram embora."

**3. Externally headed - Relative pronoun**:
- **English**: "the girl [who won the prize]"
- **Portuguese**: "a menina [que ganhou o prêmio]"

**Classification rationale**: The shared participant (the key/a chave) is both what was lost and what was found - it participates in both events.

---

### **3. KEY DISTINCTIONS**

**Matrix vs. Main Clause**:
- **Matrix clause**: relative concept - the clause that contains a dependent clause
- **Main clause**: functional concept - the pragmatically **asserted** clause

**Dependent vs. Subordinate Clause**:
- **Dependent clause**: structurally dependent on a matrix clause
- **Subordinate clause**: pragmatically **non-asserted** clause
- Usually coincide, but not always (e.g., "I guess John didn't come, did he?")

---

### **4. BORDER CASES & CLASSIFICATION CHALLENGES**

#### **A. Coordination vs. Adverbial Subordination**
The SAME semantic relations can be expressed by either construction:

**Causal relation**:
- Coordinate: "She was exhausted and (so) went to bed."
- Adverbial: "She went to bed because she was exhausted."

**The difference**: Information packaging, not semantics
- Coordination = complex unified event (symmetric)
- Adverbial = figure-ground (asymmetric)

#### **B. Discourse Markers vs. Conjunctions**
Forms like "SO" and "OTHERWISE" in discourse may function as discourse markers rather than true conjunctions - they're less conventionalized.

#### **C. Direct vs. Indirect Report**
- **Direct**: "She said, 'I'm buying the house.'" (reproduces utterance)
- **Indirect**: "She said that she was buying the house." (reports content)

**Portuguese**:
- **Direct**: "Ela disse: 'Estou comprando a casa.'"
- **Indirect**: "Ela disse que estava comprando a casa."

#### **D. Gap vs. Pronoun Retention in Pro-Drop Languages**
In languages like Portuguese where zero anaphora is common, distinguishing gap strategy from pronoun retention becomes difficult.

**Portuguese examples**:
- "Os homens [que você deu os livros __ ] foram embora." (Gap? Or zero pronoun?)

#### **E. Relativizer vs. Relative Pronoun**
English "that" is analyzed as a relativizer, but:
- It's obligatory for subjects: "the guy [that/*Ø spoke first]"
- In some dialects: "the man [that's house burned down]" (looks like a pronoun!)

#### **F. Complement vs. Relative Clause Similarities**
Both involve:
- Participant sharing (complement: controlled subject; relative: head referent)
- Can use similar deranking strategies (nominalizations, infinitives)
- This creates potential confusion in classification

**Example ambiguity**:
- "I want [to go]" - complement (event as argument)
- "the book [sitting on the floor]" - relative (event as modifier)

#### **G. Factive vs. Non-Factive Complements**
**Factive** (presupposes truth):
- "She regrets [that she voted]" → she DID vote

**Non-factive** (neutral stance):
- "She thinks [that she voted]" → maybe she did, maybe she didn't

**Portuguese**:
- Factive: "Ela lamenta [que tenha votado]"
- Non-factive: "Ela acha [que votou]"

---

### **5. SUMMARY TABLE**

| **Type** | **Info Packaging** | **Example (EN)** | **Example (PT)** |
|----------|-------------------|------------------|------------------|
| **Coordination** | Symmetric (complex figure) | She was tired and left | Ela estava cansada e saiu |
| **Adverbial** | Asymmetric (figure-ground) | She left because she was tired | Ela saiu porque estava cansada |
| **Complement** | Event as argument | She said [that she left] | Ela disse [que saiu] |
| **Relative** | Event as modifier | The woman [who left] | A mulher [que saiu] |

---

This classification system is based on **cross-linguistic comparative concepts** that prioritize information packaging over surface syntactic forms, making it applicable to diverse languages including Brazilian Portuguese.

---

Excellent question! This touches on a fundamental analytical challenge in Portuguese (and other Romance languages) - the boundary between participial adjectives and reduced relative clauses. The answer depends on your analytical framework, but I'll give you both perspectives and diagnostic criteria.

## **Two Analytical Perspectives**

### **1. Traditional Grammar Approach**
Many Portuguese grammars classify these as **adjectives** because they:
- Agree with the noun in gender/number
- Occupy typical adjective positions
- Can be replaced by "pure" adjectives

### **2. Functional/Construction Grammar Approach**
(like the document you sent me)

These should be analyzed as **reduced (deranked) relative clauses** because they:
- Express **events** (not inherent properties)
- Can take verbal arguments/complements
- Have an underlying event structure with participant roles

The document explicitly states:
> "Overt deranked predicate forms in relative clauses are typically called **participles**... In traditional grammar, certain deranked complement clause and relative clause constructions are not called 'clauses'... Since our definition is based on **information packaging function**, not a particular strategy, our definitions of 'complement' and 'relative clause' includes these deranked constructions."

---

## **Diagnostic Criteria for Your Examples**

Let me analyze each case:

### **Example 1: "O carro quebrado está na esquina"**

**Argument for ADJECTIVE:**
- Can be substituted: "O carro *vermelho* está na esquina"
- Expresses a resultant state/property
- No overt verbal arguments

**Argument for REDUCED RELATIVE:**
- Can be expanded: "O carro [que está/foi quebrado] está na esquina"
- Implies a prior breaking event
- Potential agent implied: "O carro quebrado *(pelo acidente)* está na esquina"

**Verdict**: **CONTINUUM** - leans toward **adjective** when lexicalized (property reading), but can function as **reduced relative** when event reading is salient.

---

### **Example 2: "O casal acompanhado por uma jovem caminha na rua"**

**Strong evidence for REDUCED RELATIVE:**
✓ Takes verbal argument: "por uma jovem" (agent phrase)
✓ Clearly expresses an event (accompanying)
✓ Can be expanded: "O casal [que é/está acompanhado por uma jovem]"
✓ Has participant structure: THEME (o casal) + AGENT (uma jovem)

**This is NOT a simple adjective** - pure adjectives don't take agent phrases:
- ❌ "*O carro vermelho por uma tinta*" (ungrammatical)
- ✓ "O casal acompanhado por uma jovem" (grammatical)

**Verdict**: **REDUCED RELATIVE CLAUSE** (deranked relative)

---

### **Example 3: "O roteiro cantado não é bom"**

**Argument for ADJECTIVE:**
- Can substitute: "O roteiro *musical* não é bom"
- Could express a property (type of script)
- No overt arguments

**Argument for REDUCED RELATIVE:**
- Can be expanded: "O roteiro [que é/foi cantado] não é bom"
- Implies an event (singing)
- Can take arguments: "O roteiro cantado *(pelo coro)* não é bom"

**Verdict**: **CONTINUUM** - context-dependent. Without arguments, could be either.

---

## **The Continuum Perspective**

The reality is there's a **grammaticalization continuum**:

```
CLAUSAL ←――――――――――→ ADJECTIVAL

[Event, verbal arguments] → [Lexicalized, property]
```

**Position on continuum:**
1. "acompanhado por uma jovem" → **CLAUSAL END** (has verbal complement)
2. "quebrado" / "cantado" → **MIDDLE** (context-dependent)
3. "uma pessoa sentada" → **MIDDLE**
4. "água fervida" → **MIDDLE**
5. "comportamento exagerado" → **ADJECTIVAL END** (fully lexicalized)

---

## **Diagnostic Tests You Can Use**

| **Test** | **Relative Clause** | **Adjective** |
|----------|-------------------|---------------|
| **Can take agent phrases?** | ✓ "acompanhado *por X*" | ✗ "*vermelho por X*" |
| **Can be modified by adverbs?** | ✓ "recentemente quebrado" | ✗ (uses intensifiers instead) |
| **Expandable to full clause?** | ✓ "que foi/está quebrado" | ? (marginal) |
| **Expresses event or property?** | Event (dynamic) | Property (stative) |
| **Can have implicit agent?** | ✓ "quebrado (por alguém)" | ✗ |

---

## **Portuguese-Specific Note**

Portuguese has additional complications:
- "estar + participle" = passive/resultant state
- "ser + participle" = passive voice
- Bare participles = ambiguous

**Compare**:
- "O carro **está** quebrado" (stative - IS broken)
- "O carro **foi** quebrado" (eventive - WAS broken [by something])
- "O carro quebrado" (ambiguous - could be either)

The copula choice in the expansion test can help determine the analysis!

---

**Bottom line**: For "#O casal acompanhado por uma jovem", definitively treat it as a **reduced relative clause**. For "quebrado" and "cantado" without complements, it depends on context and your analytical goals - but the functional framework from your document supports treating them as reduced relatives when an event interpretation is possible.

---

Excellent question! Infinitives in both English and Portuguese are **functionally versatile** - they don't always indicate adverbial clauses. In fact, they can function as:

1. **Adverbial clauses** (expressing purpose, result, etc.)
2. **Relative clauses** (modifying nouns)
3. **Complement clauses** (functioning as arguments)

Let me break down each function with diagnostic criteria.

---

## **1. INFINITIVES AS ADVERBIAL CLAUSES**

### **Your Example: "He bought a computer to work"**

**Analysis**: Yes, **adverbial clause** (purpose)

**Characteristics**:
- Expresses a **semantic relation between two separate events** (buying → working)
- The infinitive explains WHY/FOR WHAT PURPOSE the main action occurred
- Can be paraphrased with explicit purpose markers: "in order to work", "para trabalhar"

**Portuguese equivalent**:
- "Ele comprou um computador para trabalhar"

**Other semantic relations with infinitives as adverbials**:

**Purpose**:
- EN: "I grabbed a stick to defend myself"
- PT: "Peguei um pau para me defender"

**Result**:
- EN: "He grew up to become a doctor"
- PT: "Ele cresceu para se tornar médico"

**Temporal (posterior)**:
- EN: "After eating, he left" → "He left after eating"
- PT: "Depois de comer, ele saiu"

**Diagnostic test**: Can you add "in order to" or "para" without changing the meaning?
- "He bought a computer **(in order) to** work" ✓
- This confirms it's an adverbial (purpose) clause

---

## **2. INFINITIVES AS RELATIVE CLAUSES**

### **Your Example: "The product to kill ants is on the table"**

**Analysis**: Yes, **relative clause** (modifying "product")

**Characteristics**:
- The infinitive **modifies a noun** (product)
- There is **participant sharing**: "product" is a participant in the killing event (as INSTRUMENT/MEANS)
- Can often be paraphrased with a full relative clause: "The product [that/which kills/is used to kill ants]"

**Portuguese equivalents**:
- "O produto para matar formigas está na mesa"
- "O produto de matar formigas está na mesa" (more colloquial)

**More examples**:

**English**:
- "I have work to do" → the work [that is to be done]
- "She's the person to talk to" → the person [who you should talk to]
- "There's nothing to worry about" → nothing [that you should worry about]
- "Give me something to eat" → something [that can be eaten]

**Portuguese**:
- "Tenho trabalho a fazer" → o trabalho [que deve ser feito]
- "Ela é a pessoa a procurar" → a pessoa [que deve ser procurada]
- "Não há nada a dizer" → nada [que deva ser dito]
- "Dê-me algo para comer" → algo [que possa ser comido]

**Diagnostic tests**:

| **Test** | **Adverbial** | **Relative** |
|----------|---------------|--------------|
| Modifies a noun? | No | ✓ Yes |
| Participant sharing with head noun? | No | ✓ Yes (product = instrument) |
| Can expand to "que..."? | No | ✓ "produto que mata formigas" |
| Answers "why/for what purpose"? | ✓ Yes | No |
| Can move away from the noun? | ✓ Yes (often) | No (must stay adjacent) |

**Test application to your examples**:

"He bought a computer **to work**"
- Modifies noun? No (modifies the whole buying event)
- Can move? Yes: "To work, he bought a computer" ✓
- → **ADVERBIAL**

"The product **to kill ants**"
- Modifies noun? Yes (modifies "product")
- Can move? No: *"To kill ants the product is on the table" ✗
- Participant sharing? Yes (product is used in killing)
- → **RELATIVE CLAUSE**

---

## **3. INFINITIVES AS COMPLEMENT CLAUSES**

This is the third major function you need to distinguish:

**Examples**:
- "I want **to go**" (event as argument of "want")
- "She decided **to leave**" (event as argument of "decided")
- "He tried **to open the door**" (event as argument of "tried")

**Portuguese**:
- "Eu quero **ir**"
- "Ela decidiu **sair**"
- "Ele tentou **abrir a porta**"

**Diagnostic**: The infinitive is an **argument** of the main predicate (complement-taking predicate/CTP)

---

## **Border Cases & Ambiguities**

### **Case 1: Purpose vs. Relative - "Thing-for-purpose" Nouns**

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

### **Case 2: Portuguese "para + infinitive" Ambiguity**

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

### **Case 3: "Have/Ter + Object + Infinitive"**

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

## **Summary Table: Infinitive Functions**

| **Function** | **Example (EN)** | **Example (PT)** | **Key Test** |
|--------------|------------------|------------------|--------------|
| **ADVERBIAL** | He bought a computer **to work** | Comprou computador **para trabalhar** | Explains WHY/PURPOSE; can move |
| **RELATIVE** | The product **to kill ants** | O produto **para matar formigas** | Modifies NOUN; participant sharing |
| **COMPLEMENT** | I want **to go** | Eu quero **ir** | Is ARGUMENT of CTP |

---

## **Portuguese-Specific Patterns**

### **1. "para" vs "a" vs "de" + infinitive**

**"para + inf"** (most ambiguous):
- Can be adverbial: "Vim para ajudar" (purpose)
- Can be relative: "livro para ler" (reading book)

**"a + inf"** (more restricted):
- Usually relative: "trabalho a fazer", "nada a declarar"
- Especially after quantifiers: "muito a fazer", "pouco a dizer"

**"de + inf"** (different pattern):
- After adjectives: "fácil de fazer", "difícil de entender"
- These are relative-like (modifying the adjective's property)

### **2. Position matters**

**Post-nominal infinitives** → more likely **relative**:
- "Tenho [um problema **a resolver**]" (noun + infinitive)

**Clause-final infinitives** → more likely **adverbial**:
- "[Ele veio aqui] **para ajudar**" (action + purpose)

---

## **Final Diagnostic Flowchart**

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

---

**Bottom line for your examples**:
- "He bought a computer **to work**" → **ADVERBIAL** (purpose)
- "The product **to kill ants**" → **RELATIVE CLAUSE** (modifies "product", participant sharing)

And always remember: in ambiguous cases, context, intonation, and the specific semantic relationship determine the analysis!
