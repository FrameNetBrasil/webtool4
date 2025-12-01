# Parser Installation & Testing Checklist

## Pre-Implementation Checklist ✓

- [x] Read initial documentation
- [x] Understand existing codebase patterns
- [x] Plan implementation structure
- [x] Get user confirmation on approach

## Implementation Checklist ✓

### 1. Database Layer ✓
- [x] Create SQL schema script (`database/parser_schema.sql`)
- [x] Define 7 core tables
- [x] Add indexes and foreign keys
- [x] Include sample Portuguese grammar
- [x] Include Portuguese MWEs
- [x] Include 4 test parse graphs
- [x] Create 2 utility views

### 2. Repository Layer ✓
- [x] Create `GrammarGraph.php` repository
- [x] Create `MWE.php` repository
- [x] Create `ParseGraph.php` repository
- [x] Create `ParseNode.php` repository
- [x] Create `ParseEdge.php` repository
- [x] Implement all CRUD methods
- [x] Add specialized query methods

### 3. Data Layer ✓
- [x] Create `ParseInputData.php`
- [x] Create `ParseOutputData.php`
- [x] Create `GrammarGraphData.php`
- [x] Create `MWEData.php`
- [x] Create `NodeData.php`
- [x] Create `EdgeData.php`
- [x] Add validation rules

### 4. Service Layer ✓
- [x] Create `ParserService.php` (main orchestrator)
- [x] Create `MWEService.php` (MWE logic)
- [x] Create `GrammarGraphService.php` (grammar operations)
- [x] Create `FocusQueueService.php` (queue management)
- [x] Create `VisualizationService.php` (D3.js data)
- [x] Implement core parsing algorithm
- [x] Implement MWE prefix hierarchy
- [x] Implement activation mechanism
- [x] Implement garbage collection

### 5. Controllers ✓
- [x] Create `ParserController.php` (UI endpoints)
- [x] Create `GrammarController.php` (grammar management)
- [x] Create `ApiController.php` (JSON API)
- [x] Add PHP routing attributes
- [x] Implement dual response (Blade + JSON)

### 6. Views ✓
- [x] Create `parser.blade.php` (main interface)
- [x] Create `parserResults.blade.php` (results display)
- [x] Create `parserGraph.blade.php` (D3.js visualization)
- [x] Create `parserError.blade.php` (error display)
- [x] Create `grammarView.blade.php` (grammar details)
- [x] Add HTMX integration
- [x] Add Fomantic-UI styling

### 7. Configuration ✓
- [x] Create `config/parser.php`
- [x] Add all configuration sections
- [x] Document all options

### 8. Styling ✓
- [x] Create `resources/css/parser/parser.less`
- [x] Add parser-specific styles
- [x] Add responsive design
- [x] Add animations

### 9. Code Quality ✓
- [x] Run Laravel Pint for code formatting
- [x] Follow project conventions
- [x] Add comprehensive documentation

## Post-Implementation Checklist

### 1. Database Setup
- [ ] Import SQL schema
  ```bash
  mysql -u [username] -p [database] < database/parser_schema.sql
  ```
- [ ] Verify tables created
- [ ] Verify sample data loaded
- [ ] Check views created

### 2. Build Assets
- [ ] Compile LESS to CSS
  ```bash
  npm run build
  # or for development
  npm run dev
  ```
- [ ] Verify CSS includes parser styles

### 3. Route Registration
- [ ] Verify routes are auto-registered via PHP attributes
- [ ] Test route: `GET /parser`
- [ ] Test route: `POST /parser/parse`
- [ ] Test route: `GET /api/parser/parse`

### 4. Basic Testing

#### Test Database Access
```bash
php artisan tinker
```
```php
// Test repository
use App\Repositories\Parser\GrammarGraph;
$grammars = GrammarGraph::list();
dump($grammars);

use App\Repositories\Parser\MWE;
$mwes = MWE::listByGrammar(1);
dump($mwes);
```

#### Test Parser Service
```bash
php artisan tinker
```
```php
use App\Services\Parser\ParserService;
use App\Data\Parser\ParseInputData;

$service = app(ParserService::class);
$input = new ParseInputData(
    sentence: 'Tomei café da manhã',
    idGrammarGraph: 1,
    queueStrategy: 'fifo'
);

$result = $service->parse($input);
dump($result);
```

#### Test API Endpoint
```bash
curl -X POST http://localhost:8001/api/parser/parse \
  -H "Content-Type: application/json" \
  -d '{
    "sentence": "Tomei café da manhã",
    "idGrammarGraph": 1,
    "queueStrategy": "fifo"
  }'
```

#### Test UI
1. Navigate to `http://localhost:8001/parser`
2. Enter sentence: "Tomei café da manhã"
3. Select grammar: "Portuguese Basic Grammar"
4. Click "Parse Sentence"
5. Verify results display
6. Click "Show Graph Visualization"
7. Verify D3.js graph renders

### 5. Test Cases

#### Simple Sentence
- [ ] Parse: "Café está quente"
- [ ] Verify: 3 nodes, 2 edges
- [ ] Verify: Status = complete

#### MWE Completion
- [ ] Parse: "Tomei café da manhã"
- [ ] Verify: "café da manhã" is single MWE node
- [ ] Verify: Activation = 3, Threshold = 3
- [ ] Verify: Status = complete

#### Nested MWE
- [ ] Parse: "Mesa de café da manhã"
- [ ] Verify: Both MWEs present
- [ ] Verify: Correct nesting
- [ ] Verify: Status = complete

#### Interrupted MWE
- [ ] Parse: "Café quente da manhã"
- [ ] Verify: No MWE node (interrupted)
- [ ] Verify: 4 separate word nodes
- [ ] Verify: Edges connect properly

### 6. Visualization Testing
- [ ] Test force-directed layout
- [ ] Test node dragging
- [ ] Test hover tooltips
- [ ] Test node colors (E=green, V=blue, A=orange, F=gray, MWE=purple)
- [ ] Test responsive design

### 7. Export Testing
- [ ] Export JSON - verify format
- [ ] Export GraphML - verify format
- [ ] Export DOT - verify format
- [ ] Test file downloads

### 8. Grammar Management
- [ ] View grammar details: `/parser/grammar/1`
- [ ] Verify node list
- [ ] Verify MWE list
- [ ] Verify statistics

### 9. Error Handling
- [ ] Test empty sentence
- [ ] Test very long sentence (>100 words)
- [ ] Test invalid grammar ID
- [ ] Test malformed input
- [ ] Verify error messages display

### 10. Performance Testing
- [ ] Parse 10-word sentence - measure time
- [ ] Parse 20-word sentence - measure time
- [ ] Parse 50-word sentence - measure time
- [ ] Verify timeout works (30 seconds default)

## Optional Enhancements

### Unit/Feature Tests
- [ ] Create Pest tests for repositories
- [ ] Create Pest tests for services
- [ ] Create Pest tests for controllers
- [ ] Run test suite: `php artisan test`

### Additional Grammars
- [ ] Add English grammar
- [ ] Add more Portuguese MWEs
- [ ] Test with different languages

### Advanced Features
- [ ] Integrate POS tagger
- [ ] Add lexicon support
- [ ] Implement caching
- [ ] Add batch processing

## Known Limitations

1. **Word Type Determination**: Currently defaults to 'E' for unknown words. Need POS tagger integration.
2. **Grammar Completeness**: Portuguese grammar is basic. Needs expansion for production use.
3. **No Tests**: Unit/Feature tests not yet implemented.
4. **Caching Disabled**: Performance optimization not yet implemented.
5. **No Batch Processing**: Currently processes one sentence at a time.

## Troubleshooting

### Routes Not Found
**Issue:** 404 errors on parser routes
**Solution:** Ensure PHP attributes are being scanned by Laravel. Check `bootstrap/app.php` for route configuration.

### Database Connection Error
**Issue:** Cannot connect to database
**Solution:** Verify `.env` file has correct database credentials.

### HTMX Not Working
**Issue:** Parse button doesn't trigger request
**Solution:** Verify HTMX library is loaded in layout. Check browser console for errors.

### D3.js Graph Not Rendering
**Issue:** Blank graph canvas
**Solution:** Verify D3.js v7 CDN is loaded. Check browser console for JavaScript errors.

### Styles Not Applied
**Issue:** Parser interface looks unstyled
**Solution:** Run `npm run build` to compile LESS. Verify `resources/css/parser/parser.less` is imported in main stylesheet.

### Parse Fails Silently
**Issue:** No results, no error
**Solution:** Check Laravel logs: `tail -f storage/logs/laravel.log`

## Success Criteria

- ✓ All database tables created
- ✓ Sample data loaded
- ✓ `/parser` route accessible
- ✓ Can parse simple sentences
- ✓ MWE processing works
- ✓ Results display correctly
- ✓ Graph visualization renders
- ✓ Exports work (JSON/GraphML/DOT)
- ✓ API endpoints return JSON
- ✓ No PHP/JavaScript errors

## Final Verification

```bash
# 1. Check database
mysql -u [user] -p -e "SHOW TABLES LIKE 'grammar%'; SHOW TABLES LIKE 'parser_mwe'; SHOW TABLES LIKE 'parse%';" [database]

# 2. Check files exist
ls -la app/Repositories/Parser/
ls -la app/Services/Parser/
ls -la app/Http/Controllers/Parser/
ls -la app/UI/views/Parser/
ls -la resources/css/parser/

# 3. Run Pint
vendor/bin/pint --dirty

# 4. Check routes
php artisan route:list | grep parser

# 5. Test parse
curl -X POST http://localhost:8001/api/parser/parse \
  -H "Content-Type: application/json" \
  -d '{"sentence": "Café está quente", "idGrammarGraph": 1}'
```

## Next Steps After Installation

1. **Add More Test Data**: Expand Portuguese grammar and MWEs
2. **Write Tests**: Implement Pest tests for all components
3. **Performance Tuning**: Enable caching, optimize queries
4. **Documentation**: Add inline documentation and examples
5. **User Feedback**: Gather feedback and iterate

---

**Installation and testing should be completed systematically using this checklist.**
