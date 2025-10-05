<?php

namespace App\Console\Commands\Daisy;

use App\Repositories\Daisy;
use Illuminate\Console\Command;

class SpreadActivation extends Command
{
    protected $signature = 'daisy:spread-activation {idLemma : The Lemma ID to start activation from} {--max-iterations=100 : Maximum number of iterations} {--threshold=0.001 : Activation threshold} {--show-steps : Show detailed propagation steps}';

    protected $description = 'Spread activation through the Daisy graph starting from all Lexical Units related to a Lemma';

    private array $currentActivations = [];

    private array $nextActivations = [];

    private array $allActivations = [];

    private array $visitCount = [];

    private array $linkCache = [];

    private int $maxIterations;

    private float $threshold;

    private int $totalPropagations = 0;

    private array $startingNodes = [];

    public function handle(): int
    {
        $idLemma = (int) $this->argument('idLemma');
        $this->maxIterations = (int) $this->option('max-iterations');
        $this->threshold = (float) $this->option('threshold');

        $this->info('🌼 Daisy Spread Activation');
        $this->newLine();

        // Find all LUs related to this lemma
        $lus = $this->findLUsByLemma($idLemma);
        if (empty($lus)) {
            $this->error("❌ No Lexical Units found for idLemma={$idLemma}");

            return self::FAILURE;
        }

        $this->info('Found '.count($lus)." Lexical Unit(s) for lemma ID {$idLemma}:");
        foreach ($lus as $lu) {
            $this->line("  • {$lu->name} (idLU: {$lu->idLU}, Frame: {$lu->frameName})");
        }
        $this->newLine();

        // Find corresponding daisy nodes
        $luNodes = $this->findLUNodes($lus);
        if (empty($luNodes)) {
            $this->error('❌ No corresponding nodes found in daisy_node table');

            return self::FAILURE;
        }

        $this->info('Starting from '.count($luNodes).' LU node(s):');
        foreach ($luNodes as $node) {
            $this->line("  • {$node->name} (Node ID: {$node->idDaisyNode})");
            $this->startingNodes[] = $node->idDaisyNode;
        }
        $this->info("Threshold: {$this->threshold}");
        $this->info("Max iterations: {$this->maxIterations}");
        $this->newLine();

        // Initialize all starting nodes with activation 10.0 (max value in 0-10 range)
        foreach ($luNodes as $node) {
            $this->currentActivations[$node->idDaisyNode] = 10.0;
            $this->allActivations[$node->idDaisyNode] = 10.0;
            $this->visitCount[$node->idDaisyNode] = 0;
        }

        // Run iterations
        $this->info('🔄 Beginning spread activation...');
        $this->newLine();

        $iteration = 0;
        while ($iteration < $this->maxIterations) {
            $iteration++;

            if ($this->option('show-steps')) {
                $this->line("--- Iteration {$iteration} ---");
            }

            // Initialize next iteration activations
            $this->nextActivations = [];

            // Count active nodes
            $activeNodes = 0;

            // Process all active nodes from current iteration
            foreach ($this->currentActivations as $nodeId => $activation) {
                // Skip nodes below threshold
                if ($activation < $this->threshold) {
                    continue;
                }

                // Skip nodes that have been visited 3 times (as per paper)
                if (isset($this->visitCount[$nodeId]) && $this->visitCount[$nodeId] >= 3) {
                    continue;
                }

                $activeNodes++;

                // Calculate output using logistic function (Equation 6.3)
                $output = $this->calculateOutput($activation);

                if ($this->option('show-steps')) {
                    $this->line("  Node {$nodeId}: activation={$activation}, output={$output}");
                }

                // Get outgoing links (with caching)
                $links = $this->getLinksForNode($nodeId);

                // Propagate output to target nodes (Equation 6.2)
                foreach ($links as $link) {
                    $targetNodeId = $link->idDaisyNodeTarget;
                    $linkWeight = (float) $link->value;

                    // Accumulate activation in next iteration
                    if (! isset($this->nextActivations[$targetNodeId])) {
                        $this->nextActivations[$targetNodeId] = 0.0;
                    }

                    $propagation = $output * $linkWeight;
                    $this->nextActivations[$targetNodeId] += $propagation;

                    if ($this->option('show-steps')) {
                        $this->line("    → Node {$targetNodeId}: +{$propagation} (link {$link->type})");
                    }

                    $this->totalPropagations++;
                }

                // Increment visit count
                if (! isset($this->visitCount[$nodeId])) {
                    $this->visitCount[$nodeId] = 0;
                }
                $this->visitCount[$nodeId]++;
            }

            // Clear link cache periodically to free memory
            if ($iteration % 10 == 0) {
                $this->linkCache = [];
                gc_collect_cycles();
            }

            // Check stop condition: no active nodes
            if ($activeNodes === 0) {
                if ($this->option('show-steps')) {
                    $this->line('No active nodes. Stopping.');
                }
                break;
            }

            // Update activations for next iteration (synchronous)
            // Apply logistic function to normalize incoming activations
            foreach ($this->nextActivations as $nodeId => $activation) {
                // Normalize activation using a scaling factor to prevent explosion
                // Apply sigmoid-like normalization scaled to 0-10 range
                // A_normalized = 10 * (A / (1 + A))
                $this->currentActivations[$nodeId] = 10 * ($activation / (1 + $activation));
            }

            // Track all activations (keep highest value for each node)
            foreach ($this->currentActivations as $nodeId => $activation) {
                if (! isset($this->allActivations[$nodeId]) || $activation > $this->allActivations[$nodeId]) {
                    $this->allActivations[$nodeId] = $activation;
                }
            }

            if ($this->option('show-steps')) {
                $this->line("  Active nodes: {$activeNodes}");
                $this->newLine();
            }
        }

        $this->newLine();
        $this->info("✅ Spread activation completed after {$iteration} iteration(s)");
        $this->newLine();

        $this->displayResults();

        return self::SUCCESS;
    }

    private function calculateOutput(float $activation): float
    {
        // Equation 6.3: Output function with threshold
        if ($activation < $this->threshold) {
            return 0.0;
        }

        // Logistic function: (1 - exp(5 * (-A))) / (1 + exp(-A))
        return (1 - exp(5 * (-$activation))) / (1 + exp(-$activation));
    }

    private function getLinksForNode(int $nodeId): array
    {
        // Check cache first
        if (! isset($this->linkCache[$nodeId])) {
            $this->linkCache[$nodeId] = Daisy::getLinksByNode($nodeId, 'source');
        }

        return $this->linkCache[$nodeId];
    }

    private function findLUsByLemma(int $idLemma): array
    {
        return \App\Database\Criteria::table('lu')
            ->join('frame', 'lu.idFrame', '=', 'frame.idFrame')
            ->select('lu.idLU', 'lu.name', 'lu.idFrame', 'frame.entry as frameName')
            ->where('lu.idLemma', $idLemma)
            ->orderBy('lu.name')
            ->all();
    }

    private function findLUNodes(array $lus): array
    {
        $idLUs = array_map(fn ($lu) => $lu->idLU, $lus);

        return \App\Database\Criteria::table('daisy_node')
            ->where('type', 'LU')
            ->whereIn('idLU', $idLUs)
            ->orderBy('name')
            ->all();
    }

    private function displayResults(): void
    {
        // Sort by activation value (descending)
        arsort($this->allActivations);

        $this->info('📊 Activation Results:');
        $this->line('Total nodes activated: '.count($this->allActivations));
        $this->line("Total propagations: {$this->totalPropagations}");
        $this->newLine();

        // Prepare table data
        $tableData = [];
        foreach ($this->allActivations as $nodeId => $activation) {
            $node = Daisy::getNodeById($nodeId);
            $visits = $this->visitCount[$nodeId] ?? 0;

            $tableData[] = [
                $nodeId,
                $node->type ?? 'N/A',
                $node->name ?? 'N/A',
                number_format($activation, 6),
                $visits,
            ];

            // Limit table output to top 100 for readability
            if (count($tableData) >= 100) {
                break;
            }
        }

        $this->table(
            ['Node ID', 'Type', 'Name', 'Activation', 'Visits'],
            $tableData
        );

        if (count($this->allActivations) > 100) {
            $this->newLine();
            $this->warn('Showing top 100 nodes. Total activated: '.count($this->allActivations));
        }
    }
}
