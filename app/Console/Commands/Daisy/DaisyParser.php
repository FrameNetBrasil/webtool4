<?php

namespace App\Console\Commands\Daisy;

use App\Repositories\Daisy;
use App\Services\Trankit\TrankitService;
use Illuminate\Console\Command;

class DaisyParser extends Command
{
    protected $signature = 'daisy:parse {--max-iterations=100 : Maximum number of iterations} {--threshold=0.001 : Activation threshold} {--show-steps : Show detailed propagation steps}';

    protected $description = 'Parse frames using Daisy frame parser with spread activation';

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
        $this->maxIterations = (int) $this->option('max-iterations');
        $this->threshold = (float) $this->option('threshold');

        $this->info('🌼 Daisy Frame Parser');
        $this->newLine();

        // Stage 1: Parse sentence with UD parser
        $text = "Roberto marcou o gol no jogo de hoje.";

        $this->info("📝 Stage 1: UD Parsing");
        $this->line("Sentence: {$text}");
        $this->newLine();

        $trankit = new TrankitService();
        $trankit->init("http://localhost:8405");
        $ud = $trankit->parseSentenceRawTokens($text, 1);

        if (empty($ud)) {
            $this->error("❌ Failed to parse sentence with Trankit");
            return self::FAILURE;
        }

        // Display UD parse results
        $this->info("UD Parse Results:");
        $tableData = [];
        foreach ($ud as $node) {
            $tableData[] = [
                $node['id'],
                $node['word'],
                $node['lemma'],
                $node['pos'],
                $node['rel'],
                $node['parent']
            ];
        }
        $this->table(
            ['ID', 'Word', 'Lemma', 'POS', 'Rel', 'Parent'],
            $tableData
        );
        $this->newLine();

        // Stage 2: Lexicon Lookup for ALL content words
        $this->info("🔍 Stage 2: Lexicon Lookup (Content Words)");
        $this->newLine();

        // Define content word POS tags
        $contentPOS = ['NOUN', 'VERB', 'ADV', 'ADJ', 'ADP'];

        // Get list of MWE lemmas to exclude (position=1 filter also applied in query)
        $mweLemmas = $this->getMWELemmas();
        $this->line("Filters: position=1 only + excluding " . count($mweLemmas) . " MWE lemmas");
        $this->newLine();

        // Collect LUs from all content words
        $allLexiconEntries = [];
        $wordStats = [];
        $totalBeforeFilter = 0;
        $totalAfterFilter = 0;

        foreach ($ud as $node) {
            if (in_array($node['pos'], $contentPOS)) {
                $entries = $this->findLUsFromWordForm($node['word']);
                $totalBeforeFilter += count($entries);

                if (!empty($entries)) {
                    // Filter out MWE lemmas
                    $filteredEntries = array_filter($entries,
                        fn($entry) => !in_array($entry->idLemma, $mweLemmas)
                    );

                    if (!empty($filteredEntries)) {
                        $totalAfterFilter += count($filteredEntries);

                        $wordStats[$node['word']] = [
                            'pos' => $node['pos'],
                            'count' => count($filteredEntries),
                            'entries' => $filteredEntries
                        ];

                        foreach ($filteredEntries as $entry) {
                            $allLexiconEntries[] = $entry;
                        }
                    }
                }
            }
        }

        if (empty($allLexiconEntries)) {
            $this->error("❌ No lexicon entries found for any content words");
            return self::FAILURE;
        }

        // Display found LUs grouped by word
        $filteredCount = $totalBeforeFilter - $totalAfterFilter;
        $this->info("Filtered {$filteredCount} MWE entries, kept {$totalAfterFilter} single-word LUs");
        $totalWords = count($wordStats);
        $this->info("Found {$totalAfterFilter} Lexical Unit(s) from {$totalWords} content word(s):");
        $this->newLine();

        foreach ($wordStats as $word => $stats) {
            $this->line("  Word: '{$word}' ({$stats['pos']}) - {$stats['count']} LU(s)");
            foreach ($stats['entries'] as $entry) {
                $this->line("    • LU #{$entry->idLU}: {$entry->lu} (Frame: {$entry->idFrame})");
            }
        }
        $this->newLine();

        // Stage 3: Spread Activation
        $this->info("🔄 Stage 3: Spread Activation (Memory Graph)");
        $this->newLine();

        // Extract unique idLUs from all lexicon entries
        $idLUs = array_unique(array_map(fn($entry) => $entry->idLU, $allLexiconEntries));

        // Find corresponding daisy nodes
        $luNodes = \App\Database\Criteria::table('daisy_node')
            ->where('type', 'LU')
            ->whereIn('idLU', $idLUs)
            ->orderBy('name')
            ->all();
        if (empty($luNodes)) {
            $this->error("❌ No corresponding nodes found in daisy_node table");
            return self::FAILURE;
        }

        $this->info("Starting from " . count($luNodes) . " LU node(s):");
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
                    if (!isset($this->nextActivations[$targetNodeId])) {
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
                if (!isset($this->visitCount[$nodeId])) {
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
                    $this->line("No active nodes. Stopping.");
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
                if (!isset($this->allActivations[$nodeId]) || $activation > $this->allActivations[$nodeId]) {
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
        if (!isset($this->linkCache[$nodeId])) {
            $this->linkCache[$nodeId] = Daisy::getLinksByNode($nodeId, 'source');
        }
        return $this->linkCache[$nodeId];
    }

    private function getMWELemmas(): array
    {
        $result = \App\Database\Criteria::table('view_lexicon_mwe')
            ->select('idLemma')
            ->all();

        return array_map(fn($row) => $row->idLemma, $result);
    }

    private function findLUsFromWordForm(string $form): array
    {
        return \App\Database\Criteria::table('view_lexicon')
            ->select('idLemma', 'lemma', 'idLU', 'lu', 'idFrame', 'position')
            ->where('form', $form)
            ->where('position', 1)  // Only position=1 (single-word or head of MWE)
            ->groupBy('idLU')
            ->all();
    }

    private function displayResults(): void
    {
        // Sort by activation value (descending)
        arsort($this->allActivations);

        $this->info('📊 Activation Results:');
        $this->line("Total nodes activated: " . count($this->allActivations));
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
                $visits
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
            $this->warn("Showing top 100 nodes. Total activated: " . count($this->allActivations));
        }
    }
}
