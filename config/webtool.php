<?php

return [
    'db' => env('DB_CONNECTION', 'fnbr'),
    'lang' => 1,
    'language' => 'pt',
    'defaultIdLanguage' => 1,
    'defaultPassword' => 'default',
    'pageTitle' => 'Webtool 3.8',
    'mainTitle' => 'FrameNet Brasil Webtool 3.8',
    'headerTitle' => 'FNBr Webtool',
    'footer' => '&copy; 2014-2024 FrameNet Brasil Lab, UFJF.',
    'login' => [
        'handler' => env('APP_AUTH'),
        'AUTH0_CLIENT_ID' => env('AUTH0_CLIENT_ID'),
        'AUTH0_CLIENT_SECRET' => env('AUTH0_CLIENT_SECRET'),
        'AUTH0_COOKIE_SECRET' => env('AUTH0_COOKIE_SECRET'),
        'AUTH0_DOMAIN' => env('AUTH0_DOMAIN'),
        'AUTH0_CALLBACK_URL' => env('AUTH0_CALLBACK_URL'),
        'AUTH0_BASE_URL' => env('AUTH0_BASE_URL'),
    ],
    'actions' => [
        'report' => ['Report', '/report', '', [
            'reportframe' => ['Frame', '/report/frame', '', []],
            'reportlu' => ['LU', '/report/lu', '', []],
//            'cxnreport' => ['Constructions', '/cxn/report', 'cxnreport', '', '', []],
//            'corpusAnnotationReport' => ['Corpus Panes', '/corpus/report', 'corpusreport', '', 1, []],
        ]],
        'grapher' => ['Grapher', '/grapher', '', [
            'framegrapher' => ['Frames', '/grapher/frame', '', []],
            'domaingrapher' => ['Domain', '/grapher/domain', '', []],
//            'fullgrapher' => ['Frames & CxN', '/grapher', 'fullgrapher', '', '', []],
//            'domaingrapher' => ['Frames by Domain', '/domain/grapher', 'domaingrapher', '', '', []],
//            'ccngrapher' => ['Constructicon', '/ccn/grapher', 'ccngrapher', '', '', []],
        ]],
        'annotation' => ['Panes', '/annotation', 'MASTER', [
//            'lexicalAnnotation' => ['Frame Mode', '/lexicalAnnotation', 'lexicalAnnotation', '', 1, []],
//            'cnxAnnotation' => ['Construction Mode', '/constructionalAnnotation', 'cxnAnnotation', '', 1, []],
            //'corpusAnnotation' => ['Corpus Mode', '/annotation/corpus', 'MASTER', []],
//            'staticFrameMode1' => ['Static Frame Mode 1', '/annotation/staticFrameMode1', 'MASTER', []],
//            'staticFrameMode2' => ['Static Frame Mode 2', '/annotation/staticFrameMode2', 'MASTER', []],
            'staticEvent' => ['Static Event', '/annotation/staticEvent', 'MASTER', []],
            'fe' => ['FE', '/annotation/fe', 'MASTER', []],
            'dynamicMode' => ['Dynamic Mode', '/annotation/dynamicMode', 'MASTER', []],
//            'layers' => ['Manage Layers', '/layer/formManager', 'fa fa-list fa16px', 'JUNIOR', 1, []],
        ]],
        'structure' => ['Structure', '/structure', 'MASTER', [
            'framestructure' => ['Frame', '/frame', 'MASTER', []],
            'networkstructure' => ['Network', '/network', 'MASTER', []],
            'corpusstructure' => ['Corpus', '/corpus', 'MASTER', []],
            'lexiconstructure' => ['Lexicon', '/lexicon', 'MASTER', []],
//            'cxnstructure' => ['Construction', '/cxn', 'MASTER', []],
//            'lemmastructure' => ['Lemma', '/lemma', 'MASTER', []],
//            'lexemestructure' => ['Lexeme', '/lexeme', 'MASTER', []],
//            'qualia' => ['Qualia', '/qualia', 'menu-qualia', 'MASTER', 1, []],
//            'constrainttype' => ['Constraint Type', '/constrainttype', 'menu-constraint', 'MASTER', 1, []],
//            'conceptstructure' => ['Concept', '/concept', 'menu-concept', '', 1, []],
//            'semantictypetructure' => ['Semantic Type', '/semantictype', 'MASTER', []],
        ]],
        'admin' => ['Admin', '/admin', 'ADMIN', [
            'groupUser' => ['Group/User', '/user', 'ADMIN', []],
            'projectDataset' => ['Project/Dataset', '/dataset', 'ADMIN', []],
            'taskUser' => ['Task/User', '/task', 'ADMIN', []],
            'corpusDocument' => ['Corpus/Document', '/corpus', 'ADMIN', []],
//            'type' => ['Types', '/type', 'ADMIN', []],
//            'relation' => ['Relations', '/relationgroup', 'ADMIN', []],
//            'genre' => ['Genres', '/genre', 'ADMIN', []],
//            'layer' => ['Layers', '/layer', 'ADMIN', []],
//            'constraint' => ['Constraints', '/constraint', 'ADMIN', []],
        ]],
//        'editor' => ['Editor', 'main/visualeditor', 'edit', 'MASTER', 1, [
//            'frameeditor' => ['Frame Relation', '/visualeditor/frame/main', 'fa fa-list-alt fa16px', 'MASTER', 1, []],
//            'corenesseditor' => ['Coreness', '/visualeditor/frame/coreness', 'fa fa-th-list fa16px', 'MASTER', 1, []],
//            'cxneditor' => ['CxN Relation', '/visualeditor/cxn/main', 'fa fa-list-alt fa16px', 'MASTER', 1, []],
//            'cxnframeeditor' => ['CxN-Frame Relation', '/visualeditor/cxnframe/main', 'fa fa-list-alt fa16px', 'MASTER', 1, []],
//        ]],
//        'utils' => ['Utils', '/utils', 'construction', 'MASTER', 1, [
//            'importLexWf' => ['Import Wf-Lexeme', '/utils/importLexWf', 'utilimport', 'MASTER', 1, []],
//            'wflex' => ['Search Wf-Lexeme', '/admin/wflex', 'utilwflex', '', 1, []],
//            'registerWfLex' => ['Register Wf-Lexeme', '/utils/registerLexWf', 'registerwflex', 'MASTER', 1, []],
//            'registerLemma' => ['Register Lemma', '/utils/registerLemma', 'registerlemma', 'MASTER', 1, []],
//            'importFullText' => ['Import FullText', '/utils/importFullText', 'importfulltext', 'MASTER', 1, []],
//            'exportCxnFS' => ['Export Cxn as FS', '/utils/exportCxnFS', 'exportcxnfs', 'ADMIN', 1, []],
//            'exportCxnJson' => ['Export Cxn', '/utils/exportCxn', 'exportcxnjson', 'ADMIN', 1, []],
//        ]],
    ],
    'user' => ['userPanel', '/admin/user/main', '', [
        'language' => ['Language', '/language', '', [
            '2' => ['English', '/changeLanguage/en', '', []],
            '1' => ['Portuguese', '/changeLanguage/pt', '', []],
            '3' => ['Spanish', '/changeLanguage/es', '', []],
        ]],
        'profile' => ['Profile', '/profile', '', [
            'myprofile' => ['My Profile', '/profile', '', []],
            'logout' => ['Logout', '/logout', '', []],
        ]],
    ]],
    'relations' => [
        'rel_inheritance' => [
            'direct' => "Is inherited by",
            'inverse' => "Inherits from",
            'color' => '#FF0000'
        ],
        'rel_subframe' => [
            'direct' => "Has as subframe",
            'inverse' => "Is subframe of",
            'color' => '#0000FF'
        ],
        'rel_perspective_on' => [
            'direct' => "Is perspectivized in",
            'inverse' => "Perspective on",
            'color' => '#fdbeca'
        ],
        'rel_using' => [
            'direct' => "Is used by",
            'inverse' => "Uses",
            'color' => '#006301'
        ],
        'rel_precedes' => [
            'direct' => "Precedes",
            'inverse' => "Is preceded by",
            'color' => '#000000'
        ],
        'rel_causative_of' => [
            'direct' => "Is causative of",
            'inverse' => "Has as causative",
            'color' => '#fdd101'
        ],
        'rel_inchoative_of' => [
            'direct' => "Is inchoative of",
            'inverse' => "Has as inchoative",
            'color' => '#897201'
        ],
        'rel_see_also' => [
            'direct' => "See also",
            'inverse' => "Has as see_also",
            'color' => '#9e1fee'
        ],
        'rel_inheritance_cxn' => [
            'direct' => "Is inherited by",
            'inverse' => "Inherits from",
            'color' => '#FF0000'
        ],
        'rel_daughter_of' => [
            'direct' => "Is daughter of",
            'inverse' => "Has as daughter",
            'color' => '#0000FF'
        ],
        'rel_subtypeof' => [
            'direct' => "Is subtype of",
            'inverse' => "Has as subtype",
            'color' => '#9e1fee'
        ],
        'rel_standsfor' => [
            'direct' => "Stands for",
            'inverse' => "Has as stands_for",
            'color' => '#9e1fee'
        ],
        'rel_coreset' => [
            'direct' => "CoreSet",
            'inverse' => "CoreSet",
            'color' => '#000'
        ],
        'rel_excludes' => [
            'direct' => "Excludes",
            'inverse' => "Excludes",
            'color' => '#000'
        ],
        'rel_requires' => [
            'direct' => "Requires",
            'inverse' => "Requires",
            'color' => '#000'
        ],
        'rel_structure' => [
            'direct' => "Structure",
            'inverse' => "Structured by",
            'color' => '#000'
        ],
    ],
    'fe' => [
        'icon' => [
            "cty_core" => "black circle",
            "cty_core-unexpressed" => "black dot circle",
            "cty_peripheral" => "black stop circle outline",
            "cty_extra-thematic" => "black circle outline",
//            'tree' => [
//                "cty_core" => "material-icons wt-tree-icon wt-icon-fe-core",
//                "cty_core-unexpressed" => "material-icons-outlined wt-tree-icon wt-icon-fe-core-unexpressed",
//                "cty_peripheral" => "material-icons-outlined wt-tree-icon wt-icon-fe-peripheral",
//                "cty_extra-thematic" => "material-icons-outlined wt-tree-icon wt-icon-fe-extra-thematic",
//            ],
//            'grid' => [
//                "cty_core" => "material-icons wt-icon wt-icon-fe-core",
//                "cty_core-unexpressed" => "material-icons-outlined wt-icon wt-icon-fe-core-unexpressed",
//                "cty_peripheral" => "material-icons-outlined wt-icon wt-icon-fe-peripheral",
//                "cty_extra-thematic" => "material-icons-outlined wt-icon wt-icon-fe-extra-thematic",
//            ],
//            'grapher' => [
//                "cty_core" => "material-icons wt-grapher-icon wt-icon-fe-core",
//                "cty_core-unexpressed" => "material-icons-outlined wt-grapher-icon wt-icon-fe-core-unexpressed",
//                "cty_peripheral" => "material-icons-outlined wt-grapher-icon wt-icon-fe-peripheral",
//                "cty_extra-thematic" => "material-icons-outlined wt-grapher-icon wt-icon-fe-extra-thematic",
//            ],
        ],
        'coreness' => [
            "cty_core" => "Core",
            "cty_core-unexpressed" => "Core-Unexpressed",
            "cty_peripheral" => "Non-core",
            "cty_extra-thematic" => "Extra-thematic",
        ]
    ]
];