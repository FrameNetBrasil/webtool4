<?php

namespace App\Services\Daisy;

use App\Data\Daisy\DaisyInputData;
use App\Data\Daisy\DaisyOutputData;
use App\Services\Trankit\TrankitService;

/**
 * DaisyService - Main Semantic Disambiguation Orchestrator
 *
 * Coordinates the complete Daisy pipeline:
 * 1. Universal Dependencies parsing (Trankit)
 * 2. GRID window creation
 * 3. Lexical unit matching
 * 4. Semantic network construction
 * 5. Spreading activation
 * 6. Winner selection
 */
class DaisyService
{
    private TrankitService $trankitService;

    private GridService $gridService;

    private LexicalUnitService $lexicalUnitService;

    private SemanticNetworkService $semanticNetworkService;

    private SpreadingActivationService $spreadingActivationService;

    private WinnerSelectionService $winnerSelectionService;

    public function __construct(
        TrankitService $trankitService,
        GridService $gridService
    ) {
        $this->trankitService = $trankitService;
        $this->gridService = $gridService;

        // Initialize Trankit with URL
        $trankitUrl = config('daisy.trankitUrl', 'http://localhost:8405');
        $this->trankitService->init($trankitUrl);
    }

    /**
     * Process sentence through complete Daisy pipeline
     *
     * @param  DaisyInputData  $input  Input parameters
     * @return DaisyOutputData Disambiguation results with graph data
     */
    public function disambiguate(DaisyInputData $input): DaisyOutputData
    {
        // Initialize services with language-specific parameters
        $this->lexicalUnitService = new LexicalUnitService($input->idLanguage);
        $this->semanticNetworkService = new SemanticNetworkService(
            $input->idLanguage,
            $input->searchType,
            $input->level
        );
        $this->spreadingActivationService = new SpreadingActivationService;
        $this->winnerSelectionService = new WinnerSelectionService($input->gregnetMode);

        // Step 1: Parse sentence with Trankit (UD parsing)
        $udParsed = $this->parseWithTrankit($input->sentence, $input->idLanguage);

        // Step 2: Create GRID windows
        $gridResult = $this->gridService->processToWindows($udParsed);
        $windows = $gridResult['windows'];
        $lemmas = $gridResult['lemmas'];

        // Step 3: Match lexical units
        $windows = $this->lexicalUnitService->matchLexicalUnits($windows, $lemmas);

        // Step 4: Build semantic networks
        $windows = $this->semanticNetworkService->buildSemanticNetworks($windows);

        // Step 5: Apply spreading activation
        $windows = $this->spreadingActivationService->processSpreadingActivation($windows);

        // Step 6: Select winners
        $winnerResult = $this->winnerSelectionService->generateWinners($windows);
        $winners = $winnerResult['winners'];
        $weights = $winnerResult['weights'];

        // Format results
        $result = $this->winnerSelectionService->formatWinners($winners, $windows);

        // Generate graph visualization data
        $graph = $this->generateGraphData($windows, $winners, $udParsed);

        return new DaisyOutputData(
            result: $result,
            graph: $graph,
            sentenceUD: $udParsed,
            windows: $windows,
            weights: $weights
        );
    }

    /**
     * Parse sentence using Trankit
     */
    private function parseWithTrankit(string $sentence, int $idLanguage): array
    {
        // Use TrankitService to get UD parse
        $result = $this->trankitService->getUDTrankit($sentence, $idLanguage);

        return $result->udpipe ?? [];
    }

    /**
     * Generate graph data for visualization
     */
    private function generateGraphData(array $windows, array $winners, array $udParsed): array
    {
        $nodes = [];
        $links = [];

        // TODO: Implement graph generation
        // - Create nodes for words and frames
        // - Create links for evokes relations and frame relations

        return [
            'nodes' => $nodes,
            'links' => $links,
        ];
    }
}
