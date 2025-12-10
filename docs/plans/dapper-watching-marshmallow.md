# Unified Pattern Management Interface - Implementation Plan

## Objective
Unify the MWE and Construction management interfaces into a single UI at `/parser/construction` with auto-detection of pattern types and unified JointJS graph visualization.

## Key Decisions
- **Location**: Enhance existing `/parser/construction` page
- **Type Detection**: Auto-detect from pattern syntax (Simple MWE, Variable MWE, Construction)
- **Data Model**: Keep `parser_constructions` and `parser_mwe` tables separate (different optimization strategies)
- **Graph Visualization**: Convert DOT graphs to JointJS format, unified interactive modal

## Database Schema Analysis
**Shared Fields (4):** idGrammarGraph, semanticType, created_at, updated_at
**Construction-only (8):** compiledGraph, semantics, priority, enabled, etc.
**MWE-only (9):** anchorPosition, anchorWord, componentFormat, firstWord, length, etc.

Tables optimized for different purposes - constructions use pre-compiled graphs and priority matching, MWEs use anchor indexing for fast lookup.

---

## Implementation Steps

### Phase 1: Backend Services & Auto-Detection (Day 1-2)

#### 1.1 Create Pattern Type Detection Service
**File**: `app/Services/Parser/PatternTypeDetector.php`

```php
class PatternTypeDetector {
    public function detectType(string $pattern): PatternType;
    public function detectFromMWE(object $mwe): PatternType;
    private function hasBNFSyntax(string $pattern): bool;
    private function isSimpleWordList(string $pattern): bool;
    private function detectMWEFormat(array $components): PatternType;
}
```

**Detection Rules**:
- **Simple MWE**: Plain words, no special syntax → `a fim de`, `por favor`
- **Variable MWE**: JSON array with type/value objects → `[{"type":"W","value":"de"},{"type":"P","value":"NOUN"}]`
- **Construction**: BNF operators present → `{POS}`, `[]`, `|`, `()`, `+`, `*`

#### 1.2 Create Pattern Type Enum
**File**: `app/Enums/Parser/PatternType.php`

```php
enum PatternType: string {
    case SIMPLE_MWE = 'simple_mwe';
    case VARIABLE_MWE = 'variable_mwe';
    case CONSTRUCTION = 'construction';

    public function icon(): string;  // 'chain', 'random', 'cogs'
    public function label(): string;
    public function color(): string; // 'green', 'purple', 'blue'
}
```

#### 1.3 Create Graph Converter Service
**File**: `app/Services/Parser/GraphConverter.php`

Convert both pattern types to unified JointJS format:

```php
class GraphConverter {
    public function constructionToJointJS(array $compiledGraph): array;
    public function mweToJointJS(object $mwe): array;
    private function getNodeColor(string $type): string;
    private function getNodeShape(string $type): string;
    private function formatMWEComponent(array $component): string;
}
```

**Node Colors**:
- START: #4CAF50 (green), END: #F44336 (red)
- LITERAL: #2196F3 (blue), SLOT: #FF9800 (orange)
- WILDCARD: #9C27B0 (purple), OPTIONAL: #00BCD4 (cyan)

**Graph Structure**: Both produce `{nodes: [...], links: [...]}` format compatible with existing `grapherComponent.js`

#### 1.4 Create Unified Pattern Repository
**File**: `app/Repositories/Parser/PatternRepository.php`

Facade pattern abstracting both tables:

```php
class PatternRepository {
    public function getAllPatterns(int $idGrammarGraph, ?PatternType $filter = null): array;
    public function getPattern(int $id, string $source): object;
    public function createPattern(array $data): int;
    public function updatePattern(int $id, string $source, array $data): void;
    public function deletePattern(int $id, string $source): void;
    private function constructionToPattern(object $construction): object;
    private function mweToPattern(object $mwe): object;
}
```

Returns unified pattern objects with: `id`, `type`, `name`, `pattern`, `semanticType`, `priority`, `enabled`, `source`

#### 1.5 Create Pattern Data DTO
**File**: `app/Data/Parser/PatternData.php`

```php
class PatternData extends Data {
    public function __construct(
        public int $idGrammarGraph,
        public string $name,
        public string $pattern,
        public string $semanticType,
        public ?string $description = null,
        public ?int $priority = 0,
        public bool $enabled = true
    ) {}
}
```

#### 1.6 Create Pattern Validation Rule
**File**: `app/Rules/ValidPatternSyntax.php`

Uses `PatternTypeDetector` to validate pattern can be auto-detected

---

### Phase 2: Controller Extension (Day 2)

#### 2.1 Extend ConstructionController
**File**: `app/Http/Controllers/Parser/ConstructionController.php`

**New/Modified Methods**:

```php
// Unified listing with filter support
public function index() {
    $type = request()->get('type', 'all');
    $patterns = $this->patternRepo->getAllPatterns($grammar, $type);
    // Return unified view
}

// Type detection endpoint for real-time feedback
#[Post(path: '/parser/construction/detect-type')]
public function detectType(Request $request) {
    $type = $this->detector->detectType($request->pattern);
    return response()->json([
        'type' => $type->value,
        'label' => $type->label(),
        'color' => $type->color(),
        'description' => "Detected as {$type->label()} based on syntax"
    ]);
}

// Unified graph endpoint with source parameter
#[Get(path: '/parser/construction/{id}/graph')]
public function graph(int $id) {
    $source = request()->get('source', 'construction');
    $graph = $source === 'mwe'
        ? $this->graphConverter->mweToJointJS(MWE::byId($id))
        : $this->graphConverter->constructionToJointJS(Construction::byId($id));

    return response()->json(['graph' => $graph, ...]);
}

// Unified create/update/delete with auto-detection
public function store(Request $request) {
    $detectedType = $this->detector->detectType($request->pattern);
    return match($detectedType) {
        PatternType::CONSTRUCTION => $this->storeConstruction($request),
        default => $this->storeMWE($request, $detectedType)
    };
}
```

---

### Phase 3: View Updates (Day 3-4)

#### 3.1 Update Index View
**File**: `app/UI/views/Parser/Construction/index.blade.php`

**Key Features**:
- Statistics row showing counts for each type (Constructions, Variable MWEs, Simple MWEs, Total)
- Filter dropdowns: Type (all/construction/variable_mwe/simple_mwe), Semantic Type, Status
- Unified table with columns:
  - **Type Icon**: Icon with color indicating pattern type
  - **Name/Phrase**: Linked to detail view with `?source=` parameter
  - **Pattern**: Truncated display with monospace font
  - **Semantic Type**: Label badge
  - **Priority**: Show value for constructions, "-" for MWEs
  - **Status**: Toggle checkbox for constructions, "Active" label for MWEs
  - **Actions**: View, Edit, Graph, Delete buttons

**JavaScript**:
```javascript
// Client-side filtering by type/semantic/status
function filterPatterns() { /* filter table rows */ }

// Toggle construction enabled status
function toggleConstruction(id, enabled) { /* HTMX POST */ }

// Unified delete with source parameter
function deletePattern(id, source, name) { /* confirmation + DELETE */ }

// Show graph modal
function showPatternGraph(id, source) { /* load via AJAX */ }
```

#### 3.2 Update Create/Edit Forms
**Files**: `create.blade.php`, `edit.blade.php`

**Key Features**:
- Real-time type detection feedback using Alpine.js
- Pattern textarea with debounced auto-detection (500ms)
- Message box showing detected type with icon and description
- Dynamic field visibility:
  - **Priority field**: Only shown for constructions (`x-show="detectedType === 'construction'"`)
  - **Enabled checkbox**: Only shown for constructions
- Pattern syntax help modal with examples for all three types
- HTMX endpoint: `POST /parser/construction/detect-type` for real-time feedback

**Alpine.js Component**:
```javascript
function patternForm() {
    return {
        pattern: '',
        detectedType: null,
        typeLabel: '',
        async detectType() {
            // POST to detect-type endpoint
            // Update UI with detected type
        }
    }
}
```

#### 3.3 Create Unified Graph Modal
**File**: `app/UI/views/Parser/Construction/_graphModal.blade.php`

**Structure**:
```blade
<div class="ui large modal" id="patternGraphModal">
    <div class="header">
        <span id="graphModalTitle">Pattern Graph</span>
        <span class="ui label" id="graphTypeLabel"></span>
    </div>
    <div class="scrolling content">
        <div id="graphModalStats"><!-- Node/edge counts --></div>
        <div id="grapherAppModal" x-data="grapher({})">
            <div id="graphModal" class="wt-layout-grapher"></div>
        </div>
    </div>
    <div class="actions">
        <button class="ui button" onclick="exportGraphAsSVG()">Export SVG</button>
        <div class="ui cancel button">Close</div>
    </div>
</div>
```

**JavaScript Integration**:
```javascript
function showPatternGraph(id, source) {
    $('#patternGraphModal').modal('show');
    $.get(`/parser/construction/${id}/graph?source=${source}`, function(response) {
        // Update modal with graph data
        // Initialize/update JointJS grapher component
        Alpine.$data(grapherApp).updateData(response.graph.nodes, response.graph.links);
    });
}
```

---

### Phase 4: Frontend Graph Integration (Day 4-5)

#### 4.1 Update Grapher Component
**File**: `resources/js/components/grapherComponent.js`

**Enhancements**:
- Support for pattern-specific node types: START, END, LITERAL, SLOT, WILDCARD, INTERMEDIATE, REP_CHECK
- Node shape mapping: circles for START/END, diamonds for REP_CHECK, boxes for others
- Edge style support: solid vs dashed (for bypass/optional paths)
- Color scheme for pattern nodes (different from frame relation colors)

**Node Rendering**:
```javascript
// Add pattern node type handling
if (nodeData.type === 'pattern') {
    const shape = nodeData.shape || 'box';
    const color = nodeData.idColor || '#999';
    // Create JointJS element with appropriate shape and styling
}
```

#### 4.2 Add Pattern-Specific Styles
**File**: `resources/css/components/grapher.less`

```less
// Pattern node colors
.pattern-node-start { fill: #4CAF50; }
.pattern-node-end { fill: #F44336; }
.pattern-node-literal { fill: #2196F3; }
.pattern-node-slot { fill: #FF9800; }
.pattern-node-wildcard { fill: #9C27B0; }

// Edge styles for pattern graphs
.pattern-edge-bypass { stroke-dasharray: 5,5; }
```

---

### Phase 5: Testing & Refinement (Day 5-6)

#### 5.1 Test Auto-Detection
- Simple word patterns → Simple MWE
- JSON component arrays → Variable MWE (simple vs extended format)
- BNF patterns with `{}`, `[]`, `|` → Construction
- Edge cases: empty patterns, ambiguous syntax

#### 5.2 Test Graph Visualization
- Construction graphs display correctly with all node types
- MWE graphs show component sequence with START/END nodes
- Modal sizing and layout correct on different screen sizes
- Interactive features work: pan, zoom, drag
- Export SVG functionality

#### 5.3 Test CRUD Operations
- Create patterns of all three types
- Edit existing constructions and MWEs
- Delete with confirmation
- Toggle construction enabled status
- Validation errors display correctly

#### 5.4 Test Filtering
- Type filter shows/hides correct rows
- Semantic type filter works
- Status filter (enabled/disabled) works
- Filters combine correctly

#### 5.5 Browser Testing
- Test in Chrome, Firefox, Safari
- Check Fomantic-UI component rendering
- Verify Alpine.js reactivity
- HTMX requests complete successfully

---

## Critical Files to Modify/Create

### New Files (8)
1. `app/Services/Parser/PatternTypeDetector.php` - Auto-detection logic
2. `app/Services/Parser/GraphConverter.php` - DOT to JointJS converter
3. `app/Repositories/Parser/PatternRepository.php` - Unified data access
4. `app/Enums/Parser/PatternType.php` - Type enum with UI helpers
5. `app/Data/Parser/PatternData.php` - Validation DTO
6. `app/Rules/ValidPatternSyntax.php` - Pattern syntax validation
7. `app/UI/views/Parser/Construction/_graphModal.blade.php` - Graph modal component
8. `app/UI/views/Parser/Construction/_graphStats.blade.php` - Graph statistics partial

### Modified Files (5)
1. `app/Http/Controllers/Parser/ConstructionController.php` - Add unified methods
2. `app/UI/views/Parser/Construction/index.blade.php` - Unified table with filters
3. `app/UI/views/Parser/Construction/create.blade.php` - Auto-detection form
4. `app/UI/views/Parser/Construction/edit.blade.php` - Auto-detection form
5. `resources/js/components/grapherComponent.js` - Pattern node support

---

## Implementation Approach

### Keep Tables Separate
- `parser_constructions` - Optimized for BNF matching with compiled graphs, priority, enabled toggle
- `parser_mwe` - Optimized for fast lookup with anchor indexing, component format discrimination

### Unified Presentation Layer Only
- Single UI at `/parser/construction` showing all three types
- Backend continues using separate `ConstructionService` and `MWEService`
- Parser V3 detection unchanged (runs MWE detection then Construction detection)
- No data migration required

### Auto-Detection Strategy
1. Try parsing as JSON → if valid, detect Simple vs Variable MWE format
2. Check for BNF operators (`{}`, `[]`, `|`, `()`, `+`, `*`) → Construction
3. Plain words only → Simple MWE
4. Fallback: Throw validation error for unparseable patterns

### Graph Unification Strategy
1. Convert both formats to common JointJS structure: `{nodes, links}`
2. Use node `type: 'pattern'` to distinguish from frame relation graphs
3. Apply consistent layout algorithm (dagre with LR direction)
4. Color-code nodes by semantic type (START=green, END=red, etc.)
5. Support solid/dashed edges for normal/bypass paths

---

## Risk Mitigation

### Auto-Detection Ambiguity
- Default to most complex type (Construction) if uncertain
- Log ambiguous patterns for review
- Allow manual override if needed
- Provide real-time feedback so users can adjust syntax

### Modal Display Issues
- Test z-index with all Fomantic-UI components
- Use proper HTMX loading indicators
- Clear modal state on close
- Handle large graphs (>50 nodes) with zoom controls

### Performance
- Lazy load graphs (only on modal open)
- Cache compiled graphs (already implemented)
- Add pagination if pattern count > 100
- Client-side filtering for responsive UX

### Backward Compatibility
- Keep existing `/parser/grammar/{id}/mwes` endpoint working
- Parser V3 detection logic unchanged
- No breaking changes to repositories or services
- Gradual transition - old URLs can redirect to new unified interface

---

## Success Criteria

✅ Single UI at `/parser/construction` shows all pattern types
✅ Type auto-detection works for all three types with <1s feedback
✅ Graph modal displays interactive JointJS graphs for constructions
✅ Graph modal displays MWE component sequences
✅ Create/edit forms adapt dynamically based on detected type
✅ Filters work correctly (type, semantic, status)
✅ All CRUD operations work for both MWEs and Constructions
✅ No breaking changes to existing parser detection logic
✅ Responsive design works on desktop/tablet screen sizes

---

## Estimated Timeline: 5-6 days

- **Day 1-2**: Backend services (detector, converter, repository, validation)
- **Day 3-4**: View updates (index, forms, modals)
- **Day 4-5**: Graph visualization integration
- **Day 5-6**: Testing, refinement, bug fixes

## Next Steps After Approval

1. Create backend services starting with `PatternTypeDetector`
2. Add `PatternType` enum with UI helper methods
3. Implement `GraphConverter` for DOT→JointJS conversion
4. Test detection and conversion with sample patterns
5. Update `ConstructionController` with unified methods
6. Update views incrementally, testing each component
