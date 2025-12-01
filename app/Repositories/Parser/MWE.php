<?php

namespace App\Repositories\Parser;

use App\Database\Criteria;

class MWE
{
    /**
     * Retrieve MWE by ID
     */
    public static function byId(int $id): object
    {
        return Criteria::byId('parser_mwe', 'idMWE', $id);
    }

    /**
     * List all MWEs for a grammar graph
     */
    public static function listByGrammar(int $idGrammarGraph): array
    {
        return Criteria::table('parser_mwe')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->orderBy('phrase')
            ->all();
    }

    /**
     * Get MWEs that start with a specific word
     */
    public static function getStartingWith(int $idGrammarGraph, string $firstWord): array
    {
        $firstWord = strtolower($firstWord);

        return Criteria::table('parser_mwe')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->where("JSON_UNQUOTE(JSON_EXTRACT(components, '$[0]'))", '=', $firstWord)
            ->all();
    }

    /**
     * Get MWEs containing a specific component at any position
     */
    public static function getContaining(int $idGrammarGraph, string $word): array
    {
        $word = strtolower($word);

        return Criteria::table('parser_mwe')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->where('JSON_CONTAINS(components, JSON_QUOTE(?))', '=', $word)
            ->all();
    }

    /**
     * Get MWE by exact phrase
     */
    public static function getByPhrase(int $idGrammarGraph, string $phrase): ?object
    {
        $result = Criteria::table('parser_mwe')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->where('phrase', '=', $phrase)
            ->first();

        return $result ?: null;
    }

    /**
     * Get MWEs by length
     */
    public static function listByLength(int $idGrammarGraph, int $length): array
    {
        return Criteria::table('parser_mwe')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->where('length', '=', $length)
            ->orderBy('phrase')
            ->all();
    }

    /**
     * Get MWEs by semantic type
     */
    public static function listBySemanticType(int $idGrammarGraph, string $semanticType): array
    {
        return Criteria::table('parser_mwe')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->where('semanticType', '=', $semanticType)
            ->orderBy('phrase')
            ->all();
    }

    /**
     * Create new MWE
     */
    public static function create(array $data): int
    {
        // Ensure components is JSON encoded
        if (isset($data['components']) && is_array($data['components'])) {
            $data['components'] = json_encode($data['components']);
            $data['length'] = count($data['components']);
        }

        return Criteria::create('parser_mwe', $data);
    }

    /**
     * Update MWE
     */
    public static function update(int $id, array $data): void
    {
        // Ensure components is JSON encoded
        if (isset($data['components']) && is_array($data['components'])) {
            $data['components'] = json_encode($data['components']);
            $data['length'] = count($data['components']);
        }

        Criteria::table('parser_mwe')
            ->where('idMWE', '=', $id)
            ->update($data);
    }

    /**
     * Delete MWE
     */
    public static function delete(int $id): void
    {
        Criteria::deleteById('parser_mwe', 'idMWE', $id);
    }

    /**
     * Get components array from MWE
     */
    public static function getComponents(object $mwe): array
    {
        if (is_string($mwe->components)) {
            return json_decode($mwe->components, true);
        }

        return $mwe->components;
    }

    /**
     * Check if word matches expected component at position
     */
    public static function matchesComponent(object $mwe, string $word, int $position): bool
    {
        $components = self::getComponents($mwe);

        if (! isset($components[$position])) {
            return false;
        }

        return strtolower($components[$position]) === strtolower($word);
    }

    /**
     * Get all possible prefixes for MWEs starting with a word
     */
    public static function getPrefixesStartingWith(int $idGrammarGraph, string $firstWord): array
    {
        $mwes = self::getStartingWith($idGrammarGraph, $firstWord);
        $prefixes = [];

        foreach ($mwes as $mwe) {
            $components = self::getComponents($mwe);

            // Generate all prefixes (1-word, 2-word, ..., n-word)
            for ($i = 1; $i <= count($components); $i++) {
                $prefixComponents = array_slice($components, 0, $i);
                $prefixPhrase = implode(' ', $prefixComponents);

                $prefixes[] = (object) [
                    'idMWE' => $mwe->idMWE,
                    'phrase' => $prefixPhrase,
                    'components' => $prefixComponents,
                    'threshold' => $i,
                    'semanticType' => $mwe->semanticType,
                    'isComplete' => ($i === count($components)),
                    'fullPhrase' => $mwe->phrase,
                ];
            }
        }

        return $prefixes;
    }
}
