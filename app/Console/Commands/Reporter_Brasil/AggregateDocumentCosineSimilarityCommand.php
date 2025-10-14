<?php

namespace App\Console\Commands\Reporter_Brasil;

use Illuminate\Console\Command;

class AggregateDocumentCosineSimilarityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cosine:aggregate-reporter-brasil
                            {--output= : Output CSV file path for aggregated results}
                            {--input-dir= : Input directory containing consolidated CSV files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate sentence-level cosine similarities into document-level averages';

    private array $stats = [
        'processed' => 0,
        'total_sentences' => 0,
        'documents_with_negatives' => [
            'anno_lome' => 0,
            'anno_lomeedt' => 0,
            'lome_lomeedt' => 0,
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📊 Reporter Brasil Document-Level Cosine Aggregation');
        $this->newLine();

        $inputDir = $this->option('input-dir') ?? app_path('Console/Commands/Reporter_Brasil');
        $outputPath = $this->option('output') ?? "{$inputDir}/Document_Cosine_Averages.csv";

        $this->info("📂 Searching for consolidated files in: {$inputDir}");

        // Find all consolidated CSV files
        $files = glob("{$inputDir}/Consolidated_Reporter_Brasil_*.csv");

        if (empty($files)) {
            $this->error('❌ No consolidated CSV files found');

            return self::FAILURE;
        }

        $this->info('📄 Found '.count($files).' document(s) to process');
        $this->newLine();

        $aggregatedData = [];
        $progressBar = $this->output->createProgressBar(count($files));
        $progressBar->start();

        foreach ($files as $file) {
            $documentData = $this->processDocument($file);
            if ($documentData) {
                $aggregatedData[] = $documentData;
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->newLine();

        // Sort by document name
        usort($aggregatedData, fn ($a, $b) => strcmp($a['document_name'], $b['document_name']));

        // Export aggregated CSV
        $this->exportAggregatedCSV($aggregatedData, $outputPath);

        $this->info("✓ Document averages exported to: {$outputPath}");
        $this->newLine();

        // Display statistics
        $this->displayStatistics();

        return self::SUCCESS;
    }

    /**
     * Process a single document file
     */
    private function processDocument(string $filePath): ?array
    {
        $filename = basename($filePath);

        // Extract document name from filename
        if (! preg_match('/^Consolidated_Reporter_Brasil_(.+)\.csv$/', $filename, $matches)) {
            if ($this->option('verbose')) {
                $this->line("⚠️  Skipping invalid filename: {$filename}");
            }

            return null;
        }

        $documentName = $matches[1];

        // Read the consolidated CSV file
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return null;
        }

        // Skip header
        $header = fgetcsv($handle);

        // Find column indices
        $annoLomeIdx = array_search('cosine_anno_lome', $header);
        $annoLomeEdtIdx = array_search('cosine_anno_lomeedt', $header);
        $lomeLomeEdtIdx = array_search('cosine_lome_lomeedt', $header);

        if ($annoLomeIdx === false || $annoLomeEdtIdx === false || $lomeLomeEdtIdx === false) {
            fclose($handle);

            return null;
        }

        // Collect all cosine values (excluding -1)
        $annoLomeValues = [];
        $annoLomeEdtValues = [];
        $lomeLomeEdtValues = [];
        $sentenceCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $sentenceCount++;

            // Collect Anno-LOME values (excluding -1)
            if (isset($row[$annoLomeIdx]) && $row[$annoLomeIdx] !== '' && $row[$annoLomeIdx] != -1) {
                $annoLomeValues[] = (float) $row[$annoLomeIdx];
            }

            // Collect Anno-LOMEEdt values (excluding -1)
            if (isset($row[$annoLomeEdtIdx]) && $row[$annoLomeEdtIdx] !== '' && $row[$annoLomeEdtIdx] != -1) {
                $annoLomeEdtValues[] = (float) $row[$annoLomeEdtIdx];
            }

            // Collect LOME-LOMEEdt values (excluding -1)
            if (isset($row[$lomeLomeEdtIdx]) && $row[$lomeLomeEdtIdx] !== '' && $row[$lomeLomeEdtIdx] != -1) {
                $lomeLomeEdtValues[] = (float) $row[$lomeLomeEdtIdx];
            }
        }

        fclose($handle);

        // Track documents with -1 values
        if ($sentenceCount > count($annoLomeValues)) {
            $this->stats['documents_with_negatives']['anno_lome']++;
        }
        if ($sentenceCount > count($annoLomeEdtValues)) {
            $this->stats['documents_with_negatives']['anno_lomeedt']++;
        }
        if ($sentenceCount > count($lomeLomeEdtValues)) {
            $this->stats['documents_with_negatives']['lome_lomeedt']++;
        }

        $this->stats['processed']++;
        $this->stats['total_sentences'] += $sentenceCount;

        if ($this->option('verbose')) {
            $this->line("✓ {$documentName}: {$sentenceCount} sentences processed");
        }

        return [
            'document_name' => $documentName,
            'cosine_anno_lome' => $this->calculateAverage($annoLomeValues),
            'cosine_anno_lomeedt' => $this->calculateAverage($annoLomeEdtValues),
            'cosine_lome_lomeedt' => $this->calculateAverage($lomeLomeEdtValues),
            'sentence_count' => $sentenceCount,
        ];
    }

    /**
     * Calculate average of values (returns null if no values)
     */
    private function calculateAverage(array $values): ?float
    {
        if (empty($values)) {
            return null;
        }

        return array_sum($values) / count($values);
    }

    /**
     * Export aggregated data to CSV
     */
    private function exportAggregatedCSV(array $data, string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        // Write header
        $header = [
            'Document Name',
            'cosine_anno_lome',
            'cosine_anno_lomeedt',
            'cosine_lome_lomeedt',
        ];
        fputcsv($handle, $header);

        // Write data
        foreach ($data as $row) {
            fputcsv($handle, [
                $row['document_name'],
                $row['cosine_anno_lome'] ?? '',
                $row['cosine_anno_lomeedt'] ?? '',
                $row['cosine_lome_lomeedt'] ?? '',
            ]);
        }

        fclose($handle);
    }

    /**
     * Display statistics
     */
    private function displayStatistics(): void
    {
        $this->info('=== Statistics ===');
        $this->newLine();

        $avgSentences = $this->stats['processed'] > 0
            ? round($this->stats['total_sentences'] / $this->stats['processed'], 2)
            : 0;

        $this->table(
            ['Metric', 'Value'],
            [
                ['Documents Processed', $this->stats['processed']],
                ['Total Sentences', $this->stats['total_sentences']],
                ['Avg Sentences per Document', $avgSentences],
                ['Documents with -1 in Anno-LOME', $this->stats['documents_with_negatives']['anno_lome']],
                ['Documents with -1 in Anno-LOMEEdt', $this->stats['documents_with_negatives']['anno_lomeedt']],
                ['Documents with -1 in LOME-LOMEEdt', $this->stats['documents_with_negatives']['lome_lomeedt']],
            ]
        );
    }
}
