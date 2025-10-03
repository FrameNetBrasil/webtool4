<?php

namespace App\Console\Commands;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDocumentReporterBrasil extends Command
{
    protected $signature = 'create:document-reporter-brasil {--dry-run : Preview changes without executing} {--force : Skip confirmation prompt}';

    protected $description = 'Create documents for Reporter Brasil corpus with sentence processing';

    private array $stats = [
        'processed' => 0,
        'documents_created' => 0,
        'sentences_copied' => 0,
        'skipped' => 0,
        'errors' => 0
    ];

    public function handle(): int
    {
        $this->info('🚀 Reporter Brasil Document Creator');
        $this->newLine();

        $csvPath = app_path('Console/Commands/report_brasil_eval.csv');

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
            if (!$this->confirm('This will create document-sentence mappings in the database. Continue?')) {
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

        $this->info("📊 Found " . count($csvData) . " documents to process");
        $this->newLine();

        if (!$isDryRun) {
            DB::beginTransaction();
        }

        try {
            $progressBar = $this->output->createProgressBar(count($csvData));
            $progressBar->start();

            $index = 1;
            foreach ($csvData as $row) {
                $this->processRow($row, $isDryRun, $index);
                $progressBar->advance();
                $index++;
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

        while (($line = fgets($handle)) !== false) {
            $documentName = trim($line);
            if (!empty($documentName)) {
                $data[] = ['documentName' => $documentName];
            }
        }

        fclose($handle);
        return $data;
    }

    private function processRow(array $row, bool $isDryRun, int $index): void
    {
        $this->stats['processed']++;

        try {
            $documentName = $row['documentName'];

            // Lookup idDocument from view_document
            $sourceIdDocument = $this->getIdDocumentByName($documentName);

            if (!$sourceIdDocument) {
                $this->stats['skipped']++;
                if ($this->output->isVerbose()) {
                    $this->line("⚠️  Skipped: Document not found: {$documentName}");
                }
                return;
            }

            // Get sentences from source document
            $sentences = $this->getSentences($sourceIdDocument);

            if (empty($sentences)) {
                $this->stats['skipped']++;
                if ($this->output->isVerbose()) {
                    $this->line("⚠️  Skipped: No sentences found for document {$documentName}");
                }
                return;
            }

            // Create 3 new documents in different corpora (227, 228, 229)
            $corpora = [227, 228, 229];

            foreach ($corpora as $idCorpus) {
                // Create new document with same name
                $newIdDocument = $this->createDocument($documentName, $idCorpus, $isDryRun);
                $this->stats['documents_created']++;

                if ($this->output->isVerbose()) {
                    $this->line("📄 " . ($isDryRun ? 'Would create' : 'Created') . " document: {$documentName} in corpus {$idCorpus}");
                }

                // Copy sentences to new document
                $copiedCount = $this->copySentences($sourceIdDocument, $newIdDocument, $sentences, $isDryRun);
                $this->stats['sentences_copied'] += $copiedCount;

                if ($this->output->isVerbose()) {
                    $this->line("✅ " . ($isDryRun ? 'Would copy' : 'Copied') . " {$copiedCount} sentences to document in corpus {$idCorpus}");
                }
            }

        } catch (\Exception $e) {
            $this->stats['errors']++;
            if ($this->output->isVerbose()) {
                $this->line("❌ Error processing document {$row['documentName']}: {$e->getMessage()}");
            }
        }
    }

    private function getIdDocumentByName(string $name): ?int
    {
        $result = Criteria::table('view_document')
            ->select('idDocument')
            ->where('name', $name)
            ->first();

        return $result?->idDocument;
    }

    private function getSentences(int $idDocument): array
    {
        $results = DB::table('document_sentence')
            ->where('idDocument', $idDocument)
            ->get();

        return array_map(function($result) {
            return $result->idSentence;
        }, $results->toArray());
    }

    private function copySentences(int $sourceIdDocument, ?int $targetIdDocument, array $sentences, bool $isDryRun): int
    {
        if ($isDryRun || $targetIdDocument === null) {
            return count($sentences);
        }

        $copiedCount = 0;

        foreach ($sentences as $idSentence) {
            // Check if record already exists
            if (!$this->recordExists($targetIdDocument, $idSentence)) {
                $this->insertRecord($targetIdDocument, $idSentence);
                $copiedCount++;
            }
        }

        return $copiedCount;
    }

    private function createDocument(string $name, int $idCorpus, bool $isDryRun): ?int
    {
        if ($isDryRun) {
            return null;
        }

        $data = json_encode([
            'name' => $name,
            'idCorpus' => $idCorpus,
            'idUser' => 6
        ]);

        $idDocument = Criteria::function('document_create(?)', [$data]);

        return $idDocument;
    }

    private function recordExists(int $idDocument, int $idSentence): bool
    {
        return DB::table('document_sentence')
            ->where('idDocument', $idDocument)
            ->where('idSentence', $idSentence)
            ->exists();
    }

    private function insertRecord(int $idDocument, int $idSentence): void
    {
        DB::table('document_sentence')->insert([
            'idDocument' => $idDocument,
            'idSentence' => $idSentence
        ]);
    }

    private function displayStatistics(bool $isDryRun): void
    {
        $this->info('📊 Processing Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $this->stats['processed']],
                [$isDryRun ? 'Would Create Documents' : 'Documents Created', $this->stats['documents_created']],
                [$isDryRun ? 'Would Copy Sentences' : 'Sentences Copied', $this->stats['sentences_copied']],
                ['Skipped', $this->stats['skipped']],
                ['Errors', $this->stats['errors']]
            ]
        );

        if ($isDryRun && $this->stats['documents_created'] > 0) {
            $this->newLine();
            $this->warn("🔥 Run without --dry-run to actually create {$this->stats['documents_created']} documents and copy {$this->stats['sentences_copied']} sentences");
        }
    }
}
