<?php

namespace App\Console\Commands\Daisy;

use App\Data\Daisy\DaisyLinkData;
use App\Database\Criteria;
use App\Repositories\Daisy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateDaisyLinks extends Command
{
    protected $signature = 'daisy:populate-links {--dry-run : Preview changes without executing} {--force : Skip confirmation prompt} {--batch=1000 : Batch size for processing}';

    protected $description = 'Populate daisy_link table with relationships between FrameNet entities';

    private array $stats = [
        'ffe_links' => 0,  // Frame → FrameElement
        'luf_links' => 0,  // LU → Frame
        'f2f_links' => 0,  // Frame → Frame
        'fef_links' => 0,  // FrameElement → Frame
        'l2l_links' => 0,  // LU → LU
        'errors' => 0,
        'skipped' => 0,
        'total' => 0
    ];

    private array $nodeCache = [];

    public function handle(): int
    {
        $this->info('🔗 Daisy Link Population Tool');
        $this->newLine();

        $isDryRun = $this->option('dry-run');
        $isForced = $this->option('force');
        $batchSize = (int) $this->option('batch');

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No database changes will be made');
            $this->newLine();
        } elseif (!$isForced) {
            if (!$this->confirm('This will populate daisy_link table with FrameNet relationships. Continue?')) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
            $this->newLine();
        }

        // Get counts
        $this->info('📊 Estimating link counts...');
        $ffeCount = Criteria::table('frameelement')->where('idFrame', '!=', 0)->count();
        $lufCount = Criteria::table('lu')->whereNotNull('idFrame')->where('idFrame', '!=', 0)->count();
        $f2fCount = Criteria::table('view_frame_relation')
            ->where('idLanguage', 2)
            ->whereIn('idRelationType', [1, 2, 12])
            ->count();

        $fefCount = DB::select("
            SELECT COUNT(*) as count
            FROM view_constrainedby_frame c
            JOIN frameelement fe ON (c.idConstrained = fe.idEntity)
            JOIN frame f ON (c.idConstrainedBy = f.idEntity)
        ")[0]->count;

        $l2lCount = DB::select("
            SELECT COUNT(*) as count
            FROM view_relation r
            JOIN lu lu1 ON (r.idEntity1 = lu1.idEntity)
            JOIN lu lu2 ON (r.idEntity2 = lu2.idEntity)
            WHERE (idRelationType = 215)
        ")[0]->count;

        $totalCount = $ffeCount + $lufCount + $f2fCount + $fefCount + $l2lCount;

        $this->line("  Frame→FE (FFE): {$ffeCount}");
        $this->line("  LU→Frame (LUF): {$lufCount}");
        $this->line("  Frame→Frame (F2F): {$f2fCount}");
        $this->line("  FE→Frame (FEF): {$fefCount}");
        $this->line("  LU→LU (L2L): {$l2lCount}");
        $this->line("  Total Links: {$totalCount}");
        $this->newLine();

        if (!$isDryRun) {
            DB::beginTransaction();
        }

        try {
            // Phase 1: Frame → FrameElement
            $this->info('📝 Phase 1: Processing Frame→FrameElement links (FFE)...');
            $this->processFrameToFE($batchSize, $isDryRun);
            $this->newLine();

            // Phase 2: LU → Frame
            $this->info('📝 Phase 2: Processing LU→Frame links (LUF)...');
            $this->processLUToFrame($batchSize, $isDryRun);
            $this->newLine();

            // Phase 3: Frame → Frame
            $this->info('📝 Phase 3: Processing Frame→Frame links (F2F)...');
            $this->processFrameToFrame($batchSize, $isDryRun);
            $this->newLine();

            // Phase 4: FrameElement → Frame
            $this->info('📝 Phase 4: Processing FrameElement→Frame links (FEF)...');
            $this->processFEToFrame($batchSize, $isDryRun);
            $this->newLine();

            // Phase 5: LU → LU
            $this->info('📝 Phase 5: Processing LU→LU links (L2L)...');
            $this->processLUToLU($batchSize, $isDryRun);
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

    private function getNodeByFrame(int $idFrame): ?int
    {
        $key = "FR_{$idFrame}";
        if (!isset($this->nodeCache[$key])) {
            $node = Criteria::table('daisy_node')
                ->select('idDaisyNode')
                ->where('type', 'FR')
                ->where('idFrame', $idFrame)
                ->first();
            $this->nodeCache[$key] = $node->idDaisyNode ?? null;
        }
        return $this->nodeCache[$key];
    }

    private function getNodeByFE(int $idFrameElement): ?int
    {
        $key = "FE_{$idFrameElement}";
        if (!isset($this->nodeCache[$key])) {
            $node = Criteria::table('daisy_node')
                ->select('idDaisyNode')
                ->where('type', 'FE')
                ->where('idFrameElement', $idFrameElement)
                ->first();
            $this->nodeCache[$key] = $node->idDaisyNode ?? null;
        }
        return $this->nodeCache[$key];
    }

    private function getNodeByLU(int $idLU): ?int
    {
        $key = "LU_{$idLU}";
        if (!isset($this->nodeCache[$key])) {
            $node = Criteria::table('daisy_node')
                ->select('idDaisyNode')
                ->where('type', 'LU')
                ->where('idLU', $idLU)
                ->first();
            $this->nodeCache[$key] = $node->idDaisyNode ?? null;
        }
        return $this->nodeCache[$key];
    }

    private function processFrameToFE(int $batchSize, bool $isDryRun): void
    {
        // Clear cache at start of phase
        $this->nodeCache = [];

        $total = Criteria::table('frameelement')->where('idFrame', '!=', 0)->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $offset = 0;
        while ($offset < $total) {
            // Clear cache periodically to prevent memory exhaustion
            if ($offset % 1000 == 0) {
                $this->nodeCache = [];
            }

            $frameElements = Criteria::table('frameelement')
                ->select('idFrameElement', 'idFrame')
                ->where('idFrame', '!=', 0)
                ->orderBy('idFrameElement')
                ->limit($batchSize)
                ->offset($offset)
                ->all();

            foreach ($frameElements as $fe) {
                try {
                    $sourceNode = $this->getNodeByFrame($fe->idFrame);
                    $targetNode = $this->getNodeByFE($fe->idFrameElement);

                    if (!$sourceNode || !$targetNode) {
                        $this->stats['skipped']++;
                        $progressBar->advance();
                        continue;
                    }

                    if (!$isDryRun) {
                        $linkData = new DaisyLinkData(
                            value: 1.0,
                            type: 'FFE',
                            idDaisyNodeSource: $sourceNode,
                            idDaisyNodeTarget: $targetNode
                        );
                        Daisy::createLink($linkData);
                    }

                    $this->stats['ffe_links']++;
                    $this->stats['total']++;
                    $progressBar->advance();

                } catch (\Exception $e) {
                    $this->stats['errors']++;
                    if ($this->output->isVerbose()) {
                        $this->line("\n❌ Error creating FFE link: {$e->getMessage()}");
                    }
                    $progressBar->advance();
                }
            }

            $offset += $batchSize;
        }

        $progressBar->finish();
        $this->newLine();
    }

    private function processLUToFrame(int $batchSize, bool $isDryRun): void
    {
        // Clear cache at start of phase
        $this->nodeCache = [];

        $total = Criteria::table('lu')->whereNotNull('idFrame')->where('idFrame', '!=', 0)->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $offset = 0;
        while ($offset < $total) {
            // Clear cache periodically to prevent memory exhaustion
            if ($offset % 1000 == 0) {
                $this->nodeCache = [];
            }

            $lus = Criteria::table('lu')
                ->select('idLU', 'idFrame')
                ->whereNotNull('idFrame')
                ->where('idFrame', '!=', 0)
                ->orderBy('idLU')
                ->limit($batchSize)
                ->offset($offset)
                ->all();

            foreach ($lus as $lu) {
                try {
                    $sourceNode = $this->getNodeByLU($lu->idLU);
                    $targetNode = $this->getNodeByFrame($lu->idFrame);

                    if (!$sourceNode || !$targetNode) {
                        $this->stats['skipped']++;
                        $progressBar->advance();
                        continue;
                    }

                    if (!$isDryRun) {
                        $linkData = new DaisyLinkData(
                            value: 1.0,
                            type: 'LUF',
                            idDaisyNodeSource: $sourceNode,
                            idDaisyNodeTarget: $targetNode
                        );
                        Daisy::createLink($linkData);
                    }

                    $this->stats['luf_links']++;
                    $this->stats['total']++;
                    $progressBar->advance();

                } catch (\Exception $e) {
                    $this->stats['errors']++;
                    if ($this->output->isVerbose()) {
                        $this->line("\n❌ Error creating LUF link: {$e->getMessage()}");
                    }
                    $progressBar->advance();
                }
            }

            $offset += $batchSize;
        }

        $progressBar->finish();
        $this->newLine();
    }

    private function processFrameToFrame(int $batchSize, bool $isDryRun): void
    {
        // Clear cache at start of phase
        $this->nodeCache = [];

        $total = Criteria::table('view_frame_relation')
            ->where('idLanguage', 2)
            ->whereIn('idRelationType', [1, 2, 12])
            ->count();

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $offset = 0;
        while ($offset < $total) {
            $relations = Criteria::table('view_frame_relation')
                ->select('f1IdFrame', 'f2IdFrame', 'idRelationType')
                ->where('idLanguage', 2)
                ->whereIn('idRelationType', [1, 2, 12])
                ->limit($batchSize)
                ->offset($offset)
                ->all();

            foreach ($relations as $rel) {
                try {
                    $sourceNode = $this->getNodeByFrame($rel->f1IdFrame);
                    $targetNode = $this->getNodeByFrame($rel->f2IdFrame);

                    if (!$sourceNode || !$targetNode) {
                        $this->stats['skipped']++;
                        $progressBar->advance();
                        continue;
                    }

                    // Determine value based on relation type
                    $value = match($rel->idRelationType) {
                        1 => 0.9,
                        2 => 0.85,
                        12 => 1.0,
                        default => 0.9
                    };

                    if (!$isDryRun) {
                        $linkData = new DaisyLinkData(
                            value: $value,
                            type: 'F2F',
                            idDaisyNodeSource: $sourceNode,
                            idDaisyNodeTarget: $targetNode
                        );
                        Daisy::createLink($linkData);
                    }

                    $this->stats['f2f_links']++;
                    $this->stats['total']++;
                    $progressBar->advance();

                } catch (\Exception $e) {
                    $this->stats['errors']++;
                    if ($this->output->isVerbose()) {
                        $this->line("\n❌ Error creating F2F link: {$e->getMessage()}");
                    }
                    $progressBar->advance();
                }
            }

            $offset += $batchSize;
        }

        $progressBar->finish();
        $this->newLine();
    }

    private function processFEToFrame(int $batchSize, bool $isDryRun): void
    {
        // Clear cache at start of phase
        $this->nodeCache = [];

        $totalQuery = "
            SELECT COUNT(*) as count
            FROM view_constrainedby_frame c
            JOIN frameelement fe ON (c.idConstrained = fe.idEntity)
            JOIN frame f ON (c.idConstrainedBy = f.idEntity)
        ";
        $total = DB::select($totalQuery)[0]->count;

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $offset = 0;
        while ($offset < $total) {
            // Clear cache periodically to prevent memory exhaustion
            if ($offset % 1000 == 0) {
                $this->nodeCache = [];
            }

            $query = "
                SELECT fe.idFrameElement, f.idFrame
                FROM view_constrainedby_frame c
                JOIN frameelement fe ON (c.idConstrained = fe.idEntity)
                JOIN frame f ON (c.idConstrainedBy = f.idEntity)
                LIMIT {$batchSize} OFFSET {$offset}
            ";

            $relations = DB::select($query);

            foreach ($relations as $rel) {
            try {
                $sourceNode = $this->getNodeByFE($rel->idFrameElement);
                $targetNode = $this->getNodeByFrame($rel->idFrame);

                if (!$sourceNode || !$targetNode) {
                    $this->stats['skipped']++;
                    $progressBar->advance();
                    continue;
                }

                if (!$isDryRun) {
                    $linkData = new DaisyLinkData(
                        value: 0.8,
                        type: 'FEF',
                        idDaisyNodeSource: $sourceNode,
                        idDaisyNodeTarget: $targetNode
                    );
                    Daisy::createLink($linkData);
                }

                $this->stats['fef_links']++;
                $this->stats['total']++;
                $progressBar->advance();

            } catch (\Exception $e) {
                $this->stats['errors']++;
                if ($this->output->isVerbose()) {
                    $this->line("\n❌ Error creating FEF link: {$e->getMessage()}");
                }
                $progressBar->advance();
            }
        }

            $offset += $batchSize;
        }

        $progressBar->finish();
        $this->newLine();
    }

    private function processLUToLU(int $batchSize, bool $isDryRun): void
    {
        // Clear cache at start of phase
        $this->nodeCache = [];

        $totalQuery = "
            SELECT COUNT(*) as count
            FROM view_relation r
            JOIN lu lu1 ON (r.idEntity1 = lu1.idEntity)
            JOIN lu lu2 ON (r.idEntity2 = lu2.idEntity)
            WHERE (idRelationType = 215)
        ";
        $total = DB::select($totalQuery)[0]->count;

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $offset = 0;
        while ($offset < $total) {
            // Clear cache periodically to prevent memory exhaustion
            if ($offset % 1000 == 0) {
                $this->nodeCache = [];
            }

            $query = "
                SELECT lu1.idLU as idLU1, lu2.idLU as idLU2
                FROM view_relation r
                JOIN lu lu1 ON (r.idEntity1 = lu1.idEntity)
                JOIN lu lu2 ON (r.idEntity2 = lu2.idEntity)
                WHERE (idRelationType = 215)
                LIMIT {$batchSize} OFFSET {$offset}
            ";

            $relations = DB::select($query);

            foreach ($relations as $rel) {
                try {
                    $sourceNode = $this->getNodeByLU($rel->idLU1);
                    $targetNode = $this->getNodeByLU($rel->idLU2);

                    if (!$sourceNode || !$targetNode) {
                        $this->stats['skipped']++;
                        $progressBar->advance();
                        continue;
                    }

                    if (!$isDryRun) {
                        $linkData = new DaisyLinkData(
                            value: 0.8,
                            type: 'L2L',
                            idDaisyNodeSource: $sourceNode,
                            idDaisyNodeTarget: $targetNode
                        );
                        Daisy::createLink($linkData);
                    }

                    $this->stats['l2l_links']++;
                    $this->stats['total']++;
                    $progressBar->advance();

                } catch (\Exception $e) {
                    $this->stats['errors']++;
                    if ($this->output->isVerbose()) {
                        $this->line("\n❌ Error creating L2L link: {$e->getMessage()}");
                    }
                    $progressBar->advance();
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
            ['Link Type', $isDryRun ? 'Would Create' : 'Created'],
            [
                ['Frame→FE (FFE)', $this->stats['ffe_links']],
                ['LU→Frame (LUF)', $this->stats['luf_links']],
                ['Frame→Frame (F2F)', $this->stats['f2f_links']],
                ['FE→Frame (FEF)', $this->stats['fef_links']],
                ['LU→LU (L2L)', $this->stats['l2l_links']],
                ['Total Links', $this->stats['total']],
                ['Skipped (no nodes)', $this->stats['skipped']],
                ['Errors', $this->stats['errors']]
            ]
        );

        if ($isDryRun && $this->stats['total'] > 0) {
            $this->newLine();
            $this->warn("🔥 Run without --dry-run to actually create {$this->stats['total']} links");
        }
    }
}
