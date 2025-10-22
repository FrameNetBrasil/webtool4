<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculateTTestFEsCoreAnotados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-t-test-f-es-core-anotados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate paired t-test for % de FEs Core Anotados data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $inputFile = base_path('app/Console/Commands/Reporter_Brasil/test/Dados para avaliação paper LREC 2026 Repórter Brasil - % de FEs Core Anotados.csv');
        $outputFile = base_path('app/Console/Commands/Reporter_Brasil/test/Dados para avaliação paper LREC 2026 Repórter Brasil - % de FEs Core Anotados - with t-test.csv');

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

        // Extract column data (columns E, I, M are indices 4, 8, 12 - anno_percent, lome_percent, lomeedt_percent)
        $columnE = [];
        $columnI = [];
        $columnM = [];

        foreach ($dataRows as $row) {
            $columnE[] = $this->parseNumber($row[4]);
            $columnI[] = $this->parseNumber($row[8]);
            $columnM[] = $this->parseNumber($row[12]);
        }

        // Calculate paired t-tests for % Core FEs annotated (E, I, M)
        $tTestEI = $this->calculatePairedTTest($columnE, $columnI);
        $tTestIM = $this->calculatePairedTTest($columnI, $columnM);
        $tTestEM = $this->calculatePairedTTest($columnE, $columnM);

        // Display results
        $this->info('Paired T-Test Results:');
        $this->newLine();

        $this->info('=== % Core FEs Annotated comparisons ===');
        $this->displayTTestResults('E vs I (anno_percent vs lome_percent)', $tTestEI);
        $this->displayTTestResults('I vs M (lome_percent vs lomeedt_percent)', $tTestIM);
        $this->displayTTestResults('E vs M (anno_percent vs lomeedt_percent)', $tTestEM);

        // Add results to CSV
        $header[] = 't-statistic (E-I)';
        $header[] = 'p-value (E-I)';
        $header[] = 'df (E-I)';
        $header[] = 't-statistic (I-M)';
        $header[] = 'p-value (I-M)';
        $header[] = 'df (I-M)';
        $header[] = 't-statistic (E-M)';
        $header[] = 'p-value (E-M)';
        $header[] = 'df (E-M)';

        // Add t-test values to first data row only
        $rows[0] = $header;

        // Add summary row after the data
        $summaryRow = array_fill(0, count($rows[1]), '');
        $summaryRow[0] = 'T-Test Summary';
        $summaryRow[] = number_format($tTestEI['t'], 6);
        $summaryRow[] = number_format($tTestEI['p'], 6);
        $summaryRow[] = $tTestEI['df'];
        $summaryRow[] = number_format($tTestIM['t'], 6);
        $summaryRow[] = number_format($tTestIM['p'], 6);
        $summaryRow[] = $tTestIM['df'];
        $summaryRow[] = number_format($tTestEM['t'], 6);
        $summaryRow[] = number_format($tTestEM['p'], 6);
        $summaryRow[] = $tTestEM['df'];

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
