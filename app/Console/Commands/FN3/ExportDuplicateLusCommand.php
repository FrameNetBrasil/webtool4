<?php

namespace App\Console\Commands\FN3;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportDuplicateLusCommand extends Command
{
    protected $signature = 'fn3:export-duplicate-lus
                            {--output=app/Console/Commands/FN3/Data/duplicate_lus.csv : Output CSV file path}';

    protected $description = 'Export duplicate LUs (same name in same frame) to CSV for language ID 1';

    private array $stats = [
        'total_duplicates' => 0,
        'frames_affected' => 0,
        'duplicate_groups' => 0,
    ];

    public function handle(): int
    {
        $outputPath = $this->option('output');

        // Make path absolute if relative
        if (!str_starts_with($outputPath, '/')) {
            $outputPath = base_path($outputPath);
        }

        // Ensure directory exists
        $directory = dirname($outputPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
            $this->info("Created directory: {$directory}");
        }

        $this->info('Finding duplicate LUs...');
        $this->newLine();

        // Get duplicate LUs
        $duplicates = $this->findDuplicateLus();

        if (empty($duplicates)) {
            $this->info('No duplicate LUs found!');
            return Command::SUCCESS;
        }

        // Write CSV
        $this->writeOutputCsv($outputPath, $duplicates);

        // Display summary
        $this->displaySummary($outputPath);

        return Command::SUCCESS;
    }

    private function findDuplicateLus(): array
    {
        // First, find which (name, idFrame) combinations have duplicates
        $duplicateGroups = DB::select("
            SELECT
                lu.name,
                lu.idFrame,
                COUNT(*) as duplicate_count
            FROM lu
            JOIN lemma lem ON lu.idLemma = lem.idLemma
            WHERE lu.status IN ('CREATED', 'PENDING')
            AND lem.idLanguage = 1
            GROUP BY lu.name, lu.idFrame
            HAVING COUNT(*) > 1
        ");

        $this->stats['duplicate_groups'] = count($duplicateGroups);

        if (empty($duplicateGroups)) {
            return [];
        }

        // Track unique frames
        $uniqueFrames = [];
        foreach ($duplicateGroups as $group) {
            $uniqueFrames[$group->idFrame] = true;
        }
        $this->stats['frames_affected'] = count($uniqueFrames);

        // Now get all LUs that are part of these duplicate groups
        // Build the WHERE clause dynamically
        $conditions = [];
        $bindings = [];

        foreach ($duplicateGroups as $group) {
            $conditions[] = "(lu.name = ? AND lu.idFrame = ?)";
            $bindings[] = $group->name;
            $bindings[] = $group->idFrame;
        }

        $whereClause = implode(' OR ', $conditions);

        $query = "
            SELECT
                lu.idLU,
                f.entry as frameName,
                lu.name as luName,
                lu.origin,
                lu.status
            FROM lu
            JOIN frame f ON lu.idFrame = f.idFrame
            JOIN lemma lem ON lu.idLemma = lem.idLemma
            WHERE lu.status IN ('CREATED', 'PENDING')
            AND lem.idLanguage = 1
            AND ({$whereClause})
            ORDER BY f.entry ASC, lu.name ASC
        ";

        $duplicates = DB::select($query, $bindings);
        $this->stats['total_duplicates'] = count($duplicates);

        return $duplicates;
    }

    private function writeOutputCsv(string $path, array $duplicates): void
    {
        $handle = fopen($path, 'w');

        // Write header
        fputcsv($handle, ['idLU', 'frameName', 'luName', 'origin', 'status']);

        // Write data with progress bar
        $progressBar = $this->output->createProgressBar(count($duplicates));
        $progressBar->start();

        foreach ($duplicates as $duplicate) {
            fputcsv($handle, [
                $duplicate->idLU,
                $duplicate->frameName,
                $duplicate->luName,
                $duplicate->origin ?? '',
                $duplicate->status,
            ]);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        fclose($handle);

        $this->info("CSV created: {$path}");
    }

    private function displaySummary(string $outputPath): void
    {
        $this->newLine();
        $this->info('=== Duplicate LUs Summary ===');

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total duplicate LUs', $this->stats['total_duplicates']],
                ['Duplicate groups (name + frame)', $this->stats['duplicate_groups']],
                ['Frames affected', $this->stats['frames_affected']],
            ]
        );

        $this->newLine();
        $this->info("✓ CSV exported to: {$outputPath}");
        $this->newLine();
        $this->comment('Note: An LU is considered duplicate if it shares the same name with another LU in the same frame,');
        $this->comment('regardless of POS (part of speech). Only LUs with status CREATED or PENDING and idLanguage=1 are included.');
    }
}
