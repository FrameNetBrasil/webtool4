# Hyphen Normalization for UD Parser

## Problem

The UD parser (Trankit) doesn't always recognize hyphens ("-") as punctuation marks, which causes parsing issues when hyphens are used as clause separators (similar to commas or dashes).

### Example Issue

**Input:** `O carro - vermelho e grande - atropelou pessoas.`

**Without normalization:** Hyphen might not be recognized as PUNCT, causing incorrect dependency parsing.

## Solution

Implemented `normalizeHyphens()` method in `TrankitService` that converts standalone hyphens to commas before sending text to the UD parser.

### Implementation

**File:** `/home/ematos/devel/fnbr/webtool42/app/Services/Trankit/TrankitService.php`

```php
public function normalizeHyphens(string $sentence): string
{
    // Replace standalone hyphens (with spaces around them) with commas
    $sentence = preg_replace('/\s+-\s+/', ' , ', $sentence);

    // Handle hyphen at start/end with only one space
    $sentence = preg_replace('/^-\s+/', ', ', $sentence);
    $sentence = preg_replace('/\s+-$/', ' ,', $sentence);

    return $sentence;
}
```

### When It's Applied

The normalization is automatically applied in:
1. `processTrankit()` - before sending to Trankit API
2. `handleSentence()` - as part of preprocessing pipeline

This ensures all parsing methods benefit from the normalization.

## Behavior

### What Gets Normalized

| Input | Output | Reason |
|-------|--------|--------|
| `word - word` | `word , word` | Standalone hyphen with spaces |
| `- word` | `, word` | Hyphen at start |
| `word -` | `word ,` | Hyphen at end |
| `a - b - c` | `a , b , c` | Multiple hyphens |

### What Stays Unchanged

| Input | Output | Reason |
|-------|--------|--------|
| `palavra-composta` | `palavra-composta` | Compound word (no spaces) |
| `auto-estrada` | `auto-estrada` | Hyphenated word |
| `pré-requisito` | `pré-requisito` | Prefix with hyphen |

## Benefits

- ✅ Hyphens used as clause separators are properly recognized as PUNCT
- ✅ Improves dependency parsing accuracy
- ✅ Preserves compound words and hyphenated terms
- ✅ Works seamlessly with MWE detection
- ✅ Consistent punctuation handling across the parser

## Testing

### Test Case 1: Hyphen as Clause Separator

**Input:** `O carro - vermelho e grande - atropelou pessoas.`

**Result:**
```
| # | Word      | POS   |
|---|-----------|-------|
| 3 | ,         | PUNCT | ✓ Recognized as punctuation
| 7 | ,         | PUNCT | ✓ Recognized as punctuation
```

### Test Case 2: Combined with MWE Detection

**Input:** `O problema - pelo menos - foi resolvido.`

**Result:**
```
| #  | Word       | POS   | MWE |
|----|------------|-------|-----|
| 3  | ,          | PUNCT | -   | ✓ Hyphen normalized
| 4  | pelo menos | ADV   | ✓   | ✓ MWE detected
| 7  | ,          | PUNCT | -   | ✓ Hyphen normalized
```

Both features work together correctly!

## Integration

The hyphen normalization is transparent to calling code. No changes are needed in:
- `TestTranscriptionCommand`
- Other parser commands
- Frontend annotation interfaces

All existing code automatically benefits from the normalization.

## Future Considerations

If you need different normalization rules for specific contexts:
- Add a parameter to `normalizeHyphens()` to control behavior
- Consider configuration option in `config/parser.php`
- Add language-specific normalization rules if needed

## Related Documentation

- [MWE Processing with Trankit](mwe_processing_example.md) - MWE detection with contractions
- Parser V2 Implementation Guide
