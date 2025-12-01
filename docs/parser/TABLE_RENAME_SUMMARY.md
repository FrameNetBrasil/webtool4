# Parser Table Renaming Summary

## Date
2025-11-30

## Reason for Renaming
To follow project naming conventions, all parser tables have been renamed to include the `parser_` prefix.

## Table Name Changes

| Old Name | New Name | Description |
|----------|----------|-------------|
| `grammar_graph` | `parser_grammar_graph` | Grammar definitions |
| `grammar_node` | `parser_grammar_node` | Word types and fixed words |
| `grammar_edge` | `parser_grammar_link` | Valid transitions (renamed from "edge" to "link") |
| `mwe` | `parser_mwe` | Multi-word expressions |
| `parse_graph` | `parser_graph` | Parse instances |
| `parse_node` | `parser_node` | Instantiated nodes during parsing |
| `parse_edge` | `parser_link` | Relationships between nodes (renamed from "edge" to "link") |

## Primary Key/ID Changes

| Old Name | New Name |
|----------|----------|
| `idParseGraph` | `idParserGraph` |
| `idParseNode` | `idParserNode` |
| `idParseEdge` | `idParserLink` |
| `idGrammarEdge` | `idGrammarLink` |

## Column Name Changes

| Old Name | New Name | Affected Tables |
|----------|----------|-----------------|
| `edgeType` | `linkType` | `parser_grammar_link`, `parser_link` |

## View Name Changes

| Old Name | New Name |
|----------|----------|
| `view_grammar_graph` | `view_parser_grammar_graph` |
| `view_parse_graph_stats` | `view_parser_graph_stats` |

## Files Updated

### 1. Database Schema
- ✅ `database/parser_schema.sql` - All table definitions, foreign keys, and sample data updated

### 2. Repository Classes (5 files)
- ✅ `app/Repositories/Parser/GrammarGraph.php`
- ✅ `app/Repositories/Parser/MWE.php`
- ✅ `app/Repositories/Parser/ParseGraph.php`
- ✅ `app/Repositories/Parser/ParseNode.php`
- ✅ `app/Repositories/Parser/ParseEdge.php`

### 3. Service Classes (5 files)
- ✅ `app/Services/Parser/ParserService.php`
- ✅ `app/Services/Parser/MWEService.php`
- ✅ `app/Services/Parser/GrammarGraphService.php`
- ✅ `app/Services/Parser/FocusQueueService.php`
- ✅ `app/Services/Parser/VisualizationService.php`

### 4. Data Classes (6 files)
- ✅ `app/Data/Parser/ParseInputData.php`
- ✅ `app/Data/Parser/ParseOutputData.php`
- ✅ `app/Data/Parser/GrammarGraphData.php`
- ✅ `app/Data/Parser/MWEData.php`
- ✅ `app/Data/Parser/NodeData.php`
- ✅ `app/Data/Parser/EdgeData.php`

### 5. Controller Classes (3 files)
- ✅ `app/Http/Controllers/Parser/ParserController.php`
- ✅ `app/Http/Controllers/Parser/GrammarController.php`
- ✅ `app/Http/Controllers/Parser/ApiController.php`

### 6. Blade Views (5 files)
- ✅ `app/UI/views/Parser/parser.blade.php`
- ✅ `app/UI/views/Parser/parserResults.blade.php`
- ✅ `app/UI/views/Parser/parserGraph.blade.php`
- ✅ `app/UI/views/Parser/parserError.blade.php`
- ✅ `app/UI/views/Parser/grammarView.blade.php`

### 7. Documentation (2 files)
- ✅ `docs/parser/IMPLEMENTATION_SUMMARY.md`
- ✅ `docs/parser/INSTALLATION_CHECKLIST.md`

### 8. Configuration
- ✅ `config/parser.php` - No changes needed (doesn't reference table names directly)

## Verification Steps

### 1. Check Database Schema
```bash
mysql -u [username] -p [database] < database/parser_schema.sql
```

Expected tables:
- `parser_grammar_graph`
- `parser_grammar_node`
- `parser_grammar_link`
- `parser_mwe`
- `parser_graph`
- `parser_node`
- `parser_link`

Expected views:
- `view_parser_grammar_graph`
- `view_parser_graph_stats`

### 2. Verify Code Formatting
```bash
vendor/bin/pint app/Repositories/Parser app/Services/Parser app/Data/Parser app/Http/Controllers/Parser --dirty
```

Result: All files pass formatting checks ✅

### 3. Test Repository Access
```bash
php artisan tinker
```

```php
use App\Repositories\Parser\GrammarGraph;
$grammars = GrammarGraph::list();
dump($grammars);
```

### 4. Test API Endpoint
```bash
curl -X POST http://localhost:8001/api/parser/parse \
  -H "Content-Type: application/json" \
  -d '{
    "sentence": "Tomei café da manhã",
    "idGrammarGraph": 1,
    "queueStrategy": "fifo"
  }'
```

## Impact Assessment

### ✅ No Breaking Changes for New Installations
- Users installing fresh will use the new table names from the start

### ⚠️ Breaking Changes for Existing Installations
If you have already imported the old schema, you need to:

1. **Drop old tables** (if data is not important):
   ```sql
   DROP TABLE IF EXISTS parse_edge;
   DROP TABLE IF EXISTS parse_node;
   DROP TABLE IF EXISTS parse_graph;
   DROP TABLE IF EXISTS mwe;
   DROP TABLE IF EXISTS grammar_edge;
   DROP TABLE IF EXISTS grammar_node;
   DROP TABLE IF EXISTS grammar_graph;
   DROP VIEW IF EXISTS view_grammar_graph;
   DROP VIEW IF EXISTS view_parse_graph_stats;
   ```

2. **Import new schema**:
   ```bash
   mysql -u [username] -p [database] < database/parser_schema.sql
   ```

3. **OR use rename statements**:
   ```sql
   RENAME TABLE grammar_graph TO parser_grammar_graph;
   RENAME TABLE grammar_node TO parser_grammar_node;
   RENAME TABLE grammar_edge TO parser_grammar_link;
   RENAME TABLE mwe TO parser_mwe;
   RENAME TABLE parse_graph TO parser_graph;
   RENAME TABLE parse_node TO parser_node;
   RENAME TABLE parse_edge TO parser_link;

   -- Update column names in renamed tables
   ALTER TABLE parser_grammar_link CHANGE edgeType linkType ENUM('sequential', 'activate', 'dependency', 'prediction') NOT NULL;
   ALTER TABLE parser_link CHANGE edgeType linkType ENUM('sequential', 'activate', 'dependency', 'prediction') DEFAULT 'dependency';

   -- Recreate views with new names
   DROP VIEW IF EXISTS view_grammar_graph;
   DROP VIEW IF EXISTS view_parse_graph_stats;

   -- Then run the CREATE VIEW statements from parser_schema.sql
   ```

## Summary Statistics

**Total Files Updated**: 28 files
- 1 SQL schema file
- 5 Repository files
- 5 Service files
- 6 Data files
- 3 Controller files
- 5 Blade view files
- 2 Documentation files
- 1 Configuration file (no changes needed)

**Table Renames**: 7 tables
**View Renames**: 2 views
**ID Renames**: 4 primary keys/foreign keys
**Column Renames**: 1 column type (`edgeType` → `linkType`)

**Code Quality**: All PHP files pass Laravel Pint formatting ✅

---

**Renaming completed successfully on 2025-11-30**
