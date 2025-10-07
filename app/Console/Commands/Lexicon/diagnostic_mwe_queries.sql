-- ============================================================================
-- Diagnostic Queries for MWE (Multi-Word Expression) Data Integrity
-- ============================================================================
-- Purpose: Identify problematic MWE lemmas before running lemma:store command
-- Author: Generated for FNBr Webtool 4.2
-- Date: 2025-10-07
-- ============================================================================

-- ----------------------------------------------------------------------------
-- QUERY 1: Lemmas with multi-word names but only 1 expression (incomplete MWEs)
-- ----------------------------------------------------------------------------
-- These lemmas have spaces in their name but only one word form in expressions
-- Example: "added time" → "time" (missing "added")
-- Action: Add missing expressions or correct the lemma name
-- ----------------------------------------------------------------------------

SELECT
    l.idLemma,
    l.idLexicon,
    l.name as lemma_name,
    l.idLanguage,
    lang.language,
    COUNT(le.idLexiconExpression) as expression_count,
    GROUP_CONCAT(lex.form ORDER BY le.position SEPARATOR ' ') as actual_expressions
FROM view_lemma l
JOIN lexicon_expression le ON l.idLexicon = le.idLexicon
JOIN lexicon lex ON le.idExpression = lex.idLexicon
LEFT JOIN language lang ON l.idLanguage = lang.idLanguage
WHERE l.name LIKE '% %'
GROUP BY l.idLemma, l.idLexicon, l.name, l.idLanguage, lang.language
HAVING COUNT(le.idLexiconExpression) = 1
ORDER BY l.idLanguage, l.name;


-- ----------------------------------------------------------------------------
-- QUERY 2: Lemmas where expressions don't match the lemma name (mismatches)
-- ----------------------------------------------------------------------------
-- These lemmas have a multi-word name that differs from the actual expressions
-- Example: lemma "kick ball" but expressions are "strike sphere"
-- Action: Verify if expressions are correct or if lemma name needs update
-- ----------------------------------------------------------------------------

SELECT
    l.idLemma,
    l.idLexicon,
    l.name as lemma_name,
    l.idLanguage,
    lang.language,
    GROUP_CONCAT(lex.form ORDER BY le.position SEPARATOR ' ') as actual_expressions,
    COUNT(le.idLexiconExpression) as expression_count
FROM view_lemma l
JOIN lexicon_expression le ON l.idLexicon = le.idLexicon
JOIN lexicon lex ON le.idExpression = lex.idLexicon
LEFT JOIN language lang ON l.idLanguage = lang.idLanguage
WHERE l.name LIKE '% %'
GROUP BY l.idLemma, l.idLexicon, l.name, l.idLanguage, lang.language
HAVING GROUP_CONCAT(lex.form ORDER BY le.position SEPARATOR ' ') != l.name
ORDER BY l.idLanguage, l.name;


-- ----------------------------------------------------------------------------
-- QUERY 3: Lemmas with gaps in position sequence
-- ----------------------------------------------------------------------------
-- These lemmas have non-sequential positions (e.g., position 1 and 3, missing 2)
-- Example: positions "1, 3, 4" instead of "1, 2, 3"
-- Action: Renumber positions to be sequential starting from 1
-- ----------------------------------------------------------------------------

SELECT
    l.idLemma,
    l.idLexicon,
    l.name as lemma_name,
    l.idLanguage,
    lang.language,
    GROUP_CONCAT(le.position ORDER BY le.position) as positions,
    GROUP_CONCAT(lex.form ORDER BY le.position SEPARATOR ' ') as expressions,
    COUNT(*) as expr_count
FROM view_lemma l
JOIN lexicon_expression le ON l.idLexicon = le.idLexicon
JOIN lexicon lex ON le.idExpression = lex.idLexicon
LEFT JOIN language lang ON l.idLanguage = lang.idLanguage
WHERE l.name LIKE '% %'
GROUP BY l.idLemma, l.idLexicon, l.name, l.idLanguage, lang.language
ORDER BY l.idLanguage, l.name;

-- Note: Review the 'positions' column for any gaps or non-sequential numbers


-- ----------------------------------------------------------------------------
-- QUERY 4: Summary statistics
-- ----------------------------------------------------------------------------
-- Overview of MWE data quality across the database
-- ----------------------------------------------------------------------------

SELECT
    'Total MWE lemmas (by name with spaces)' as category,
    COUNT(*) as count
FROM view_lemma
WHERE name LIKE '% %'

UNION ALL

SELECT
    'MWE lemmas with only 1 expression (INCOMPLETE)',
    COUNT(*)
FROM (
    SELECT l.idLemma
    FROM view_lemma l
    JOIN lexicon_expression le ON l.idLexicon = le.idLexicon
    WHERE l.name LIKE '% %'
    GROUP BY l.idLemma
    HAVING COUNT(*) = 1
) as incomplete

UNION ALL

SELECT
    'MWE lemmas with 2+ expressions (VALID)',
    COUNT(*)
FROM (
    SELECT l.idLemma
    FROM view_lemma l
    JOIN lexicon_expression le ON l.idLexicon = le.idLexicon
    WHERE l.name LIKE '% %'
    GROUP BY l.idLemma
    HAVING COUNT(*) > 1
) as valid

UNION ALL

SELECT
    'MWE lemmas with mismatched expressions',
    COUNT(*)
FROM (
    SELECT l.idLemma
    FROM view_lemma l
    JOIN lexicon_expression le ON l.idLexicon = le.idLexicon
    JOIN lexicon lex ON le.idExpression = lex.idLexicon
    WHERE l.name LIKE '% %'
    GROUP BY l.idLemma, l.name
    HAVING GROUP_CONCAT(lex.form ORDER BY le.position SEPARATOR ' ') != l.name
) as mismatched;


-- ----------------------------------------------------------------------------
-- QUERY 5 (BONUS): Get detailed info for a specific problematic lemma
-- ----------------------------------------------------------------------------
-- Replace @idLemma with the actual lemma ID you want to investigate
-- ----------------------------------------------------------------------------

-- SET @idLemma = 54;  -- Example: 'added time'
--
-- SELECT
--     l.idLemma,
--     l.idLexicon,
--     l.name as lemma_name,
--     l.idLanguage,
--     le.idLexiconExpression,
--     le.position,
--     le.head,
--     le.breakBefore,
--     le.idExpression,
--     lex.form as expression_form
-- FROM view_lemma l
-- LEFT JOIN lexicon_expression le ON l.idLexicon = le.idLexicon
-- LEFT JOIN lexicon lex ON le.idExpression = lex.idLexicon
-- WHERE l.idLemma = @idLemma
-- ORDER BY le.position;


-- ============================================================================
-- RECOMMENDED WORKFLOW:
-- ============================================================================
-- 1. Run Query 4 first to get overall statistics
-- 2. Run Query 1 to find incomplete MWEs (priority fix)
-- 3. Run Query 3 to find position sequence issues
-- 4. Run Query 2 to find mismatches (may be intentional)
-- 5. Fix the data issues found
-- 6. Re-run Query 4 to verify improvements
-- 7. Then run: php artisan lemma:store --dry-run --limit=20
-- ============================================================================
