<?php

namespace App\Console\Commands;

use App\Database\Criteria;
use Illuminate\Console\Command;

class CoreFrameElementsReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'frame:core-elements-report
                            {--output= : Output CSV file path for results}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a report of core Frame Elements count per Frame';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Generating Core Frame Elements Report');
        $this->newLine();

        // Get all frames with their core FE counts
        $this->info('📊 Querying frames and counting core Frame Elements...');
        $results = $this->getCoreFrameElementCounts();

        if (empty($results)) {
            $this->error('❌ No frames found in database');

            return self::FAILURE;
        }

        $this->info('✓ Found '.count($results).' frame(s)');
        $this->newLine();

        // Calculate minimum FEs to annotate for each frame
        $this->info('🔢 Calculating minimum FEs to annotate...');
        $results = $this->calculateMinimumFEs($results);

        // Display results
        $this->displayResults($results);

        // Display statistics
        $this->displayStatistics($results);

        // Export to CSV if requested
        $outputPath = $this->option('output');
        if ($outputPath) {
            $fullOutputPath = $this->resolveOutputPath($outputPath);
            $this->exportToCSV($results, $fullOutputPath);
            $this->info("✓ Results exported to: {$fullOutputPath}");
        }

        return self::SUCCESS;
    }

    /**
     * Get core frame element counts and relation statistics for all frames
     */
    private function getCoreFrameElementCounts(): array
    {
        $results = Criteria::table('frame as f')
            ->leftJoin('frameelement as fe', function ($join) {
                $join->on('f.idFrame', '=', 'fe.idFrame')
                    ->whereIn('fe.coreType', ['cty_core', 'cty_core-unexpressed']);
            })
            ->leftJoinSub(
                Criteria::table('view_fe_internal_relation')
                    ->select('fe1IdFrame')
                    ->selectRaw('COUNT(DISTINCT idEntityRelation) as coreset_count')
                    ->selectRaw('COUNT(DISTINCT fe1IdFrameElement) + COUNT(DISTINCT fe2IdFrameElement) as coreset_fes')
                    ->where('relationType', 'rel_coreset')
                    ->where('idLanguage', 1)
                    ->groupBy('fe1IdFrame'),
                'cs',
                'f.idFrame',
                '=',
                'cs.fe1IdFrame'
            )
            ->leftJoinSub(
                Criteria::table('view_fe_internal_relation')
                    ->select('fe1IdFrame')
                    ->selectRaw('COUNT(DISTINCT idEntityRelation) as excludes_count')
                    ->selectRaw('COUNT(DISTINCT fe1IdFrameElement) + COUNT(DISTINCT fe2IdFrameElement) as excludes_fes')
                    ->where('relationType', 'rel_excludes')
                    ->where('idLanguage', 1)
                    ->groupBy('fe1IdFrame'),
                'ex',
                'f.idFrame',
                '=',
                'ex.fe1IdFrame'
            )
            ->select(
                'f.idFrame',
                'f.entry',
                'f.defaultName'
            )
            ->selectRaw('COUNT(fe.idFrameElement) as core_fe_count')
            ->selectRaw('COALESCE(cs.coreset_count, 0) as coreset_relations_count')
            ->selectRaw('COALESCE(cs.coreset_fes, 0) as fes_in_coreset')
            ->selectRaw('COALESCE(ex.excludes_count, 0) as excludes_relations_count')
            ->selectRaw('COALESCE(ex.excludes_fes, 0) as fes_in_excludes')
            ->groupBy('f.idFrame', 'f.entry', 'f.defaultName', 'cs.coreset_count', 'cs.coreset_fes', 'ex.excludes_count', 'ex.excludes_fes')
            ->orderBy('f.entry')
            ->get()
            ->all();

        return $results;
    }

    /**
     * Calculate minimum FEs to annotate for each frame
     */
    private function calculateMinimumFEs(array $results): array
    {
        foreach ($results as $result) {
            // Get core FEs for this frame
            $coreFEs = Criteria::table('frameelement')
                ->where('idFrame', $result->idFrame)
                ->whereIn('coreType', ['cty_core', 'cty_core-unexpressed'])
                ->pluck('idFrameElement')
                ->toArray();

            // If no core FEs, minimum is 0
            if (empty($coreFEs)) {
                $result->min_fes_to_annotate = 0;

                continue;
            }

            // Step 1: Find coreset groups
            $coresetGroups = [];
            $fesInCoresets = [];

            if ($result->coreset_relations_count > 0) {
                $coresetRelations = Criteria::table('view_fe_internal_relation')
                    ->where('fe1IdFrame', $result->idFrame)
                    ->where('relationType', 'rel_coreset')
                    ->where('idLanguage', 1)
                    ->select('fe1IdFrameElement', 'fe2IdFrameElement')
                    ->get()
                    ->toArray();

                $coresetGroups = $this->findCoresetGroups($coreFEs, $coresetRelations);

                foreach ($coresetGroups as $group) {
                    $fesInCoresets = array_merge($fesInCoresets, $group);
                }
            }

            // Step 2: Identify standalone FEs (not in any coreset)
            $standaloneFEs = array_diff($coreFEs, $fesInCoresets);

            // Initial minimum = coreset groups + standalone FEs
            $initialMin = count($coresetGroups) + count($standaloneFEs);

            // Step 3: Process excludes relations to reduce minimum
            $finalMin = $initialMin;

            if ($result->excludes_relations_count > 0) {
                $excludesRelations = Criteria::table('view_fe_internal_relation')
                    ->where('fe1IdFrame', $result->idFrame)
                    ->where('relationType', 'rel_excludes')
                    ->where('idLanguage', 1)
                    ->select('fe1IdFrameElement', 'fe2IdFrameElement')
                    ->get()
                    ->toArray();

                $finalMin = $this->applyExcludesConstraints(
                    $initialMin,
                    $coreFEs,
                    $coresetGroups,
                    $standaloneFEs,
                    $excludesRelations
                );
            }

            $result->min_fes_to_annotate = $finalMin;
        }

        return $results;
    }

    /**
     * Apply excludes constraints to reduce minimum FEs
     */
    private function applyExcludesConstraints(
        int $initialMin,
        array $coreFEs,
        array $coresetGroups,
        array $standaloneFEs,
        array $excludesRelations
    ): int {
        // Create mappings
        $coreFESet = array_flip($coreFEs);
        $standaloneFESet = array_flip($standaloneFEs);

        // Map each FE to its coreset group index (if any)
        $feToGroup = [];
        foreach ($coresetGroups as $groupIdx => $group) {
            foreach ($group as $fe) {
                $feToGroup[$fe] = $groupIdx;
            }
        }

        // Process excludes relations and build exclusion groups among standalone FEs
        $standaloneExclusions = []; // Track which standalone FEs exclude each other

        foreach ($excludesRelations as $relation) {
            $fe1 = $relation->fe1IdFrameElement;
            $fe2 = $relation->fe2IdFrameElement;

            // Only process if both are core FEs
            if (! isset($coreFESet[$fe1]) || ! isset($coreFESet[$fe2])) {
                continue;
            }

            $fe1IsStandalone = isset($standaloneFESet[$fe1]);
            $fe2IsStandalone = isset($standaloneFESet[$fe2]);

            if ($fe1IsStandalone && $fe2IsStandalone) {
                // Case A: Both are standalone - they form an exclusion group
                if (! isset($standaloneExclusions[$fe1])) {
                    $standaloneExclusions[$fe1] = [];
                }
                if (! isset($standaloneExclusions[$fe2])) {
                    $standaloneExclusions[$fe2] = [];
                }
                $standaloneExclusions[$fe1][] = $fe2;
                $standaloneExclusions[$fe2][] = $fe1;
            }
            // Case B: One or both in coreset groups - propagate exclusions
            // For simplicity, we note these constraints exist but don't reduce minimum
            // (Conservative approach: coreset groups still require 1 FE each)
        }

        // Find exclusion groups among standalone FEs using Union-Find
        if (! empty($standaloneExclusions)) {
            $exclusionGroups = $this->findExclusionGroups(array_values($standaloneFEs), $standaloneExclusions);

            // Each exclusion group reduces minimum by (size - 1)
            // Because we can pick at most 1 from each exclusion group
            $reduction = 0;
            foreach ($exclusionGroups as $group) {
                $reduction += count($group) - 1;
            }

            return max(1, $initialMin - $reduction); // Minimum must be at least 1
        }

        return $initialMin;
    }

    /**
     * Find exclusion groups (cliques) among standalone FEs
     */
    private function findExclusionGroups(array $standaloneFEs, array $exclusions): array
    {
        if (empty($exclusions)) {
            return [];
        }

        // Use Union-Find to group mutually exclusive FEs
        $parent = [];
        foreach ($standaloneFEs as $fe) {
            $parent[$fe] = $fe;
        }

        $find = function ($x) use (&$parent, &$find) {
            if (! isset($parent[$x])) {
                return $x;
            }
            if ($parent[$x] != $x) {
                $parent[$x] = $find($parent[$x]);
            }

            return $parent[$x];
        };

        $union = function ($x, $y) use (&$parent, $find) {
            $rootX = $find($x);
            $rootY = $find($y);
            if ($rootX != $rootY) {
                $parent[$rootX] = $rootY;
            }
        };

        // Process exclusion relations
        foreach ($exclusions as $fe1 => $excludedFEs) {
            foreach ($excludedFEs as $fe2) {
                $union($fe1, $fe2);
            }
        }

        // Group FEs by their root
        $groups = [];
        foreach ($standaloneFEs as $fe) {
            if (isset($exclusions[$fe])) {
                $root = $find($fe);
                if (! isset($groups[$root])) {
                    $groups[$root] = [];
                }
                $groups[$root][] = $fe;
            }
        }

        return array_values($groups);
    }

    /**
     * Find coreset groups (connected components) using Union-Find algorithm
     */
    private function findCoresetGroups(array $coreFEs, array $relations): array
    {
        if (empty($relations)) {
            return [];
        }

        // Initialize parent array for Union-Find - only for core FEs
        $parent = [];
        $coreFESet = array_flip($coreFEs);
        foreach ($coreFEs as $fe) {
            $parent[$fe] = $fe;
        }

        // Find function with path compression
        $find = function ($x) use (&$parent, &$find) {
            if (! isset($parent[$x])) {
                return $x;
            }
            if ($parent[$x] != $x) {
                $parent[$x] = $find($parent[$x]);
            }

            return $parent[$x];
        };

        // Union function
        $union = function ($x, $y) use (&$parent, $find, $coreFESet) {
            // Only process if both FEs are core FEs
            if (! isset($coreFESet[$x]) || ! isset($coreFESet[$y])) {
                return;
            }
            $rootX = $find($x);
            $rootY = $find($y);
            if ($rootX != $rootY) {
                $parent[$rootX] = $rootY;
            }
        };

        // Process all coreset relations - only between core FEs
        foreach ($relations as $relation) {
            $fe1 = $relation->fe1IdFrameElement;
            $fe2 = $relation->fe2IdFrameElement;
            // Only create unions for core FEs
            if (isset($coreFESet[$fe1]) && isset($coreFESet[$fe2])) {
                $union($fe1, $fe2);
            }
        }

        // Group FEs by their root parent
        $groups = [];
        foreach ($coreFEs as $fe) {
            // Only include FEs that are in at least one coreset relation
            $hasRelation = false;
            foreach ($relations as $relation) {
                if ($relation->fe1IdFrameElement == $fe || $relation->fe2IdFrameElement == $fe) {
                    $hasRelation = true;
                    break;
                }
            }

            if ($hasRelation) {
                $root = $find($fe);
                if (! isset($groups[$root])) {
                    $groups[$root] = [];
                }
                $groups[$root][] = $fe;
            }
        }

        return array_values($groups);
    }

    /**
     * Display results in formatted table
     */
    private function displayResults(array $results): void
    {
        $this->info('=== Core Frame Elements Report ===');
        $this->newLine();

        // Show first 20 results in console
        $displayResults = array_slice($results, 0, 20);
        $tableData = [];

        foreach ($displayResults as $result) {
            $tableData[] = [
                $result->idFrame,
                $result->entry,
                $result->defaultName ?? 'N/A',
                $result->core_fe_count,
                $result->min_fes_to_annotate,
                $result->coreset_relations_count,
                $result->fes_in_coreset,
                $result->excludes_relations_count,
                $result->fes_in_excludes,
            ];
        }

        $this->table(
            ['Frame ID', 'Frame Entry', 'Frame Name', 'Core FEs', 'Min FEs', 'CoreSet Rels', 'FEs in CoreSet', 'Excludes Rels', 'FEs in Excludes'],
            $tableData
        );

        if (count($results) > 20) {
            $this->line('<fg=yellow>Showing first 20 of '.count($results).' frames. Use --output to export all results.</>');
        }

        $this->newLine();
    }

    /**
     * Display summary statistics
     */
    private function displayStatistics(array $results): void
    {
        $this->info('=== Statistics ===');
        $this->newLine();

        $totalFrames = count($results);
        $framesWithCoreFEs = 0;
        $totalCoreFEs = 0;
        $maxCoreFEs = 0;
        $minCoreFEs = PHP_INT_MAX;

        $framesWithCoreSetRels = 0;
        $totalCoreSetRels = 0;
        $framesWithExcludesRels = 0;
        $totalExcludesRels = 0;

        foreach ($results as $result) {
            $count = $result->core_fe_count;
            $totalCoreFEs += $count;

            if ($count > 0) {
                $framesWithCoreFEs++;
            }

            if ($count > $maxCoreFEs) {
                $maxCoreFEs = $count;
            }

            if ($count < $minCoreFEs) {
                $minCoreFEs = $count;
            }

            // Relation statistics
            if ($result->coreset_relations_count > 0) {
                $framesWithCoreSetRels++;
                $totalCoreSetRels += $result->coreset_relations_count;
            }

            if ($result->excludes_relations_count > 0) {
                $framesWithExcludesRels++;
                $totalExcludesRels += $result->excludes_relations_count;
            }
        }

        // If no frames at all, set min to 0
        if ($minCoreFEs === PHP_INT_MAX) {
            $minCoreFEs = 0;
        }

        $avgCoreFEs = $totalFrames > 0 ? round($totalCoreFEs / $totalFrames, 2) : 0;
        $framesWithoutCoreFEs = $totalFrames - $framesWithCoreFEs;
        $avgCoreSetRels = $framesWithCoreSetRels > 0 ? round($totalCoreSetRels / $framesWithCoreSetRels, 2) : 0;
        $avgExcludesRels = $framesWithExcludesRels > 0 ? round($totalExcludesRels / $framesWithExcludesRels, 2) : 0;

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Frames', $totalFrames],
                ['Frames with Core FEs', $framesWithCoreFEs],
                ['Frames without Core FEs', $framesWithoutCoreFEs],
                ['Total Core FEs', $totalCoreFEs],
                ['Average Core FEs per Frame', $avgCoreFEs],
                ['Maximum Core FEs in a Frame', $maxCoreFEs],
                ['Minimum Core FEs in a Frame', $minCoreFEs],
                ['---', '---'],
                ['Frames with CoreSet Relations', $framesWithCoreSetRels],
                ['Total CoreSet Relations', $totalCoreSetRels],
                ['Avg CoreSet Relations (frames with relations)', $avgCoreSetRels],
                ['Frames with Excludes Relations', $framesWithExcludesRels],
                ['Total Excludes Relations', $totalExcludesRels],
                ['Avg Excludes Relations (frames with relations)', $avgExcludesRels],
            ]
        );
    }

    /**
     * Export results to CSV
     */
    private function exportToCSV(array $results, string $outputPath): void
    {
        $handle = fopen($outputPath, 'w');

        // Write header
        fputcsv($handle, [
            'Frame ID',
            'Frame Entry',
            'Frame Name',
            'Core FE Count',
            'Min FEs to Annotate',
            'CoreSet Relations',
            'FEs in CoreSet',
            'Excludes Relations',
            'FEs in Excludes',
        ]);

        // Write data
        foreach ($results as $result) {
            fputcsv($handle, [
                $result->idFrame,
                $result->entry,
                $result->defaultName ?? '',
                $result->core_fe_count,
                $result->min_fes_to_annotate,
                $result->coreset_relations_count,
                $result->fes_in_coreset,
                $result->excludes_relations_count,
                $result->fes_in_excludes,
            ]);
        }

        fclose($handle);
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
}
