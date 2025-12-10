# Parser V3 Documentation

## Hybrid Architecture: MWE Patterns + BNF Constructions

**Status:** Planning Complete
**Date:** December 2024

---

## Overview

Parser V3 extends the three-stage parsing pipeline with a **hybrid detection system** that combines:

1. **MWE Patterns (V2)** - Efficient detection of fixed and variable-slot sequences
2. **BNF Constructions (V3 NEW)** - Full context-free grammar support for complex patterns

---

## Documentation Index

### Core Documents

| Document | Description |
|----------|-------------|
| [V3_IMPLEMENTATION_PLAN.md](V3_IMPLEMENTATION_PLAN.md) | Complete implementation plan with phases, architecture, and code design |
| [MWE_BNF_GUIDE.md](MWE_BNF_GUIDE.md) | Practical guide for defining patterns using both methods |

### V2 Reference (Foundation)

| Document | Location |
|----------|----------|
| Variable MWE Patterns | `../v2/VARIABLE_MWE_PATTERNS.md` |
| Stage 1 Transcription | `../v2/STAGE1_TRANSCRIPTION.md` |
| Stage 2 Translation | `../v2/STAGE2_TRANSLATION_IMPLEMENTATION.md` |
| Framework Summary | `../v2/FRAMEWORK_v2_SUMMARY.md` |

### Related Documentation

| Document | Location |
|----------|----------|
| BNF Proposal | `../../bnf/bnf.md` |
| Comparative Analysis | `../../bnf/comparative_analysis.md` |
| CE Labels Reference | `../../flat_syntax/ce_labels.md` |

---

## Quick Summary

### What's New in V3

| Feature | V2 | V3 |
|---------|----|----|
| Fixed MWE sequences | Yes | Yes |
| Variable slots (POS, Lemma, CE) | Yes | Yes |
| Optional elements `[...]` | No | **Yes** |
| Alternatives `(A \| B)` | No | **Yes** |
| Repetition `A+`, `A*` | No | **Yes** |
| Semantic calculation | No | **Yes** |
| Pre-compiled patterns | No | **Yes** |

### Architecture

```
Stage 1 (Transcription) - Enhanced
├── Layer 3: BNF Constructions (NEW)
│   └── Complex patterns with CFG features
├── Layer 2: Variable MWE Patterns
│   └── [NOUN] de [NOUN] style
└── Layer 1: Simple MWE Patterns
    └── "café da manhã" style
```

### Decision Guide

| Pattern Type | Method |
|--------------|--------|
| Fixed word sequence | Simple MWE |
| Fixed length with slots | Variable MWE |
| Has optional elements | BNF Construction |
| Has alternatives | BNF Construction |
| Needs semantic value | BNF Construction |

---

## Implementation Status

### Completed (V2 Foundation)

- [x] Simple MWE detection with prefix activation
- [x] Variable MWE with POS/Lemma/CE/Wildcard types
- [x] Two-phase detection (anchored + fully variable)
- [x] Anchor-based database indexing
- [x] Three-stage pipeline integration
- [x] Test commands and documentation

### Planned (V3 Extension)

- [ ] Phase 1: Core BNF Infrastructure
- [ ] Phase 2: Matching Engine
- [ ] Phase 3: Semantic Actions
- [ ] Phase 4: Pipeline Integration
- [ ] Phase 5: Admin Interface
- [ ] Phase 6: Predefined Constructions

---

## Key Files (V3)

### To Be Created

```
app/Services/Parser/
├── PatternCompiler.php      # Compile BNF to graph
├── BNFMatcher.php           # Graph traversal matching
├── ConstructionService.php  # Detection orchestration
└── SemanticCalculator.php   # Value calculation

app/Repositories/Parser/
└── Construction.php         # Database access

app/Data/Parser/
├── ConstructionMatch.php    # Match result DTO
└── CompiledGraph.php        # Graph structure DTO

app/Console/Commands/Parser/
├── TestConstructionCommand.php
├── ImportConstructionsCommand.php
└── CompileConstructionCommand.php

database/migrations/
└── xxxx_create_parser_constructions_table.php

resources/data/constructions/
├── pt_numbers.json
└── pt_dates.json
```

### Existing (V2)

```
app/Services/Parser/
├── MWEService.php           # MWE activation
├── TranscriptionService.php # Stage 1
├── PhraseAssemblyService.php# Stage 2
└── ParserService.php        # Main parser

app/Repositories/Parser/
└── MWE.php                  # MWE database access

app/Enums/Parser/
├── MWEComponentType.php     # W, L, P, C, *
├── PhrasalCE.php            # Head, Mod, Adp, etc.
└── ClausalCE.php            # Pred, Arg, CPP, etc.
```

---

## Getting Started

### 1. Review Current Implementation

```bash
# Test current MWE detection
php artisan parser:test-transcription sentences.txt --grammar=1 -v

# Check existing MWEs
php artisan tinker
>>> App\Repositories\Parser\MWE::listByGrammar(1)
```

### 2. Read the Documentation

1. Start with [V3_IMPLEMENTATION_PLAN.md](V3_IMPLEMENTATION_PLAN.md) for architecture
2. Use [MWE_BNF_GUIDE.md](MWE_BNF_GUIDE.md) for pattern creation
3. Reference V2 docs for existing features

### 3. Plan Your Patterns

Identify patterns that need V3 features:
- Portuguese numbers
- Dates and times
- Complex prepositions
- Any pattern with optional elements

---

## Contact

**Project:** Webtool 4.2 - FrameNet Brasil
**Documentation:** Claude Code
**Date:** December 2024
