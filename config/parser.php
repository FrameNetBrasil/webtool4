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

    // Stage control (three-stage parsing framework)
    'stages' => [
        'enableTranscription' => env('PARSER_ENABLE_TRANSCRIPTION', true),  // Stage 1: Word → PhrasalCE
        'enableTranslation' => env('PARSER_ENABLE_TRANSLATION', true),      // Stage 2: PhrasalCE → ClausalCE
        'enableFolding' => env('PARSER_ENABLE_FOLDING', true),              // Stage 3: ClausalCE → SententialCE
    ],

    // Feature system (morphological features from UD)
    'features' => [
        'enableCompatibilityCheck' => env('PARSER_ENABLE_FEATURES', true),
        'minCompatibilityScore' => env('PARSER_MIN_COMPAT_SCORE', 0.5),
        'extractedFeatures' => [
            'Gender', 'Number', 'Case', 'Person',
            'Tense', 'Aspect', 'Mood', 'VerbForm',
            'Definite', 'PronType', 'Polarity',
        ],
    ],

    // Language profiles (feature emphasis per language)
    'languageProfiles' => [
        'pt' => [
            'name' => 'Portuguese',
            'agreementFeatures' => [
                'Gender' => 1.0,   // Strong gender agreement
                'Number' => 1.0,   // Strong number agreement
                'Person' => 0.8,   // Person agreement in verbs
            ],
            'caseFeatures' => [
                'Case' => 0.5,     // Weak case (mostly pronouns)
            ],
            'positionWeight' => 0.3,  // Moderate word order constraints
            'emphasis' => 'agreement',
        ],
        'en' => [
            'name' => 'English',
            'agreementFeatures' => [
                'Number' => 0.6,   // Limited number agreement
                'Person' => 0.5,
            ],
            'caseFeatures' => [
                'Case' => 0.8,     // Moderate case (I/me, he/him)
            ],
            'positionWeight' => 1.5,  // Strong word order constraints
            'emphasis' => 'position',
        ],
    ],

    // Translation stage parameters (Phase 2)
    'translation' => [
        'maxPhraseDistance' => 3,      // Local dependencies only
        'requireAgreement' => true,
    ],

    // Folding stage parameters (Phase 3)
    'folding' => [
        'allowNonProjective' => true,  // Allow crossing edges
        'maxLongDistance' => 10,       // Max distance for long-range dependencies
    ],

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
        'R' => ['VERB', 'AUX', 'ADP', 'CONJ', 'CCONJ', 'SCONJ', 'PUNCT'],

        // Attributes
        'A' => ['ADJ', 'ADV', 'NUM'],

        // Fixed (function words)
        'F' => ['DET', 'PART', 'INTJ'],
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

        // Log morphological features
        'logFeatures' => env('PARSER_LOG_FEATURES', false),

        // Log stage transitions
        'logStages' => env('PARSER_LOG_STAGES', false),
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
