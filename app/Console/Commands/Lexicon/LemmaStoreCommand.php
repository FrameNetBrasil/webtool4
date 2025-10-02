<?php

namespace App\Console\Commands\Lexicon;

use App\Services\Lexicon\LexiconPatternService;
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
                            {--limit= : Limit number of MWEs to process (for testing)}
                            {--dry-run : Preview MWEs without storing patterns}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store MWE patterns from database (view_lexicon_mwe)';

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
        $idLanguage = $this->option('language') ? (int) $this->option('language') : null;
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $languageFilter = $idLanguage ? config('udparser.languages')[$idLanguage] ?? "ID {$idLanguage}" : 'all languages';

        $this->info('📚 MWE Pattern Storage from Database');
        $this->info("🌍 Language: {$languageFilter}");
        if ($limit) {
            $this->info("🔢 Limit: {$limit} MWEs");
        }
        $this->newLine();

        // Query MWE count
        $mweQuery = DB::table('view_lexicon_mwe as mwe')
            ->join('view_lexicon_lemma as lemma', 'mwe.idLemma', '=', 'lemma.idLexicon')
            ->when($idLanguage, fn ($q) => $q->where('lemma.idLanguage', $idLanguage));

        $totalMwes = $mweQuery->count();

        if ($totalMwes === 0) {
            $this->warn('No MWEs found in database');

            return Command::SUCCESS;
        }

        $this->info("Found {$totalMwes} MWE(s) to process");
        $this->newLine();

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - Previewing MWEs:');
            $this->newLine();

            $mwes = $mweQuery
                ->select('lemma.idLexicon', 'lemma.name', 'lemma.idLanguage')
                ->when($limit, fn ($q) => $q->limit($limit))
                ->get();

            foreach ($mwes as $index => $mwe) {
                $expressions = DB::table('view_lexicon_expression')
                    ->where('idLemma', $mwe->idLexicon)
                    ->orderBy('position')
                    ->pluck('form')
                    ->toArray();

                $fullText = implode(' ', $expressions);
                $langName = config('udparser.languages')[$mwe->idLanguage] ?? "ID {$mwe->idLanguage}";

                $this->line(($index + 1).". [{$langName}] {$mwe->name} → \"{$fullText}\"");
            }

            $this->newLine();
            $this->info('✓ Dry run complete');

            return Command::SUCCESS;
        }

        // Confirm truncate
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

        // Process MWEs
        $mwes = $mweQuery
            ->select('lemma.idLexicon', 'lemma.name', 'lemma.idLanguage')
            ->when($limit, fn ($q) => $q->limit($limit))
            ->get();

        $processed = 0;
        $errors = 0;

        $this->info('⚙️  Processing MWEs...');
        $this->output->progressStart($mwes->count());

        foreach ($mwes as $mwe) {
            try {
                // Get component words
                $expressions = DB::table('view_lexicon_expression')
                    ->where('idLemma', $mwe->idLexicon)
                    ->orderBy('position')
                    ->pluck('form')
                    ->toArray();

                $fullText = implode(' ', $expressions);

                // Extract pattern using Trankit
                $pattern = $this->lemmaService->extractPatternFromLemma($fullText, 'MWE', $mwe->idLanguage);

                // Store pattern (linked to existing lexicon entry)
                // Note: We're not creating a new lexicon entry, just the pattern
                $this->storePatternForExistingLemma($mwe->idLexicon, $pattern);

                $processed++;

            } catch (\Exception $e) {
                $this->error("Error processing '{$mwe->name}' (ID: {$mwe->idLexicon}): ".$e->getMessage());
                $errors++;
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->newLine();

        $this->info("✓ Successfully processed: {$processed} MWE pattern(s)");

        if ($errors > 0) {
            $this->warn("⚠ Errors encountered: {$errors}");
        }

        return Command::SUCCESS;
    }

    /**
     * Store pattern for an existing lexicon entry
     */
    protected function storePatternForExistingLemma(int $idLexicon, array $parsedPattern): void
    {
        DB::transaction(function () use ($idLexicon, $parsedPattern) {
            // Create pattern entry
            $idLexiconPattern = DB::table('lexicon_pattern')->insertGetId([
                'idLexicon' => $idLexicon,
                'patternType' => 'canonical',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Store all nodes in the pattern
            $nodeMapping = []; // pattern position -> idLexiconPatternNode
            foreach ($parsedPattern['nodes'] as $node) {
                $idLexiconPatternNode = DB::table('lexicon_pattern_node')->insertGetId([
                    'idLexiconPattern' => $idLexiconPattern,
                    'position' => $node['position'],
                    'idLexicon' => $node['idLexicon'] ?? null,
                    'idUDPOS' => $node['idUDPOS'] ?? null,
                    'isRoot' => $node['is_root'],
                    'isRequired' => $node['is_required'] ?? true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $nodeMapping[$node['position']] = $idLexiconPatternNode;
            }

            // Store ALL edges (complete dependency tree)
            foreach ($parsedPattern['edges'] as $edge) {
                DB::table('lexicon_pattern_edge')->insert([
                    'idLexiconPattern' => $idLexiconPattern,
                    'idNodeHead' => $nodeMapping[$edge['head_position']],
                    'idNodeDependent' => $nodeMapping[$edge['dependent_position']],
                    'idUDRelation' => $edge['idUDRelation'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Store constraints if any
            if (isset($parsedPattern['constraints'])) {
                foreach ($parsedPattern['constraints'] as $constraint) {
                    DB::table('lexicon_pattern_constraint')->insert([
                        'idLexiconPattern' => $idLexiconPattern,
                        'constraintType' => $constraint['type'],
                        'constraintValue' => $constraint['value'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }
}
