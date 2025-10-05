<?php

namespace App\Console\Commands\Lexicon;

use App\Services\Lemma\LexiconPatternService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LemmaStoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemma:store
                            {--language= : Filter by language ID (1=Portuguese, 2=English)}
                            {--limit= : Limit number of lemmas to process (for testing)}
                            {--dry-run : Preview lemmas without storing patterns}
                            {--resume : Resume processing, skip already processed lemmas}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store lemma patterns from database (all SWE and MWE)';

    protected LexiconPatternService $lemmaService;

    /**
     * Create a new command instance.
     */
    public function __construct(LexiconPatternService $lemmaService)
    {
        parent::__construct();
        $this->lemmaService = $lemmaService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Increase memory limit for large batch processing
        ini_set('memory_limit', '512M');

        $idLanguage = $this->option('language') ? (int) $this->option('language') : null;
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $dryRun = $this->option('dry-run');
        $resume = $this->option('resume');
        $force = $this->option('force');

        $languageFilter = $idLanguage ? config('udparser.languages')[$idLanguage] ?? "ID {$idLanguage}" : 'all languages';

        $this->info('📚 Lemma Pattern Storage from Database');
        $this->info("🌍 Language: {$languageFilter}");
        if ($resume) {
            $this->info('♻️  Mode: Resume (skip already processed)');
        }
        if ($limit) {
            $this->info("🔢 Limit: {$limit} lemmas");
        }
        $this->newLine();

        // Get ALL lemmas (not just MWEs)
        $lemmaQuery = DB::table('view_lemma')
            ->when($idLanguage, fn ($q) => $q->where('idLanguage', $idLanguage))
            ->when($resume, fn ($q) => $q->whereNotIn('idLemma',
                DB::table('lexicon_pattern')->pluck('idLemma')
            ))
            ->when($limit, fn ($q) => $q->limit($limit))
            ->orderBy('idLemma');

        $totalLemmas = $lemmaQuery->count();

        if ($totalLemmas === 0) {
            $this->warn('No lemmas found in database');

            return Command::SUCCESS;
        }

        $this->info("Found {$totalLemmas} lemma(s) to process");
        $this->newLine();

        if ($dryRun) {
            $this->displayDryRunPreview($lemmaQuery, $limit);

            return Command::SUCCESS;
        }

        // Confirm truncate (only if not resuming)
        if (! $resume) {
            if (! $force) {
                $this->warn('⚠️  This will TRUNCATE all existing pattern tables:');
                $this->line('   - lexicon_pattern_constraint');
                $this->line('   - lexicon_pattern_edge');
                $this->line('   - lexicon_pattern_node');
                $this->line('   - lexicon_pattern');
                $this->newLine();

                if (! $this->confirm('Do you want to continue?', false)) {
                    $this->info('Operation cancelled');

                    return Command::SUCCESS;
                }
            }

            // Truncate pattern tables
            $this->info('🗑️  Truncating pattern tables...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('lexicon_pattern_constraint')->truncate();
            DB::table('lexicon_pattern_edge')->truncate();
            DB::table('lexicon_pattern_node')->truncate();
            DB::table('lexicon_pattern')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->newLine();
        } else {
            $this->info('♻️  Resuming from previous run (existing patterns preserved)...');
            $this->newLine();
        }

        // Use service to store patterns
        $this->info('⚙️  Processing lemmas...');
        $this->output->progressStart($totalLemmas);

        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        // CSV file for lemmas without expressions
        $csvPath = storage_path('logs/lemmas_without_expressions_'.date('Y-m-d_His').'.csv');
        $csvHandle = fopen($csvPath, 'w');
        fputcsv($csvHandle, ['idLemma', 'name', 'error', 'timestamp']);

        // Process in chunks to avoid memory issues
        $chunkCount = 0;
        $lemmaQuery->chunk(100, function ($lemmas) use ($idLanguage, &$results, $csvHandle, &$chunkCount) {
            $idLemmas = $lemmas->pluck('idLemma')->toArray();

            $batchResults = $this->lemmaService->storeLemmaPatternsBatch(
                $idLemmas,
                $idLanguage ?? 1,
                function ($processed, $total, $result) {
                    $this->output->progressAdvance();
                }
            );

            // Log lemmas without expressions to CSV
            foreach ($batchResults['errors'] as $idLemma => $error) {
                if (str_contains($error, 'No expressions found')) {
                    $lemma = $lemmas->firstWhere('idLemma', $idLemma);
                    fputcsv($csvHandle, [
                        $idLemma,
                        $lemma->name ?? 'N/A',
                        $error,
                        now()->toDateTimeString(),
                    ]);
                }
            }

            // Aggregate results
            $results['success'] += $batchResults['success'];
            $results['failed'] += $batchResults['failed'];
            $results['errors'] = array_merge($results['errors'], $batchResults['errors']);

            // Force garbage collection every 10 chunks
            $chunkCount++;
            if ($chunkCount % 10 === 0) {
                gc_collect_cycles();
            }
        });

        fclose($csvHandle);

        $this->output->progressFinish();
        $this->newLine();

        $this->info("✓ Successfully processed: {$results['success']} lemma pattern(s)");

        if ($results['failed'] > 0) {
            $this->warn("⚠ Errors encountered: {$results['failed']}");

            // Count lemmas without expressions
            $noExpressionCount = 0;
            foreach ($results['errors'] as $error) {
                if (str_contains($error, 'No expressions found')) {
                    $noExpressionCount++;
                }
            }

            if ($noExpressionCount > 0) {
                $this->newLine();
                $this->info("📄 Logged {$noExpressionCount} lemma(s) without expressions to:");
                $this->line("   {$csvPath}");
            }

            // Show first few errors
            $errorCount = min(5, count($results['errors']));
            $this->newLine();
            $this->warn("First {$errorCount} errors:");
            $shown = 0;
            foreach ($results['errors'] as $idLemma => $error) {
                if ($shown >= $errorCount) {
                    break;
                }
                $this->line("  Lemma {$idLemma}: {$error}");
                $shown++;
            }

            if (count($results['errors']) > $errorCount) {
                $remaining = count($results['errors']) - $errorCount;
                $this->line("  ... and {$remaining} more errors");
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Display dry run preview
     */
    protected function displayDryRunPreview($lemmaQuery, ?int $limit): void
    {
        $this->info('🔍 DRY RUN MODE - Previewing lemmas:');
        $this->newLine();

        $lemmas = $lemmaQuery->limit($limit ?? 10)->get();

        foreach ($lemmas as $index => $lemma) {
            // Get expressions for this lemma
            $expressions = DB::table('view_lexicon_expression')
                ->where('idLemma', $lemma->idLexicon)
                ->orderBy('position')
                ->pluck('form')
                ->toArray();

            $fullText = implode(' ', $expressions);
            $langName = config('udparser.languages')[$lemma->idLanguage] ?? "ID {$lemma->idLanguage}";
            $type = count($expressions) > 1 ? 'MWE' : 'SWE';

            $this->line(($index + 1).". [{$langName}] [{$type}] {$lemma->name} → \"{$fullText}\"");
        }

        $this->newLine();
        $this->info('✓ Dry run complete');
    }
}
