<?php

namespace App\Console\Commands\ParserV2;

use App\Data\Annotation\Corpus\AnnotationData;
use App\Data\Annotation\Corpus\SelectionData;
use App\Database\Criteria;
use App\Models\Parser\PhrasalCENode;
use App\Repositories\Parser\MWE;
use App\Services\Annotation\CorpusService;
use App\Services\AppService;
use App\Services\Trankit\TrankitService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

/**
 * Register Stage 1 (Transcription) CE labels as annotations
 *
 * Processes sentences from a document, runs the Transcription stage parser,
 * and registers the resulting CE (Constructional Element) labels as annotations.
 */
class RegisterCEAnnotationsCommand extends Command
{
    protected $signature = 'parser:register-ce-annotations
                            {idDocument : Document ID to process}
                            {--language=pt : Language code (pt, en)}
                            {--grammar= : Grammar graph ID for MWE detection}
                            {--dry-run : Show what would be done without making changes}
                            {--limit= : Limit number of sentences to process}';

    protected $description = 'Register Stage 1 parser CE labels as annotations for document sentences';

    private TrankitService $trankit;

    private ?int $idGrammarGraph = null;

    private array $ceEntityMap = [];

    private array $stats = [
        'sentences_processed' => 0,
        'sentences_skipped' => 0,
        'annotations_created' => 0,
        'parse_errors' => 0,
        'mwes_detected' => 0,
    ];

    public function handle(): int
    {
        // Authenticate as user ID 6 for annotation operations
        Auth::loginUsingId(6);

        // Set current language (1 = Portuguese)
        AppService::setCurrentLanguage(1);

        $idDocument = (int) $this->argument('idDocument');
        $language = $this->option('language');
        $dryRun = $this->option('dry-run');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        // Grammar graph for MWE detection
        $this->idGrammarGraph = $this->option('grammar') ? (int) $this->option('grammar') : null;

        // Validate document exists
        $document = Criteria::byId('document', 'idDocument', $idDocument);
        if (is_null($document)) {
            $this->error("Document not found: {$idDocument}");

            return Command::FAILURE;
        }

        $this->displayConfiguration($idDocument, $language, $dryRun, $limit);

        // Initialize services
        if (! $this->initServices()) {
            return Command::FAILURE;
        }

        // Load CE label entity map
        $this->loadCEEntityMap();

        // Fetch sentences with annotation sets
        $sentences = $this->fetchDocumentSentences($idDocument, $limit);

        if (empty($sentences)) {
            $this->warn('No sentences with annotation sets found for this document.');

            return Command::SUCCESS;
        }

        $this->info("Processing {$this->stats['sentences_processed']} sentences...");
        $this->newLine();

        // Process each sentence
        $progressBar = $this->output->createProgressBar(count($sentences));
        $progressBar->start();

        foreach ($sentences as $sentence) {
            $this->processSentence($sentence, $language, $dryRun);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display statistics
        $this->displayStatistics();

        return Command::SUCCESS;
    }

    private function displayConfiguration(int $idDocument, string $language, bool $dryRun, ?int $limit): void
    {
        $this->info('Register CE Annotations Command');
        $this->line(str_repeat('─', 60));
        $this->line('Configuration:');
        $this->line("  • Document ID: {$idDocument}");
        $this->line("  • Language: {$language}");
        $this->line('  • Dry run: '.($dryRun ? 'Yes' : 'No'));
        $this->line('  • Limit: '.($limit ?: 'No limit'));

        if ($this->idGrammarGraph) {
            $this->line("  • Grammar Graph: ID {$this->idGrammarGraph}");
        } else {
            $this->line('  • Grammar Graph: <fg=yellow>None</> (use --grammar=ID for MWE detection)');
        }

        $this->newLine();
    }

    private function initServices(): bool
    {
        // Initialize Trankit
        $this->trankit = new TrankitService;
        $trankitUrl = config('parser.trankit.url');

        try {
            $this->trankit->init($trankitUrl);
            $this->info("Trankit service initialized at: {$trankitUrl}");
        } catch (\Exception $e) {
            $this->error("Failed to initialize Trankit: {$e->getMessage()}");

            return false;
        }

        // Count MWEs in the grammar if provided
        if ($this->idGrammarGraph) {
            $mwes = MWE::listByGrammar($this->idGrammarGraph);
            $this->info('Grammar Graph loaded with '.count($mwes).' MWEs');
        }

        return true;
    }

    private function loadCEEntityMap(): void
    {
        // Load CE label to idEntity mapping from genericlabel table
        // idLayerType=57 is for CE labels, idLanguage=1 is Portuguese
        $labels = Criteria::table('genericlabel')
            ->where('idLayerType', 57)
            ->where('idLanguage', 1)
            ->select('idEntity', 'name')
            ->get();

        foreach ($labels as $label) {
            $this->ceEntityMap[$label->name] = $label->idEntity;
        }

        $this->info('Loaded '.count($this->ceEntityMap).' CE labels: '.implode(', ', array_keys($this->ceEntityMap)));
    }

    private function fetchDocumentSentences(int $idDocument, ?int $limit): array
    {
        $query = Criteria::table('document_sentence as ds')
            ->join('sentence as s', 'ds.idSentence', '=', 's.idSentence')
            ->join('annotationset as a', 'ds.idDocumentSentence', '=', 'a.idDocumentSentence')
            ->where('ds.idDocument', $idDocument)
            ->where('a.status', '<>', 'DELETED')
            ->select(
                'ds.idDocumentSentence',
                'ds.idSentence',
                's.text',
                'a.idAnnotationSet'
            )
            ->orderBy('ds.idDocumentSentence');

        if ($limit) {
            $query->limit($limit);
        }

        $sentences = $query->get()->toArray();
        $this->stats['sentences_processed'] = count($sentences);

        return $sentences;
    }

    private function processSentence(object $sentence, string $language, bool $dryRun): void
    {
        try {
            // Get language ID
            $idLanguage = config('parser.languageMap')[$language] ?? 1;

            // Parse with Trankit to get tokens
            $textResult = $this->trankit->getUDTrankitText($sentence->text, $idLanguage);
            $textTokens = $textResult->udpipe ?? [];

            if (empty($textTokens)) {
                $this->stats['parse_errors']++;

                return;
            }

            // Build nodes from text tokens (with preserved contractions)
            // These will be used for annotation to preserve original word forms like "do", "pelo", etc.
            $textNodes = [];
            foreach ($textTokens as $token) {
                $textNodes[] = PhrasalCENode::fromUDToken($token);
            }

            // Detect MWEs if grammar is available
            $detectedMWEs = [];
            if ($this->idGrammarGraph) {
                [, $detectedMWEs] = $this->detectMWEs($textNodes);
                $this->stats['mwes_detected'] += count($detectedMWEs);
            }

            // Apply MWE assembly to text nodes if detected
            $finalNodes = $textNodes;
            if (! empty($detectedMWEs)) {
                $finalNodes = $this->assembleMWEs($finalNodes, $detectedMWEs, $language);
            }

            // Register annotations using text nodes (preserves contractions like "do", "pelo")
            $this->registerAnnotations($sentence, $finalNodes, $dryRun);

        } catch (\Exception $e) {
            $this->stats['parse_errors']++;
            if ($this->output->isVerbose()) {
                $this->warn("Error processing sentence {$sentence->idDocumentSentence}: {$e->getMessage()}");
            }
        }
    }

    private function registerAnnotations(object $sentence, array $nodes, bool $dryRun): void
    {
        // Remove previous annotations for this annotationset before registering new ones
        if (! $dryRun) {
            $this->removePreviousAnnotations($sentence->idAnnotationSet);
        }

        $sentenceText = $sentence->text;
        $currentPosition = 0;

        foreach ($nodes as $node) {
            // Get idEntity for the CE label
            $ceLabel = $node->phrasalCE->value;
            $idEntity = $this->ceEntityMap[$ceLabel] ?? null;

            if (is_null($idEntity)) {
                if ($this->output->isVerbose()) {
                    $this->warn("CE label not found in database: {$ceLabel}");
                }

                continue;
            }

            // Calculate text span
            $wordText = $node->isMWE ? str_replace('^', ' ', $node->word) : $node->word;

            // Find the word in the sentence text
            $startChar = $this->findWordPosition($sentenceText, $wordText, $currentPosition);

            if ($startChar === false) {
                // Try finding individual words for MWE
                if ($node->isMWE) {
                    $words = explode('^', $node->word);
                    $startChar = $this->findWordPosition($sentenceText, $words[0], $currentPosition);
                    if ($startChar !== false) {
                        $lastWord = end($words);
                        $lastWordPos = $this->findWordPosition($sentenceText, $lastWord, $startChar);
                        if ($lastWordPos !== false) {
                            $endChar = $lastWordPos + mb_strlen($lastWord) - 1;
                            $currentPosition = $endChar + 1;
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            } else {
                $endChar = $startChar + mb_strlen($wordText) - 1;
                $currentPosition = $endChar + 1;
            }

            if ($dryRun) {
                $this->line("  Would annotate: '{$wordText}' [{$startChar}-{$endChar}] as {$ceLabel}");

                continue;
            }

            // Create annotation using CorpusService::annotateObject
            try {
                $annotationData = new AnnotationData(
                    idAnnotationSet: $sentence->idAnnotationSet,
                    idEntity: $idEntity,
                    range: new SelectionData(
                        type: 'word',
                        start: (string) $startChar,
                        end: (string) $endChar
                    ),
                    selection: $wordText,
                    token: $wordText,
                    corpusAnnotationType: 'flex'
                );

                CorpusService::annotateObject($annotationData);
                $this->stats['annotations_created']++;

            } catch (\Exception $e) {
                $this->warn("Failed to create annotation for '{$wordText}' (CE: {$ceLabel}): {$e->getMessage()}");
                if ($this->output->isVerbose()) {
                    $this->error($e->getTraceAsString());
                }
            }
        }
    }

    /**
     * Find word position in sentence text, handling UTF-8 and case-insensitive matching
     */
    private function findWordPosition(string $text, string $word, int $offset): int|false
    {
        // Use mb_stripos for case-insensitive UTF-8 handling
        return mb_stripos($text, $word, $offset);
    }

    /**
     * Detect MWEs in a sequence of nodes using prefix activation
     *
     * @return array [candidates, detected]
     */
    private function detectMWEs(array $nodes): array
    {
        $candidates = [];
        $detected = [];

        $nodesByPosition = array_values($nodes);

        foreach ($nodesByPosition as $nodePosition => $node) {
            $mwes = MWE::getStartingWith($this->idGrammarGraph, strtolower($node->word));

            foreach ($mwes as $mwe) {
                $components = MWE::getComponents($mwe);
                $threshold = count($components);

                $candidate = [
                    'idMWE' => $mwe->idMWE,
                    'phrase' => $mwe->phrase,
                    'components' => $components,
                    'threshold' => $threshold,
                    'startIndex' => $node->index,
                    'activation' => 1,
                    'matchedWords' => [$node->word],
                ];

                $currentNodePosition = $nodePosition;
                for ($i = 1; $i < $threshold; $i++) {
                    $nextPosition = $currentNodePosition + 1;

                    if (! isset($nodesByPosition[$nextPosition])) {
                        break;
                    }

                    $nextNode = $nodesByPosition[$nextPosition];

                    if (strtolower($nextNode->word) === strtolower($components[$i])) {
                        $candidate['activation']++;
                        $candidate['matchedWords'][] = $nextNode->word;
                        $candidate['endIndex'] = $nextNode->index;
                        $currentNodePosition = $nextPosition;
                    } else {
                        break;
                    }
                }

                if (! isset($candidate['endIndex'])) {
                    $candidate['endIndex'] = $node->index;
                }

                if ($candidate['activation'] >= $threshold) {
                    $candidate['complete'] = true;
                    $detected[] = $candidate;
                } else {
                    $candidate['complete'] = false;
                    $candidates[] = $candidate;
                }
            }
        }

        return [$candidates, $detected];
    }

    /**
     * Assemble MWEs from detected MWE candidates
     * Works directly with text nodes to preserve contractions
     */
    private function assembleMWEs(array $nodes, array $detectedMWEs, string $language): array
    {
        $idLanguage = config('parser.languageMap')[$language] ?? 1;

        // Sort MWEs by start index in descending order to process from end to beginning
        usort($detectedMWEs, fn ($a, $b) => $b['startIndex'] <=> $a['startIndex']);

        foreach ($detectedMWEs as $mwe) {
            $componentNodes = [];
            $startArrayIdx = null;
            $endArrayIdx = null;

            // Find the component nodes for this MWE
            foreach ($nodes as $arrayIdx => $node) {
                if ($node->index >= $mwe['startIndex'] && $node->index <= $mwe['endIndex']) {
                    if ($startArrayIdx === null) {
                        $startArrayIdx = $arrayIdx;
                    }
                    $endArrayIdx = $arrayIdx;
                    $componentNodes[] = $node;
                }
            }

            if ($startArrayIdx !== null && ! empty($componentNodes)) {
                $mwePos = MWE::getPOS($mwe['phrase'], $idLanguage);

                $mweNode = PhrasalCENode::fromMWEComponents(
                    $componentNodes,
                    count($componentNodes),
                    $mwePos
                );

                // Set word to phrase with ^ separator for text span calculation
                $mweNode->word = str_replace(' ', '^', $mwe['phrase']);

                // Replace the component nodes with the single MWE node
                array_splice($nodes, $startArrayIdx, count($componentNodes), [$mweNode]);
            }
        }

        return $nodes;
    }

    /**
     * Remove previous annotations and their associated textspans for an annotationset
     */
    private function removePreviousAnnotations(int $idAnnotationSet): void
    {
        // Get all textspans associated with this annotationset
        $textspans = Criteria::table('textspan')
            ->where('idAnnotationSet', $idAnnotationSet)
            ->pluck('idTextSpan')
            ->toArray();

        if (! empty($textspans)) {
            // Delete annotations associated with these textspans
            Criteria::table('annotation')
                ->whereIn('idTextSpan', $textspans)
                ->delete();

            // Delete the textspans themselves
            Criteria::table('textspan')
                ->whereIn('idTextSpan', $textspans)
                ->delete();
        }
    }

    private function displayStatistics(): void
    {
        $this->info('Statistics');
        $this->line(str_repeat('─', 60));

        $stats = [
            ['Sentences Processed', $this->stats['sentences_processed']],
            ['Sentences Skipped', $this->stats['sentences_skipped']],
            ['Annotations Created', $this->stats['annotations_created']],
            ['Parse Errors', $this->stats['parse_errors']],
        ];

        if ($this->idGrammarGraph) {
            $stats[] = ['MWEs Detected', $this->stats['mwes_detected']];
        }

        $this->table(['Metric', 'Value'], $stats);
    }
}
