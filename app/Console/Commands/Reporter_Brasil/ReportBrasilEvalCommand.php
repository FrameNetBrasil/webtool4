<?php

namespace App\Console\Commands\Reporter_Brasil;

use App\Database\Criteria;
use Illuminate\Console\Command;

class ReportBrasilEvalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'annotation:report-brasil-eval
                            {--output= : Output CSV file path for detailed results}
                            {--summary= : Output CSV file path for document summary only}
                            {--fe-summary= : Output CSV file path for Frame Element annotations summary}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Reporter Brasil evaluation documents and retrieve their annotation sets';

    private array $stats = [
        'processed' => 0,
        'found' => 0,
        'not_found' => 0,
        'total_annotation_sets' => 0,
        'total_annotations' => 0,
        'total_fe_annotations' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📊 Reporter Brasil Evaluation - Annotation Sets Report');
        $this->newLine();

        $csvPath = app_path('Console/Commands/Annotation/report_brasil_eval.csv');

        if (! file_exists($csvPath)) {
            $this->error("❌ CSV file not found: {$csvPath}");

            return self::FAILURE;
        }

        $this->info('📂 Reading CSV file...');
        $documentNames = $this->readCsvFile($csvPath);

        if (empty($documentNames)) {
            $this->error('❌ No document names found in CSV file');

            return self::FAILURE;
        }

        $this->info('📄 Found '.count($documentNames).' document names to process');
        $this->newLine();

        $allResults = [];
        $progressBar = $this->output->createProgressBar(count($documentNames));
        $progressBar->start();

        foreach ($documentNames as $documentName) {
            $result = $this->processDocument($documentName);
            if ($result) {
                $allResults[] = $result;
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->newLine();

        // Display results
        $this->displayResults($allResults);

        // Display statistics
        $this->displayStatistics();

        // Export detailed results to CSV if requested
        $outputPath = $this->option('output');
        if ($outputPath) {
            $fullOutputPath = $this->resolveOutputPath($outputPath);
            $this->exportToCSV($allResults, $fullOutputPath);
            $this->info("✓ Detailed results exported to: {$fullOutputPath}");
        }

        // Export summary to CSV if requested
        $summaryPath = $this->option('summary');
        if ($summaryPath) {
            $fullSummaryPath = $this->resolveOutputPath($summaryPath);
            $this->exportSummaryToCSV($allResults, $fullSummaryPath);
            $this->info("✓ Document summary exported to: {$fullSummaryPath}");
        }

        // Export FE summary to CSV if requested
        $feSummaryPath = $this->option('fe-summary');
        if ($feSummaryPath) {
            $fullFeSummaryPath = $this->resolveOutputPath($feSummaryPath);
            $this->exportFESummaryToCSV($allResults, $fullFeSummaryPath);
            $this->info("✓ Frame Element summary exported to: {$fullFeSummaryPath}");
        }

        return self::SUCCESS;
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
    private function processDocument(string $documentName): ?array
    {
        $this->stats['processed']++;

        // Lookup document by name with idLanguage = 1
        $document = Criteria::table('view_document')
            ->select('idDocument', 'name', 'corpusName', 'description')
            ->where('name', '=', $documentName)
            ->where('idLanguage', '=', 1)
            ->where('idCorpus', '=', 228)
            ->first();

        if (! $document) {
            $this->stats['not_found']++;
            if ($this->option('verbose')) {
                $this->line("⚠️  Document not found: {$documentName}");
            }

            return null;
        }

        $this->stats['found']++;

        // Get annotation sets grouped by status for this document
        $annotationSetsByStatus = Criteria::table('view_annotationset')
            ->selectRaw('status, COUNT(*) as count')
            ->where('idDocument', '=', $document->idDocument)
            ->groupBy('status')
            ->get()
            ->keyBy('status')
            ->all();

        // Get FE annotations grouped by timeline operation and user type for this document
        // Note: view_annotation_text_fe has multiple rows per annotation (one per language)
        // So we count all rows to get the total FE annotation count
        $feAnnotationsByOperation = Criteria::table('view_annotation_text_fe as fe')
            ->join('view_annotationset as vas', 'fe.idAnnotationSet', '=', 'vas.idAnnotationSet')
            ->leftJoin('timeline as tl', function ($join) {
                $join->on('tl.id', '=', 'fe.idAnnotation')
                    ->where('tl.tableName', '=', 'annotation');
            })
            ->where('vas.idDocument', '=', $document->idDocument)
            ->selectRaw('
                COALESCE(tl.operation, "C") as operation,
                COUNT(*) as count,
                SUM(CASE WHEN COALESCE(tl.idUser, 611) = 611 THEN 1 ELSE 0 END) as automatic_count,
                SUM(CASE WHEN COALESCE(tl.idUser, 611) <> 611 THEN 1 ELSE 0 END) as human_count
            ')
            ->groupBy('operation')
            ->get()
            ->keyBy('operation')
            ->all();

        // Get all annotation sets for detailed processing
        $annotationSets = Criteria::table('view_annotationset')
            ->where('idDocument', '=', $document->idDocument)
            ->orderBy('idAnnotationSet')
            ->get()
            ->all();

        $annotationSetCount = count($annotationSets);
        $this->stats['total_annotation_sets'] += $annotationSetCount;

        // Get annotations for each annotation set
        $totalAnnotations = 0;
        $totalFeAnnotations = 0;
        foreach ($annotationSets as $annotationSet) {
            $annotations = Criteria::table('view_annotation_text_target')
                ->where('idAnnotationSet', '=', $annotationSet->idAnnotationSet)
                ->orderBy('idAnnotation')
                ->get()
                ->all();

            $feAnnotations = Criteria::table('view_annotation_text_fe')
                ->where('idAnnotationSet', '=', $annotationSet->idAnnotationSet)
                ->orderBy('idAnnotation')
                ->get()
                ->all();

            $annotationSet->annotations = $annotations;
            $annotationSet->annotation_count = count($annotations);
            $annotationSet->fe_annotations = $feAnnotations;
            $annotationSet->fe_annotation_count = count($feAnnotations);
            $totalAnnotations += count($annotations);
            $totalFeAnnotations += count($feAnnotations);
        }

        $this->stats['total_annotations'] += $totalAnnotations;
        $this->stats['total_fe_annotations'] += $totalFeAnnotations;

        if ($this->option('verbose')) {
            $statusSummary = collect($annotationSetsByStatus)->map(fn ($s) => "{$s->status}:{$s->count}")->implode(', ');
            $this->line("✓ {$documentName}: {$annotationSetCount} AS ({$statusSummary}), {$totalAnnotations} annotations");
        }

        return [
            'document_name' => $document->name,
            'idDocument' => $document->idDocument,
            'corpus' => $document->corpusName ?? '',
            'description' => $document->description ?? '',
            'annotation_set_count' => $annotationSetCount,
            'total_annotations' => $totalAnnotations,
            'total_fe_annotations' => $totalFeAnnotations,
            'status_counts' => $annotationSetsByStatus,
            'fe_operations' => $feAnnotationsByOperation,
            'annotation_sets' => $annotationSets,
        ];
    }

    /**
     * Display results in formatted tables
     */
    private function displayResults(array $results): void
    {
        if (empty($results)) {
            $this->warn('No results to display');

            return;
        }

        $this->info('=== Document Summary ===');
        $this->newLine();

        // Collect all unique statuses across all documents
        $allStatuses = [];
        foreach ($results as $result) {
            foreach ($result['status_counts'] as $status => $statusData) {
                if (! in_array($status, $allStatuses)) {
                    $allStatuses[] = $status;
                }
            }
        }
        sort($allStatuses);

        // Build table headers
        $headers = ['Document Name', 'ID', 'Corpus', 'Total AS'];
        foreach ($allStatuses as $status) {
            $headers[] = $status;
        }
        $headers[] = 'Annotations';

        // Build table data
        $summaryData = [];
        foreach ($results as $result) {
            $row = [
                $result['document_name'],
                $result['idDocument'],
                $result['corpus'],
                $result['annotation_set_count'],
            ];

            // Add count for each status
            foreach ($allStatuses as $status) {
                $row[] = $result['status_counts'][$status]->count ?? 0;
            }

            $row[] = $result['total_annotations'];
            $summaryData[] = $row;
        }

        $this->table($headers, $summaryData);

        // Show detailed annotation set information if verbose
        if ($this->option('verbose')) {
            $this->newLine();
            $this->info('=== Annotation Set Details ===');
            $this->newLine();

            foreach ($results as $result) {
                if ($result['annotation_set_count'] > 0) {
                    $this->line("<fg=cyan>Document: {$result['document_name']} (ID: {$result['idDocument']})</>");

                    $asData = [];
                    foreach ($result['annotation_sets'] as $as) {
                        $asData[] = [
                            $as->idAnnotationSet,
                            $as->status ?? 'N/A',
                            $as->idLU ?? '-',
                            $as->idConstruction ?? '-',
                            $as->idSentence ?? '-',
                            $as->annotation_count ?? 0,
                            $as->login ?? 'N/A',
                        ];
                    }

                    $this->table(
                        ['AS ID', 'Status', 'LU', 'Cxn', 'Sentence', 'Ann Count', 'User'],
                        $asData
                    );

                    // Show annotations for each annotation set
                    foreach ($result['annotation_sets'] as $as) {
                        if (! empty($as->annotations)) {
                            $this->line("  <fg=yellow>Annotations for AS {$as->idAnnotationSet}:</>");
                            $annData = [];
                            foreach ($as->annotations as $ann) {
                                $annData[] = [
                                    $ann->idAnnotation,
                                    $ann->name ?? 'N/A',
                                    $ann->layerTypeName ?? 'N/A',
                                    $ann->startChar ?? '-',
                                    $ann->endChar ?? '-',
                                ];
                            }
                            $this->table(
                                ['  Ann ID', '  Label', '  Layer Type', '  Start', '  End'],
                                $annData
                            );
                        }
                    }
                    $this->newLine();
                }
            }
        }
    }

    /**
     * Display statistics
     */
    private function displayStatistics(): void
    {
        $this->info('=== Statistics ===');
        $this->newLine();

        $avgAnnotationSets = $this->stats['found'] > 0
            ? round($this->stats['total_annotation_sets'] / $this->stats['found'], 2)
            : 0;

        $avgAnnotations = $this->stats['found'] > 0
            ? round($this->stats['total_annotations'] / $this->stats['found'], 2)
            : 0;

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Processed', $this->stats['processed']],
                ['Documents Found', $this->stats['found']],
                ['Documents Not Found', $this->stats['not_found']],
                ['Total Annotation Sets', $this->stats['total_annotation_sets']],
                ['Avg Annotation Sets per Document', $avgAnnotationSets],
                ['Total Annotations', $this->stats['total_annotations']],
                ['Avg Annotations per Document', $avgAnnotations],
            ]
        );
    }

    /**
     * Export results to CSV
     */
    private function exportToCSV(array $results, string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        // Collect all unique statuses
        $allStatuses = [];
        foreach ($results as $result) {
            foreach ($result['status_counts'] as $status => $statusData) {
                if (! in_array($status, $allStatuses)) {
                    $allStatuses[] = $status;
                }
            }
        }
        sort($allStatuses);

        // Write header with status columns
        $header = [
            'Document Name',
            'Document ID',
            'Corpus',
            'Total AS Count',
        ];
        foreach ($allStatuses as $status) {
            $header[] = "Status {$status} Count";
        }
        $header = array_merge($header, [
            'Total Annotations',
            'AS ID',
            'AS Status',
            'LU ID',
            'Construction ID',
            'Sentence ID',
            'User Login',
            'Annotation Count',
            'Annotation ID',
            'Annotation Label',
            'Layer Type',
            'Start Char',
            'End Char',
        ]);
        fputcsv($handle, $header);

        // Write data
        foreach ($results as $result) {
            // Prepare status counts for this document
            $statusCounts = [];
            foreach ($allStatuses as $status) {
                $statusCounts[] = $result['status_counts'][$status]->count ?? 0;
            }

            if ($result['annotation_set_count'] > 0) {
                foreach ($result['annotation_sets'] as $as) {
                    if (! empty($as->annotations)) {
                        foreach ($as->annotations as $ann) {
                            $row = [
                                $result['document_name'],
                                $result['idDocument'],
                                $result['corpus'],
                                $result['annotation_set_count'],
                            ];
                            $row = array_merge($row, $statusCounts);
                            $row = array_merge($row, [
                                $result['total_annotations'],
                                $as->idAnnotationSet ?? '',
                                $as->status ?? '',
                                $as->idLU ?? '',
                                $as->idConstruction ?? '',
                                $as->idSentence ?? '',
                                $as->login ?? '',
                                $as->annotation_count ?? 0,
                                $ann->idAnnotation ?? '',
                                $ann->name ?? '',
                                $ann->layerTypeName ?? '',
                                $ann->startChar ?? '',
                                $ann->endChar ?? '',
                            ]);
                            fputcsv($handle, $row);
                        }
                    } else {
                        // Write AS row without annotations
                        $row = [
                            $result['document_name'],
                            $result['idDocument'],
                            $result['corpus'],
                            $result['annotation_set_count'],
                        ];
                        $row = array_merge($row, $statusCounts);
                        $row = array_merge($row, [
                            $result['total_annotations'],
                            $as->idAnnotationSet ?? '',
                            $as->status ?? '',
                            $as->idLU ?? '',
                            $as->idConstruction ?? '',
                            $as->idSentence ?? '',
                            $as->login ?? '',
                            0,
                            '',
                            '',
                            '',
                            '',
                            '',
                        ]);
                        fputcsv($handle, $row);
                    }
                }
            } else {
                // Write document row even if no annotation sets
                $row = [
                    $result['document_name'],
                    $result['idDocument'],
                    $result['corpus'],
                    $result['annotation_set_count'],
                ];
                $row = array_merge($row, $statusCounts);
                $row = array_merge($row, [
                    $result['total_annotations'],
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    0,
                    '',
                    '',
                    '',
                    '',
                    '',
                ]);
                fputcsv($handle, $row);
            }
        }

        fclose($handle);
    }

    /**
     * Export document summary to CSV
     */
    private function exportSummaryToCSV(array $results, string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        // Collect all unique statuses
        $allStatuses = [];
        foreach ($results as $result) {
            foreach ($result['status_counts'] as $status => $statusData) {
                if (! in_array($status, $allStatuses)) {
                    $allStatuses[] = $status;
                }
            }
        }
        sort($allStatuses);

        // Build header
        $header = [
            'Document Name',
            'Document ID',
            'Corpus',
            'Total AS Count',
        ];
        foreach ($allStatuses as $status) {
            $header[] = "Status {$status} Count";
        }
        $header[] = 'Total Annotations';

        fputcsv($handle, $header);

        // Write summary data only (one row per document)
        foreach ($results as $result) {
            $row = [
                $result['document_name'],
                $result['idDocument'],
                $result['corpus'],
                $result['annotation_set_count'],
            ];

            // Add count for each status
            foreach ($allStatuses as $status) {
                $row[] = $result['status_counts'][$status]->count ?? 0;
            }

            $row[] = $result['total_annotations'];

            fputcsv($handle, $row);
        }

        fclose($handle);
    }

    /**
     * Export Frame Element annotations summary to CSV
     */
    private function exportFESummaryToCSV(array $results, string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        // Use fixed operations from timeline (C=Create, U=Update, D=Delete)
        $allOperations = ['C', 'U', 'D'];

        // Build header - with timeline operations and automatic/human counts
        $header = [
            'Document Name',
            'Document ID',
            'Corpus',
            'Total AS Count',
        ];
        foreach ($allOperations as $operation) {
            $header[] = "Operation {$operation} Count";
            $header[] = "Operation {$operation} Automatic";
            $header[] = "Operation {$operation} Human";
        }
        $header[] = 'Total Annotations';

        fputcsv($handle, $header);

        // Write summary data only (one row per document) with FE annotation counts
        foreach ($results as $result) {
            $row = [
                $result['document_name'],
                $result['idDocument'],
                $result['corpus'],
                $result['annotation_set_count'],
            ];

            // Add count for each operation from timeline with automatic/human breakdown
            foreach ($allOperations as $operation) {
                $operationData = $result['fe_operations'][$operation] ?? null;
                $row[] = $operationData->count ?? 0;
                $row[] = $operationData->automatic_count ?? 0;
                $row[] = $operationData->human_count ?? 0;
            }

            // Use FE annotations count instead of target annotations
            $row[] = $result['total_fe_annotations'];

            fputcsv($handle, $row);
        }

        fclose($handle);
    }
}
