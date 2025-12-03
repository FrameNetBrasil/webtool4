<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Parser Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the graph-based predictive parser with multi-word
    | expression processing inspired by Relational Network Theory.
    |
    */

    // Default grammar graph to use
    'defaultGrammarId' => 1,

    // Default language
    'defaultLanguage' => 'pt',

    // Focus queue strategy: 'fifo' or 'lifo'
    'queueStrategy' => env('PARSER_QUEUE_STRATEGY', 'fifo'),

    // Activation parameters
    'activation' => [
        // Minimum threshold for single words
        'minThreshold' => 1,

        // Whether to use weighted activation
        'weighted' => false,

        // Decay rate for activation over time (future feature)
        'decayRate' => 0.0,
    ],

    // MWE processing parameters
    'mwe' => [
        // Maximum MWE length to process
        'maxLength' => 10,

        // Whether to allow partial MWE matches
        'allowPartial' => false,

        // Strategy for competing MWEs: 'longest', 'first', 'all'
        'competitionStrategy' => 'longest',

        // Whether to generate prefix hierarchy automatically
        'generatePrefixes' => true,
    ],

    // Parse validation parameters
    'validation' => [
        // Require all nodes to be connected
        'requireConnected' => true,

        // Allow isolated nodes
        'allowIsolated' => false,

        // Minimum edge count (nodes - 1 for connected graph)
        'minEdgeRatio' => 0.9,
    ],

    // Garbage collection parameters
    'garbageCollection' => [
        // Remove nodes below threshold
        'enabled' => true,

        // Keep incomplete MWE prefixes for debugging
        'keepIncompleteMWE' => false,
    ],

    // Prediction parameters
    'prediction' => [
        // Minimum weight for predictions to be considered
        'minWeight' => 0.1,

        // Maximum prediction depth
        'maxDepth' => 3,

        // Use recursive linking
        'recursiveLinking' => true,
    ],

    // Visualization parameters
    'visualization' => [
        // Default graph layout: 'force', 'hierarchical', 'circular'
        'layout' => 'force',

        // Node colors by type
        'nodeColors' => [
            'E' => '#4CAF50', // Green for Entities
            'R' => '#2196F3', // Blue for Relational
            'A' => '#FF9800', // Orange for Attributes
            'F' => '#9E9E9E', // Gray for Fixed
            'MWE' => '#9C27B0', // Purple for MWEs
        ],

        // Edge colors by type
        'edgeColors' => [
            'sequential' => '#757575',
            'activate' => '#FF5722',
            'dependency' => '#000000',
            'prediction' => '#03A9F4',
        ],

        // Node size parameters
        'nodeSize' => [
            'min' => 10,
            'max' => 30,
            'scale' => 'threshold', // 'threshold', 'activation', 'constant'
        ],

        // Edge width parameters
        'edgeWidth' => [
            'min' => 1,
            'max' => 5,
            'scale' => 'weight', // 'weight', 'constant'
        ],
    ],

    // Word type mappings (UPOS to parser types)
    'wordTypeMappings' => [
        // Entities
        'E' => ['NOUN', 'PROPN', 'PRON'],

        // Relational
        'R' => ['VERB', 'AUX'],

        // Attributes
        'A' => ['ADJ', 'ADV', 'NUM'],

        // Fixed (function words)
        'F' => ['ADP', 'DET', 'CONJ', 'CCONJ', 'SCONJ', 'PART', 'INTJ'],
    ],

    // Trankit UD Parser configuration
    'trankit' => [
        'url' => config('udparser.trankit_url', env('TRANKIT_URL', 'http://localhost:8405')),
        'timeout' => config('udparser.timeout', 300),
    ],

    // Language ID mappings
    'languageMap' => [
        'pt' => 1,  // Portuguese
        'en' => 2,  // English
    ],

    // Logging parameters
    'logging' => [
        // Log parse steps
        'logSteps' => env('PARSER_LOG_STEPS', false),

        // Log MWE activation
        'logMWE' => env('PARSER_LOG_MWE', false),

        // Log focus queue changes
        'logQueue' => env('PARSER_LOG_QUEUE', false),
    ],

    // Performance parameters
    'performance' => [
        // Maximum parse time in seconds
        'maxParseTime' => 30,

        // Maximum sentence length
        'maxSentenceLength' => 100,

        // Enable caching
        'cacheEnabled' => false,

        // Cache TTL in seconds
        'cacheTTL' => 3600,
    ],
];
