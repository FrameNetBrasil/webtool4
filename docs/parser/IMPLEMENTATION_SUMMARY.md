# Parser Implementation Summary

## Overview

This document summarizes the implementation of the **Graph-Based Predictive Parser with Multi-Word Expression (MWE) Processing** for Webtool 4.2. The implementation follows the existing architectural patterns of the application and is fully integrated with the Laravel 12 framework.

## Implementation Date

**Initial Implementation:** 2025-11-30
**UD Parser Integration:** 2025-12-01
**Grammar Visualization:** 2025-12-02

## Architecture Overview

The parser is implemented as a separate module under the `Parser` namespace, following the project's established patterns:

- **Repository Pattern**: Static repository classes for data access (NO Eloquent models)
- **Criteria-based Query Builder**: Custom query builder for database operations
- **Service Layer**: Business logic separated into focused services
- **Data Transfer Objects**: Using Spatie Laravel Data for validation
- **Controllers with PHP Attributes**: Routing via attributes, not route files
- **Blade Views with HTMX**: Dynamic UI updates without full page reloads
- **D3.js Visualization**: Interactive graph rendering

---

## Files Created

### 1. Database Schema
**File:** `database/parser_schema.sql`

- 7 core tables for grammar graphs, MWEs, and parse instances
- Sample Portuguese grammar with 4 word types (E, R, A, F)
- 5 Portuguese MWEs including "café da manhã", "café da tarde"
- 4 test parse graphs demonstrating various scenarios
- 2 utility views for statistics

**Tables:**
- `parser_grammar_graph` - Grammar definitions
- `parser_grammar_node` - Word types and fixed words
- `parser_grammar_link` - Valid transitions
- `parser_mwe` - Multi-word expressions
- `parser_graph` - Parse instances
- `parser_node` - Instantiated nodes during parsing
- `parser_link` - Relationships between nodes

---

### 2. Repository Layer (5 files)
**Location:** `app/Repositories/Parser/`

#### GrammarGraph.php
- Grammar CRUD operations
- Node and edge retrieval
- Predicted types lookup
- Structure management

#### MWE.php
- MWE CRUD operations
- Prefix generation
- Component matching
- Search by first word, phrase, length, type

#### ParseGraph.php
- Parse instance CRUD
- Statistics retrieval
- Status management
- Validation helpers

#### ParseNode.php
- Node CRUD operations
- Focus node management
- MWE prefix tracking
- Activation increment
- Garbage collection

#### ParseEdge.php
- Edge CRUD operations
- Link transfer functionality
- Edge type filtering
- Relationship queries

---

### 3. Data Layer (6 files)
**Location:** `app/Data/Parser/`

All extend `Spatie\LaravelData\Data` for type safety and validation:

- `ParseInputData.php` - Sentence input with grammar selection
- `ParseOutputData.php` - Complete parse results
- `GrammarGraphData.php` - Grammar creation/update
- `MWEData.php` - MWE definition
- `NodeData.php` - Node structure
- `EdgeData.php` - Edge structure

---

### 4. Service Layer (6 files)
**Location:** `app/Services/Parser/`

#### ParserService.php
**Main orchestrator implementing the core parsing algorithm:**

```php
Algorithm Steps:
1. Tokenize sentence
2. Get UD parse (lemmas and POS tags) ✨ NEW
3. For each word:
   a. Create word node (threshold=1, activation=1)
   b. Determine type using UD POS tag ✨ NEW
   c. Instantiate MWE prefix nodes if word starts any MWE
   d. Check existing MWE prefixes for activation
   e. Check focus nodes for predictions
   f. If no match, add to focus queue
4. Garbage collect nodes below threshold
5. Validate parse connectivity
6. Mark status (complete/failed)
```

#### UDParserService.php ✨ NEW
**Universal Dependencies integration:**
- Integration with Trankit service
- Lemma extraction for each word
- POS tag extraction (UPOS)
- Type mapping (NOUN→E, VERB→R, ADJ/ADV→A, ADP/DET/PRON→F)
- Fallback handling when UD parsing fails
- Error logging and graceful degradation

#### MWEService.php
**MWE-specific logic:**
- Prefix hierarchy generation
- Node instantiation on first word
- Activation incrementation
- Threshold checking
- Link aggregation and transfer
- Competition resolution (longest/first/all)

#### GrammarGraphService.php
**Grammar operations:**
- MWE lookup by word
- Predicted type retrieval
- Link validation
- Word type determination
- Grammar building from rules
- Validation

#### FocusQueueService.php
**Queue management:**
- FIFO/LIFO strategies
- Enqueue/dequeue operations
- Queue inspection
- Node removal
- Type filtering

#### VisualizationService.php
**Graph rendering:**
- D3.js data preparation
- Node/edge formatting
- Statistics calculation
- Export (GraphML, DOT, JSON)

---

### 5. Controllers (3 files)
**Location:** `app/Http/Controllers/Parser/`

#### ParserController.php
**Main UI endpoints:**
- `GET /parser` - Main interface
- `POST /parser/parse` - Process sentence (HTMX)
- `GET /parser/result/{id}` - View parse result
- `GET /parser/visualization/{id}` - Graph visualization
- `GET /parser/history` - Recent parses
- `GET /parser/export/{id}/{format}` - Export (JSON/GraphML/DOT)

#### GrammarController.php
**Grammar management:**
- `GET /parser/grammar` - List grammars
- `GET /parser/grammar/{id}` - View grammar details
- `GET /parser/grammar/create` - Create form
- `POST /parser/grammar/create` - Store grammar
- `GET /parser/grammar/{id}/mwes` - List MWEs
- `POST /parser/grammar/{id}/mwes` - Add MWE
- `GET /parser/grammar/{id}/visualization` - Interactive graph (HTMX) ✨ NEW
- `GET /parser/grammar/{id}/tables` - Filtered tables (HTMX) ✨ NEW

#### ApiController.php
**JSON API endpoints:**
- `POST /api/parser/parse` - Parse sentence (JSON)
- `GET /api/parser/result/{id}` - Get result (JSON)
- `GET /api/parser/visualization/{id}` - Visualization data
- `GET /api/parser/grammars` - List grammars
- `GET /api/parser/grammar/{id}` - Grammar details
- `GET /api/parser/results` - List parse results

---

### 6. Blade Views (7 files)
**Location:** `app/UI/views/Parser/`

#### parser.blade.php
Main interface with:
- Sentence input form
- Grammar selection dropdown
- Queue strategy selection (FIFO/LIFO)
- Parse button with HTMX
- Recent parses table

#### parserResults.blade.php
Results display with:
- Parse statistics
- Node table with activation progress
- Edge table with relationships
- Action buttons (visualize, export)
- HTMX integration for graph loading

#### parserGraph.blade.php
D3.js visualization for parse results with:
- Force-directed graph layout
- Draggable nodes
- Color-coded by type
- Interactive tooltips
- Statistics panel

#### parserError.blade.php
Error display with retry functionality

#### grammarView.blade.php
Grammar details with reorganized layout:
- Header and description
- Statistics (nodes, edges, MWEs)
- Filter controls with HTMX
- Dynamic visualization area
- Dynamic tables area
- Validation warnings (at end)

#### grammarGraph.blade.php ✨ NEW
Interactive grammar visualization with:
- Full-width D3.js force-directed graph
- Pan/zoom controls (svgPanZoom)
- Draggable nodes
- Color-coded by type
- Edge labels with weights
- Statistics and legend
- Filter indicator

#### grammarTables.blade.php ✨ NEW
On-demand filtered tables with:
- Grammar nodes table
- MWE table
- Result counts
- Empty state messages
- Filtered results only

---

### 7. Configuration
**File:** `config/parser.php`

Comprehensive configuration including:
- Default settings (grammar ID, language, queue strategy)
- Activation parameters
- MWE processing (max length, competition strategy)
- Validation rules
- Garbage collection settings
- Visualization parameters (colors, sizes, layouts)
- Word type mappings (UPOS to E/R/A/F)
- Logging flags
- Performance limits

---

### 8. Styling
**File:** `resources/css/parser/parser.less`

LESS stylesheet with:
- Parser controls styling
- Results container animations
- Graph canvas styling
- Node type labels
- Parse status badges
- Activation progress bars
- Responsive design (@media queries)
- Animations (fadeIn, pulse)

---

## Key Features

### 1. MWE Processing with Prefix Hierarchy
Every n-word MWE automatically generates all prefix nodes (1-word, 2-word, ..., n-word) with incremental thresholds.

**Example:** "café da manhã" (3 words) creates:
- "café" (threshold=1)
- "café da" (threshold=2)
- "café da manhã" (threshold=3)

### 2. Activation-Based Mechanism
Nodes have:
- `threshold`: Required activation count
- `activation`: Current activation level
- `isFocus`: Boolean (true when activation >= threshold)

### 3. Focus Queue Management
Two strategies:
- **FIFO**: First In, First Out (default)
- **LIFO**: Last In, First Out (for testing)

### 4. Predictive Linking
Grammar links define which node types can follow others, enabling the parser to predict and link nodes as they appear.

### 5. Garbage Collection
After parsing, nodes with `activation < threshold` are removed, leaving only complete structures.

### 6. Interactive Visualization
D3.js force-directed graph with:
- Color-coded nodes by type
- Draggable nodes
- Hover tooltips
- Edge thickness by weight
- Node size by threshold

---

## Usage

### 1. Database Setup
```bash
# Import the schema
mysql -u username -p database_name < database/parser_schema.sql
```

### 2. Access the Parser
Navigate to: `http://localhost:8001/parser`

### 3. Parse a Sentence
1. Enter sentence: "Tomei café da manhã cedo"
2. Select grammar: "Portuguese Basic Grammar"
3. Choose queue strategy: "FIFO"
4. Click "Parse Sentence"

### 4. View Results
- Statistics (nodes, links, focus nodes, MWEs)
- Node table with activation status
- Edge table with relationships
- Click "Show Graph Visualization" for D3.js graph

### 5. Export Results
- JSON format (for programmatic use)
- GraphML format (for Gephi, Cytoscape)
- DOT format (for Graphviz)

---

## API Usage

### Parse a Sentence
```bash
curl -X POST http://localhost:8001/api/parser/parse \
  -H "Content-Type: application/json" \
  -d '{
    "sentence": "Tomei café da manhã",
    "idGrammarGraph": 1,
    "queueStrategy": "fifo"
  }'
```

### Get Parse Result
```bash
curl http://localhost:8001/api/parser/result/1
```

### Get Visualization Data
```bash
curl http://localhost:8001/api/parser/visualization/1
```

---

## Test Cases (Included in SQL)

### Test 1: Simple Sentence
**Input:** "Café está quente" (Coffee is hot)
**Expected:** 3 nodes (café→está→quente), 2 links

### Test 2: MWE Completion
**Input:** "Tomei café da manhã" (I had breakfast)
**Expected:** "café da manhã" aggregates as single MWE node

### Test 3: Nested MWE
**Input:** "Mesa de café da manhã" (Breakfast table)
**Expected:** "café da manhã" completes first, then "mesa de café da manhã"

### Test 4: Interrupted MWE
**Input:** "Café quente da manhã" (Hot morning coffee)
**Expected:** MWE fails (interrupted by "quente"), parses as separate words

---

## Configuration Options

### Queue Strategy
```php
// In config/parser.php
'queueStrategy' => 'fifo', // or 'lifo'
```

### MWE Competition Strategy
```php
// When multiple MWEs share prefixes
'parser_mwe' => [
    'competitionStrategy' => 'longest', // 'longest', 'first', or 'all'
]
```

### Logging
```php
// Enable detailed logging
'logging' => [
    'logSteps' => true,   // Log each parse step
    'logMWE' => true,     // Log MWE activation
    'logQueue' => true,   // Log queue changes
]
```

### Visualization
```php
'visualization' => [
    'layout' => 'force', // 'force', 'hierarchical', or 'circular'
    'nodeColors' => [
        'E' => '#4CAF50',   // Green
        'R' => '#2196F3',   // Blue
        'A' => '#FF9800',   // Orange
        'F' => '#9E9E9E',   // Gray
        'MWE' => '#9C27B0', // Purple
    ],
]
```

---

## Performance Considerations

### Timeouts
- Max parse time: 30 seconds (configurable)
- Max sentence length: 100 words (configurable)

### Database Indexes
All foreign keys and frequently queried columns are indexed for performance.

### Caching
Caching is disabled by default but can be enabled:
```php
'performance' => [
    'cacheEnabled' => true,
    'cacheTTL' => 3600, // 1 hour
]
```

---

## Recent Updates (December 2025)

### UD Parser Integration (2025-12-01)

**Purpose:** Enhance word type determination using Universal Dependencies parsing.

**Implementation:**
- Created `UDParserService` in `app/Services/Parser/`
- Integration with existing Trankit service (`app/Services/TrankitService.php`)
- Automatic lemma and POS tag extraction for each word
- Configurable UD-to-parser type mapping in `config/parser.php`

**Features:**
- Lemma extraction for lexicon lookup
- POS tagging (UPOS tags: NOUN, VERB, ADJ, ADP, etc.)
- Type mapping: NOUN→E, VERB→R, ADJ/ADV→A, ADP/DET/PRON→F
- Fallback to word form when lemma unavailable
- Error handling for service failures

**Files Modified:**
- `app/Services/Parser/ParserService.php` - Integrated UD parsing into main algorithm
- `app/Services/Parser/UDParserService.php` (NEW) - UD service wrapper
- `config/parser.php` - Added UPOS mapping configuration

**Testing:**
Successfully tested with Portuguese sentences using Trankit service.

---

### Grammar Graph Visualization (2025-12-02)

**Purpose:** Provide interactive visualization and filtering for grammar graphs with thousands of nodes.

**Implementation:**

#### 1. Full-Width Interactive Visualization
- **File:** `app/UI/views/Parser/grammarGraph.blade.php` (NEW)
- D3.js force-directed graph with full-width responsive SVG
- Pan/zoom controls using `svgPanZoom` library (matching frame grapher)
- Color-coded nodes by type (E/R/A/F/MWE)
- Interactive features: draggable nodes, tooltips, edge labels
- Statistics display with node distribution by type

#### 2. Filtering System
- **Files Modified:** `app/Http/Controllers/Parser/GrammarController.php`
- Word-based filtering for large grammars
- Filters both nodes and connected edges
- Dynamic statistics (filtered vs. total counts)
- Clear filter functionality

#### 3. On-Demand Table Display
- **File:** `app/UI/views/Parser/grammarTables.blade.php` (NEW)
- Tables hidden by default (performance optimization)
- "Show Tables" button for filtered results only
- Result counts displayed (e.g., "Grammar Nodes 3 results")
- Separate displays for Grammar Nodes and MWEs

#### 4. Page Layout Reorganization
- **File Modified:** `app/UI/views/Parser/grammarView.blade.php`
- New layout order:
  1. Header (name, language)
  2. Description
  3. Statistics (total nodes, edges, MWEs)
  4. Filter controls (after statistics)
  5. Graph visualization area
  6. Filtered tables area (on-demand)
  7. Validation warnings (moved to end)

**Controller Methods Added:**
- `GET /parser/grammar/{id}/visualization` - HTMX endpoint for graph
- `GET /parser/grammar/{id}/tables` - HTMX endpoint for filtered tables

**Features:**
- Full-width graph canvas (100% container width)
- Pan/zoom controls (zoom in, zoom out, reset)
- Filter by word (case-insensitive substring match)
- On-demand table loading (prevents initial slowdown)
- Clear button (resets filter, visualization, and tables)
- Responsive design for various screen sizes

**Files Created:**
1. `app/UI/views/Parser/grammarGraph.blade.php` - D3.js visualization template
2. `app/UI/views/Parser/grammarTables.blade.php` - Filtered table display

**Files Modified:**
1. `app/Http/Controllers/Parser/GrammarController.php` - Added visualization() and tables() methods
2. `app/UI/views/Parser/grammarView.blade.php` - Reorganized layout, added filter controls

**Testing:**
- Tested with 17-node Portuguese grammar
- Verified filtering (e.g., "café" → 3 MWE nodes)
- Confirmed pan/zoom functionality
- Validated full-width responsive behavior

---

## Future Enhancements

### Phase 1 Completion ✓
- [x] Core infrastructure (repositories, services, controllers)
- [x] Basic parsing algorithm
- [x] MWE processing with prefix hierarchy
- [x] Blade views with HTMX
- [x] D3.js visualization
- [x] Sample Portuguese grammar

### Phase 2 (In Progress)
- [x] POS tagging integration for word type determination ✓ (UD Parser)
- [x] Grammar visualization with filtering ✓
- [ ] Lexicon integration for E/R/A classification
- [ ] Advanced prediction mechanisms
- [ ] Parse tree comparison and similarity scoring
- [ ] Batch processing interface
- [ ] Grammar editor UI (creation/editing)
- [ ] Unit/Feature tests with Pest

### Phase 3 (Future)
- [ ] Performance optimization for long sentences
- [ ] Parallel parsing strategies
- [ ] Ambiguity resolution strategies
- [ ] Confidence scores for parses
- [ ] Machine learning integration

---

## Code Quality

### Laravel Pint
All PHP code has been formatted with Laravel Pint:
```bash
vendor/bin/pint --dirty
```
**Result:** 20 files formatted, 13 style issues fixed ✓

### Conventions Followed
- ✓ Repository pattern (static methods, no Eloquent)
- ✓ Criteria-based query builder
- ✓ PHP 8 constructor property promotion
- ✓ Type declarations for all methods
- ✓ Spatie Laravel Data for DTOs
- ✓ PHP attributes for routing
- ✓ Fomantic-UI CSS framework
- ✓ HTMX for dynamic updates
- ✓ D3.js for visualization
- ✓ LESS for styling

---

## Summary Statistics

**Total Files Created:** 28 (Initial: 25, Updates: +3)

**Lines of Code:**
- SQL: ~500 lines
- PHP: ~4,100 lines (Initial: 3,800, Updates: +300)
- Blade: ~650 lines (Initial: 400, Updates: +250)
- LESS: ~250 lines
- JavaScript (embedded): ~300 lines (Initial: 150, Updates: +150)

**Database Tables:** 7
**Views:** 2
**Repositories:** 5
**Services:** 6 (Initial: 5, Updates: +1 UDParserService)
**Controllers:** 3
**Data Classes:** 6
**Blade Templates:** 7 (Initial: 5, Updates: +2 grammarGraph, grammarTables)

---

## Contact & Support

For questions or issues related to this parser implementation, please refer to:
- Initial Documentation: `/docs/parser/initial_doc.md`
- This Summary: `/docs/parser/IMPLEMENTATION_SUMMARY.md`

---

**Implementation completed following all project conventions and architectural patterns.**
