<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculateTTestDiversidadeFrames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-t-test-diversidade-frames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate paired t-test for Diversidade de Frames data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputFile = base_path('app/Console/Commands/Reporter_Brasil/test/Dados para avaliação paper LREC 2026 Repórter Brasil - Diversidade de Frames.csv');
        $outputFile = base_path('app/Console/Commands/Reporter_Brasil/test/Dados para avaliação paper LREC 2026 Repórter Brasil - Diversidade de Frames - with t-test.csv');

        if (!file_exists($inputFile)) {
            $this->error("Input file not found: {$inputFile}");
            return 1;
        }

        // Read CSV file
        $rows = [];
        if (($handle = fopen($inputFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        // Extract header and data rows (lines 2-13, which are indices 1-12)
        $header = $rows[0];
        $dataRows = array_slice($rows, 1, 12);

        // Extract column data (columns C, H, M are indices 2, 7, 12; E, J, O are indices 4, 9, 14; F, K, P are indices 5, 10, 15)
        $columnC = [];
        $columnH = [];
        $columnM = [];
        $columnE = [];
        $columnJ = [];
        $columnO = [];
        $columnF = [];
        $columnK = [];
        $columnP = [];

        foreach ($dataRows as $row) {
            $columnC[] = $this->parseNumber($row[2]);
            $columnH[] = $this->parseNumber($row[7]);
            $columnM[] = $this->parseNumber($row[12]);
            $columnE[] = $this->parseNumber($row[4]);
            $columnJ[] = $this->parseNumber($row[9]);
            $columnO[] = $this->parseNumber($row[14]);
            $columnF[] = $this->parseNumber($row[5]);
            $columnK[] = $this->parseNumber($row[10]);
            $columnP[] = $this->parseNumber($row[15]);
        }

        // Calculate paired t-tests for #distinct_frames (C, H, M)
        $tTestCH = $this->calculatePairedTTest($columnC, $columnH);
        $tTestHM = $this->calculatePairedTTest($columnH, $columnM);
        $tTestCM = $this->calculatePairedTTest($columnC, $columnM);

        // Calculate paired t-tests for avg_frame_sentence (E, J, O)
        $tTestEJ = $this->calculatePairedTTest($columnE, $columnJ);
        $tTestJO = $this->calculatePairedTTest($columnJ, $columnO);
        $tTestEO = $this->calculatePairedTTest($columnE, $columnO);

        // Calculate paired t-tests for avg_fe_sentence (F, K, P)
        $tTestFK = $this->calculatePairedTTest($columnF, $columnK);
        $tTestKP = $this->calculatePairedTTest($columnK, $columnP);
        $tTestFP = $this->calculatePairedTTest($columnF, $columnP);

        // Display results
        $this->info('Paired T-Test Results:');
        $this->newLine();

        $this->info('=== #distinct_frames comparisons ===');
        $this->displayTTestResults('C vs H (#distinct_frames: Anno vs LOME)', $tTestCH);
        $this->displayTTestResults('H vs M (#distinct_frames: LOME vs LOMEEdt)', $tTestHM);
        $this->displayTTestResults('C vs M (#distinct_frames: Anno vs LOMEEdt)', $tTestCM);

        $this->info('=== avg_frame_sentence comparisons ===');
        $this->displayTTestResults('E vs J (avg_frame_sentence: Anno vs LOME)', $tTestEJ);
        $this->displayTTestResults('J vs O (avg_frame_sentence: LOME vs LOMEEdt)', $tTestJO);
        $this->displayTTestResults('E vs O (avg_frame_sentence: Anno vs LOMEEdt)', $tTestEO);

        $this->info('=== avg_fe_sentence comparisons ===');
        $this->displayTTestResults('F vs K (avg_fe_sentence: Anno vs LOME)', $tTestFK);
        $this->displayTTestResults('K vs P (avg_fe_sentence: LOME vs LOMEEdt)', $tTestKP);
        $this->displayTTestResults('F vs P (avg_fe_sentence: Anno vs LOMEEdt)', $tTestFP);

        // Add results to CSV
        $header[] = 't-statistic (C-H)';
        $header[] = 'p-value (C-H)';
        $header[] = 'df (C-H)';
        $header[] = 't-statistic (H-M)';
        $header[] = 'p-value (H-M)';
        $header[] = 'df (H-M)';
        $header[] = 't-statistic (C-M)';
        $header[] = 'p-value (C-M)';
        $header[] = 'df (C-M)';
        $header[] = 't-statistic (E-J)';
        $header[] = 'p-value (E-J)';
        $header[] = 'df (E-J)';
        $header[] = 't-statistic (J-O)';
        $header[] = 'p-value (J-O)';
        $header[] = 'df (J-O)';
        $header[] = 't-statistic (E-O)';
        $header[] = 'p-value (E-O)';
        $header[] = 'df (E-O)';
        $header[] = 't-statistic (F-K)';
        $header[] = 'p-value (F-K)';
        $header[] = 'df (F-K)';
        $header[] = 't-statistic (K-P)';
        $header[] = 'p-value (K-P)';
        $header[] = 'df (K-P)';
        $header[] = 't-statistic (F-P)';
        $header[] = 'p-value (F-P)';
        $header[] = 'df (F-P)';

        // Add t-test values to first data row only
        $rows[0] = $header;

        // Add summary row after the data
        $summaryRow = array_fill(0, count($rows[1]), '');
        $summaryRow[0] = 'T-Test Summary';
        $summaryRow[] = number_format($tTestCH['t'], 6);
        $summaryRow[] = number_format($tTestCH['p'], 6);
        $summaryRow[] = $tTestCH['df'];
        $summaryRow[] = number_format($tTestHM['t'], 6);
        $summaryRow[] = number_format($tTestHM['p'], 6);
        $summaryRow[] = $tTestHM['df'];
        $summaryRow[] = number_format($tTestCM['t'], 6);
        $summaryRow[] = number_format($tTestCM['p'], 6);
        $summaryRow[] = $tTestCM['df'];
        $summaryRow[] = number_format($tTestEJ['t'], 6);
        $summaryRow[] = number_format($tTestEJ['p'], 6);
        $summaryRow[] = $tTestEJ['df'];
        $summaryRow[] = number_format($tTestJO['t'], 6);
        $summaryRow[] = number_format($tTestJO['p'], 6);
        $summaryRow[] = $tTestJO['df'];
        $summaryRow[] = number_format($tTestEO['t'], 6);
        $summaryRow[] = number_format($tTestEO['p'], 6);
        $summaryRow[] = $tTestEO['df'];
        $summaryRow[] = number_format($tTestFK['t'], 6);
        $summaryRow[] = number_format($tTestFK['p'], 6);
        $summaryRow[] = $tTestFK['df'];
        $summaryRow[] = number_format($tTestKP['t'], 6);
        $summaryRow[] = number_format($tTestKP['p'], 6);
        $summaryRow[] = $tTestKP['df'];
        $summaryRow[] = number_format($tTestFP['t'], 6);
        $summaryRow[] = number_format($tTestFP['p'], 6);
        $summaryRow[] = $tTestFP['df'];

        $rows[] = $summaryRow;

        // Write to output file
        if (($handle = fopen($outputFile, 'w')) !== false) {
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }

        $this->info("Results written to: {$outputFile}");

        return 0;
    }

    private function parseNumber(string $value): float
    {
        // Replace comma with dot for decimal separator
        return (float) str_replace(',', '.', $value);
    }

    private function calculatePairedTTest(array $group1, array $group2): array
    {
        $n = count($group1);

        if ($n !== count($group2)) {
            throw new \Exception('Groups must have the same size for paired t-test');
        }

        // Calculate differences
        $differences = [];
        for ($i = 0; $i < $n; $i++) {
            $differences[] = $group1[$i] - $group2[$i];
        }

        // Calculate mean of differences
        $meanDiff = array_sum($differences) / $n;

        // Calculate standard deviation of differences
        $sumSquaredDiff = 0;
        foreach ($differences as $diff) {
            $sumSquaredDiff += pow($diff - $meanDiff, 2);
        }
        $stdDiff = sqrt($sumSquaredDiff / ($n - 1));

        // Calculate t-statistic
        $standardError = $stdDiff / sqrt($n);
        $tStatistic = $meanDiff / $standardError;

        // Degrees of freedom
        $df = $n - 1;

        // Calculate p-value (two-tailed)
        $pValue = $this->calculatePValue($tStatistic, $df);

        return [
            't' => $tStatistic,
            'p' => $pValue,
            'df' => $df,
            'mean_diff' => $meanDiff,
            'std_diff' => $stdDiff,
            'se' => $standardError,
        ];
    }

    private function calculatePValue(float $t, int $df): float
    {
        // Use the Student's t-distribution to calculate p-value
        // This is a two-tailed test
        $t = abs($t);

        // Using the incomplete beta function to calculate the cumulative distribution
        // P(T > |t|) for a two-tailed test
        $x = $df / ($df + $t * $t);
        $p = $this->incompleteBeta($x, $df / 2, 0.5);

        return $p;
    }

    private function incompleteBeta(float $x, float $a, float $b): float
    {
        // Incomplete beta function approximation
        // For the t-distribution, we use: I_x(a, b)

        if ($x <= 0.0) {
            return 0.0;
        }

        if ($x >= 1.0) {
            return 1.0;
        }

        // Use continued fraction approximation
        $bt = exp(
            $this->logGamma($a + $b) -
            $this->logGamma($a) -
            $this->logGamma($b) +
            $a * log($x) +
            $b * log(1.0 - $x)
        );

        if ($x < ($a + 1.0) / ($a + $b + 2.0)) {
            return $bt * $this->betaContinuedFraction($x, $a, $b) / $a;
        } else {
            return 1.0 - $bt * $this->betaContinuedFraction(1.0 - $x, $b, $a) / $b;
        }
    }

    private function betaContinuedFraction(float $x, float $a, float $b): float
    {
        $maxIterations = 100;
        $epsilon = 1.0e-10;

        $qab = $a + $b;
        $qap = $a + 1.0;
        $qam = $a - 1.0;
        $c = 1.0;
        $d = 1.0 - $qab * $x / $qap;

        if (abs($d) < $epsilon) {
            $d = $epsilon;
        }

        $d = 1.0 / $d;
        $h = $d;

        for ($m = 1; $m <= $maxIterations; $m++) {
            $m2 = 2 * $m;
            $aa = $m * ($b - $m) * $x / (($qam + $m2) * ($a + $m2));
            $d = 1.0 + $aa * $d;

            if (abs($d) < $epsilon) {
                $d = $epsilon;
            }

            $c = 1.0 + $aa / $c;

            if (abs($c) < $epsilon) {
                $c = $epsilon;
            }

            $d = 1.0 / $d;
            $h *= $d * $c;
            $aa = -($a + $m) * ($qab + $m) * $x / (($a + $m2) * ($qap + $m2));
            $d = 1.0 + $aa * $d;

            if (abs($d) < $epsilon) {
                $d = $epsilon;
            }

            $c = 1.0 + $aa / $c;

            if (abs($c) < $epsilon) {
                $c = $epsilon;
            }

            $d = 1.0 / $d;
            $del = $d * $c;
            $h *= $del;

            if (abs($del - 1.0) < $epsilon) {
                break;
            }
        }

        return $h;
    }

    private function logGamma(float $x): float
    {
        // Lanczos approximation for log(gamma(x))
        $coefficients = [
            76.18009172947146,
            -86.50532032941677,
            24.01409824083091,
            -1.231739572450155,
            0.1208650973866179e-2,
            -0.5395239384953e-5
        ];

        $y = $x;
        $tmp = $x + 5.5;
        $tmp -= ($x + 0.5) * log($tmp);
        $ser = 1.000000000190015;

        for ($j = 0; $j < 6; $j++) {
            $ser += $coefficients[$j] / ++$y;
        }

        return -$tmp + log(2.5066282746310005 * $ser / $x);
    }

    private function displayTTestResults(string $label, array $results): void
    {
        $this->line("<fg=cyan>{$label}</>");
        $this->line("  t-statistic: " . number_format($results['t'], 6));
        $this->line("  p-value:     " . number_format($results['p'], 6));
        $this->line("  df:          {$results['df']}");
        $this->line("  Mean diff:   " . number_format($results['mean_diff'], 6));
        $this->line("  Std diff:    " . number_format($results['std_diff'], 6));
        $this->line("  Std error:   " . number_format($results['se'], 6));

        if ($results['p'] < 0.001) {
            $this->line("  <fg=green>Significance: p < 0.001 (highly significant)</>");
        } elseif ($results['p'] < 0.01) {
            $this->line("  <fg=green>Significance: p < 0.01 (very significant)</>");
        } elseif ($results['p'] < 0.05) {
            $this->line("  <fg=yellow>Significance: p < 0.05 (significant)</>");
        } else {
            $this->line("  <fg=red>Significance: p >= 0.05 (not significant)</>");
        }

        $this->newLine();
    }
}
