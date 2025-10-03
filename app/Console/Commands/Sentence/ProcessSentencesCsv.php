<?php

namespace App\Console\Commands\Sentence;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessSentencesCsv extends Command
{
    protected $signature = 'sentence:process-csv {--dry-run : Preview data without processing} {--force : Skip confirmation prompt}';

    protected $description = 'Process sentences from CSV file and update/create in webtool database';

    private array $stats = [
        'rows_processed' => 0,
        'sentences_found' => 0,
        'sentences_not_found' => 0,
        'sentences_updated' => 0,
        'sentences_created' => 0,
        'timespans_updated' => 0,
        'errors' => 0
    ];

    public function handle(): int
    {
        $this->info('📄 Sentence CSV Processor');
        $this->newLine();

        $csvPath = app_path('Console/Commands/Sentence/sentences.csv');

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
            if (!$this->confirm('This will update/create sentences in webtool database. Continue?')) {
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

        $this->info("📊 Found " . count($csvData) . " row(s) to process");
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
            $this->error("❌ Error: {$e->getMessage()}");
            return self::FAILURE;
        }

        $this->displayStatistics();

        return self::SUCCESS;
    }

    private function readCsvFile(string $csvPath): array
    {
        $data = [];
        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            return $data;
        }

        // Read header row
        $header = fgetcsv($handle);

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (!empty(array_filter($row))) { // Skip completely empty rows
                $data[] = $row;
            }
        }

        fclose($handle);
        return $data;
    }

    private function processRow(array $row, bool $isDryRun): void
    {
        $this->stats['rows_processed']++;

        try {
            // Process each idSentence column (up to 6)
            foreach ($row as $index => $idSentence) {
                $idSentence = trim($idSentence);

                // Skip empty values
                if (empty($idSentence)) {
                    continue;
                }

                $sentenceData = $this->getSentenceData((int) $idSentence);

                if ($sentenceData) {
                    $this->stats['sentences_found']++;

                    if ($this->output->isVerbose()) {
                        $this->displaySentenceData($sentenceData, $index);
                    }

                    // First sentence (index 0) - UPDATE existing
                    if ($index === 0) {
                        $this->updateFirstSentence($sentenceData, $isDryRun);
                    } else {
                        // Other sentences - CREATE new
                        $this->createNewSentence($sentenceData, $isDryRun);
                    }
                } else {
                    $this->stats['sentences_not_found']++;

                    if ($this->output->isVerbose()) {
                        $this->line("⚠️  Sentence not found: idSentence={$idSentence}");
                    }
                }
            }

        } catch (\Exception $e) {
            $this->stats['errors']++;
            if ($this->output->isVerbose()) {
                $this->line("❌ Error processing row: {$e->getMessage()}");
            }
        }
    }

    private function getSentenceData(int $idSentence): ?object
    {
        $query = "
            SELECT
                s.idSentence,
                s.text,
                s.idOriginMM,
                ds.idDocument,
                vst.startTime,
                vst.endTime
            FROM sentence s
            LEFT JOIN document_sentence ds ON s.idSentence = ds.idSentence
            LEFT JOIN view_sentence_timespan vst ON s.idSentence = vst.idSentence
            WHERE s.idSentence = ?
        ";

        $result = DB::connection('webtool41')->select($query, [$idSentence]);

        return !empty($result) ? $result[0] : null;
    }

    private function updateFirstSentence(object $data, bool $isDryRun): void
    {
        if ($isDryRun) {
            $this->stats['sentences_updated']++;
            return;
        }

        // Update sentence text in webtool database (default connection)
        DB::table('sentence')
            ->where('idSentence', $data->idSentence)
            ->update(['text' => $data->text]);

        $this->stats['sentences_updated']++;

        // Find timespan via sentence_timespan
        $timespanId = DB::table('sentence_timespan')
            ->where('idSentence', $data->idSentence)
            ->value('idTimeSpan');

        // Update timespan if exists and has new data
        if ($timespanId && $data->startTime !== null && $data->endTime !== null) {
            DB::table('timespan')
                ->where('idTimeSpan', $timespanId)
                ->update([
                    'startTime' => $data->startTime,
                    'endTime' => $data->endTime
                ]);

            $this->stats['timespans_updated']++;
        }
    }

    private function createNewSentence(object $data, bool $isDryRun): void
    {
        if ($isDryRun) {
            $this->stats['sentences_created']++;
            return;
        }

        $sentenceData = [
            'text' => $data->text,
            'idDocument' => $data->idDocument,
            'idLanguage' => 1,
            'idUser' => 6,
            'startTime' => $data->startTime,
            'endTime' => $data->endTime
        ];

        $jsonData = json_encode($sentenceData);

        Criteria::function('sentence_create(?)', [$jsonData]);

        $this->stats['sentences_created']++;
    }

    private function displaySentenceData(object $data, int $index): void
    {
        $text = $data->text ?? '';
        $truncatedText = strlen($text) > 50 ? substr($text, 0, 50) . '...' : $text;

        $action = $index === 0 ? 'UPDATE' : 'CREATE';

        $this->line("✅ [{$action}] idSentence: {$data->idSentence}");
        $this->line("   Text: {$truncatedText}");
        $this->line("   idDocument: " . ($data->idDocument ?? 'N/A'));
        $this->line("   Timespan: " . ($data->startTime ? "{$data->startTime} - {$data->endTime}" : 'N/A'));
        $this->newLine();
    }

    private function displayStatistics(): void
    {
        $this->info('📊 Processing Statistics:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Rows Processed', $this->stats['rows_processed']],
                ['Sentences Found (webtool41)', $this->stats['sentences_found']],
                ['Sentences Not Found', $this->stats['sentences_not_found']],
                ['Sentences Updated (webtool)', $this->stats['sentences_updated']],
                ['Sentences Created (webtool)', $this->stats['sentences_created']],
                ['Timespans Updated', $this->stats['timespans_updated']],
                ['Errors', $this->stats['errors']]
            ]
        );
    }
}
