<?php

namespace App\Console\Commands\Lexicon;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LemmaCheckFormCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemma:check-forms
                            {--language= : Filter by language ID (1=Portuguese, 2=English)}
                            {--limit= : Limit number of results (for testing)}
                            {--output= : Output file path for CSV export}
                            {--create-forms : Automatically create missing canonical forms}
                            {--force : Skip confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check which lemmas do not have a specific form associated';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $idLanguage = $this->option('language') ? (int) $this->option('language') : null;
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $output = $this->option('output');

        $languageFilter = $idLanguage ? config('udparser.languages')[$idLanguage] ?? "ID {$idLanguage}" : 'all languages';

        $this->info('🔍 Checking lemmas without associated forms');
        $this->info("🌍 Language: {$languageFilter}");
        if ($limit) {
            $this->info("🔢 Limit: {$limit} results");
        }
        $this->newLine();

        // Build query to find lemmas without canonical forms (excluding MWE)
        $query = Criteria::table('view_lemma as lm')
            ->select(
                'lm.idLemma',
                'lm.idLexicon',
                'lm.name as lemma_form',
                'lm.idLanguage'
            )
            ->whereNotIn('lm.idLexicon', function ($subquery) {
                $subquery->select('l1.idLexicon')
                    ->from('lexicon_expression as le')
                    ->join('lexicon as l1', 'le.idLexicon', '=', 'l1.idLexicon')
                    ->join('lexicon as l2', 'le.idExpression', '=', 'l2.idLexicon')
                    ->whereRaw('l1.form collate utf8mb4_bin = l2.form collate utf8mb4_bin')
                    ->where('l1.idLexiconGroup', '=', 2)
                    ->where('l2.idLexiconGroup', '=', 1);
            })
            ->whereRaw('lm.name not like "% %"')
            ->when($idLanguage, fn ($q) => $q->where('lm.idLanguage', '=', $idLanguage))
            ->orderBy('lm.idLemma');

        // Get total count
        $totalCount = $query->count();

        if ($totalCount === 0) {
            $this->info('✓ All lemmas have associated forms!');

            return Command::SUCCESS;
        }

        $this->warn("⚠ Found {$totalCount} lemma(s) without associated forms");
        $this->newLine();

        // Get limited results for display
        $displayLimit = $limit ?? 20;
        $results = $query->limit($displayLimit)->get();

        // Display results in table
        $this->displayResults($results, $totalCount, $displayLimit);

        // Create canonical forms if requested
        if ($this->option('create-forms')) {
            $createResults = $query->get(); // Get all results for creation
            $stats = $this->createCanonicalForms($createResults, $this->option('force'));

            // Display creation statistics
            if ($stats['form_created'] > 0 || $stats['expression_created'] > 0) {
                $this->info('✅ Canonical form creation complete:');
                $this->line("   Forms already existed: {$stats['already_exists']}");
                $this->line("   Forms created: {$stats['form_created']}");
                $this->line("   Expressions created: {$stats['expression_created']}");

                if (! empty($stats['errors'])) {
                    $this->newLine();
                    $this->warn('⚠ Errors encountered:');
                    foreach (array_slice($stats['errors'], 0, 5) as $error) {
                        $this->line("   {$error}");
                    }
                    if (count($stats['errors']) > 5) {
                        $remaining = count($stats['errors']) - 5;
                        $this->line("   ... and {$remaining} more error(s)");
                    }
                }
            }
        }

        // Export to CSV if requested
        if ($output) {
            $this->exportToCSV($query, $output, $limit);
            $this->info("✓ Results exported to: {$output}");
        }

        return Command::SUCCESS;
    }

    /**
     * Display results in console table
     */
    protected function displayResults($results, int $totalCount, int $displayLimit): void
    {
        if ($results->isEmpty()) {
            return;
        }

        $this->info('Showing first '.$displayLimit.' results:');
        $this->newLine();

        $tableData = [];
        foreach ($results as $lemma) {
            $tableData[] = [
                $lemma->idLemma,
                $lemma->idLexicon,
                $lemma->lemma_form,
                $lemma->idLanguage,
            ];
        }

        $this->table(
            ['ID Lemma', 'ID Lexicon', 'Lemma Form', 'Language'],
            $tableData
        );

        if ($totalCount > $displayLimit) {
            $remaining = $totalCount - $displayLimit;
            $this->newLine();
            $this->line("... and {$remaining} more lemma(s) without forms");
        }
    }

    /**
     * Create canonical forms for lemmas that don't have them
     */
    protected function createCanonicalForms($results, bool $force): array
    {
        $stats = [
            'already_exists' => 0,
            'form_created' => 0,
            'expression_created' => 0,
            'errors' => [],
        ];

        if ($results->isEmpty()) {
            return $stats;
        }

        $totalLemmas = $results->count();

        if (! $force) {
            $this->newLine();
            $this->warn("⚠️  This will create canonical forms for {$totalLemmas} lemma(s)");
            $this->newLine();

            if (! $this->confirm('Do you want to continue?', false)) {
                $this->info('Operation cancelled');

                return $stats;
            }
        }

        $this->newLine();
        $this->info('⚙️  Creating canonical forms...');
        $this->output->progressStart($totalLemmas);

        foreach ($results as $lemma) {
            try {
                // Check if canonical form already exists
                $existingForm = Criteria::table('lexicon')
                    ->whereRaw('form collate utf8mb4_bin = ? collate utf8mb4_bin', [$lemma->lemma_form])
                    ->where('idLexiconGroup', 1)
                    ->where('idLanguage', $lemma->idLanguage)
                    ->first();

                $idCanonicalForm = null;

                if ($existingForm) {
                    $idCanonicalForm = $existingForm->idLexicon;
                    $stats['already_exists']++;
                } else {
                    // Create new canonical form directly (without lexicon_create function)
                    // because canonical forms don't require POS information

                    // First create entity (auto-increment will generate ID)
                    $idEntity = Criteria::table('entity')->insertGetId([]);

                    // Then create lexicon entry with entity reference
                    $idCanonicalForm = Criteria::table('lexicon')->insertGetId([
                        'form' => $lemma->lemma_form,
                        'idLexiconGroup' => 1,
                        'idLanguage' => $lemma->idLanguage,
                        'idEntity' => $idEntity,
                    ]);

                    $stats['form_created']++;
                }

                // Check if lexicon_expression already exists
                $existingExpression = Criteria::table('lexicon_expression')
                    ->where('idLexicon', $lemma->idLexicon)
                    ->where('idExpression', $idCanonicalForm)
                    ->first();

                if (! $existingExpression) {
                    // Create lexicon_expression linking lemma to canonical form
                    Criteria::create('lexicon_expression', [
                        'idLexicon' => $lemma->idLexicon,
                        'idExpression' => $idCanonicalForm,
                        'head' => 1,
                        'breakBefore' => 0,
                        'position' => 1,
                    ]);
                    $stats['expression_created']++;
                }

                $this->output->progressAdvance();
            } catch (\Exception $e) {
                $stats['errors'][] = "Lemma {$lemma->idLemma}: {$e->getMessage()}";
                $this->output->progressAdvance();
            }
        }

        $this->output->progressFinish();
        $this->newLine();

        return $stats;
    }

    /**
     * Export results to CSV file
     */
    protected function exportToCSV($query, string $outputPath, ?int $limit): void
    {
        // Get all results (or limited if specified)
        $results = $limit ? $query->limit($limit)->get() : $query->get();

        // Ensure directory exists
        $directory = dirname($outputPath);
        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Write CSV
        $handle = fopen($outputPath, 'w');
        fputcsv($handle, ['idLemma', 'idLexicon', 'lemma_form', 'idLanguage', 'timestamp']);

        foreach ($results as $lemma) {
            fputcsv($handle, [
                $lemma->idLemma,
                $lemma->idLexicon,
                $lemma->lemma_form,
                $lemma->idLanguage,
                now()->toDateTimeString(),
            ]);
        }

        fclose($handle);
    }
}
