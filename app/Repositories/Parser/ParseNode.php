<?php

namespace App\Repositories\Parser;

use App\Database\Criteria;

class ParseNode
{
    /**
     * Retrieve parse node by ID
     */
    public static function byId(int $id): object
    {
        return Criteria::byId('parser_node', 'idParserNode', $id);
    }

    /**
     * List all nodes for a parse graph
     */
    public static function listByParseGraph(int $idParserGraph): array
    {
        return Criteria::table('parser_node')
            ->where('idParserGraph', '=', $idParserGraph)
            ->orderBy('positionInSentence')
            ->all();
    }

    /**
     * Get node by position in sentence
     */
    public static function getByPosition(int $idParserGraph, int $position): ?object
    {
        $result = Criteria::table('parser_node')
            ->where('idParserGraph', '=', $idParserGraph)
            ->where('positionInSentence', '=', $position)
            ->where('isFocus', '=', true)
            ->first();

        return $result ?: null;
    }

    /**
     * Get focus nodes
     */
    public static function getFocusNodes(int $idParserGraph): array
    {
        return Criteria::table('parser_node')
            ->where('idParserGraph', '=', $idParserGraph)
            ->where('isFocus', '=', true)
            ->orderBy('positionInSentence')
            ->all();
    }

    /**
     * Get MWE prefix nodes (not yet complete)
     */
    public static function getMWEPrefixes(int $idParserGraph): array
    {
        return Criteria::table('parser_node')
            ->where('idParserGraph', '=', $idParserGraph)
            ->where('type', '=', 'MWE')
            ->whereRaw('activation < threshold')
            ->all();
    }

    /**
     * Get completed MWE nodes
     */
    public static function getCompletedMWEs(int $idParserGraph): array
    {
        return Criteria::table('parser_node')
            ->where('idParserGraph', '=', $idParserGraph)
            ->where('type', '=', 'MWE')
            ->whereRaw('activation >= threshold')
            ->all();
    }

    /**
     * Get nodes by type
     */
    public static function listByType(int $idParserGraph, string $type): array
    {
        return Criteria::table('parser_node')
            ->where('idParserGraph', '=', $idParserGraph)
            ->where('type', '=', $type)
            ->orderBy('positionInSentence')
            ->all();
    }

    /**
     * Find MWE prefix node by label
     */
    public static function findMWEPrefix(int $idParserGraph, string $label): ?object
    {
        $result = Criteria::table('parser_node')
            ->where('idParserGraph', '=', $idParserGraph)
            ->where('label', '=', $label)
            ->where('type', '=', 'MWE')
            ->first();

        return $result ?: null;
    }

    /**
     * Create new parse node
     */
    public static function create(array $data): int
    {
        if (! isset($data['threshold'])) {
            $data['threshold'] = 1;
        }

        if (! isset($data['activation'])) {
            $data['activation'] = 1;
        }

        if (! isset($data['isFocus'])) {
            $data['isFocus'] = false;
        }

        return Criteria::create('parser_node', $data);
    }

    /**
     * Update parse node
     */
    public static function update(int $id, array $data): void
    {
        Criteria::table('parser_node')
            ->where('idParserNode', '=', $id)
            ->update($data);
    }

    /**
     * Increment activation
     */
    public static function incrementActivation(int $id): void
    {
        Criteria::table('parser_node')
            ->where('idParserNode', '=', $id)
            ->increment('activation', 1);
    }

    /**
     * Set as focus
     */
    public static function setFocus(int $id, bool $isFocus = true): void
    {
        self::update($id, ['isFocus' => $isFocus]);
    }

    /**
     * Check if node reached threshold
     */
    public static function hasReachedThreshold(object $node): bool
    {
        return $node->activation >= $node->threshold;
    }

    /**
     * Delete parse node
     */
    public static function delete(int $id): void
    {
        Criteria::deleteById('parser_node', 'idParserNode', $id);
    }

    /**
     * Delete nodes below threshold (garbage collection)
     */
    public static function deleteGarbage(int $idParserGraph): int
    {
        $deleted = Criteria::table('parser_node')
            ->where('idParserGraph', '=', $idParserGraph)
            ->whereRaw('activation < threshold')
            ->delete();

        return $deleted;
    }

    /**
     * Get nodes that link to this node
     */
    public static function getIncomingNodes(int $idParserNode): array
    {
        return Criteria::table('parse_node as pn')
            ->join('parse_edge as pe', 'pn.idParserNode', '=', 'pe.idSourceNode')
            ->where('pe.idTargetNode', '=', $idParserNode)
            ->select('pn.*')
            ->all();
    }

    /**
     * Get nodes that this node links to
     */
    public static function getOutgoingNodes(int $idParserNode): array
    {
        return Criteria::table('parse_node as pn')
            ->join('parse_edge as pe', 'pn.idParserNode', '=', 'pe.idTargetNode')
            ->where('pe.idSourceNode', '=', $idParserNode)
            ->select('pn.*')
            ->all();
    }

    /**
     * Check if node has any connections
     */
    public static function hasConnections(int $idParserNode): bool
    {
        $count = Criteria::table('parser_link')
            ->where(function ($query) use ($idParserNode) {
                $query->where('idSourceNode', '=', $idParserNode)
                    ->orWhere('idTargetNode', '=', $idParserNode);
            })
            ->count();

        return $count > 0;
    }
}
