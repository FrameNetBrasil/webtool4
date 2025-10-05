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

        // Get all lemmas without existing patterns (incremental processing)
        // Use NOT EXISTS subquery instead of whereNotIn to avoid loading all IDs into memory
        $lemmaQuery = DB::table('view_lemma')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('lexicon_pattern')
                    ->whereColumn('lexicon_pattern.idLemma', 'view_lemma.idLemma');
            })
            ->when($idLanguage, fn ($q) => $q->where('idLanguage', $idLanguage))
            ->when($limit, fn ($q) => $q->limit($limit))
            ->orderBy('idLemma');

        $totalLemmas = $lemmaQuery->count();

        if ($totalLemmas === 0) {
            $this->warn('No lemmas without patterns found');

            return Command::SUCCESS;
        }

        $this->info("Found {$totalLemmas} lemma(s) to process");
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
                    // Detect SWE vs MWE based on spaces in name
                    $isSWE = ! str_contains($lemma->name, ' ');

                    if ($isSWE) {
                        // Create simple SWE pattern directly
                        $result = $this->createSimpleSWEPattern($lemma, $idLanguage ?? 1);
                        $results['success']++;
                    } else {
                        // Use service for MWE (needs UD parser)
                        $result = $this->lemmaService->storeLemmaPattern($lemma->idLemma, $idLanguage ?? 1);
                        $results['success']++;
                    }

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
     * Create simple SWE pattern without UD parser
     */
    protected function createSimpleSWEPattern(object $lemma, int $idLanguage): array
    {
        try {
            // Get expression (single word form)
            $expressions = DB::table('view_lexicon_expression')
                ->where('idLemma', $lemma->idLexicon)
                ->orderBy('position')
                ->get();

            if ($expressions->isEmpty()) {
                throw new \RuntimeException("No expressions found for lemma: {$lemma->idLemma}");
            }

            // Create pattern in transaction
            return DB::transaction(function () use ($lemma, $expressions) {
                // Create pattern entry
                $idLexiconPattern = DB::table('lexicon_pattern')->insertGetId([
                    'idLemma' => $lemma->idLemma,
                    'patternType' => 'canonical',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create single root node (SWE has only one node)
                $firstExpression = $expressions->first();
                DB::table('lexicon_pattern_node')->insert([
                    'idLexiconPattern' => $idLexiconPattern,
                    'position' => 0,
                    'idLexicon' => $firstExpression->idForm,
                    'idUDPOS' => $lemma->idUDPOS ?? null,
                    'isRoot' => 1,
                    'isRequired' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // No edges needed for SWE (single word)

                return [
                    'idLemma' => $lemma->idLemma,
                    'idLexiconPattern' => $idLexiconPattern,
                    'type' => 'SWE',
                ];
            });
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to create SWE pattern for lemma {$lemma->idLemma}: {$e->getMessage()}");
        }
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
