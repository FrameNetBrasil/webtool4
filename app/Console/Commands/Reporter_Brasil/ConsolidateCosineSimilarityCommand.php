<?php

namespace App\Console\Commands\Reporter_Brasil;

use Illuminate\Console\Command;

class ConsolidateCosineSimilarityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cosine:consolidate-reporter-brasil
                            {--output-dir= : Output directory for consolidated CSV files}
                            {--document= : Process specific document only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consolidate cosine similarity CSV files (Anno/LOME/LOMEEdt) for Reporter Brasil documents';

    private array $stats = [
        'processed' => 0,
        'total_rows' => 0,
        'mismatches' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📊 Reporter Brasil Cosine Similarity Consolidation');
        $this->newLine();

        $csvPath = app_path('Console/Commands/Reporter_Brasil/reporter_brasil_eval.csv');

        if (! file_exists($csvPath)) {
            $this->error("❌ CSV file not found: {$csvPath}");

            return self::FAILURE;
        }

        $this->info('📂 Reading document list...');
        $documentNames = $this->readCsvFile($csvPath);

        if (empty($documentNames)) {
            $this->error('❌ No document names found in CSV file');

            return self::FAILURE;
        }

        // Filter by specific document if requested
        $specificDocument = $this->option('document');
        if ($specificDocument) {
            $documentNames = array_filter($documentNames, fn ($name) => $name === $specificDocument);
            if (empty($documentNames)) {
                $this->error("❌ Document not found: {$specificDocument}");

                return self::FAILURE;
            }
        }

        $this->info('📄 Found '.count($documentNames).' document(s) to process');
        $this->newLine();

        $outputDir = $this->option('output-dir') ?? app_path('Console/Commands/Reporter_Brasil');

        $progressBar = $this->output->createProgressBar(count($documentNames));
        $progressBar->start();

        foreach ($documentNames as $documentName) {
            $this->processDocument($documentName, $outputDir);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->newLine();

        // Display statistics
        $this->displayStatistics();

        return self::SUCCESS;
    }

    /**
     * Read CSV file and return array of document names
     */
    private function readCsvFile(string $csvPath): array
    {
        $documentNames = [];
        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            return $documentNames;
        }

        while (($line = fgets($handle)) !== false) {
            $documentName = trim($line);
            if (! empty($documentName)) {
                $documentNames[] = $documentName;
            }
        }

        fclose($handle);

        return $documentNames;
    }

    /**
     * Process a single document
     */
    private function processDocument(string $documentName, string $outputDir): void
    {
        $baseDir = app_path('Console/Commands/Reporter_Brasil');

        $annoLomePath = "{$baseDir}/Anno_LOME_{$documentName}.csv";
        $annoLomeEdtPath = "{$baseDir}/Anno_LOMEEdt_{$documentName}.csv";
        $lomeLomeEdtPath = "{$baseDir}/LOME_LOMEEdt_{$documentName}.csv";

        // Check if all three files exist
        if (! file_exists($annoLomePath) || ! file_exists($annoLomeEdtPath) || ! file_exists($lomeLomeEdtPath)) {
            if ($this->option('verbose')) {
                $this->line("⚠️  Missing cosine files for document: {$documentName}");
            }

            return;
        }

        // Read all three CSV files
        $annoLomeData = $this->readCosineCSV($annoLomePath);
        $annoLomeEdtData = $this->readCosineCSV($annoLomeEdtPath);
        $lomeLomeEdtData = $this->readCosineCSV($lomeLomeEdtPath);

        // Consolidate data by matching sentences
        $consolidated = $this->consolidateData($annoLomeData, $annoLomeEdtData, $lomeLomeEdtData);

        // Export consolidated CSV
        $outputPath = "{$outputDir}/Consolidated_{$documentName}.csv";
        $this->exportConsolidatedCSV($consolidated, $outputPath);

        $this->stats['processed']++;
        $this->stats['total_rows'] += count($consolidated);

        if ($this->option('verbose')) {
            $this->line("✓ {$documentName}: ".count($consolidated).' consolidated rows');
        }
    }

    /**
     * Read cosine similarity CSV file
     */
    private function readCosineCSV(string $filePath): array
    {
        $data = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return $data;
        }

        // Skip header
        fgets($handle);

        while (($line = fgets($handle)) !== false) {
            $row = str_getcsv($line);
            if (count($row) >= 4) {
                $sentence = trim($row[3], '"');
                $data[$sentence] = [
                    'idDocumentSentence1' => $row[0],
                    'idDocumentSentence2' => $row[1],
                    'cosine' => $row[2],
                    'sentence' => $sentence,
                ];
            }
        }

        fclose($handle);

        return $data;
    }

    /**
     * Consolidate data from three sources by matching sentences
     */
    private function consolidateData(array $annoLome, array $annoLomeEdt, array $lomeLomeEdt): array
    {
        $consolidated = [];

        // Get all unique sentences
        $allSentences = array_unique(array_merge(
            array_keys($annoLome),
            array_keys($annoLomeEdt),
            array_keys($lomeLomeEdt)
        ));

        foreach ($allSentences as $sentence) {
            $annoLomeRow = $annoLome[$sentence] ?? null;
            $annoLomeEdtRow = $annoLomeEdt[$sentence] ?? null;
            $lomeLomeEdtRow = $lomeLomeEdt[$sentence] ?? null;

            // Use the first available IDs, prioritizing Anno_LOME
            $ids1 = $annoLomeRow['idDocumentSentence1'] ?? $annoLomeEdtRow['idDocumentSentence1'] ?? $lomeLomeEdtRow['idDocumentSentence1'] ?? null;
            $ids2 = $annoLomeRow['idDocumentSentence2'] ?? $annoLomeEdtRow['idDocumentSentence2'] ?? $lomeLomeEdtRow['idDocumentSentence2'] ?? null;

            if ($ids1 === null || $ids2 === null) {
                $this->stats['mismatches']++;

                continue;
            }

            $consolidated[] = [
                'idDocumentSentence1' => $ids1,
                'idDocumentSentence2' => $ids2,
                'cosine_anno_lome' => $annoLomeRow['cosine'] ?? null,
                'cosine_anno_lomeedt' => $annoLomeEdtRow['cosine'] ?? null,
                'cosine_lome_lomeedt' => $lomeLomeEdtRow['cosine'] ?? null,
                'sentence' => $sentence,
            ];
        }

        return $consolidated;
    }

    /**
     * Export consolidated data to CSV
     */
    private function exportConsolidatedCSV(array $data, string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        // Write header
        $header = [
            'idDocumentSentence1',
            'idDocumentSentence2',
            'cosine_anno_lome',
            'cosine_anno_lomeedt',
            'cosine_lome_lomeedt',
            'sentence',
        ];
        fputcsv($handle, $header);

        // Write data
        foreach ($data as $row) {
            fputcsv($handle, [
                $row['idDocumentSentence1'],
                $row['idDocumentSentence2'],
                $row['cosine_anno_lome'] ?? '',
                $row['cosine_anno_lomeedt'] ?? '',
                $row['cosine_lome_lomeedt'] ?? '',
                $row['sentence'],
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

        $avgRows = $this->stats['processed'] > 0
            ? round($this->stats['total_rows'] / $this->stats['processed'], 2)
            : 0;

        $this->table(
            ['Metric', 'Value'],
            [
                ['Documents Processed', $this->stats['processed']],
                ['Total Consolidated Rows', $this->stats['total_rows']],
                ['Avg Rows per Document', $avgRows],
                ['Mismatches/Missing IDs', $this->stats['mismatches']],
            ]
        );
    }
}
