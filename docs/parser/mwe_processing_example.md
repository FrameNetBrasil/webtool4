# MWE Processing with Trankit

## Problem

When processing Portuguese sentences with contractions (like "pelo", "do", "na", etc.), the UD parser expands these contractions into their constituent parts:
- "pelo" → "por" + "o"
- "do" → "de" + "o"
- "na" → "em" + "a"

This expansion causes MWE (Multi-Word Expression) identification to fail, because MWEs registered in the database use the contracted forms.

### Example

**Sentence:** "O carro atropelou pelo menos 5 pessoas."

**MWE in database:** "pelo menos" (at least)

**Problem with old approach:**
1. UD Parser runs first → expands "pelo" to "por o"
2. Text becomes: "O carro atropelou por o menos 5 pessoas"
3. MWE lookup searches for "pelo menos" → **NOT FOUND**
4. Parse becomes incorrect

## Solution

Use a two-step processing approach:

### Step 1: MWE Identification (NEW)
Use `TrankitService::getUDTrankitText()` which preserves contractions:

```php
$trankitService = new TrankitService();
$trankitService->init(config('parser.trankit_url'));

// Get parse with preserved contractions
$result = $trankitService->getUDTrankitText($sentence, $idLanguage);

// Result tokens: ["O", "carro", "atropelou", "pelo", "menos", "5", "pessoas", "."]
// Now "pelo menos" can be found in the database!
```

### Step 2: Full Syntactic Analysis
After identifying and handling MWEs, use the standard method for complete dependency parsing:

```php
// Now run full UD parse with expanded contractions
$result = $trankitService->getUDTrankit($sentence, $idLanguage);

// Result tokens: ["O", "carro", "atropelou", "por", "o", "menos", "5", "pessoas", "."]
// Full dependency tree with proper syntactic analysis
```

## Workflow

```php
// 1. Get original text tokens (for MWE matching)
$textTokens = $trankitService->getUDTrankitText($sentence, $idLanguage);

// 2. Identify MWEs using original tokens
$mwes = $mweService->identifyMWEs($textTokens->udpipe);

// 3. Get full UD parse with expanded contractions
$fullParse = $trankitService->getUDTrankit($sentence, $idLanguage);

// 4. Merge MWE information with full parse
$finalParse = $parserService->mergeMWEsWithParse($mwes, $fullParse->udpipe);
```

## Benefits

- ✅ Correct MWE identification for contracted forms
- ✅ Complete syntactic analysis with UD dependencies
- ✅ Proper handling of Portuguese contractions
- ✅ Maintains compatibility with existing MWE database entries

## Trankit API Response Structure

When Trankit encounters a contraction, it provides both forms:

```json
{
    "id": [4, 5],
    "text": "pelo",           // ← Original contracted form
    "expanded": [
        {
            "id": 4,
            "text": "por",    // ← Expanded form (preposition)
            "upos": "ADP",
            "head": 6,
            "deprel": "case"
        },
        {
            "id": 5,
            "text": "o",      // ← Expanded form (article)
            "upos": "DET",
            "head": 6,
            "deprel": "det"
        }
    ],
    "span": [18, 22],
    "dspan": [18, 22]
}
```

- `getUDTrankit()` uses the `expanded` array
- `getUDTrankitText()` uses the `text` field directly
