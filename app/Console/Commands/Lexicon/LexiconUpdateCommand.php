<?php

namespace App\Console\Commands\Lexicon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LexiconUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lexicon:update
                            {--source=webtool : Source database connection name}
                            {--target=webtool42_3 : Target database connection name}
                            {--batch-size=1000 : Number of records to process per batch}
                            {--dry-run : Preview the operation without making changes}
                            {--truncate : Truncate target table before update}
                            {--force : Skip confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update lexicon table from source database to target database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $source = $this->option('source');
        $target = $this->option('target');
        $batchSize = (int) $this->option('batch-size');
        $dryRun = $this->option('dry-run');
        $truncate = $this->option('truncate');
        $force = $this->option('force');

        $this->info('📊 Lexicon Table Update');
        $this->info("Source: {$source}");
        $this->info("Target: {$target}");
        $this->newLine();

        // Validate connections
        try {
            $this->info('🔍 Validating database connections...');

            DB::connection($source)->getPdo();
            $this->line("✓ Source connection '{$source}' is valid");

            DB::connection($target)->getPdo();
            $this->line("✓ Target connection '{$target}' is valid");

            $this->newLine();
        } catch (\Exception $e) {
            $this->error("Database connection failed: {$e->getMessage()}");

            return Command::FAILURE;
        }

        // Get record counts
        try {
            $sourceCount = DB::connection($source)->table('lexicon')->count();
            $targetCount = DB::connection($target)->table('lexicon')->count();

            $this->info('📈 Record counts:');
            $this->line('   Source: '.number_format($sourceCount).' records');
            $this->line('   Target: '.number_format($targetCount).' records');
            $this->newLine();

            if ($sourceCount === 0) {
                $this->warn('No records found in source database');

                return Command::SUCCESS;
            }
        } catch (\Exception $e) {
            $this->error("Failed to get record counts: {$e->getMessage()}");

            return Command::FAILURE;
        }

        // Dry run mode
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - Preview:');
            $this->newLine();

            // Get target columns
            $targetColumns = DB::connection($target)
                ->getSchemaBuilder()
                ->getColumnListing('lexicon');

            $this->info('Target database columns: '.implode(', ', $targetColumns));
            $this->newLine();

            $sample = DB::connection($source)
                ->table('lexicon')
                ->limit(5)
                ->get();

            $this->table(
                ['idLexicon', 'form', 'idLexiconGroup', 'idEntity', 'idUDPOS', 'idLanguage'],
                $sample->map(fn ($row) => [
                    $row->idLexicon,
                    $row->form,
                    $row->idLexiconGroup,
                    $row->idEntity ?? 'NULL',
                    $row->idUDPOS ?? 'NULL',
                    $row->idLanguage,
                ])
            );

            $this->newLine();
            $this->info("Would process {$sourceCount} records in batches of {$batchSize}");
            $this->warn('Note: Only columns existing in target will be transferred');
            if ($truncate) {
                $this->warn('Would TRUNCATE target table before update');
            }
            $this->info('✓ Dry run complete');

            return Command::SUCCESS;
        }

        // Confirmation
        if (! $force) {
            $this->warn('⚠️  This will update the lexicon table in the target database');
            if ($truncate) {
                $this->error('⚠️  WARNING: --truncate flag will DELETE ALL existing records in target!');
            }
            $this->newLine();

            if (! $this->confirm('Do you want to continue?', false)) {
                $this->info('Operation cancelled');

                return Command::SUCCESS;
            }
        }

        // Truncate if requested
        if ($truncate) {
            $this->info('🗑️  Truncating target table...');
            try {
                DB::connection($target)->statement('SET FOREIGN_KEY_CHECKS=0');
                DB::connection($target)->table('lexicon')->truncate();
                DB::connection($target)->statement('SET FOREIGN_KEY_CHECKS=1');
                $this->line('✓ Target table truncated');
                $this->newLine();
            } catch (\Exception $e) {
                $this->error("Failed to truncate table: {$e->getMessage()}");

                return Command::FAILURE;
            }
        }

        // Process records in batches
        $this->info('⚙️  Updating lexicon records...');
        $processed = 0;
        $errors = 0;

        $this->output->progressStart($sourceCount);

        try {
            DB::connection($source)
                ->table('lexicon')
                ->orderBy('idLexicon')
                ->chunk($batchSize, function ($records) use ($target, &$processed, &$errors) {
                    try {
                        // Get target table columns
                        static $targetColumns = null;
                        if ($targetColumns === null) {
                            $targetColumns = DB::connection($target)
                                ->getSchemaBuilder()
                                ->getColumnListing('lexicon');
                        }

                        // Prepare batch for upsert - only include fields that exist in target
                        $batch = $records->map(function ($record) use ($targetColumns) {
                            $data = [
                                'idLexicon' => $record->idLexicon,
                                'form' => $record->form,
                                'idLexiconGroup' => $record->idLexiconGroup,
                                'idEntity' => $record->idEntity,
                                'idUDPOS' => $record->idUDPOS,
                                'idLanguage' => $record->idLanguage,
                            ];

                            // Only include columns that exist in target
                            return array_filter($data, function ($key) use ($targetColumns) {
                                return in_array($key, $targetColumns);
                            }, ARRAY_FILTER_USE_KEY);
                        })->toArray();

                        // Use INSERT ... ON DUPLICATE KEY UPDATE
                        DB::connection($target)->transaction(function () use ($target, $batch) {
                            foreach ($batch as $record) {
                                DB::connection($target)
                                    ->table('lexicon')
                                    ->updateOrInsert(
                                        ['idLexicon' => $record['idLexicon']],
                                        $record
                                    );
                            }
                        });

                        $processed += count($records);
                        $this->output->progressAdvance(count($records));

                    } catch (\Exception $e) {
                        $this->error("\nError processing batch: {$e->getMessage()}");
                        $errors++;
                    }
                });

        } catch (\Exception $e) {
            $this->output->progressFinish();
            $this->newLine();
            $this->error("Fatal error during processing: {$e->getMessage()}");

            return Command::FAILURE;
        }

        $this->output->progressFinish();
        $this->newLine();

        // Summary
        $this->info('✅ Update complete!');
        $this->info('   Processed: '.number_format($processed).' records');

        if ($errors > 0) {
            $this->warn("   Errors: {$errors} batch(es) failed");
        }

        // Final count
        $finalCount = DB::connection($target)->table('lexicon')->count();
        $this->info('   Final target count: '.number_format($finalCount).' records');

        return Command::SUCCESS;
    }
}
