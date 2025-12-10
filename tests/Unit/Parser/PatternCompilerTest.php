<?php

namespace Tests\Unit\Parser;

use App\Services\Parser\PatternCompiler;
use PHPUnit\Framework\TestCase;

class PatternCompilerTest extends TestCase
{
    private PatternCompiler $compiler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->compiler = new PatternCompiler;
    }

    /**
     * Test basic literal sequence compilation
     */
    public function test_compiles_simple_sequence(): void
    {
        $pattern = 'a b c';
        $graph = $this->compiler->compile($pattern);

        $this->assertArrayHasKey('nodes', $graph);
        $this->assertArrayHasKey('edges', $graph);
        $this->assertCount(5, $graph['nodes']); // START + 3 literals + END

        // Should have START and END nodes
        $nodeTypes = array_column($graph['nodes'], 'type');
        $this->assertContains('START', $nodeTypes);
        $this->assertContains('END', $nodeTypes);
        $this->assertEquals(3, array_count_values($nodeTypes)['LITERAL'] ?? 0);
    }

    /**
     * Test POS slot compilation
     */
    public function test_compiles_pos_slot(): void
    {
        $pattern = '{NOUN}';
        $graph = $this->compiler->compile($pattern);

        // Find SLOT node
        $slotNodes = array_filter($graph['nodes'], fn ($n) => $n['type'] === 'SLOT');
        $this->assertCount(1, $slotNodes);

        $slotNode = reset($slotNodes);
        $this->assertEquals('NOUN', $slotNode['pos']);
        $this->assertNull($slotNode['constraint']);
    }

    /**
     * Test constrained slot compilation
     */
    public function test_compiles_constrained_slot(): void
    {
        $pattern = '{VERB:inf}';
        $graph = $this->compiler->compile($pattern);

        $slotNodes = array_filter($graph['nodes'], fn ($n) => $n['type'] === 'SLOT');
        $slotNode = reset($slotNodes);

        $this->assertEquals('VERB', $slotNode['pos']);
        $this->assertEquals('inf', $slotNode['constraint']);
    }

    /**
     * Test wildcard compilation
     */
    public function test_compiles_wildcard(): void
    {
        $pattern = '{*}';
        $graph = $this->compiler->compile($pattern);

        $wildcardNodes = array_filter($graph['nodes'], fn ($n) => $n['type'] === 'WILDCARD');
        $this->assertCount(1, $wildcardNodes);
    }

    /**
     * Test optional element compilation
     */
    public function test_compiles_optional_element(): void
    {
        $pattern = 'a [b] c';
        $graph = $this->compiler->compile($pattern);

        // Should have bypass edge
        $bypassEdges = array_filter($graph['edges'], fn ($e) => isset($e['bypass']) && $e['bypass']);
        $this->assertGreaterThan(0, count($bypassEdges));
    }

    /**
     * Test nested optional
     */
    public function test_compiles_nested_optional(): void
    {
        $pattern = '[{NUM}] mil';
        $graph = $this->compiler->compile($pattern);

        // Should have both SLOT and LITERAL nodes
        $nodeTypes = array_column($graph['nodes'], 'type');
        $this->assertContains('SLOT', $nodeTypes);
        $this->assertContains('LITERAL', $nodeTypes);

        // Should have bypass edge for optional
        $bypassEdges = array_filter($graph['edges'], fn ($e) => isset($e['bypass']) && $e['bypass']);
        $this->assertGreaterThan(0, count($bypassEdges));
    }

    /**
     * Test alternative compilation
     */
    public function test_compiles_alternatives(): void
    {
        $pattern = '(a | b | c)';
        $graph = $this->compiler->compile($pattern);

        // Should have 3 LITERAL nodes
        $literalNodes = array_filter($graph['nodes'], fn ($n) => $n['type'] === 'LITERAL');
        $this->assertCount(3, $literalNodes);
    }

    /**
     * Test complex pattern with multiple features
     */
    public function test_compiles_complex_pattern(): void
    {
        $pattern = '[{NUM}] mil [, ] [{NUM}] [e {NUM}]';
        $graph = $this->compiler->compile($pattern);

        // Should compile successfully
        $this->assertNotEmpty($graph['nodes']);
        $this->assertNotEmpty($graph['edges']);

        // Should have START and END
        $nodeTypes = array_column($graph['nodes'], 'type');
        $this->assertContains('START', $nodeTypes);
        $this->assertContains('END', $nodeTypes);
    }

    /**
     * Test pattern validation
     */
    public function test_validates_correct_pattern(): void
    {
        $pattern = '{NOUN} de {NOUN}';
        $result = $this->compiler->validate($pattern);

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
    }

    /**
     * Test pattern validation with unmatched brackets
     */
    public function test_validates_unmatched_brackets(): void
    {
        $pattern = '[{NOUN} de {NOUN';
        $result = $this->compiler->validate($pattern);

        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']);
    }

    /**
     * Test tokenization of literals
     */
    public function test_tokenizes_literals(): void
    {
        $pattern = 'palavra1 palavra2';
        $tokens = $this->compiler->tokenize($pattern);

        $this->assertCount(2, $tokens);
        $this->assertEquals('LITERAL', $tokens[0]['type']);
        $this->assertEquals('palavra1', $tokens[0]['value']);
        $this->assertEquals('LITERAL', $tokens[1]['type']);
        $this->assertEquals('palavra2', $tokens[1]['value']);
    }

    /**
     * Test tokenization of slots
     */
    public function test_tokenizes_slots(): void
    {
        $pattern = '{NOUN} {VERB:inf}';
        $tokens = $this->compiler->tokenize($pattern);

        $this->assertCount(2, $tokens);
        $this->assertEquals('SLOT', $tokens[0]['type']);
        $this->assertEquals('NOUN', $tokens[0]['pos']);
        $this->assertEquals('SLOT', $tokens[1]['type']);
        $this->assertEquals('VERB', $tokens[1]['pos']);
        $this->assertEquals('inf', $tokens[1]['constraint']);
    }

    /**
     * Test tokenization of optionals
     */
    public function test_tokenizes_optionals(): void
    {
        $pattern = 'a [b c] d';
        $tokens = $this->compiler->tokenize($pattern);

        $this->assertCount(3, $tokens);
        $this->assertEquals('LITERAL', $tokens[0]['type']);
        $this->assertEquals('OPTIONAL', $tokens[1]['type']);
        $this->assertEquals('b c', $tokens[1]['content']);
        $this->assertEquals('LITERAL', $tokens[2]['type']);
    }

    /**
     * Test tokenization of alternatives
     */
    public function test_tokenizes_alternatives(): void
    {
        $pattern = '(por causa | por meio | apesar)';
        $tokens = $this->compiler->tokenize($pattern);

        $this->assertCount(1, $tokens);
        $this->assertEquals('ALTERNATIVE', $tokens[0]['type']);
        $this->assertCount(3, $tokens[0]['alternatives']);
        $this->assertContains('por causa', $tokens[0]['alternatives']);
        $this->assertContains('por meio', $tokens[0]['alternatives']);
        $this->assertContains('apesar', $tokens[0]['alternatives']);
    }

    /**
     * Test DOT export
     */
    public function test_exports_to_dot(): void
    {
        $pattern = 'a b';
        $graph = $this->compiler->compile($pattern);
        $dot = $this->compiler->toDot($graph);

        $this->assertStringContainsString('digraph BNFPattern', $dot);
        $this->assertStringContainsString('START', $dot);
        $this->assertStringContainsString('END', $dot);
        $this->assertStringContainsString('->', $dot);
    }

    /**
     * Test JSON export
     */
    public function test_exports_to_json(): void
    {
        $pattern = '{NOUN}';
        $graph = $this->compiler->compile($pattern);
        $json = $this->compiler->toJson($graph);

        $decoded = json_decode($json, true);
        $this->assertArrayHasKey('nodes', $decoded);
        $this->assertArrayHasKey('edges', $decoded);
    }

    /**
     * Test Portuguese number pattern
     */
    public function test_compiles_portuguese_number_pattern(): void
    {
        $pattern = '[{NUM_UNIT}] mil [, ] [{NUM_HUNDRED}] [e {NUM_TEN}] [e {NUM_UNIT}]';
        $graph = $this->compiler->compile($pattern);

        // Should compile without errors
        $this->assertNotEmpty($graph['nodes']);
        $this->assertNotEmpty($graph['edges']);

        // Validate the pattern
        $result = $this->compiler->validate($pattern);
        $this->assertTrue($result['valid'], 'Pattern should be valid');
    }

    /**
     * Test case insensitivity for literals
     */
    public function test_literals_are_lowercased(): void
    {
        $pattern = 'Palavra TESTE';
        $graph = $this->compiler->compile($pattern);

        $literalNodes = array_filter($graph['nodes'], fn ($n) => $n['type'] === 'LITERAL');
        $values = array_column($literalNodes, 'value');

        $this->assertContains('palavra', $values);
        $this->assertContains('teste', $values);
    }

    /**
     * Test empty pattern
     */
    public function test_handles_empty_pattern(): void
    {
        $pattern = '';
        $graph = $this->compiler->compile($pattern);

        // Should have at least START and END nodes
        $this->assertArrayHasKey('nodes', $graph);
        $this->assertGreaterThanOrEqual(2, count($graph['nodes']));
    }

    /**
     * Test whitespace handling
     */
    public function test_handles_extra_whitespace(): void
    {
        $pattern = '  a    b   c  ';
        $graph = $this->compiler->compile($pattern);

        $literalNodes = array_filter($graph['nodes'], fn ($n) => $n['type'] === 'LITERAL');
        $this->assertCount(3, $literalNodes);
    }

    /**
     * Test Portuguese date pattern
     */
    public function test_compiles_portuguese_date_pattern(): void
    {
        $pattern = '{NUM} de (janeiro | fevereiro | março) [de {NUM}]';
        $graph = $this->compiler->compile($pattern);

        $this->assertNotEmpty($graph['nodes']);
        $this->assertNotEmpty($graph['edges']);

        $result = $this->compiler->validate($pattern);
        $this->assertTrue($result['valid']);
    }

    /**
     * Test complex preposition pattern
     */
    public function test_compiles_complex_preposition_pattern(): void
    {
        $pattern = '(por causa | por meio | por falta | apesar) de [a | o | as | os]';
        $graph = $this->compiler->compile($pattern);

        $this->assertNotEmpty($graph['nodes']);
        $this->assertNotEmpty($graph['edges']);

        $result = $this->compiler->validate($pattern);
        $this->assertTrue($result['valid']);
    }
}
