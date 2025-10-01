<?php

namespace App\Console\Commands;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessDocumentCsv extends Command
{
    protected $signature = 'process:document-csv {--output= : Output CSV file path (optional)}';

    protected $description = 'Process document CSV and enrich with database data';

    private array $stats = [
        'processed' => 0,
        'successful' => 0,
        'errors' => 0
    ];

    public function handle(): int
    {
        $this->info('📊 Document CSV Processing Tool');
        $this->newLine();

        $inputPath = app_path('Console/Commands/dtake_documents');

        if (!file_exists($inputPath)) {
            $this->error("❌ Input CSV file not found: {$inputPath}");
            return self::FAILURE;
        }

        $outputPath = $this->option('output') ?: storage_path('app/processed_documents.csv');

        $this->info("📂 Input file: {$inputPath}");
        $this->info("📄 Output file: {$outputPath}");
        $this->newLine();

        $this->info('📖 Reading input CSV...');
        $inputData = $this->readInputCsv($inputPath);

        if (empty($inputData)) {
            $this->error('❌ No data found in input CSV file');
            return self::FAILURE;
        }

        $this->info("📊 Found " . count($inputData) . " documents to process");
        $this->newLine();

        $this->info('🔍 Processing documents...');
        $enrichedData = $this->processDocuments($inputData);

        $this->info('💾 Writing output CSV...');
        $this->writeOutputCsv($outputPath, $enrichedData);

        $this->newLine();
        $this->displayStatistics();

        $this->info("✅ Processing completed! Output saved to: {$outputPath}");

        return self::SUCCESS;
    }

    private function readInputCsv(string $inputPath): array
    {
        $data = [];
        $handle = fopen($inputPath, 'r');

        if ($handle === false) {
            return $data;
        }

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 2 && is_numeric($row[0])) {
                $data[] = [
                    'idDocument' => (int) $row[0],
                    'cosine_similarity' => $row[1]
                ];
            }
        }

        fclose($handle);
        return $data;
    }

    private function processDocuments(array $inputData): array
    {
        $enrichedData = [];
        $progressBar = $this->output->createProgressBar(count($inputData));
        $progressBar->start();

        foreach ($inputData as $document) {
            $this->stats['processed']++;

            try {
                $idDocument = $document['idDocument'];

                $names = $this->getDocumentNames($idDocument);
                $title = $this->getDocumentTitle($idDocument);
                $excerpt = $this->getDocumentExcerpt($idDocument);

                $enrichedData[] = [
                    'idDocument' => $idDocument,
                    'cosine_similarity' => $document['cosine_similarity'],
                    'names' => $names,
                    'title' => $title,
                    'excerpt' => $excerpt
                ];

                $this->stats['successful']++;

                if ($this->output->isVerbose()) {
                    $this->line("✅ Processed document {$idDocument}");
                }

            } catch (\Exception $e) {
                $this->stats['errors']++;
                if ($this->output->isVerbose()) {
                    $this->line("❌ Error processing document {$document['idDocument']}: {$e->getMessage()}");
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        return $enrichedData;
    }

    private function getDocumentNames(int $idDocument): string
    {
        $query = "
            SELECT ds.idDocument, s.text as name
            FROM document_sentence ds
            JOIN sentence s ON (ds.idSentence = s.idSentence)
            JOIN document d ON (ds.idDocument = d.idDocument)
            JOIN view_lexicon_lemma lm ON (lower(s.text) = lm.name)
            JOIN lu ON (lu.idLexicon = lm.idLexicon)
            WHERE d.entry LIKE 'doc_dtake%'
            AND (s.idOriginmm IN (9))
            AND (lu.idFrame <> 0)
            AND d.idDocument = ?
        ";

        $results = DB::select($query, [$idDocument]);
        $names = array_map(function($result) {
            return $result->name;
        }, $results);

        return implode('; ', array_unique($names));
    }

    private function getDocumentTitle(int $idDocument): string
    {
        $query = "
            SELECT ds.idDocument, s.text as title
            FROM document_sentence ds
            JOIN sentence s ON (ds.idSentence = s.idSentence)
            JOIN lome_resultfe lome ON (ds.idSentence = lome.idSentence)
            JOIN document d ON (ds.idDocument = d.idDocument)
            WHERE d.entry LIKE 'doc_dtake%'
            AND (lome.type = 'lu')
            AND (lome.idFrame <> 0)
            AND (s.idOriginmm IN (15))
            AND d.idDocument = ?
        ";

        $results = DB::select($query, [$idDocument]);
        return !empty($results) ? $results[0]->title : '';
    }

    private function getDocumentExcerpt(int $idDocument): string
    {
        $query = "
            SELECT ds.idDocument, s.text as excerpt
            FROM document_sentence ds
            JOIN sentence s ON (ds.idSentence = s.idSentence)
            JOIN lome_resultfe lome ON (ds.idSentence = lome.idSentence)
            JOIN document d ON (ds.idDocument = d.idDocument)
            WHERE d.entry LIKE 'doc_dtake%'
            AND (lome.type = 'lu')
            AND (lome.idFrame <> 0)
            AND (s.idOriginmm IN (16))
            AND d.idDocument = ?
        ";

        $results = DB::select($query, [$idDocument]);
        return !empty($results) ? $results[0]->excerpt : '';
    }

    private function writeOutputCsv(string $outputPath, array $enrichedData): void
    {
        $handle = fopen($outputPath, 'w');

        if ($handle === false) {
            throw new \Exception("Unable to create output file: {$outputPath}");
        }

        // Write header
        fputcsv($handle, ['idDocument', 'cosine_similarity', 'names', 'title', 'excerpt']);

        // Write data
        foreach ($enrichedData as $row) {
            fputcsv($handle, [
                $row['idDocument'],
                $row['cosine_similarity'],
                $row['names'],
                $row['title'],
                $row['excerpt']
            ]);
        }

        fclose($handle);
    }

    private function displayStatistics(): void
    {
        $this->info('📊 Processing Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $this->stats['processed']],
                ['Successful', $this->stats['successful']],
                ['Errors', $this->stats['errors']]
            ]
        );
    }
}
