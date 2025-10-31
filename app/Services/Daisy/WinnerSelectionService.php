<?php

namespace App\Services\Daisy;

/**
 * WinnerSelectionService - Frame Disambiguation Resolution
 *
 * Responsible for:
 * - Selecting winning frames based on final energy values
 * - Handling ties (ambiguous cases)
 * - Excluding verbs if configured
 * - Supporting GregNet mode (multiple winners per word)
 */
class WinnerSelectionService
{
    private bool $excludeVerbs;

    private bool $gregnetMode;

    public function __construct(bool $gregnetMode = false)
    {
        $this->excludeVerbs = config('daisy.winnerSelection.excludeVerbs');
        $this->gregnetMode = $gregnetMode;
    }

    /**
     * Generate winners from final energy values
     *
     * @param  array  $windows  Windows with final energy values
     * @param  array  $qualiaFrames  Qualia frame energy contributions
     * @param  array  $luEquivalence  LU equivalence mappings
     * @return array Winners indexed by word position
     */
    public function generateWinners(array $windows, array $qualiaFrames = [], array $luEquivalence = []): array
    {
        $winners = [];
        $weights = [];

        foreach ($windows as $idWindow => $words) {
            foreach ($words as $word => $frames) {
                $maxEnergy = 0.0;
                $winnerCandidates = [];

                foreach ($frames as $frameEntry => $frame) {
                    $energy = $frame->energy;

                    // Apply additional qualia bonuses
                    if (isset($qualiaFrames[$frame->idLU])) {
                        foreach ($qualiaFrames[$frame->idLU] as $qualiaValue) {
                            $energy += (float) $qualiaValue;
                        }
                    }

                    $finalEnergy = round($energy, 2);
                    $weights[$idWindow][$frame->idLU] = $finalEnergy;

                    // Skip verbs if configured
                    if ($this->excludeVerbs && str_contains($frame->lu, '.v')) {
                        continue;
                    }

                    // Winner selection logic
                    if ($energy > $maxEnergy) {
                        // New winner
                        $maxEnergy = $energy;
                        $winnerCandidates = [[
                            'idLU' => $frame->idLU,
                            'lu' => $frame->lu,
                            'frame' => $frameEntry,
                            'value' => $finalEnergy,
                            'equivalence' => $luEquivalence[$frame->idLU] ?? '',
                        ]];
                    } elseif ($energy == $maxEnergy && ! $this->gregnetMode) {
                        // Tie - ambiguous (empty winner array)
                        $winnerCandidates = [];
                    } elseif ($energy == $maxEnergy && $this->gregnetMode) {
                        // GregNet mode - allow multiple winners
                        $winnerCandidates[] = [
                            'idLU' => $frame->idLU,
                            'lu' => $frame->lu,
                            'frame' => $frameEntry,
                            'value' => $finalEnergy,
                            'equivalence' => $luEquivalence[$frame->idLU] ?? '',
                        ];
                    }
                }

                $winners[$frame->iword] = $winnerCandidates;
            }
        }

        return [
            'winners' => $winners,
            'weights' => $weights,
        ];
    }

    /**
     * Format winners for output
     */
    public function formatWinners(array $winners, array $windows): array
    {
        $result = [];

        foreach ($windows as $idWindow => $words) {
            $result[$idWindow] = [];

            foreach ($words as $word => $frames) {
                $wordWinners = [];

                foreach ($frames as $frame) {
                    if (isset($winners[$frame->iword])) {
                        $wordWinners = $winners[$frame->iword];
                        break;
                    }
                }

                if (! empty($wordWinners)) {
                    $result[$idWindow][$word] = $wordWinners;
                }
            }
        }

        return $result;
    }
}
