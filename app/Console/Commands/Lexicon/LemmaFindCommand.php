<?php

namespace App\Console\Commands\Lexicon;

use App\Services\Lemma\LexiconPatternService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LemmaFindCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemma:find
                            {--file= : Input file with sentences}
                            {--sentence= : Single sentence to process}
                            {--type= : Lemma type filter (SWE or MWE)}
                            {--output= : Output file path for results (JSON)}
                            {--language=1 : Language ID (1=Portuguese, 2=English)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find lemma occurrences (SWE and MWE) in sentences';

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
        $file = $this->option('file');
        $sentence = $this->option('sentence');
        $type = $this->option('type');
        $output = $this->option('output');
        $idLanguage = (int) $this->option('language');

        if (! $file && ! $sentence) {
            $this->error('Either --file or --sentence option is required');
            $this->info('Usage: php artisan lemma:find --sentence="Text to analyze" [--language=1]');
            $this->info('   or: php artisan lemma:find --file=sentences.txt [--type=MWE] [--output=results.json]');

            return Command::FAILURE;
        }

        $sentences = [];

        if ($sentence) {
            $sentences = [$sentence];
        } else {
            if (! File::exists($file)) {
                $this->error("File not found: {$file}");

                return Command::FAILURE;
            }
            $sentences = File::lines($file)->filter(fn ($line) => ! empty(trim($line)))->toArray();
        }

        $languageName = config('udparser.languages')[$idLanguage] ?? 'unknown';
        $typeFilter = $type ? " (filter: {$type})" : '';
        $this->info('🔍 Finding lemma occurrences in '.count($sentences).' sentence(s)');
        $this->info("🌍 Language: {$languageName}{$typeFilter}");
        $this->newLine();

        $allOccurrences = [];
        $this->output->progressStart(count($sentences));

        foreach ($sentences as $index => $sentenceText) {
            $sentenceText = trim($sentenceText);

            if (empty($sentenceText)) {
                $this->output->progressAdvance();

                continue;
            }

            try {
                // Parse sentence and find lemmas using service
                $occurrences = $this->lemmaService->parseSentenceAndFindLemmas($sentenceText, $idLanguage);

                // Apply type filter if requested
                if ($type) {
                    $occurrences = array_filter($occurrences, fn ($occ) => $occ['lemma_type'] === $type);
                }

                if (count($occurrences) > 0) {
                    $sentenceId = 'sent_'.($index + 1);
                    $allOccurrences[$sentenceId] = [
                        'text' => $sentenceText,
                        'occurrences' => $occurrences,
                    ];
                }

            } catch (\Exception $e) {
                $this->error("Error processing sentence {$index}: ".$e->getMessage());
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->newLine();

        // Display results
        $this->displayResults($allOccurrences);

        // Count total occurrences
        $totalOccurrences = 0;
        foreach ($allOccurrences as $sentenceData) {
            $totalOccurrences += count($sentenceData['occurrences']);
        }

        $this->info("✓ Found {$totalOccurrences} lemma occurrence(s)");

        // Export to file if requested
        if ($output) {
            $this->exportResults($allOccurrences, $output);
            $this->info("✓ Results exported to: {$output}");
        }

        return Command::SUCCESS;
    }

    /**
     * Display results in console
     */
    protected function displayResults(array $results): void
    {
        if (empty($results)) {
            $this->info('No lemma occurrences found.');

            return;
        }

        $this->newLine();
        $this->info('=== Lemma Occurrences Found ===');
        $this->newLine();

        foreach ($results as $sentenceId => $data) {
            $this->line("<fg=cyan>{$sentenceId}:</> {$data['text']}");

            foreach ($data['occurrences'] as $occ) {
                $type = $occ['lemma_type'];
                $confidence = number_format($occ['confidence'] * 100, 1);
                $indices = implode(', ', $occ['token_indices']);

                $this->line("  <fg=green>→</> [{$type}] <fg=yellow>{$occ['lemma_text']}</> (confidence: {$confidence}%, tokens: {$indices})");
            }

            $this->newLine();
        }
    }

    /**
     * Export results to JSON file
     */
    protected function exportResults(array $results, string $outputPath): void
    {
        $json = json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        File::put($outputPath, $json);
    }
}
