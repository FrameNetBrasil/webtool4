<?php

namespace App\Console\Commands\Daisy;

use App\Data\Daisy\DaisyNodeData;
use App\Database\Criteria;
use App\Repositories\Daisy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateDaisyNodes extends Command
{
    protected $signature = 'daisy:populate-nodes {--dry-run : Preview changes without executing} {--force : Skip confirmation prompt} {--batch=1000 : Batch size for processing}';

    protected $description = 'Populate daisy_node table with Frames, Frame Elements, and Lexical Units from FrameNet';

    private array $stats = [
        'frames_inserted' => 0,
        'fes_inserted' => 0,
        'lus_inserted' => 0,
        'errors' => 0,
        'total' => 0
    ];

    public function handle(): int
    {
        $this->info('🌼 Daisy Node Population Tool');
        $this->newLine();

        $isDryRun = $this->option('dry-run');
        $isForced = $this->option('force');
        $batchSize = (int) $this->option('batch');

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No database changes will be made');
            $this->newLine();
        } elseif (!$isForced) {
            if (!$this->confirm('This will populate daisy_node table with FrameNet data. Continue?')) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
            $this->newLine();
        }

        // Get counts
        $frameCount = Criteria::table('frame')->count();
        $feCount = Criteria::table('frameelement')->count();
        $luCount = Criteria::table('lu')->count();
        $totalCount = $frameCount + $feCount + $luCount;

        $this->info("📊 Data Summary:");
        $this->line("  Frames: {$frameCount}");
        $this->line("  Frame Elements: {$feCount}");
        $this->line("  Lexical Units: {$luCount}");
        $this->line("  Total Nodes: {$totalCount}");
        $this->newLine();

        if (!$isDryRun) {
            DB::beginTransaction();
        }

        try {
            // Phase 1: Process Frames
            $this->info('📝 Phase 1: Processing Frames...');
            $this->processFrames($batchSize, $isDryRun);
            $this->newLine();

            // Phase 2: Process Frame Elements
            $this->info('📝 Phase 2: Processing Frame Elements...');
            $this->processFrameElements($batchSize, $isDryRun);
            $this->newLine();

            // Phase 3: Process Lexical Units
            $this->info('📝 Phase 3: Processing Lexical Units...');
            $this->processLexicalUnits($batchSize, $isDryRun);
            $this->newLine();

            if (!$isDryRun) {
                DB::commit();
                $this->info('✅ Transaction committed successfully');
            }

        } catch (\Exception $e) {
            if (!$isDryRun) {
                DB::rollBack();
                $this->error('❌ Transaction rolled back due to error');
            }
            $this->error("Error: {$e->getMessage()}");
            return self::FAILURE;
        }

        $this->displayStatistics($isDryRun);

        return self::SUCCESS;
    }

    private function processFrames(int $batchSize, bool $isDryRun): void
    {
        $total = Criteria::table('frame')->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $offset = 0;
        while ($offset < $total) {
            $frames = Criteria::table('frame')
                ->select('idFrame', 'entry')
                ->orderBy('idFrame')
                ->limit($batchSize)
                ->offset($offset)
                ->all();

            foreach ($frames as $frame) {
                try {
                    if (!$isDryRun) {
                        $nodeData = new DaisyNodeData(
                            name: $frame->entry,
                            type: 'FR',
                            idFrame: $frame->idFrame,
                            idFrameElement: null,
                            idLU: null
                        );
                        Daisy::createNode($nodeData);
                    }

                    $this->stats['frames_inserted']++;
                    $this->stats['total']++;
                    $progressBar->advance();

                } catch (\Exception $e) {
                    $this->stats['errors']++;
                    if ($this->output->isVerbose()) {
                        $this->line("\n❌ Error inserting frame {$frame->idFrame}: {$e->getMessage()}");
                    }
                }
            }

            $offset += $batchSize;
        }

        $progressBar->finish();
        $this->newLine();
    }

    private function processFrameElements(int $batchSize, bool $isDryRun): void
    {
        $total = Criteria::table('frameelement')->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $offset = 0;
        while ($offset < $total) {
            $frameElements = Criteria::table('frameelement')
                ->select('idFrameElement', 'entry', 'idFrame')
                ->orderBy('idFrameElement')
                ->limit($batchSize)
                ->offset($offset)
                ->all();

            foreach ($frameElements as $fe) {
                try {
                    if (!$isDryRun) {
                        $nodeData = new DaisyNodeData(
                            name: $fe->entry,
                            type: 'FE',
                            idFrame: $fe->idFrame,
                            idFrameElement: $fe->idFrameElement,
                            idLU: null
                        );
                        Daisy::createNode($nodeData);
                    }

                    $this->stats['fes_inserted']++;
                    $this->stats['total']++;
                    $progressBar->advance();

                } catch (\Exception $e) {
                    $this->stats['errors']++;
                    if ($this->output->isVerbose()) {
                        $this->line("\n❌ Error inserting FE {$fe->idFrameElement}: {$e->getMessage()}");
                    }
                }
            }

            $offset += $batchSize;
        }

        $progressBar->finish();
        $this->newLine();
    }

    private function processLexicalUnits(int $batchSize, bool $isDryRun): void
    {
        $total = Criteria::table('lu')->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $offset = 0;
        while ($offset < $total) {
            $lus = Criteria::table('lu')
                ->select('idLU', 'name', 'idFrame')
                ->orderBy('idLU')
                ->limit($batchSize)
                ->offset($offset)
                ->all();

            foreach ($lus as $lu) {
                try {
                    if (!$isDryRun) {
                        $nodeData = new DaisyNodeData(
                            name: $lu->name,
                            type: 'LU',
                            idFrame: $lu->idFrame,
                            idFrameElement: null,
                            idLU: $lu->idLU
                        );
                        Daisy::createNode($nodeData);
                    }

                    $this->stats['lus_inserted']++;
                    $this->stats['total']++;
                    $progressBar->advance();

                } catch (\Exception $e) {
                    $this->stats['errors']++;
                    if ($this->output->isVerbose()) {
                        $this->line("\n❌ Error inserting LU {$lu->idLU}: {$e->getMessage()}");
                    }
                }
            }

            $offset += $batchSize;
        }

        $progressBar->finish();
        $this->newLine();
    }

    private function displayStatistics(bool $isDryRun): void
    {
        $this->info('📊 Processing Statistics:');
        $this->table(
            ['Entity Type', $isDryRun ? 'Would Insert' : 'Inserted'],
            [
                ['Frames (FR)', $this->stats['frames_inserted']],
                ['Frame Elements (FE)', $this->stats['fes_inserted']],
                ['Lexical Units (LU)', $this->stats['lus_inserted']],
                ['Total Nodes', $this->stats['total']],
                ['Errors', $this->stats['errors']]
            ]
        );

        if ($isDryRun && $this->stats['total'] > 0) {
            $this->newLine();
            $this->warn("🔥 Run without --dry-run to actually insert {$this->stats['total']} nodes");
        }
    }
}
