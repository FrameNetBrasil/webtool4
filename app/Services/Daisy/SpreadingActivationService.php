<?php

namespace App\Services\Daisy;

/**
 * SpreadingActivationService - Energy Spreading Algorithm
 *
 * Responsible for:
 * - Implementing the spreading activation algorithm
 * - Calculating energy contributions from pool objects
 * - Applying energy bonuses (MWE, MKNOB, Qualia)
 * - Updating final energy values for all frame candidates
 */
class SpreadingActivationService
{
    private array $energyBonus;

    public function __construct()
    {
        $this->energyBonus = config('daisy.energyBonus');
    }

    /**
     * Process spreading activation across all windows
     *
     * @param  array  $windows  Windows with frame candidates and their pools
     * @return array Windows with updated energy values
     */
    public function processSpreadingActivation(array $windows): array
    {
        foreach ($windows as $idWindow => $words) {
            foreach ($words as $word => $frames) {
                foreach ($frames as $frameEntry => $frame) {
                    // Calculate energy from spreading activation
                    $spreadEnergy = $this->calculateSpreadEnergy($frame, $word, $idWindow);

                    // Update frame energy
                    $windows[$idWindow][$word][$frameEntry]->energy += $spreadEnergy;

                    // Apply bonuses
                    $windows[$idWindow][$word][$frameEntry]->energy += $this->calculateBonuses($frame);
                }
            }
        }

        return $windows;
    }

    /**
     * Calculate energy spread from pool objects
     */
    private function calculateSpreadEnergy(object $frame, string $currentWord, int $currentWindowId): float
    {
        $totalEnergy = 0.0;

        foreach ($frame->pool as $poolObject) {
            foreach ($poolObject->set as $contributingWord => $element) {
                // Don't self-activate
                if ($currentWord === $contributingWord) {
                    continue;
                }

                // Check if can use this energy:
                // - Same window OR
                // - Qualia relation
                $canUse = $element['isQualia'] || ($element['idWindow'] === $currentWindowId);

                if ($canUse) {
                    $totalEnergy += $element['energy'];
                }
            }
        }

        return $totalEnergy;
    }

    /**
     * Calculate energy bonuses
     */
    private function calculateBonuses(object $frame): float
    {
        $bonus = 0.0;

        // Multi-word expression bonus
        if ($frame->mwe) {
            $bonus += $this->energyBonus['mwe'];
        }

        // MKNOB domain bonus
        if ($frame->mknob) {
            $bonus += $this->energyBonus['mknob'];
        }

        // Qualia bonuses are already applied in semantic network construction

        return $bonus;
    }
}
