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
                            {--dry-run : Preview lemmas without storing patterns}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store lemma patterns for MWE (Multi-Word Expressions) only';

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
        ini_set('memory_limit', '1G');

        $idLanguage = $this->option('language') ? (int) $this->option('language') : null;
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $dryRun = $this->option('dry-run');

        $languageFilter = $idLanguage ? config('udparser.languages')[$idLanguage] ?? "ID {$idLanguage}" : 'all languages';

        $this->info('📚 Lemma Pattern Storage from Database');
        $this->info("🌍 Language: {$languageFilter}");
        if ($limit) {
            $this->info("🔢 Limit: {$limit} lemmas");
        }
        $this->newLine();

        // Get MWE lemmas without existing patterns (incremental processing)
        // Filter: only lemmas with spaces (MWE)
        // Use NOT EXISTS subquery instead of whereNotIn to avoid loading all IDs into memory
        $lemmaQuery = DB::table('view_lemma')
            ->where('name', 'like', '% %')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('lexicon_pattern')
                    ->whereColumn('lexicon_pattern.idLemma', 'view_lemma.idLemma');
            })
            ->when($idLanguage, fn ($q) => $q->where('idLanguage', $idLanguage))
            ->orderBy('idLemma');

        // When limit is set, get IDs first to ensure both count and chunking respect the limit
        if ($limit) {
            $limitedIds = (clone $lemmaQuery)->limit($limit)->pluck('idLemma')->toArray();
            $lemmaQuery->whereIn('idLemma', $limitedIds);
            $totalLemmas = count($limitedIds);
        } else {
            $totalLemmas = $lemmaQuery->count();
        }

        if ($totalLemmas === 0) {
            $this->warn('No MWE lemmas without patterns found');

            return Command::SUCCESS;
        }

        $this->info("Found {$totalLemmas} MWE lemma(s) to process");
        $this->newLine();

        if ($dryRun) {
            $this->displayDryRunPreview($lemmaQuery, $limit);

            return Command::SUCCESS;
        }

        // Use service to store patterns (incremental, never truncate)
        // Disable query logging to save memory
        DB::connection()->disableQueryLog();

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
        $lemmaQuery->chunk(50, function ($lemmas) use ($idLanguage, &$results, $csvHandle, &$chunkCount) {
            foreach ($lemmas as $lemma) {
                try {
                    // Process MWE using UD parser service
                    $result = $this->lemmaService->storeLemmaPattern($lemma->idLemma, $idLanguage ?? 1);
                    $results['success']++;

                    $this->output->progressAdvance();
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][$lemma->idLemma] = $e->getMessage();

                    // Log lemmas without expressions to CSV
                    if (str_contains($e->getMessage(), 'No expressions found')) {
                        fputcsv($csvHandle, [
                            $lemma->idLemma,
                            $lemma->name ?? 'N/A',
                            $e->getMessage(),
                            now()->toDateTimeString(),
                        ]);
                    }

                    $this->output->progressAdvance();
                }
            }

            // Force garbage collection every 5 chunks (more frequently)
            $chunkCount++;
            if ($chunkCount % 5 === 0) {
                gc_collect_cycles();
                $this->lemmaService->clearCaches();
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
        $this->info('🔍 DRY RUN MODE - Previewing MWE lemmas:');
        $this->info('   (Lemma name will be parsed with Trankit to generate UD pattern)');
        $this->newLine();

        $lemmas = $lemmaQuery->limit($limit ?? 10)->get();

        foreach ($lemmas as $index => $lemma) {
            $langName = config('udparser.languages')[$lemma->idLanguage] ?? "ID {$lemma->idLanguage}";
            $this->line(($index + 1).". [{$langName}] [MWE] {$lemma->name}");
        }

        $this->newLine();
        $this->info('✓ Dry run complete');
    }
}
