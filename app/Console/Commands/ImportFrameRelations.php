<?php

namespace App\Console\Commands;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportFrameRelations extends Command
{
    protected $signature = 'import:frame-relations {--dry-run : Preview changes without executing} {--force : Skip confirmation prompt}';

    protected $description = 'Import frame inheritance relations from CSV file';

    private array $stats = [
        'processed' => 0,
        'created' => 0,
        'skipped' => 0,
        'errors' => 0,
        'duplicates' => 0
    ];

    public function handle(): int
    {
        $this->info('🚀 Frame Relations Import Tool');
        $this->newLine();

        $csvPath = storage_path('app/data/fnbr_frames_sem_pai_resumido.csv');

        if (!file_exists($csvPath)) {
            $this->error("❌ CSV file not found: {$csvPath}");
            return self::FAILURE;
        }

        $isDryRun = $this->option('dry-run');
        $isForced = $this->option('force');

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No database changes will be made');
            $this->newLine();
        } elseif (!$isForced) {
            if (!$this->confirm('This will create frame inheritance relations in the database. Continue?')) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
            $this->newLine();
        }

        $this->info('📂 Reading CSV file...');
        $csvData = $this->readCsvFile($csvPath);

        if (empty($csvData)) {
            $this->error('❌ No data found in CSV file');
            return self::FAILURE;
        }

        $this->info("📊 Found {" . count($csvData) . "} rows to process");
        $this->newLine();

        if (!$isDryRun) {
            DB::beginTransaction();
        }

        try {
            $progressBar = $this->output->createProgressBar(count($csvData));
            $progressBar->start();

            foreach ($csvData as $row) {
                $this->processRow($row, $isDryRun);
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();
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

    private function readCsvFile(string $csvPath): array
    {
        $data = [];
        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            return $data;
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            return $data;
        }

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $data[] = array_combine($headers, $row);
            }
        }

        fclose($handle);
        return $data;
    }

    private function processRow(array $row, bool $isDryRun): void
    {
        $this->stats['processed']++;

        if (empty($row['parent_name']) || trim($row['parent_name']) === '') {
            $this->stats['skipped']++;
            if ($this->output->isVerbose()) {
                $this->line("⚠️  Skipped row: No parent_name for frame '{$row['name']}'");
            }
            return;
        }

        $parentName = trim($row['parent_name']);
        $childIdEntity = (int) $row['idEntity'];
        $childName = trim($row['name']);

        try {
            $parentFrame = $this->findParentFrame($parentName);

            if (!$parentFrame) {
                $this->stats['skipped']++;
                if ($this->output->isVerbose()) {
                    $this->line("⚠️  Skipped: Parent frame '{$parentName}' not found for '{$childName}'");
                }
                return;
            }

            $parentIdEntity = $parentFrame->idEntity;

            if ($this->relationExists($parentIdEntity, $childIdEntity)) {
                $this->stats['duplicates']++;
                if ($this->output->isVerbose()) {
                    $this->line("🔄 Duplicate: Relation '{$parentName}' -> '{$childName}' already exists");
                }
                return;
            }

            if (!$isDryRun) {
                $this->createRelation($parentIdEntity, $childIdEntity);
            }

            $this->stats['created']++;
            if ($this->output->isVerbose()) {
                $this->line("✅ " . ($isDryRun ? 'Would create' : 'Created') . ": '{$parentName}' -> '{$childName}'");
            }

        } catch (\Exception $e) {
            $this->stats['errors']++;
            if ($this->output->isVerbose()) {
                $this->line("❌ Error processing '{$childName}': {$e->getMessage()}");
            }
        }
    }

    private function findParentFrame(string $parentName): ?object
    {
        return Criteria::table('view_frame')
            ->where('idLanguage', '=', 2)
            ->where('name', '=', $parentName)
            ->first();
    }

    private function relationExists(int $parentIdEntity, int $childIdEntity): bool
    {
        $existing = Criteria::table('EntityRelation')
            ->where('idEntity1', '=', $parentIdEntity)
            ->where('idEntity2', '=', $childIdEntity)
            ->where('idRelationType', '=', 1)
            ->first();

        return $existing !== null;
    }

    private function createRelation(int $parentIdEntity, int $childIdEntity): void
    {
        Criteria::create('EntityRelation', [
            'idEntity1' => $parentIdEntity,
            'idEntity2' => $childIdEntity,
            'idRelationType' => 1
        ]);
    }

    private function displayStatistics(bool $isDryRun): void
    {
        $this->info('📊 Import Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $this->stats['processed']],
                [$isDryRun ? 'Would Create' : 'Created', $this->stats['created']],
                ['Skipped (No Parent)', $this->stats['skipped']],
                ['Duplicates Found', $this->stats['duplicates']],
                ['Errors', $this->stats['errors']]
            ]
        );

        if ($isDryRun && $this->stats['created'] > 0) {
            $this->newLine();
            $this->warn("🔥 Run without --dry-run to actually create {$this->stats['created']} relations");
        }
    }
}
