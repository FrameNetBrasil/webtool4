<?php

namespace App\Console\Commands\Reporter_Brasil;

use App\Database\Criteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReporterBrasilMultiCorpusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reporter-brasil:multi-corpus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze Reporter Brasil documents across multiple corpora';

    private array $stats = [
        'total_documents' => 0,
        'found_in_227' => 0,
        'found_in_228' => 0,
        'found_in_other' => 0,
        'not_found' => 0,
    ];

    private array $results = [];

    private array $frameMinimumFEs = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Reporter Brasil Multi-Corpus Analysis...');
        $this->newLine();

        // Load frame minimum FEs mapping
        $this->loadFrameMinimumFEs();

        // Read CSV file
        $documentNames = $this->readCsvFile();

        if (empty($documentNames)) {
            $this->error('No document names found in CSV file.');
            return self::FAILURE;
        }

        $this->stats['total_documents'] = count($documentNames);
        $this->info("Processing {$this->stats['total_documents']} documents...");
        $this->newLine();

        // Process each document
        $progressBar = $this->output->createProgressBar(count($documentNames));
        $progressBar->start();

        foreach ($documentNames as $documentName) {
            $this->processDocument($documentName);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Generate CSV output
        $this->generateCsvOutput();

        // Display statistics
        $this->displayStatistics();

        return self::SUCCESS;
    }

    private function loadFrameMinimumFEs(): void
    {
        $csvPath = $this->resolveInputPath('frame_minimum_fes.csv');

        if (!file_exists($csvPath)) {
            $this->warn("Frame minimum FEs file not found at: {$csvPath}");
            $this->warn("Minimum FE sums will be 0.");
            return;
        }

        $handle = fopen($csvPath, 'r');

        // Skip header
        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) >= 2) {
                $idFrame = (int) $row[0];
                $minimumFE = (int) $row[1];
                $this->frameMinimumFEs[$idFrame] = $minimumFE;
            }
        }

        fclose($handle);

        $this->info("Loaded " . count($this->frameMinimumFEs) . " frame minimum FE mappings.");
        $this->newLine();
    }

    private function readCsvFile(): array
    {
        $csvPath = $this->resolveInputPath('reporter_brasil_eval.csv');

        if (!file_exists($csvPath)) {
            $this->error("CSV file not found at: {$csvPath}");
            return [];
        }

        $documentNames = [];
        $lines = file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $documentName = trim($line);
            if (!empty($documentName)) {
                $documentNames[] = $documentName;
            }
        }

        return $documentNames;
    }

    private function processDocument(string $documentName): void
    {
        // Query corpus 227
        $doc227 = Criteria::table('view_document')
            ->select('idDocument', 'name', 'corpusName', 'description')
            ->where('name', '=', $documentName)
            ->where('idLanguage', '=', 1)
            ->where('idCorpus', '=', 227)
            ->first();

        // Query corpus 228
        $doc228 = Criteria::table('view_document')
            ->select('idDocument', 'name', 'corpusName', 'description')
            ->where('name', '=', $documentName)
            ->where('idLanguage', '=', 1)
            ->where('idCorpus', '=', 228)
            ->first();

        // Query other corpus (NOT IN 227, 228, 229)
        $docOther = Criteria::table('view_document')
            ->select('idDocument', 'name', 'corpusName', 'description')
            ->where('name', '=', $documentName)
            ->where('idLanguage', '=', 1)
            ->whereNotIn('idCorpus', [227, 228, 229])
            ->first();

        // Update statistics
        if ($doc227) {
            $this->stats['found_in_227']++;
        }
        if ($doc228) {
            $this->stats['found_in_228']++;
        }
        if ($docOther) {
            $this->stats['found_in_other']++;
        }
        if (!$doc227 && !$doc228 && !$docOther) {
            $this->stats['not_found']++;
        }

        // Get FE metrics for each corpus
        $metrics227 = $doc227 ? $this->getFEMetrics($documentName, 227) : ['total_fes' => 0, 'core_fes' => 0, 'ni_fes' => 0];
        $metrics228 = $doc228 ? $this->getFEMetrics($documentName, 228) : ['total_fes' => 0, 'core_fes' => 0, 'ni_fes' => 0];
        $metricsOther = $docOther ? $this->getFEMetrics($documentName, null, true) : ['total_fes' => 0, 'core_fes' => 0, 'ni_fes' => 0];

        // Get minimum FE sum for each corpus
        $minFESum227 = $doc227 ? $this->getMinimumFESum($documentName, 227) : 0;
        $minFESum228 = $doc228 ? $this->getMinimumFESum($documentName, 228) : 0;
        $minFESumOther = $docOther ? $this->getMinimumFESum($documentName, null, true) : 0;

        // Calculate percentage of minimum FE annotated (core_fes / min_fe_sum * 100, max 100)
        $percent227 = $minFESum227 > 0 ? min(100, round(($metrics227['core_fes'] / $minFESum227) * 100, 2)) : 0;
        $percent228 = $minFESum228 > 0 ? min(100, round(($metrics228['core_fes'] / $minFESum228) * 100, 2)) : 0;
        $percentOther = $minFESumOther > 0 ? min(100, round(($metricsOther['core_fes'] / $minFESumOther) * 100, 2)) : 0;

        // Store results
        $this->results[] = [
            'documentName' => $documentName,
            'corpus227_id' => $doc227->idDocument ?? null,
            'corpus227_name' => $doc227->corpusName ?? null,
            'corpus227_total_fes' => $metrics227['total_fes'],
            'corpus227_core_fes' => $metrics227['core_fes'],
            'corpus227_min_fe_sum' => $minFESum227,
            'corpus227_percent' => $percent227,
            'corpus228_id' => $doc228->idDocument ?? null,
            'corpus228_name' => $doc228->corpusName ?? null,
            'corpus228_total_fes' => $metrics228['total_fes'],
            'corpus228_core_fes' => $metrics228['core_fes'],
            'corpus228_min_fe_sum' => $minFESum228,
            'corpus228_percent' => $percent228,
            'other_corpus_id' => $docOther->idDocument ?? null,
            'other_corpus_name' => $docOther->corpusName ?? null,
            'other_total_fes' => $metricsOther['total_fes'],
            'other_core_fes' => $metricsOther['core_fes'],
            'other_min_fe_sum' => $minFESumOther,
            'other_percent' => $percentOther,
        ];
    }

    private function getFEMetrics(string $documentName, ?int $corpusId = null, bool $isOther = false): array
    {
        // Build base query for document
        $baseQuery = function() use ($documentName, $corpusId, $isOther) {
            $query = Criteria::table('view_annotation_text_fe as afe1')
                ->join('view_annotationset as a', 'afe1.idAnnotationSet', '=', 'a.idAnnotationSet')
                ->join('view_document as d', 'a.idDocument', '=', 'd.idDocument')
                ->where('d.name', '=', $documentName)
                ->where('d.idLanguage', '=', 1)
                ->where('afe1.idLanguage', '=', 1);

            if ($isOther) {
                $query->whereNotIn('d.idCorpus', [227, 228, 229]);
            } elseif ($corpusId !== null) {
                $query->where('d.idCorpus', '=', $corpusId);
            }

            return $query;
        };

        // Total number of FEs
        $totalFEs = $baseQuery()
            ->selectRaw('count(afe1.idFrameElement) as n')
            ->first()
            ->n ?? 0;

        // Total number of FE Core
        $coreFEs = $baseQuery()
            ->whereIn('afe1.coreType', ['cty_core', 'cty_core-unexpressed'])
            ->selectRaw('count(afe1.idFrameElement) as n')
            ->first()
            ->n ?? 0;

        // Total Number of NI (not idInstantiationType = 12)
        $niFEs = $baseQuery()
            ->where('afe1.idInstantiationType', '<>', 12)
            ->selectRaw('count(afe1.idFrameElement) as n')
            ->first()
            ->n ?? 0;

        return [
            'total_fes' => $totalFEs,
            'core_fes' => $coreFEs,
            'ni_fes' => $niFEs,
        ];
    }

    private function getMinimumFESum(string $documentName, ?int $corpusId = null, bool $isOther = false): int
    {
        // Build query for document annotationsets with frames
        $query = Criteria::table('view_annotationset as a')
            ->join('view_document as d', 'a.idDocument', '=', 'd.idDocument')
            ->join('lu as l', 'a.idLU', '=', 'l.idLU')
            ->where('d.name', '=', $documentName)
            ->where('d.idLanguage', '=', 1)
            ->whereNotNull('l.idFrame')
            ->where('l.idFrame', '>', 0);

        if ($isOther) {
            $query->whereNotIn('d.idCorpus', [227, 228, 229]);
        } elseif ($corpusId !== null) {
            $query->where('d.idCorpus', '=', $corpusId);
        }

        $annotationSets = $query->select('a.idAnnotationSet', 'l.idFrame')->get();

        $sum = 0;
        foreach ($annotationSets as $as) {
            $idFrame = $as->idFrame;
            $minimumFE = $this->frameMinimumFEs[$idFrame] ?? 0;
            $sum += $minimumFE;
        }

        return $sum;
    }

    private function generateCsvOutput(): void
    {
        $outputPath = $this->resolveOutputPath('reporter_brasil_multi_corpus.csv');

        $fp = fopen($outputPath, 'w');

        // Write header - 13 columns total
        fputcsv($fp, [
            'documentName',
            'other_total_fes',
            'other_core_fes',
            'other_min_fe_sum',
            'other_percent',
            'corpus227_total_fes',
            'corpus227_core_fes',
            'corpus227_min_fe_sum',
            'corpus227_percent',
            'corpus228_total_fes',
            'corpus228_core_fes',
            'corpus228_min_fe_sum',
            'corpus228_percent',
        ]);

        // Sort results by document name
        usort($this->results, function($a, $b) {
            return strcmp($a['documentName'], $b['documentName']);
        });

        // Write data rows
        foreach ($this->results as $result) {
            fputcsv($fp, [
                $result['documentName'],
                $result['other_total_fes'],
                $result['other_core_fes'],
                $result['other_min_fe_sum'],
                $result['other_percent'],
                $result['corpus227_total_fes'],
                $result['corpus227_core_fes'],
                $result['corpus227_min_fe_sum'],
                $result['corpus227_percent'],
                $result['corpus228_total_fes'],
                $result['corpus228_core_fes'],
                $result['corpus228_min_fe_sum'],
                $result['corpus228_percent'],
            ]);
        }

        fclose($fp);

        $this->info("CSV output saved to: {$outputPath}");
        $this->newLine();
    }

    private function displayStatistics(): void
    {
        $this->info('=== Statistics ===');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Documents', $this->stats['total_documents']],
                ['Found in Corpus 227', $this->stats['found_in_227']],
                ['Found in Corpus 228', $this->stats['found_in_228']],
                ['Found in Other Corpus', $this->stats['found_in_other']],
                ['Not Found', $this->stats['not_found']],
            ]
        );
    }

    private function resolveInputPath(string $filename): string
    {
        return __DIR__ . '/' . $filename;
    }

    private function resolveOutputPath(string $filename): string
    {
        return __DIR__ . '/' . $filename;
    }
}
