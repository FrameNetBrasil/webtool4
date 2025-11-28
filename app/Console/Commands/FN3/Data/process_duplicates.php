<?php

/**
 * Process duplicate LUs and generate a CSV of deletable idLU values
 *
 * Rules:
 * - LUs with annotations (as != '-') are always kept
 * - Among LUs without annotations in the same group, keep only the one with lowest idLU
 * - All others can be deleted
 */

$inputFile = __DIR__ . '/lus_duplicadas.csv';
$outputFile = __DIR__ . '/lus_to_delete.csv';

if (!file_exists($inputFile)) {
    die("Error: Input file not found: {$inputFile}\n");
}

// Read and parse CSV
$handle = fopen($inputFile, 'r');
if ($handle === false) {
    die("Error: Could not open input file\n");
}

// Skip header row
$header = fgetcsv($handle);

// Group LUs by (idLemma, idFrame)
$groups = [];
$allLus = [];

while (($row = fgetcsv($handle)) !== false) {
    $idLU = (int) $row[0];
    $idLemma = (int) $row[1];
    $idFrame = (int) $row[2];
    $frameName = $row[3];
    $lu = $row[4];
    $as = $row[5]; // annotations

    $groupKey = "{$idLemma}_{$idFrame}";

    $luData = [
        'idLU' => $idLU,
        'idLemma' => $idLemma,
        'idFrame' => $idFrame,
        'frameName' => $frameName,
        'lu' => $lu,
        'as' => $as,
        'hasAnnotations' => $as !== '-',
    ];

    $groups[$groupKey][] = $luData;
    $allLus[$idLU] = $luData;
}

fclose($handle);

echo "Loaded " . count($allLus) . " LUs in " . count($groups) . " duplicate groups\n\n";

// Process each group to determine deletable LUs
$deletableLUs = [];
$keptLUs = [];
$statistics = [
    'totalGroups' => count($groups),
    'groupsWithAnnotations' => 0,
    'groupsWithoutAnnotations' => 0,
    'lusWithAnnotations' => 0,
    'lusWithoutAnnotations' => 0,
    'deletableLus' => 0,
    'keptLus' => 0,
];

foreach ($groups as $groupKey => $groupLus) {
    // Separate LUs with and without annotations
    $withAnnotations = [];
    $withoutAnnotations = [];

    foreach ($groupLus as $lu) {
        if ($lu['hasAnnotations']) {
            $withAnnotations[] = $lu;
        } else {
            $withoutAnnotations[] = $lu;
        }
    }

    // Count statistics
    if (!empty($withAnnotations)) {
        $statistics['groupsWithAnnotations']++;
    } else {
        $statistics['groupsWithoutAnnotations']++;
    }

    $statistics['lusWithAnnotations'] += count($withAnnotations);
    $statistics['lusWithoutAnnotations'] += count($withoutAnnotations);

    // All LUs with annotations are kept
    foreach ($withAnnotations as $lu) {
        $keptLUs[] = $lu['idLU'];
    }

    // Among LUs without annotations, keep only the one with lowest idLU
    if (!empty($withoutAnnotations)) {
        // Sort by idLU ascending
        usort($withoutAnnotations, fn($a, $b) => $a['idLU'] <=> $b['idLU']);

        // Keep the first one (lowest idLU)
        $keptLUs[] = $withoutAnnotations[0]['idLU'];

        // Mark the rest for deletion
        for ($i = 1; $i < count($withoutAnnotations); $i++) {
            $deletableLUs[] = $withoutAnnotations[$i]['idLU'];
        }
    }
}

$statistics['deletableLus'] = count($deletableLUs);
$statistics['keptLus'] = count($keptLUs);

// Sort deletable LUs for consistent output
sort($deletableLUs);

// Write output CSV
$outputHandle = fopen($outputFile, 'w');
if ($outputHandle === false) {
    die("Error: Could not create output file\n");
}

// Write header
fputcsv($outputHandle, ['idLU']);

// Write deletable LU IDs
foreach ($deletableLUs as $idLU) {
    fputcsv($outputHandle, [$idLU]);
}

fclose($outputHandle);

// Print statistics
echo "=== Processing Statistics ===\n";
echo "Total duplicate groups: {$statistics['totalGroups']}\n";
echo "  - Groups with at least one annotation: {$statistics['groupsWithAnnotations']}\n";
echo "  - Groups without any annotations: {$statistics['groupsWithoutAnnotations']}\n";
echo "\n";
echo "Total LUs processed: " . count($allLus) . "\n";
echo "  - LUs with annotations (always kept): {$statistics['lusWithAnnotations']}\n";
echo "  - LUs without annotations: {$statistics['lusWithoutAnnotations']}\n";
echo "\n";
echo "Results:\n";
echo "  - LUs to KEEP: {$statistics['keptLus']}\n";
echo "  - LUs to DELETE: {$statistics['deletableLus']}\n";
echo "\n";
echo "Output file created: {$outputFile}\n";

// Show some examples
echo "\n=== Sample Deletable LUs ===\n";
for ($i = 0; $i < min(10, count($deletableLUs)); $i++) {
    $idLU = $deletableLUs[$i];
    $lu = $allLus[$idLU];
    echo "idLU={$lu['idLU']} - {$lu['lu']} (Frame: {$lu['frameName']}, as={$lu['as']})\n";
}

echo "\nDone!\n";
