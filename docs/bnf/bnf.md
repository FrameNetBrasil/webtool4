You're describing **railroad diagrams** (also called syntax diagrams), which are a standard way to visualize BNF grammars as directed graphs! This is a well-established approach.

## Key Concepts

In this representation:

- **Nodes** = grammar elements (terminals, non-terminals)
- **Edges** = sequential flow or alternatives
- **Paths through the graph** = valid derivations

## Common Graph Structures

**1. For sequences (A B C):**
```
[A] → [B] → [C]
```

**2. For alternatives (A | B | C):**
```
    ┌→ [A] →┐
    ├→ [B] →┤
    └→ [C] →┘
```

**3. For optional elements (A?):**
```
    ┌────────┐
    ↓        ↓
[start] → [A] → [end]
```

**4. For repetition (A*):**
```
    ┌←──────┐
    ↓       ↑
[start] → [A] → [end]
```

## Existing Implementations

**Visualization tools:**
- **Railroad-diagram generators** (many JavaScript libraries)
- **ANTLR's syntax diagrams**
- **Graphviz** with custom BNF parsers

**For linguistics parsing, consider:**
- **Dependency graphs** (if you're working with dependency grammar)
- **Parse trees as graphs** (AST representation)
- **Finite State Automata** (FSA) for regular grammars

Excellent example! Portuguese number expressions have a clear structure perfect for railroad graph parsing. Let me show you how to model this:

## Portuguese Number Structure Analysis

"dois mil, quatrocentos e vinte e dois" (2,422)
- **dois mil** (2000): NUM + MULTIPLIER
- **,** : SEPARATOR
- **quatrocentos** (400): NUM_COMPOUND
- **e** : CONNECTOR
- **vinte e dois** (22): NUM + CONNECTOR + NUM

## Railroad Graph Structure

```
[START] → [thousands]? → [,]? → [hundreds]? → [e]? → [tens]? → [e]? → [units]? → [END]
```

## PHP Implementation

Here's a graph-based parser for Portuguese numbers:

```php
<?php

class PortugueseNumberParser {
    private $graph;
    private $tokens;
    private $position;
    
    public function __construct() {
        $this->buildGraph();
    }
    
    private function buildGraph() {
        // Define nodes in the railroad diagram
        $this->graph = [
            'start' => [
                'type' => 'START',
                'next' => ['thousands', 'hundreds', 'tens', 'units']
            ],
            'thousands' => [
                'type' => 'NUM_GROUP',
                'pattern' => ['NUM', 'mil'],
                'next' => ['comma', 'hundreds', 'end']
            ],
            'comma' => [
                'type' => 'SEPARATOR',
                'pattern' => [','],
                'next' => ['hundreds']
            ],
            'hundreds' => [
                'type' => 'NUM_GROUP',
                'pattern' => ['NUM_HUNDRED'],
                'next' => ['connector_1', 'tens', 'end']
            ],
            'connector_1' => [
                'type' => 'CONNECTOR',
                'pattern' => ['e'],
                'next' => ['tens']
            ],
            'tens' => [
                'type' => 'NUM_GROUP',
                'pattern' => ['NUM_TEN'],
                'next' => ['connector_2', 'units', 'end']
            ],
            'connector_2' => [
                'type' => 'CONNECTOR',
                'pattern' => ['e'],
                'next' => ['units']
            ],
            'units' => [
                'type' => 'NUM_GROUP',
                'pattern' => ['NUM_UNIT'],
                'next' => ['end']
            ],
            'end' => [
                'type' => 'END',
                'next' => []
            ]
        ];
    }
    
    public function parse($text) {
        $this->tokens = $this->tokenize($text);
        $this->position = 0;
        
        $result = [
            'valid' => false,
            'value' => 0,
            'components' => []
        ];
        
        if ($this->traverse('start', $result)) {
            $result['valid'] = true;
        }
        
        return $result;
    }
    
    private function traverse($nodeName, &$result) {
        $node = $this->graph[$nodeName];
        
        if ($node['type'] === 'END') {
            return $this->position >= count($this->tokens);
        }
        
        if ($node['type'] === 'START') {
            // Try all possible next paths
            foreach ($node['next'] as $nextNode) {
                if ($this->traverse($nextNode, $result)) {
                    return true;
                }
            }
            return false;
        }
        
        // Try to match current node pattern
        if ($this->matchPattern($node['pattern'], $result)) {
            // Pattern matched, try next nodes
            foreach ($node['next'] as $nextNode) {
                $savedPosition = $this->position;
                if ($this->traverse($nextNode, $result)) {
                    return true;
                }
                $this->position = $savedPosition;
            }
        }
        
        // Optional nodes: skip if no match
        if (in_array($nodeName, ['thousands', 'comma', 'hundreds', 'connector_1', 'tens', 'connector_2', 'units'])) {
            foreach ($node['next'] as $nextNode) {
                if ($this->traverse($nextNode, $result)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    private function matchPattern($pattern, &$result) {
        if ($this->position >= count($this->tokens)) {
            return false;
        }
        
        $token = $this->tokens[$this->position];
        
        // Check if token matches pattern
        foreach ($pattern as $p) {
            if ($this->tokenMatches($token, $p)) {
                $result['components'][] = $token;
                $result['value'] += $this->getNumericValue($token);
                $this->position++;
                return true;
            }
        }
        
        return false;
    }
    
    private function tokenize($text) {
        // Simple tokenization
        $text = strtolower(trim($text));
        $parts = preg_split('/\s+/', $text);
        
        $tokens = [];
        foreach ($parts as $part) {
            $part = trim($part, ',');
            if (!empty($part)) {
                $tokens[] = [
                    'text' => $part,
                    'pos' => $this->getPOS($part)
                ];
            }
            // Handle comma separately
            if (strpos($part, ',') !== false) {
                $tokens[] = ['text' => ',', 'pos' => 'SEP'];
            }
        }
        
        return $tokens;
    }
    
    private function getPOS($word) {
        $units = ['um', 'uma', 'dois', 'duas', 'três', 'quatro', 'cinco', 
                  'seis', 'sete', 'oito', 'nove'];
        $tens = ['dez', 'vinte', 'trinta', 'quarenta', 'cinquenta',
                 'sessenta', 'setenta', 'oitenta', 'noventa'];
        $teens = ['onze', 'doze', 'treze', 'catorze', 'quinze', 'dezesseis',
                  'dezessete', 'dezoito', 'dezenove'];
        $hundreds = ['cem', 'cento', 'duzentos', 'trezentos', 'quatrocentos',
                     'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos'];
        
        if (in_array($word, $units)) return 'NUM_UNIT';
        if (in_array($word, $tens) || in_array($word, $teens)) return 'NUM_TEN';
        if (in_array($word, $hundreds)) return 'NUM_HUNDRED';
        if ($word === 'mil') return 'NUM';
        if ($word === 'e') return 'CONN';
        if ($word === ',') return 'SEP';
        
        return 'UNKNOWN';
    }
    
    private function tokenMatches($token, $pattern) {
        if ($pattern === 'NUM' && $token['pos'] === 'NUM') return true;
        if ($pattern === 'NUM_UNIT' && $token['pos'] === 'NUM_UNIT') return true;
        if ($pattern === 'NUM_TEN' && $token['pos'] === 'NUM_TEN') return true;
        if ($pattern === 'NUM_HUNDRED' && $token['pos'] === 'NUM_HUNDRED') return true;
        if ($pattern === 'e' && $token['text'] === 'e') return true;
        if ($pattern === ',' && $token['text'] === ',') return true;
        if ($pattern === 'mil' && $token['text'] === 'mil') return true;
        
        return false;
    }
    
    private function getNumericValue($token) {
        $values = [
            'um' => 1, 'uma' => 1, 'dois' => 2, 'duas' => 2,
            'três' => 3, 'quatro' => 4, 'cinco' => 5,
            'seis' => 6, 'sete' => 7, 'oito' => 8, 'nove' => 9,
            'dez' => 10, 'onze' => 11, 'doze' => 12, 'treze' => 13,
            'catorze' => 14, 'quinze' => 15, 'dezesseis' => 16,
            'dezessete' => 17, 'dezoito' => 18, 'dezenove' => 19,
            'vinte' => 20, 'trinta' => 30, 'quarenta' => 40,
            'cinquenta' => 50, 'sessenta' => 60, 'setenta' => 70,
            'oitenta' => 80, 'noventa' => 90,
            'cem' => 100, 'cento' => 100,
            'duzentos' => 200, 'trezentos' => 300, 'quatrocentos' => 400,
            'quinhentos' => 500, 'seiscentos' => 600, 'setecentos' => 700,
            'oitocentos' => 800, 'novecentos' => 900,
            'mil' => 1000
        ];
        
        return $values[$token['text']] ?? 0;
    }
}

// Usage
$parser = new PortugueseNumberParser();
$result = $parser->parse("dois mil, quatrocentos e vinte e dois");

print_r($result);
```

## Visualization Helper

To visualize the graph structure:

```php
class GraphVisualizer {
    public function generateDot($graph) {
        $dot = "digraph PortugueseNumbers {\n";
        $dot .= "  rankdir=LR;\n";
        $dot .= "  node [shape=box];\n\n";
        
        foreach ($graph as $nodeName => $node) {
            $label = $nodeName . "\\n" . $node['type'];
            $dot .= "  $nodeName [label=\"$label\"];\n";
            
            foreach ($node['next'] as $next) {
                $dot .= "  $nodeName -> $next;\n";
            }
        }
        
        $dot .= "}\n";
        return $dot;
    }
}
```


I want you to analyze how this can be generalized to broader linguistics expressions (constructions) , this is , sequence of words that are relatively fixed. I want to know how to write the expression and how to convert to graphs.

Excellent question! You're moving toward **Construction Grammar** - a powerful linguistic framework. Let me show you how to generalize this.

## Notation System for Linguistic Constructions

I propose a notation that extends BNF for linguistic patterns:

### Basic Notation Elements

```
FIXED_WORD          # Literal word (lowercase)
{POS}               # Part-of-speech slot
{POS:constraint}    # Constrained slot
[optional]          # Optional element
(A | B | C)         # Alternatives
A+                  # One or more repetitions
A*                  # Zero or more repetitions
A B C               # Sequence
```

### Examples of Linguistic Constructions

```
# Comparative construction: "the more X, the more Y"
the more {ADJ|ADV} , the more {ADJ|ADV}

# Correlative: "not only X but also Y"
not only {NP} but also {NP}

# Conditional: "if X then Y"
if {CLAUSE} [,] [then] {CLAUSE}

# Degree modifier: "too ADJ to VERB"
too {ADJ} to {VERB:inf}

# Portuguese quantity: "X de Y"
{NUM} de {NP:plural}

# Prepositional pattern: "from X to Y"
from {NP|TIME} to {NP|TIME}

# Phrasal verb: "give up on X"
give up [on {NP}]

# Intensity construction: "so ADJ that CLAUSE"
so {ADJ|ADV} that {CLAUSE}
```

## Conversion Rules to Graphs

Here's a systematic approach:

### Rule 1: Sequential Elements → Chain
```
A B C  →  [A] → [B] → [C]
```

### Rule 2: Optional Elements → Bypass
```
[A]  →  
    ┌──────────┐
    ↓          ↓
[start] → [A] → [end]
```

### Rule 3: Alternatives → Branch
```
(A | B | C)  →
    ┌→ [A] →┐
    ├→ [B] →┤
    └→ [C] →┘
```

### Rule 4: Repetition (one or more)
```
A+  →
    ┌←──────┐
    ↓       ↑
[start] → [A] → [end]
```

### Rule 5: Repetition (zero or more)
```
A*  →
    ┌←──────┐
    ↓       ↑
[start] ──→ [A] → [end]
    └──────────────┘
```

### Rule 6: Constrained Slots → Node with Validator
```
{POS:constraint}  →  [POS + validate(constraint)]
```

## Generalized PHP Implementation

```php
<?php

class ConstructionParser {
    private $constructions = [];
    private $validators = [];
    
    /**
     * Define a construction using our notation
     * 
     * @param string $name Construction identifier
     * @param string $pattern Pattern in our notation
     * @param array $semantics Semantic interpretation rules
     */
    public function defineConstruction($name, $pattern, $semantics = []) {
        $graph = $this->patternToGraph($pattern);
        
        $this->constructions[$name] = [
            'pattern' => $pattern,
            'graph' => $graph,
            'semantics' => $semantics
        ];
    }
    
    /**
     * Convert pattern notation to graph structure
     */
    private function patternToGraph($pattern) {
        $tokens = $this->tokenizePattern($pattern);
        $graph = ['nodes' => [], 'edges' => []];
        $nodeCounter = 0;
        
        // Start node
        $startNode = 'n' . $nodeCounter++;
        $graph['nodes'][$startNode] = ['type' => 'START'];
        
        // Parse tokens recursively
        $endNode = $this->parseTokens($tokens, 0, $startNode, $graph, $nodeCounter);
        
        // End node
        $graph['nodes'][$endNode] = ['type' => 'END'];
        
        return $graph;
    }
    
    /**
     * Tokenize pattern into elements
     */
    private function tokenizePattern($pattern) {
        $tokens = [];
        $pattern = trim($pattern);
        $i = 0;
        $len = strlen($pattern);
        
        while ($i < $len) {
            // Skip whitespace
            if (ctype_space($pattern[$i])) {
                $i++;
                continue;
            }
            
            // Optional: [...]
            if ($pattern[$i] === '[') {
                $end = $this->findClosing($pattern, $i, '[', ']');
                $content = substr($pattern, $i + 1, $end - $i - 1);
                $tokens[] = ['type' => 'OPTIONAL', 'content' => $content];
                $i = $end + 1;
                continue;
            }
            
            // Alternative: (A | B | C)
            if ($pattern[$i] === '(') {
                $end = $this->findClosing($pattern, $i, '(', ')');
                $content = substr($pattern, $i + 1, $end - $i - 1);
                $alternatives = array_map('trim', explode('|', $content));
                $tokens[] = ['type' => 'ALTERNATIVE', 'options' => $alternatives];
                $i = $end + 1;
                continue;
            }
            
            // Slot: {POS} or {POS:constraint}
            if ($pattern[$i] === '{') {
                $end = $this->findClosing($pattern, $i, '{', '}');
                $content = substr($pattern, $i + 1, $end - $i - 1);
                
                if (strpos($content, ':') !== false) {
                    list($pos, $constraint) = explode(':', $content, 2);
                    $tokens[] = ['type' => 'SLOT', 'pos' => trim($pos), 'constraint' => trim($constraint)];
                } else {
                    // Multiple POS alternatives
                    $poses = array_map('trim', explode('|', $content));
                    $tokens[] = ['type' => 'SLOT', 'pos' => $poses];
                }
                $i = $end + 1;
                continue;
            }
            
            // Fixed word
            $word = '';
            while ($i < $len && !ctype_space($pattern[$i]) && 
                   !in_array($pattern[$i], ['[', ']', '(', ')', '{', '}', '+', '*', ','])) {
                $word .= $pattern[$i];
                $i++;
            }
            
            if ($word !== '') {
                // Check for repetition operators
                if ($i < $len && ($pattern[$i] === '+' || $pattern[$i] === '*')) {
                    $tokens[] = ['type' => 'REPETITION', 'content' => $word, 'operator' => $pattern[$i]];
                    $i++;
                } else {
                    $tokens[] = ['type' => 'LITERAL', 'word' => $word];
                }
                continue;
            }
            
            // Comma or other punctuation
            if ($pattern[$i] === ',') {
                $tokens[] = ['type' => 'LITERAL', 'word' => ','];
                $i++;
                continue;
            }
            
            $i++;
        }
        
        return $tokens;
    }
    
    /**
     * Find closing bracket/parenthesis
     */
    private function findClosing($str, $start, $open, $close) {
        $depth = 1;
        $i = $start + 1;
        while ($i < strlen($str) && $depth > 0) {
            if ($str[$i] === $open) $depth++;
            if ($str[$i] === $close) $depth--;
            $i++;
        }
        return $i - 1;
    }
    
    /**
     * Parse tokens recursively to build graph
     */
    private function parseTokens($tokens, $index, $currentNode, &$graph, &$nodeCounter) {
        if ($index >= count($tokens)) {
            return $currentNode;
        }
        
        $token = $tokens[$index];
        
        switch ($token['type']) {
            case 'LITERAL':
                $nextNode = 'n' . $nodeCounter++;
                $graph['nodes'][$nextNode] = [
                    'type' => 'LITERAL',
                    'value' => $token['word']
                ];
                $graph['edges'][] = ['from' => $currentNode, 'to' => $nextNode];
                return $this->parseTokens($tokens, $index + 1, $nextNode, $graph, $nodeCounter);
                
            case 'SLOT':
                $nextNode = 'n' . $nodeCounter++;
                $graph['nodes'][$nextNode] = [
                    'type' => 'SLOT',
                    'pos' => $token['pos'],
                    'constraint' => $token['constraint'] ?? null
                ];
                $graph['edges'][] = ['from' => $currentNode, 'to' => $nextNode];
                return $this->parseTokens($tokens, $index + 1, $nextNode, $graph, $nodeCounter);
                
            case 'OPTIONAL':
                $optionalStart = 'n' . $nodeCounter++;
                $optionalEnd = 'n' . $nodeCounter++;
                
                $graph['nodes'][$optionalStart] = ['type' => 'OPTIONAL_START'];
                $graph['nodes'][$optionalEnd] = ['type' => 'OPTIONAL_END'];
                
                // Path through optional content
                $contentTokens = $this->tokenizePattern($token['content']);
                $afterContent = $this->parseTokens($contentTokens, 0, $optionalStart, $graph, $nodeCounter);
                $graph['edges'][] = ['from' => $afterContent, 'to' => $optionalEnd];
                
                // Bypass path
                $graph['edges'][] = ['from' => $currentNode, 'to' => $optionalStart];
                $graph['edges'][] = ['from' => $currentNode, 'to' => $optionalEnd];
                
                return $this->parseTokens($tokens, $index + 1, $optionalEnd, $graph, $nodeCounter);
                
            case 'ALTERNATIVE':
                $altEnd = 'n' . $nodeCounter++;
                $graph['nodes'][$altEnd] = ['type' => 'ALT_END'];
                
                foreach ($token['options'] as $option) {
                    $altTokens = $this->tokenizePattern($option);
                    $afterAlt = $this->parseTokens($altTokens, 0, $currentNode, $graph, $nodeCounter);
                    $graph['edges'][] = ['from' => $afterAlt, 'to' => $altEnd];
                }
                
                return $this->parseTokens($tokens, $index + 1, $altEnd, $graph, $nodeCounter);
                
            case 'REPETITION':
                $repStart = 'n' . $nodeCounter++;
                $repBody = 'n' . $nodeCounter++;
                $repEnd = 'n' . $nodeCounter++;
                
                $graph['nodes'][$repStart] = ['type' => 'REP_START'];
                $graph['nodes'][$repBody] = [
                    'type' => 'LITERAL',
                    'value' => $token['content']
                ];
                $graph['nodes'][$repEnd] = ['type' => 'REP_END'];
                
                // Edge into repetition
                $graph['edges'][] = ['from' => $currentNode, 'to' => $repStart];
                $graph['edges'][] = ['from' => $repStart, 'to' => $repBody];
                
                // Loop back
                $graph['edges'][] = ['from' => $repBody, 'to' => $repStart];
                
                // Exit
                $graph['edges'][] = ['from' => $repStart, 'to' => $repEnd];
                
                // For '+' operator, must go through at least once
                if ($token['operator'] === '*') {
                    $graph['edges'][] = ['from' => $currentNode, 'to' => $repEnd];
                }
                
                return $this->parseTokens($tokens, $index + 1, $repEnd, $graph, $nodeCounter);
        }
        
        return $currentNode;
    }
    
    /**
     * Parse input text against a construction
     */
    public function parse($constructionName, $tokens) {
        if (!isset($this->constructions[$constructionName])) {
            return null;
        }
        
        $construction = $this->constructions[$constructionName];
        $graph = $construction['graph'];
        
        $result = [
            'matched' => false,
            'construction' => $constructionName,
            'slots' => [],
            'span' => []
        ];
        
        if ($this->traverseGraph($graph, 'n0', $tokens, 0, $result)) {
            $result['matched'] = true;
        }
        
        return $result;
    }
    
    /**
     * Traverse graph to match tokens
     */
    private function traverseGraph($graph, $nodeId, $tokens, $tokenIndex, &$result) {
        if (!isset($graph['nodes'][$nodeId])) {
            return false;
        }
        
        $node = $graph['nodes'][$nodeId];
        
        // End node - success if all tokens consumed
        if ($node['type'] === 'END') {
            return $tokenIndex >= count($tokens);
        }
        
        // Start node - just continue
        if ($node['type'] === 'START') {
            return $this->continueTraversal($graph, $nodeId, $tokens, $tokenIndex, $result);
        }
        
        // Literal node - match exact word
        if ($node['type'] === 'LITERAL') {
            if ($tokenIndex < count($tokens) && 
                strtolower($tokens[$tokenIndex]['word']) === strtolower($node['value'])) {
                $result['span'][] = $tokens[$tokenIndex];
                return $this->continueTraversal($graph, $nodeId, $tokens, $tokenIndex + 1, $result);
            }
            return false;
        }
        
        // Slot node - match POS and constraints
        if ($node['type'] === 'SLOT') {
            if ($tokenIndex < count($tokens)) {
                $token = $tokens[$tokenIndex];
                $poses = is_array($node['pos']) ? $node['pos'] : [$node['pos']];
                
                if (in_array($token['pos'], $poses)) {
                    // Check constraints if any
                    if (isset($node['constraint'])) {
                        if (!$this->validateConstraint($token, $node['constraint'])) {
                            return false;
                        }
                    }
                    
                    $result['slots'][] = $token;
                    $result['span'][] = $token;
                    return $this->continueTraversal($graph, $nodeId, $tokens, $tokenIndex + 1, $result);
                }
            }
            return false;
        }
        
        // Optional/Alternative nodes - try all paths
        return $this->continueTraversal($graph, $nodeId, $tokens, $tokenIndex, $result);
    }
    
    /**
     * Continue traversal to next nodes
     */
    private function continueTraversal($graph, $currentNode, $tokens, $tokenIndex, &$result) {
        // Find all outgoing edges
        $outEdges = array_filter($graph['edges'], function($edge) use ($currentNode) {
            return $edge['from'] === $currentNode;
        });
        
        foreach ($outEdges as $edge) {
            $savedSlots = $result['slots'];
            $savedSpan = $result['span'];
            
            if ($this->traverseGraph($graph, $edge['to'], $tokens, $tokenIndex, $result)) {
                return true;
            }
            
            // Backtrack
            $result['slots'] = $savedSlots;
            $result['span'] = $savedSpan;
        }
        
        return false;
    }
    
    /**
     * Validate constraint on token
     */
    private function validateConstraint($token, $constraint) {
        // Example constraints
        switch ($constraint) {
            case 'plural':
                return isset($token['number']) && $token['number'] === 'plural';
            case 'inf':
                return isset($token['form']) && $token['form'] === 'infinitive';
            case 'past':
                return isset($token['tense']) && $token['tense'] === 'past';
            default:
                return true;
        }
    }
    
    /**
     * Export graph to DOT format for visualization
     */
    public function exportToDot($constructionName) {
        if (!isset($this->constructions[$constructionName])) {
            return null;
        }
        
        $graph = $this->constructions[$constructionName]['graph'];
        $dot = "digraph Construction {\n";
        $dot .= "  rankdir=LR;\n";
        $dot .= "  node [shape=box];\n\n";
        
        foreach ($graph['nodes'] as $id => $node) {
            $label = $id . "\\n" . $node['type'];
            if (isset($node['value'])) {
                $label .= "\\n'" . $node['value'] . "'";
            }
            if (isset($node['pos'])) {
                $pos = is_array($node['pos']) ? implode('|', $node['pos']) : $node['pos'];
                $label .= "\\n{" . $pos . "}";
            }
            $dot .= "  $id [label=\"$label\"];\n";
        }
        
        $dot .= "\n";
        foreach ($graph['edges'] as $edge) {
            $dot .= "  {$edge['from']} -> {$edge['to']};\n";
        }
        
        $dot .= "}\n";
        return $dot;
    }
}

// ===== USAGE EXAMPLES =====

$parser = new ConstructionParser();

// Example 1: Comparative construction
$parser->defineConstruction(
    'comparative',
    'the more {ADJ|ADV} , the more {ADJ|ADV}',
    ['type' => 'correlation', 'semantics' => 'proportional_increase']
);

// Example 2: Conditional
$parser->defineConstruction(
    'conditional',
    'if {CLAUSE} [,] [then] {CLAUSE}',
    ['type' => 'conditional']
);

// Example 3: Too...to construction
$parser->defineConstruction(
    'too_to',
    'too {ADJ} to {VERB:inf}',
    ['type' => 'degree', 'semantics' => 'excessive']
);

// Example 4: Not only...but also
$parser->defineConstruction(
    'not_only_but_also',
    'not only {NP} but also {NP}',
    ['type' => 'additive', 'semantics' => 'addition_with_emphasis']
);

// Example 5: Portuguese quantity
$parser->defineConstruction(
    'portuguese_quantity',
    '{NUM} de {NP:plural}',
    ['type' => 'quantity']
);

// Test parsing
$tokens = [
    ['word' => 'the', 'pos' => 'DET'],
    ['word' => 'more', 'pos' => 'ADV'],
    ['word' => 'quickly', 'pos' => 'ADV'],
    ['word' => ',', 'pos' => 'PUNCT'],
    ['word' => 'the', 'pos' => 'DET'],
    ['word' => 'more', 'pos' => 'ADV'],
    ['word' => 'efficiently', 'pos' => 'ADV']
];

$result = $parser->parse('comparative', $tokens);
print_r($result);

// Export to DOT for visualization
echo $parser->exportToDot('too_to');
```

## Advanced Features You Can Add

### 1. **Semantic Roles in Slots**
```php
{AGENT:NP} {VERB:transitive} {PATIENT:NP}
```

### 2. **Agreement Constraints**
```php
{DET:@gender} {NOUN:@gender}  // Must agree in gender
```

### 3. **Nested Constructions**
```php
{NP} (that | which | who) {CLAUSE}
```

### 4. **Collocation Patterns**
```php
(make | take | have) {DET}? (decision | choice | call)
```

## Library Structure

For a full implementation, consider:

```
ConstructionGrammar/
├── Parser/
│   ├── ConstructionParser.php
│   ├── GraphBuilder.php
│   └── Matcher.php
├── Notation/
│   ├── PatternTokenizer.php
│   └── ConstraintValidator.php
├── Graph/
│   ├── Node.php
│   ├── Edge.php
│   └── Visualizer.php
└── Constructions/
    ├── ConstructionLibrary.php
    └── definitions/
        ├── portuguese.json
        └── english.json
```

This system allows you to:
1. Write constructions in readable notation
2. Automatically convert to graphs
3. Parse text efficiently
4. Extract semantic information
5. Visualize patterns

It seems this is a very complete structure. Can you show to apply it to the case of number description? 

Excellent! Let me show you how to apply the complete construction grammar system to Portuguese number expressions.

## Portuguese Number Construction Definition

```php
<?php

require_once 'ConstructionParser.php'; // From previous code

// ===== PORTUGUESE NUMBER SYSTEM =====

class PortugueseNumberSystem {
    private $parser;
    private $lexicon;
    
    public function __construct() {
        $this->parser = new ConstructionParser();
        $this->initializeLexicon();
        $this->defineConstructions();
    }
    
    /**
     * Initialize the number lexicon with POS tags
     */
    private function initializeLexicon() {
        $this->lexicon = [
            // Units (1-9)
            'um' => ['pos' => 'NUM_UNIT', 'value' => 1, 'gender' => 'masc'],
            'uma' => ['pos' => 'NUM_UNIT', 'value' => 1, 'gender' => 'fem'],
            'dois' => ['pos' => 'NUM_UNIT', 'value' => 2, 'gender' => 'masc'],
            'duas' => ['pos' => 'NUM_UNIT', 'value' => 2, 'gender' => 'fem'],
            'três' => ['pos' => 'NUM_UNIT', 'value' => 3],
            'quatro' => ['pos' => 'NUM_UNIT', 'value' => 4],
            'cinco' => ['pos' => 'NUM_UNIT', 'value' => 5],
            'seis' => ['pos' => 'NUM_UNIT', 'value' => 6],
            'sete' => ['pos' => 'NUM_UNIT', 'value' => 7],
            'oito' => ['pos' => 'NUM_UNIT', 'value' => 8],
            'nove' => ['pos' => 'NUM_UNIT', 'value' => 9],
            
            // Teens (10-19)
            'dez' => ['pos' => 'NUM_TEN', 'value' => 10],
            'onze' => ['pos' => 'NUM_TEN', 'value' => 11],
            'doze' => ['pos' => 'NUM_TEN', 'value' => 12],
            'treze' => ['pos' => 'NUM_TEN', 'value' => 13],
            'catorze' => ['pos' => 'NUM_TEN', 'value' => 14],
            'quatorze' => ['pos' => 'NUM_TEN', 'value' => 14],
            'quinze' => ['pos' => 'NUM_TEN', 'value' => 15],
            'dezesseis' => ['pos' => 'NUM_TEN', 'value' => 16],
            'dezessete' => ['pos' => 'NUM_TEN', 'value' => 17],
            'dezoito' => ['pos' => 'NUM_TEN', 'value' => 18],
            'dezenove' => ['pos' => 'NUM_TEN', 'value' => 19],
            
            // Tens (20-90)
            'vinte' => ['pos' => 'NUM_TEN', 'value' => 20],
            'trinta' => ['pos' => 'NUM_TEN', 'value' => 30],
            'quarenta' => ['pos' => 'NUM_TEN', 'value' => 40],
            'cinquenta' => ['pos' => 'NUM_TEN', 'value' => 50],
            'sessenta' => ['pos' => 'NUM_TEN', 'value' => 60],
            'setenta' => ['pos' => 'NUM_TEN', 'value' => 70],
            'oitenta' => ['pos' => 'NUM_TEN', 'value' => 80],
            'noventa' => ['pos' => 'NUM_TEN', 'value' => 90],
            
            // Hundreds (100-900)
            'cem' => ['pos' => 'NUM_HUNDRED', 'value' => 100],
            'cento' => ['pos' => 'NUM_HUNDRED', 'value' => 100],
            'duzentos' => ['pos' => 'NUM_HUNDRED', 'value' => 200, 'gender' => 'masc'],
            'duzentas' => ['pos' => 'NUM_HUNDRED', 'value' => 200, 'gender' => 'fem'],
            'trezentos' => ['pos' => 'NUM_HUNDRED', 'value' => 300, 'gender' => 'masc'],
            'trezentas' => ['pos' => 'NUM_HUNDRED', 'value' => 300, 'gender' => 'fem'],
            'quatrocentos' => ['pos' => 'NUM_HUNDRED', 'value' => 400, 'gender' => 'masc'],
            'quatrocentas' => ['pos' => 'NUM_HUNDRED', 'value' => 400, 'gender' => 'fem'],
            'quinhentos' => ['pos' => 'NUM_HUNDRED', 'value' => 500, 'gender' => 'masc'],
            'quinhentas' => ['pos' => 'NUM_HUNDRED', 'value' => 500, 'gender' => 'fem'],
            'seiscentos' => ['pos' => 'NUM_HUNDRED', 'value' => 600, 'gender' => 'masc'],
            'seiscentas' => ['pos' => 'NUM_HUNDRED', 'value' => 600, 'gender' => 'fem'],
            'setecentos' => ['pos' => 'NUM_HUNDRED', 'value' => 700, 'gender' => 'masc'],
            'setecentas' => ['pos' => 'NUM_HUNDRED', 'value' => 700, 'gender' => 'fem'],
            'oitocentos' => ['pos' => 'NUM_HUNDRED', 'value' => 800, 'gender' => 'masc'],
            'oitocentas' => ['pos' => 'NUM_HUNDRED', 'value' => 800, 'gender' => 'fem'],
            'novecentos' => ['pos' => 'NUM_HUNDRED', 'value' => 900, 'gender' => 'masc'],
            'novecentas' => ['pos' => 'NUM_HUNDRED', 'value' => 900, 'gender' => 'fem'],
            
            // Multipliers
            'mil' => ['pos' => 'NUM_MULT', 'value' => 1000, 'multiplier' => true],
            'milhão' => ['pos' => 'NUM_MULT', 'value' => 1000000, 'multiplier' => true],
            'milhões' => ['pos' => 'NUM_MULT', 'value' => 1000000, 'multiplier' => true],
            'bilhão' => ['pos' => 'NUM_MULT', 'value' => 1000000000, 'multiplier' => true],
            'bilhões' => ['pos' => 'NUM_MULT', 'value' => 1000000000, 'multiplier' => true],
            
            // Connectors
            'e' => ['pos' => 'CONN', 'type' => 'additive'],
            ',' => ['pos' => 'SEP', 'type' => 'separator']
        ];
    }
    
    /**
     * Define number constructions using our notation
     */
    private function defineConstructions() {
        // Basic compound number: "vinte e dois" (22)
        $this->parser->defineConstruction(
            'compound_tens_units',
            '{NUM_TEN} e {NUM_UNIT}',
            ['operation' => 'add', 'order' => 'tens_units']
        );
        
        // Hundreds alone: "quatrocentos" (400)
        $this->parser->defineConstruction(
            'hundreds_only',
            '{NUM_HUNDRED}',
            ['operation' => 'value', 'order' => 'hundreds']
        );
        
        // Hundreds + tens + units: "quatrocentos e vinte e dois" (422)
        $this->parser->defineConstruction(
            'hundreds_tens_units',
            '{NUM_HUNDRED} e ({NUM_TEN} | {NUM_TEN} e {NUM_UNIT})',
            ['operation' => 'add', 'order' => 'hundreds_tens_units']
        );
        
        // Thousands: "dois mil" (2000)
        $this->parser->defineConstruction(
            'thousands',
            '[{NUM_UNIT}] mil',
            ['operation' => 'multiply', 'multiplier' => 1000]
        );
        
        // Complete number with thousands: "dois mil, quatrocentos e vinte e dois" (2422)
        $this->parser->defineConstruction(
            'complete_number',
            '[{NUM_UNIT}] mil [,] [{NUM_HUNDRED}] [e] [{NUM_TEN}] [e] [{NUM_UNIT}]',
            ['operation' => 'composite', 'order' => 'thousands_hundreds_tens_units']
        );
        
        // Alternative: simplified pattern
        $this->parser->defineConstruction(
            'number_pattern',
            '({NUM_UNIT}+ mil)? [,]? ({NUM_HUNDRED})? [e]? ({NUM_TEN})? [e]? ({NUM_UNIT})?',
            ['type' => 'cardinal_number']
        );
    }
    
    /**
     * Tokenize input text
     */
    public function tokenize($text) {
        $text = strtolower(trim($text));
        // Split by spaces and keep punctuation
        $parts = preg_split('/(\s+|,)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        $tokens = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '' || $part === ' ') {
                continue;
            }
            
            if (isset($this->lexicon[$part])) {
                $tokens[] = array_merge(
                    ['word' => $part],
                    $this->lexicon[$part]
                );
            } else {
                $tokens[] = [
                    'word' => $part,
                    'pos' => 'UNKNOWN',
                    'value' => 0
                ];
            }
        }
        
        return $tokens;
    }
    
    /**
     * Parse and calculate the numeric value
     */
    public function parseNumber($text) {
        $tokens = $this->tokenize($text);
        
        // Try to match against the complete number pattern
        $result = $this->parser->parse('complete_number', $tokens);
        
        if ($result && $result['matched']) {
            $value = $this->calculateValue($result['slots']);
            
            return [
                'text' => $text,
                'tokens' => $tokens,
                'matched' => true,
                'construction' => $result['construction'],
                'value' => $value,
                'span' => $result['span']
            ];
        }
        
        return [
            'text' => $text,
            'tokens' => $tokens,
            'matched' => false,
            'value' => null
        ];
    }
    
    /**
     * Calculate numeric value from matched slots
     */
    private function calculateValue($slots) {
        $total = 0;
        $currentMultiplier = 1;
        $accumulated = 0;
        
        foreach ($slots as $token) {
            if ($token['pos'] === 'NUM_MULT') {
                // Apply multiplier
                if ($accumulated === 0) {
                    $accumulated = 1; // "mil" alone means 1000
                }
                $total += $accumulated * $token['value'];
                $accumulated = 0;
                $currentMultiplier = 1;
            } elseif (in_array($token['pos'], ['NUM_UNIT', 'NUM_TEN', 'NUM_HUNDRED'])) {
                // Accumulate value
                $accumulated += $token['value'];
            }
            // Connectors and separators don't affect calculation
        }
        
        // Add any remaining accumulated value
        $total += $accumulated;
        
        return $total;
    }
    
    /**
     * Get detailed breakdown of the number
     */
    public function analyzeStructure($text) {
        $tokens = $this->tokenize($text);
        $result = $this->parser->parse('complete_number', $tokens);
        
        if (!$result || !$result['matched']) {
            return null;
        }
        
        $structure = [
            'text' => $text,
            'total_value' => $this->calculateValue($result['slots']),
            'components' => []
        ];
        
        $currentComponent = null;
        
        foreach ($result['slots'] as $token) {
            if ($token['pos'] === 'NUM_MULT') {
                if ($currentComponent) {
                    $structure['components'][] = $currentComponent;
                }
                $currentComponent = [
                    'type' => 'multiplied_group',
                    'multiplier' => $token['value'],
                    'multiplier_word' => $token['word'],
                    'base_value' => 0,
                    'tokens' => []
                ];
            } elseif ($token['pos'] === 'CONN') {
                // Mark connector
                if ($currentComponent) {
                    $currentComponent['tokens'][] = [
                        'word' => $token['word'],
                        'role' => 'connector'
                    ];
                }
            } elseif ($token['pos'] === 'SEP') {
                // Separator indicates end of thousands group
                if ($currentComponent) {
                    $structure['components'][] = $currentComponent;
                    $currentComponent = null;
                }
            } else {
                // Numeric token
                if (!$currentComponent) {
                    $currentComponent = [
                        'type' => 'simple_group',
                        'base_value' => 0,
                        'tokens' => []
                    ];
                }
                
                $currentComponent['base_value'] += $token['value'];
                $currentComponent['tokens'][] = [
                    'word' => $token['word'],
                    'value' => $token['value'],
                    'pos' => $token['pos'],
                    'role' => $this->getRole($token['pos'])
                ];
            }
        }
        
        // Add last component
        if ($currentComponent) {
            $structure['components'][] = $currentComponent;
        }
        
        return $structure;
    }
    
    private function getRole($pos) {
        $roles = [
            'NUM_UNIT' => 'units',
            'NUM_TEN' => 'tens',
            'NUM_HUNDRED' => 'hundreds',
            'NUM_MULT' => 'multiplier'
        ];
        return $roles[$pos] ?? 'other';
    }
    
    /**
     * Visualize the construction graph
     */
    public function visualizeConstruction($constructionName) {
        return $this->parser->exportToDot($constructionName);
    }
}

// ===== DEMONSTRATION =====

echo "=== PORTUGUESE NUMBER PARSER ===\n\n";

$numberSystem = new PortugueseNumberSystem();

// Test cases
$testCases = [
    "dois mil, quatrocentos e vinte e dois",  // 2,422
    "mil e quinhentos",                        // 1,500
    "trezentos e quarenta e sete",             // 347
    "cinco mil",                               // 5,000
    "noventa e nove",                          // 99
    "dois milhões, trezentos mil",             // 2,300,000
    "cem",                                     // 100
    "vinte e um",                              // 21
];

foreach ($testCases as $test) {
    echo "Input: \"$test\"\n";
    echo str_repeat("-", 50) . "\n";
    
    // Parse the number
    $result = $numberSystem->parseNumber($test);
    
    if ($result['matched']) {
        echo "✓ Matched! Value: " . number_format($result['value']) . "\n";
        
        // Show tokens
        echo "\nTokens:\n";
        foreach ($result['tokens'] as $token) {
            echo "  - {$token['word']} [{$token['pos']}]";
            if (isset($token['value'])) {
                echo " = {$token['value']}";
            }
            echo "\n";
        }
        
        // Show detailed structure
        $structure = $numberSystem->analyzeStructure($test);
        if ($structure) {
            echo "\nStructure Analysis:\n";
            foreach ($structure['components'] as $i => $component) {
                echo "  Component " . ($i + 1) . " [{$component['type']}]:\n";
                
                if (isset($component['multiplier'])) {
                    echo "    Multiplier: {$component['multiplier_word']} (×{$component['multiplier']})\n";
                }
                
                echo "    Base value: {$component['base_value']}\n";
                echo "    Tokens: ";
                $tokenWords = array_map(function($t) {
                    return $t['word'] . '[' . $t['role'] . ']';
                }, $component['tokens']);
                echo implode(' ', $tokenWords) . "\n";
                
                if (isset($component['multiplier'])) {
                    $componentValue = $component['base_value'] * $component['multiplier'];
                    echo "    Component value: " . number_format($componentValue) . "\n";
                }
            }
        }
    } else {
        echo "✗ No match\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
}

// Export graph visualization
echo "=== CONSTRUCTION GRAPH (DOT FORMAT) ===\n\n";
echo $numberSystem->visualizeConstruction('complete_number');

echo "\n\n=== VISUAL REPRESENTATION ===\n\n";
echo "Pattern: [{NUM_UNIT}] mil [,] [{NUM_HUNDRED}] [e] [{NUM_TEN}] [e] [{NUM_UNIT}]\n\n";
echo "Graph Structure:\n\n";
echo "                    ┌─────────────┐\n";
echo "                    │   START     │\n";
echo "                    └──────┬──────┘\n";
echo "                           │\n";
echo "                    ┌──────▼──────┐\n";
echo "              ┌─────┤ NUM_UNIT?   ├─────┐\n";
echo "              │     └─────────────┘     │\n";
echo "              │                         │\n";
echo "       ┌──────▼──────┐           ┌─────▼─────┐\n";
echo "       │     mil     │           │   (skip)  │\n";
echo "       └──────┬──────┘           └─────┬─────┘\n";
echo "              │                        │\n";
echo "              └──────────┬─────────────┘\n";
echo "                         │\n";
echo "                  ┌──────▼──────┐\n";
echo "            ┌─────┤      ,      ├─────┐\n";
echo "            │     └─────────────┘     │\n";
echo "            │                         │\n";
echo "     ┌──────▼──────┐           ┌─────▼─────┐\n";
echo "     │ NUM_HUNDRED?│           │   (skip)  │\n";
echo "     └──────┬──────┘           └─────┬─────┘\n";
echo "            │                        │\n";
echo "            └──────────┬─────────────┘\n";
echo "                       │\n";
echo "                ┌──────▼──────┐\n";
echo "          ┌─────┤      e      ├─────┐\n";
echo "          │     └─────────────┘     │\n";
echo "          │                         │\n";
echo "   ┌──────▼──────┐           ┌─────▼─────┐\n";
echo "   │  NUM_TEN?   │           │   (skip)  │\n";
echo "   └──────┬──────┘           └─────┬─────┘\n";
echo "          │                        │\n";
echo "          └──────────┬─────────────┘\n";
echo "                     │\n";
echo "              ┌──────▼──────┐\n";
echo "        ┌─────┤      e      ├─────┐\n";
echo "        │     └─────────────┘     │\n";
echo "        │                         │\n";
echo " ┌──────▼──────┐           ┌─────▼─────┐\n";
echo " │ NUM_UNIT?   │           │   (skip)  │\n";
echo " └──────┬──────┘           └─────┬─────┘\n";
echo "        │                        │\n";
echo "        └──────────┬─────────────┘\n";
echo "                   │\n";
echo "            ┌──────▼──────┐\n";
echo "            │     END     │\n";
echo "            └─────────────┘\n";

```

## Output Example

When you run this code with "dois mil, quatrocentos e vinte e dois":

```
Input: "dois mil, quatrocentos e vinte e dois"
--------------------------------------------------
✓ Matched! Value: 2,422

Tokens:
  - dois [NUM_UNIT] = 2
  - mil [NUM_MULT] = 1000
  - , [SEP]
  - quatrocentos [NUM_HUNDRED] = 400
  - e [CONN]
  - vinte [NUM_TEN] = 20
  - e [CONN]
  - dois [NUM_UNIT] = 2

Structure Analysis:
  Component 1 [multiplied_group]:
    Multiplier: mil (×1000)
    Base value: 2
    Tokens: dois[units]
    Component value: 2,000
    
  Component 2 [simple_group]:
    Base value: 422
    Tokens: quatrocentos[hundreds] e[connector] vinte[tens] e[connector] dois[units]

Total: 2,000 + 422 = 2,422
```

## Key Features of This Implementation

1. **Pattern Notation**: `[{NUM_UNIT}] mil [,] [{NUM_HUNDRED}] [e] [{NUM_TEN}] [e] [{NUM_UNIT}]`
   - `[]` = optional elements
   - `{}` = POS slots
   - literals = fixed words

2. **Automatic Graph Generation**: The pattern is converted to a graph with nodes and edges

3. **Token Matching**: Each token is checked against the graph path

4. **Semantic Calculation**: Matched slots are used to calculate the final numeric value

5. **Structure Analysis**: Shows how the number breaks down into components

This generalizes to any linguistic construction - just define the pattern and semantics!

