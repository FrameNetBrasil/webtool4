<?php

namespace App\Console\Commands;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessDocumentSentenceMapping extends Command
{
    protected $signature = 'process:document-sentence-mapping {--dry-run : Preview changes without executing} {--force : Skip confirmation prompt}';

    protected $description = 'Process document CSV and create new document pairs (Dtake_lome and Dtake_daisy) with sentence associations';

    private array $stats = [
        'processed' => 0,
        'documents_created' => 0,
        'sentences_associated' => 0,
        'skipped' => 0,
        'errors' => 0,
        'duplicates' => 0
    ];

    public function handle(): int
    {
        $this->info('🚀 Document Sentence Mapping Tool');
        $this->newLine();

        $csvPath = app_path('Console/Commands/dtake_documents_1000.csv');

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

    private function processRow(array $row, bool $isDryRun, int $index): void
    {
        $this->stats['processed']++;

        try {
            $sourceIdDocument = $row['idDocument'];

            $idSentences = $this->getIdSentences($sourceIdDocument);

            if (empty($idSentences)) {
                $this->stats['skipped']++;
                if ($this->output->isVerbose()) {
                    $this->line("⚠️  Skipped: No idSentence found for document {$sourceIdDocument}");
                }
                return;
            }

            // Format NNNN as 0001, 0002, etc.
            $formattedIndex = str_pad($index, 4, '0', STR_PAD_LEFT);

            // Create Document A (Dtake_lome_NNNN in corpus 220)
            $documentNameLome = "Dtake_lome_{$formattedIndex}";
            $idDocumentLome = $this->createDocument($documentNameLome, 220, $isDryRun);

            // Create Document B (Dtake_daisy_NNNN in corpus 226)
            $documentNameDaisy = "Dtake_daisy_{$formattedIndex}";
            $idDocumentDaisy = $this->createDocument($documentNameDaisy, 226, $isDryRun);

            // Track document creation for both dry-run and actual execution
            $this->stats['documents_created'] += 2;

            if ($this->output->isVerbose()) {
                $this->line("📄 " . ($isDryRun ? 'Would create' : 'Created') . " documents: {$documentNameLome} and {$documentNameDaisy}");
            }

            // Associate sentences to both new documents
            // Since these are newly created documents, no need to check for duplicates
            if (!$isDryRun) {
                foreach ([$idDocumentLome, $idDocumentDaisy] as $targetIdDocument) {
                    foreach ($idSentences as $idSentence) {
                        $this->insertRecord($targetIdDocument, $idSentence);
                        $this->stats['sentences_associated']++;

                        if ($this->output->isVerbose()) {
                            $this->line("✅ Associated: sentence {$idSentence} to document {$targetIdDocument}");
                        }
                    }
                }
            } else {
                // In dry-run mode, just count what would be inserted
                $sentencesCount = count($idSentences) * 2; // 2 documents per source
                $this->stats['sentences_associated'] += $sentencesCount;

                if ($this->output->isVerbose()) {
                    foreach ($idSentences as $idSentence) {
                        $this->line("✅ Would associate: sentence {$idSentence} to new documents");
                    }
                }
            }

        } catch (\Exception $e) {
            $this->stats['errors']++;
            if ($this->output->isVerbose()) {
                $this->line("❌ Error processing document {$row['idDocument']}: {$e->getMessage()}");
            }
        }
    }

    private function getIdSentences(int $idDocument): array
    {
        $query = "
            SELECT s.idSentence
            FROM document_sentence ds
            JOIN sentence s ON (ds.idSentence = s.idSentence)
            JOIN document d ON (ds.idDocument = d.idDocument)
            WHERE (s.idOriginMM IN (15,16))
                AND d.idDocument = ?
        ";

        $results = DB::select($query, [$idDocument]);
        return array_map(function($result) {
            return $result->idSentence;
        }, $results);
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
                [$isDryRun ? 'Would Associate Sentences' : 'Sentences Associated', $this->stats['sentences_associated']],
                ['Skipped (No Sentences)', $this->stats['skipped']],
                ['Duplicates Found', $this->stats['duplicates']],
                ['Errors', $this->stats['errors']]
            ]
        );

        if ($isDryRun && $this->stats['documents_created'] > 0) {
            $this->newLine();
            $this->warn("🔥 Run without --dry-run to actually create {$this->stats['documents_created']} documents and associate {$this->stats['sentences_associated']} sentences");
        }
    }
}
