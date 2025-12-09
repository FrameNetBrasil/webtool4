# Plan: Token-Based UD Parsing with Post-Parse MWE Detection

**Date:** 2025-12-08
**Status:** Ready for Implementation

## Objective
Refactor `RegisterCEAnnotationsCommand.php` to use token-based UD parsing, reducing complexity and API calls while enabling dependency-aware MWE disambiguation.

## Current vs New Workflow

### Current (4 passes, 2 API calls):
```
PASS 1: getUDTrankitText()          → API call 1 (preserved contractions)
PASS 2: getUDTrankit()              → API call 2 (expanded contractions)
PASS 3: assembleMWEsWithExpanded()  → Merge MWE info
PASS 4: mergePreservedContractions() → Re-merge contractions
```

### New (single tokenization, single API call):
```
STEP 1: tokenizeSentence($text, false)           → Tokenize preserving contractions
STEP 2: getUDTrankitTokensPreserved($tokens)     → Single API call
STEP 3: PhrasalCENode::fromUDToken()             → Build nodes with dependency info
STEP 4: detectMWEsWithDependencies($nodes)       → MWE detection using deprel/head
STEP 5: assembleMWEs($nodes, $detectedMWEs)      → Assemble validated MWEs
```

## Key Insight: MWE Detection After Parsing

MWE detection must happen AFTER UD parsing because dependency relations disambiguate cases like:
- "gol contra aos 20 minutos" → "gol contra" IS an MWE (compound noun)
- "gol contra o adversário" → "contra" is ADP linking to "adversário", NOT an MWE

---

## Implementation Steps

### 1. TrankitService: Add `getUDTrankitTokensPreserved()`

**File:** `app/Services/Trankit/TrankitService.php`

Add method that:
- Takes pre-tokenized array as input
- Calls `/tkbytoken` API
- Preserves original tokens in output (doesn't expand contractions)
- Returns `udpipe` array with full dependency info

```php
public function getUDTrankitTokensPreserved(array $tokens, int $idLanguage = 1): object
{
    // Similar to getUDTrankitText() but for token input
    // When node has 'expanded', create single node with original text
    // Use first expanded token's deprel/head
}
```

### 2. RegisterCEAnnotationsCommand: Add `validateMWECandidate()`

**File:** `app/Console/Commands/ParserV2/RegisterCEAnnotationsCommand.php`

Add dependency-based validation:

```php
private function validateMWECandidate(array $candidate, array $nodes): bool
{
    $candidateIndices = range($candidate['startIndex'], $candidate['endIndex']);

    foreach ($candidateIndices as $idx) {
        $node = $this->findNodeByIndex($nodes, $idx);

        // Rule 1: ADP with head OUTSIDE MWE span → NOT an MWE
        if ($node->deprel === 'case' && !in_array($node->head, $candidateIndices)) {
            return false;
        }

        // Rule 2: Word has nmod/obl/obj dependent OUTSIDE span → NOT an MWE
        foreach ($nodes as $otherNode) {
            if ($otherNode->head === $idx && !in_array($otherNode->index, $candidateIndices)) {
                if (in_array($otherNode->deprel, ['nmod', 'obl', 'obj', 'iobj'])) {
                    return false;
                }
            }
        }
    }
    return true;
}
```

### 3. RegisterCEAnnotationsCommand: Add `detectMWEsWithDependencies()`

Modify existing `detectMWEs()` to:
1. Use the same prefix matching algorithm
2. Call `validateMWECandidate()` before marking as detected

```php
private function detectMWEsWithDependencies(array $nodes): array
{
    // Same prefix-matching logic as current detectMWEs()
    // Add validation call:
    if ($candidate['activation'] >= $threshold) {
        if ($this->validateMWECandidate($candidate, $nodes)) {
            $detected[] = $candidate;
        } else {
            $candidates[] = $candidate; // Failed validation
        }
    }
}
```

### 4. RegisterCEAnnotationsCommand: Refactor `processSentence()`

Replace current 4-pass logic with:

```php
private function processSentence(object $sentence, string $language, string $layers, bool $dryRun): void
{
    $idLanguage = config('parser.languageMap')[$language] ?? 1;

    // STEP 1: Tokenize (preserving contractions)
    $tokens = $this->trankit->tokenizeSentence($sentence->text, false);

    // STEP 2: Parse with pre-tokenized input
    $result = $this->trankit->getUDTrankitTokensPreserved($tokens, $idLanguage);

    // STEP 3: Build PhrasalCENodes
    $phrasalNodes = [];
    foreach ($result->udpipe as $token) {
        $phrasalNodes[] = PhrasalCENode::fromUDToken($token);
    }

    // STEP 4: Detect MWEs with dependency validation
    if ($this->idGrammarGraph) {
        [$mweCandidates, $detectedMWEs] = $this->detectMWEsWithDependencies($phrasalNodes);
    }

    // STEP 5: Assemble MWEs
    if (!empty($detectedMWEs)) {
        $phrasalNodes = $this->assembleMWEs($phrasalNodes, $detectedMWEs, $language);
    }

    // STEP 6: Register annotations (unchanged)
    // ...
}
```

### 5. RegisterCEAnnotationsCommand: Simplify `assembleMWEs()`

Replace `assembleMWEsWithExpanded()` with simpler version that works directly with preserved nodes.

### 6. Cleanup: Remove Unused Methods

- Remove `assembleMWEsWithExpanded()`
- Remove `mergePreservedContractions()`

---

## Files to Modify

| File | Changes |
|------|---------|
| `app/Services/Trankit/TrankitService.php` | Add `getUDTrankitTokensPreserved()` |
| `app/Console/Commands/ParserV2/RegisterCEAnnotationsCommand.php` | Refactor `processSentence()`, add `detectMWEsWithDependencies()`, `validateMWECandidate()`, simplify `assembleMWEs()`, remove old methods |

---

## MWE Validation Rules Summary

**Confirmed:** Use only core dependency relations for validation.

| Rule | Condition | Result |
|------|-----------|--------|
| 1 | Word has `deprel=case` with head OUTSIDE MWE span | NOT an MWE |
| 2 | Word has dependent with `deprel` in `[nmod, obl, obj, iobj]` OUTSIDE span | NOT an MWE |
| 3 | All words contained within span | Valid MWE |

Note: Relations like `det`, `amod`, `nummod` are NOT considered breaking relations.

---

## Testing Scenarios

1. **Contractions:** "pelo menos", "do lado", "das pessoas"
2. **Ambiguous MWEs:** "gol contra" vs "gol contra o adversário"
3. **Complex prepositions:** "atrás de", "dentro de", "por causa de"
4. **Consecutive MWEs:** sentences with multiple MWEs

---

## Example: MWE Disambiguation

### Sentence 1: "Ele marcou um gol contra aos 20 minutos"
```
Parse:
  - "gol" (NOUN, id=4, head=2)
  - "contra" (NOUN, id=5, head=4, deprel=compound)  ← deprel=compound, head within span

Result: "gol contra" IS an MWE (compound noun)
```

### Sentence 2: "Ele marcou um gol contra o adversário"
```
Parse:
  - "gol" (NOUN, id=4, head=2)
  - "contra" (ADP, id=5, head=7, deprel=case)      ← deprel=case, head OUTSIDE span
  - "o" (DET, id=6, head=7)
  - "adversário" (NOUN, id=7, head=4, deprel=nmod)

Result: "gol contra" is NOT an MWE (contra is ADP linking to "adversário")
```
