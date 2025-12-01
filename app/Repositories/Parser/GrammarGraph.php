<?php

namespace App\Repositories\Parser;

use App\Database\Criteria;

class GrammarGraph
{
    /**
     * Retrieve grammar graph by ID
     */
    public static function byId(int $id): object
    {
        return Criteria::byId('parser_grammar_graph', 'idGrammarGraph', $id);
    }

    /**
     * List all grammar graphs
     */
    public static function list(): array
    {
        return Criteria::table('view_parser_grammar_graph')
            ->orderBy('name')
            ->all();
    }

    /**
     * List grammar graphs by language
     */
    public static function listByLanguage(string $language): array
    {
        return Criteria::table('view_parser_grammar_graph')
            ->where('language', '=', $language)
            ->orderBy('name')
            ->all();
    }

    /**
     * Get grammar graph with full structure (nodes and edges)
     */
    public static function getWithStructure(int $id): object
    {
        $grammar = self::byId($id);
        $grammar->nodes = self::getNodes($id);
        $grammar->edges = self::getEdges($id);
        $grammar->mwes = MWE::listByGrammar($id);

        return $grammar;
    }

    /**
     * Get all nodes for a grammar graph
     */
    public static function getNodes(int $idGrammarGraph): array
    {
        return Criteria::table('parser_grammar_node')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->orderBy('label')
            ->all();
    }

    /**
     * Get all edges for a grammar graph
     */
    public static function getEdges(int $idGrammarGraph): array
    {
        return Criteria::table('parser_grammar_link')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->all();
    }

    /**
     * Get edges from a specific source node
     */
    public static function getEdgesFromNode(int $idSourceNode): array
    {
        return Criteria::table('parser_grammar_link')
            ->where('idSourceNode', '=', $idSourceNode)
            ->all();
    }

    /**
     * Get predicted types from a focus node
     */
    public static function getPredictedTypes(int $idGrammarGraph, string $sourceType): array
    {
        return Criteria::table('grammar_edge as ge')
            ->join('grammar_node as gn_source', 'ge.idSourceNode', '=', 'gn_source.idGrammarNode')
            ->join('grammar_node as gn_target', 'ge.idTargetNode', '=', 'gn_target.idGrammarNode')
            ->select('gn_target.type', 'gn_target.label', 'ge.weight')
            ->where('ge.idGrammarGraph', '=', $idGrammarGraph)
            ->where('gn_source.type', '=', $sourceType)
            ->where('ge.linkType', '=', 'prediction')
            ->orderBy('ge.weight', 'DESC')
            ->all();
    }

    /**
     * Create new grammar graph
     */
    public static function create(array $data): int
    {
        return Criteria::create('parser_grammar_graph', $data);
    }

    /**
     * Update grammar graph
     */
    public static function update(int $id, array $data): void
    {
        Criteria::table('parser_grammar_graph')
            ->where('idGrammarGraph', '=', $id)
            ->update($data);
    }

    /**
     * Delete grammar graph
     */
    public static function delete(int $id): void
    {
        Criteria::deleteById('parser_grammar_graph', 'idGrammarGraph', $id);
    }

    /**
     * Create grammar node
     */
    public static function createNode(array $data): int
    {
        return Criteria::create('parser_grammar_node', $data);
    }

    /**
     * Create grammar edge
     */
    public static function createEdge(array $data): int
    {
        return Criteria::create('parser_grammar_link', $data);
    }

    /**
     * Delete all nodes and edges for a grammar graph
     */
    public static function clearStructure(int $idGrammarGraph): void
    {
        Criteria::table('parser_grammar_link')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->delete();

        Criteria::table('parser_grammar_node')
            ->where('idGrammarGraph', '=', $idGrammarGraph)
            ->delete();
    }
}
