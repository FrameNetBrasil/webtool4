<?php

namespace App\Console\Commands\Reporter_Brasil;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CompareTimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'annotation:compare-time
                            {--output= : Output CSV file path for results}
                            {--aggregated-output= : Output CSV file path for aggregated document-level results}
                            {--group-by=document : Group results by document or sentence}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare annotation time spent by annotators across different sentences and documents';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $outputPath = $this->option('output');
        $groupBy = $this->option('group-by');

        $this->info('🕐 Comparing annotation times across documents and sentences');
        $this->newLine();

        // Get documents and sentence pairs using the specified query
        $documents = Criteria::table('document_sentence as ds1')
            ->join('document_sentence as ds2', 'ds1.idSentence', '=', 'ds2.idSentence')
            ->join('document as d1', 'ds1.idDocument', '=', 'd1.idDocument')
            ->join('view_document as d2', 'ds2.idDocument', '=', 'd2.idDocument')
            ->join('view_sentence as s', 'ds2.idSentence', '=', 's.idSentence')
            ->join('view_sentence_timespan as ts', 's.idSentence', '=', 'ts.idSentence')
            ->where('d2.idCorpus', 228)
            ->whereNotIn('d1.idCorpus', [227, 228, 229])
            ->where('d2.idLanguage', 1)
            ->select('d2.name', 'ds1.idDocumentSentence as idDs1', 'ds2.idDocumentSentence as idDs2', 's.text', 'ts.startTime')
            ->orderBy('d2.name')
            ->orderBy('ts.startTime')
            ->get()
            ->groupBy('name')
            ->toArray();

        if (empty($documents)) {
            $this->error('No document sentence pairs found matching the criteria.');

            return Command::FAILURE;
        }

        $this->info('Found '.count($documents).' document(s) with sentence pairs');
        $this->newLine();

        $allResults = [];
        $this->output->progressStart(count($documents));

        foreach ($documents as $documentName => $sentencePairs) {
            $this->output->progressAdvance();

            $documentResults = [];

            foreach ($sentencePairs as $pair) {
                $timeData1 = $this->getAnnotationTime($pair->idDs1);
                $timeData2 = $this->getAnnotationTime($pair->idDs2);

                $result = [
                    'document_name' => $documentName,
                    'start_time' => $pair->startTime ?? '',
                    'idDocumentSentence1' => $pair->idDs1,
                    'idDocumentSentence2' => $pair->idDs2,
                    'total_time_ds1' => $timeData1['total_seconds'],
                    'total_time_ds2' => $timeData2['total_seconds'],
                    'time_difference' => $timeData1['total_seconds'] - $timeData2['total_seconds'],
                    // 'sessions_ds1' => $timeData1['session_count'],
                    // 'sessions_ds2' => $timeData2['session_count'],
                    // 'users_ds1' => $timeData1['user_count'],
                    // 'users_ds2' => $timeData2['user_count'],
                    // 'avg_time_per_session_ds1' => $timeData1['avg_per_session'],
                    // 'avg_time_per_session_ds2' => $timeData2['avg_per_session'],
                    'sentence_text' => $pair->text,
                ];

                $documentResults[] = $result;
                $allResults[] = $result;
            }

            // Display results for this document
            if ($groupBy === 'document') {
                $this->displayDocumentResults($documentName, $documentResults);
            }
        }

        $this->output->progressFinish();
        $this->newLine();

        // Display summary statistics
        $this->displaySummaryStatistics($allResults);

        // Export to CSV if requested
        if ($outputPath) {
            // Resolve paths relative to command directory
            $fullOutputPath = $this->resolveOutputPath($outputPath);
            $this->exportToCSV($allResults, $fullOutputPath);
            $this->info("✓ Results exported to: {$fullOutputPath}");

            // Export aggregated document-level CSV
            $aggregatedPath = $this->option('aggregated-output');
            if ($aggregatedPath) {
                $fullAggregatedPath = $this->resolveOutputPath($aggregatedPath);
            } else {
                $fullAggregatedPath = str_replace('.csv', '_aggregated.csv', $fullOutputPath);
            }
            $this->exportAggregatedToCSV($allResults, $fullAggregatedPath);
            $this->info("✓ Aggregated results exported to: {$fullAggregatedPath}");
        }

        return Command::SUCCESS;
    }

    /**
     * Resolve output path relative to command directory if not absolute
     */
    private function resolveOutputPath(string $path): string
    {
        // If path is absolute, return as-is
        if (str_starts_with($path, '/') || preg_match('/^[A-Z]:/i', $path)) {
            return $path;
        }

        // Otherwise, resolve relative to command directory
        return __DIR__.'/'.$path;
    }

    /**
     * Get annotation time data for a document sentence
     */
    protected function getAnnotationTime(int $idDocumentSentence): array
    {
        $sessions = DB::table('annotation_session')
            ->where('idDocumentSentence', $idDocumentSentence)
            ->where('idUser', '!=', 6)
            ->whereNotNull('endedAt')
            ->select(
                'idUser',
                DB::raw('TIMESTAMPDIFF(SECOND, startedAt, endedAt) as duration')
            )
            ->get();

        if ($sessions->isEmpty()) {
            return [
                'total_seconds' => 0,
                'session_count' => 0,
                'user_count' => 0,
                'avg_per_session' => 0,
            ];
        }

        $totalSeconds = $sessions->sum('duration');
        $sessionCount = $sessions->count();
        $userCount = $sessions->unique('idUser')->count();
        $avgPerSession = $sessionCount > 0 ? round($totalSeconds / $sessionCount, 2) : 0;

        return [
            'total_seconds' => $totalSeconds,
            'session_count' => $sessionCount,
            'user_count' => $userCount,
            'avg_per_session' => $avgPerSession,
        ];
    }

    /**
     * Display results for a document
     */
    protected function displayDocumentResults(string $documentName, array $results): void
    {
        $this->newLine();
        $this->line("<fg=cyan>Document: {$documentName}</>");
        $this->newLine();

        $tableData = [];
        foreach ($results as $result) {
            $diff = $result['time_difference'];
            $diffFormatted = ($diff >= 0 ? '+' : '').$this->formatTime($diff);

            $tableData[] = [
                substr($result['sentence_text'], 0, 50).'...',
                $this->formatTime($result['total_time_ds1']),
                $this->formatTime($result['total_time_ds2']),
                $diffFormatted,
                // $result['sessions_ds1'].'/'.$result['sessions_ds2'],
            ];
        }

        $this->table(
            ['Sentence', 'Time DS1', 'Time DS2', 'Difference (DS1-DS2)'],
            $tableData
        );
    }

    /**
     * Display summary statistics
     */
    protected function displaySummaryStatistics(array $results): void
    {
        if (empty($results)) {
            return;
        }

        $this->info('=== Summary Statistics ===');
        $this->newLine();

        $totalPairs = count($results);
        $differences = array_column($results, 'time_difference');
        $avgTimeDiff = array_sum($differences) / $totalPairs;
        $maxTimeDiff = max($differences);
        $minTimeDiff = min($differences);

        $avgTimeDs1 = array_sum(array_column($results, 'total_time_ds1')) / $totalPairs;
        $avgTimeDs2 = array_sum(array_column($results, 'total_time_ds2')) / $totalPairs;

        $avgDiffFormatted = ($avgTimeDiff >= 0 ? '+' : '').$this->formatTime($avgTimeDiff);
        $maxDiffFormatted = ($maxTimeDiff >= 0 ? '+' : '').$this->formatTime($maxTimeDiff);
        $minDiffFormatted = ($minTimeDiff >= 0 ? '+' : '').$this->formatTime($minTimeDiff);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total sentence pairs', $totalPairs],
                ['Average time DS1 (corpus != 227)', $this->formatTime($avgTimeDs1)],
                ['Average time DS2 (corpus 228)', $this->formatTime($avgTimeDs2)],
                ['Average time difference (DS1-DS2)', $avgDiffFormatted],
                ['Maximum time difference (DS1-DS2)', $maxDiffFormatted],
                ['Minimum time difference (DS1-DS2)', $minDiffFormatted],
            ]
        );
    }

    /**
     * Export results to CSV
     */
    protected function exportToCSV(array $results, string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        // Write header
        fputcsv($handle, [
            'Document Name',
            'Start Time',
            'idDocumentSentence1',
            'idDocumentSentence2',
            'Total Time DS1 (seconds)',
            'Total Time DS2 (seconds)',
            'Time Difference (seconds)',
            // 'Sessions DS1',
            // 'Sessions DS2',
            // 'Users DS1',
            // 'Users DS2',
            // 'Avg Time/Session DS1',
            // 'Avg Time/Session DS2',
            'Sentence Text',
        ]);

        // Write data
        foreach ($results as $result) {
            fputcsv($handle, [
                $result['document_name'],
                $result['start_time'],
                $result['idDocumentSentence1'],
                $result['idDocumentSentence2'],
                $result['total_time_ds1'],
                $result['total_time_ds2'],
                $result['time_difference'],
                // $result['sessions_ds1'],
                // $result['sessions_ds2'],
                // $result['users_ds1'],
                // $result['users_ds2'],
                // $result['avg_time_per_session_ds1'],
                // $result['avg_time_per_session_ds2'],
                $result['sentence_text'],
            ]);
        }

        fclose($handle);
    }

    /**
     * Export aggregated document-level results to CSV
     */
    protected function exportAggregatedToCSV(array $results, string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        // Write header
        fputcsv($handle, [
            'Document Name',
            'number of sentences',
            'avg sentence length',
            'avg time DS1',
            'avg time DS2',
            'time difference',
        ]);

        // Group results by document
        $documentGroups = [];
        foreach ($results as $result) {
            $documentGroups[$result['document_name']][] = $result;
        }

        // Process each document
        foreach ($documentGroups as $documentName => $documentResults) {
            // Filter valid sentences (exclude if time is 0 or > 10000 for either DS1 or DS2)
            $validResults = array_filter($documentResults, function ($result) {
                return $result['total_time_ds1'] > 0
                    && $result['total_time_ds2'] > 0
                    && $result['total_time_ds1'] <= 10000
                    && $result['total_time_ds2'] <= 10000;
            });

            $validCount = count($validResults);

            if ($validCount === 0) {
                // If no valid sentences, write row with zeros
                fputcsv($handle, [
                    $documentName,
                    0,
                    0,
                    0,
                    0,
                    0,
                ]);

                continue;
            }

            // Calculate average sentence length
            $totalLength = array_sum(array_map(fn ($r) => mb_strlen($r['sentence_text']), $validResults));
            $avgLength = round($totalLength / $validCount, 2);

            // Calculate average times
            $avgTimeDS1 = array_sum(array_column($validResults, 'total_time_ds1')) / $validCount;
            $avgTimeDS2 = array_sum(array_column($validResults, 'total_time_ds2')) / $validCount;

            // Calculate time difference
            $timeDifference = $avgTimeDS1 - $avgTimeDS2;

            // Write row
            fputcsv($handle, [
                $documentName,
                $validCount,
                $avgLength,
                round($avgTimeDS1, 2),
                round($avgTimeDS2, 2),
                round($timeDifference, 2),
            ]);

            // Display filtering info if verbose
            if ($this->option('verbose')) {
                $totalCount = count($documentResults);
                $filtered = $totalCount - $validCount;
                if ($filtered > 0) {
                    $this->line("  {$documentName}: {$validCount}/{$totalCount} valid sentences (filtered {$filtered})");
                }
            }
        }

        fclose($handle);
    }

    /**
     * Format seconds into human-readable time
     */
    protected function formatTime(float $seconds): string
    {
        $isNegative = $seconds < 0;
        $absSeconds = abs($seconds);

        if ($absSeconds < 60) {
            return ($isNegative ? '-' : '').round($absSeconds, 1).'s';
        }

        $minutes = floor($absSeconds / 60);
        $remainingSeconds = $absSeconds - ($minutes * 60);

        if ($minutes < 60) {
            return sprintf('%s%dm %ds', $isNegative ? '-' : '', $minutes, round($remainingSeconds));
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes - ($hours * 60);

        return sprintf('%s%dh %dm %ds', $isNegative ? '-' : '', $hours, $remainingMinutes, round($remainingSeconds));
    }
}
