<?php

namespace App\Console\Commands\Lexicon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LemmaBatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemma:batch
                            {--patterns= : Lemma patterns file (required)}
                            {--sentences= : Sentences file (required)}
                            {--output= : Output file path for results (JSON)}
                            {--language=1 : Language ID (1=Portuguese, 2=English)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batch process: store lemma patterns and find occurrences in sentences';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $patterns = $this->option('patterns');
        $sentences = $this->option('sentences');
        $output = $this->option('output');
        $language = $this->option('language');

        if (! $patterns || ! $sentences) {
            $this->error('Both --patterns and --sentences options are required');
            $this->info('Usage: php artisan lemma:batch --patterns=lemmas.txt --sentences=sentences.txt [--output=results.json]');

            return Command::FAILURE;
        }

        $this->info('🚀 Starting batch lemma processing...');
        $this->newLine();

        // Step 1: Store patterns
        $this->info('📚 Step 1: Storing lemma patterns');
        $this->line('────────────────────────────────────────');

        $storeResult = Artisan::call('lemma:store', [
            '--file' => $patterns,
            '--language' => $language,
        ], $this->output);

        if ($storeResult !== Command::SUCCESS) {
            $this->error('Failed to store lemma patterns');

            return Command::FAILURE;
        }

        $this->newLine();

        // Step 2: Find occurrences
        $this->info('🔍 Step 2: Finding lemma occurrences');
        $this->line('────────────────────────────────────────');

        $findOptions = [
            '--file' => $sentences,
            '--language' => $language,
        ];

        if ($output) {
            $findOptions['--output'] = $output;
        }

        $findResult = Artisan::call('lemma:find', $findOptions, $this->output);

        if ($findResult !== Command::SUCCESS) {
            $this->error('Failed to find lemma occurrences');

            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('✅ Batch processing completed successfully!');

        return Command::SUCCESS;
    }
}
