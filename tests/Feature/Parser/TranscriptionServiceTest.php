<?php

use App\Services\Parser\TranscriptionService;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    // Set up test configuration
    config(['parser.features.extractedFeatures' => [
        'Gender', 'Number', 'Case', 'Person',
        'Tense', 'Mood', 'VerbForm',
    ]]);

    config(['parser.logging.logFeatures' => false]);
    config(['parser.logging.logStages' => false]);
    config(['parser.garbageCollection.enabled' => false]);
});

describe('TranscriptionService Feature Extraction', function () {
    it('extracts Gender and Number features from UD token', function () {
        $service = app(TranscriptionService::class);

        // Simulate a UD token with morph features
        $token = [
            'id' => 1,
            'word' => 'menino',
            'lemma' => 'menino',
            'pos' => 'NOUN',
            'morph' => [
                'Gender' => 'Masc',
                'Number' => 'Sing',
            ],
        ];

        // Use reflection to access private method
        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('buildFeatureBundle');
        $method->setAccessible(true);

        $features = $method->invoke($service, $token['morph']);

        expect($features)->toHaveKey('lexical');
        expect($features)->toHaveKey('derived');
        expect($features['lexical']['Gender'])->toBe('Masc');
        expect($features['lexical']['Number'])->toBe('Sing');
    });

    it('extracts verbal features (Tense, Mood, VerbForm)', function () {
        $service = app(TranscriptionService::class);

        $token = [
            'morph' => [
                'VerbForm' => 'Fin',
                'Mood' => 'Ind',
                'Tense' => 'Past',
                'Person' => '1',
            ],
        ];

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('buildFeatureBundle');
        $method->setAccessible(true);

        $features = $method->invoke($service, $token['morph']);

        expect($features['lexical']['VerbForm'])->toBe('Fin');
        expect($features['lexical']['Mood'])->toBe('Ind');
        expect($features['lexical']['Tense'])->toBe('Past');
        expect($features['lexical']['Person'])->toBe('1');
    });

    it('filters out non-tracked features', function () {
        $service = app(TranscriptionService::class);

        $token = [
            'morph' => [
                'Gender' => 'Fem',
                'Animacy' => 'Anim',  // Not in extractedFeatures
                'Foreign' => 'Yes',    // Not in extractedFeatures
            ],
        ];

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('buildFeatureBundle');
        $method->setAccessible(true);

        $features = $method->invoke($service, $token['morph']);

        expect($features['lexical'])->toHaveKey('Gender');
        expect($features['lexical'])->not->toHaveKey('Animacy');
        expect($features['lexical'])->not->toHaveKey('Foreign');
    });

    it('handles empty morph features', function () {
        $service = app(TranscriptionService::class);

        $token = ['morph' => []];

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('buildFeatureBundle');
        $method->setAccessible(true);

        $features = $method->invoke($service, $token['morph']);

        expect($features['lexical'])->toBeEmpty();
        expect($features['derived'])->toBeEmpty();
    });
});

describe('TranscriptionService Node Feature Extraction', function () {
    it('extracts lexical features from node', function () {
        $service = app(TranscriptionService::class);

        $node = (object) [
            'features' => json_encode([
                'lexical' => ['Gender' => 'Fem', 'Number' => 'Plur'],
                'derived' => [],
            ]),
        ];

        $features = $service->getLexicalFeatures($node);

        expect($features)->toHaveKey('Gender');
        expect($features)->toHaveKey('Number');
        expect($features['Gender'])->toBe('Fem');
        expect($features['Number'])->toBe('Plur');
    });

    it('extracts derived features from node', function () {
        $service = app(TranscriptionService::class);

        $node = (object) [
            'features' => json_encode([
                'lexical' => [],
                'derived' => ['phraseType' => 'Pred', 'isRoot' => true],
            ]),
        ];

        $features = $service->getDerivedFeatures($node);

        expect($features)->toHaveKey('phraseType');
        expect($features)->toHaveKey('isRoot');
        expect($features['phraseType'])->toBe('Pred');
        expect($features['isRoot'])->toBeTrue();
    });

    it('handles node with no features', function () {
        $service = app(TranscriptionService::class);

        $node = (object) ['features' => null];

        $features = $service->getNodeFeatures($node);

        expect($features['lexical'])->toBeEmpty();
        expect($features['derived'])->toBeEmpty();
    });

    it('handles node with malformed JSON', function () {
        $service = app(TranscriptionService::class);

        $node = (object) ['features' => 'invalid json{'];

        $features = $service->getNodeFeatures($node);

        expect($features['lexical'])->toBeEmpty();
        expect($features['derived'])->toBeEmpty();
    });
});

describe('TranscriptionService Word Type Classification', function () {
    it('classifies NOUN as E type', function () {
        // This will be tested through integration since it uses GrammarGraphService
        expect(true)->toBeTrue();
    })->skip('Integration test - requires database');

    it('classifies VERB as R type', function () {
        // Integration test
        expect(true)->toBeTrue();
    })->skip('Integration test - requires database');
});

describe('TranscriptionService Label Generation', function () {
    it('uses lemma for E/R/A types', function () {
        // Private method test - covered by integration
        expect(true)->toBeTrue();
    })->skip('Covered by integration tests');

    it('uses word form for F type', function () {
        // Private method test - covered by integration
        expect(true)->toBeTrue();
    })->skip('Covered by integration tests');
});

describe('TranscriptionService Configuration', function () {
    it('respects logging configuration', function () {
        config(['parser.logging.logFeatures' => true]);

        // Create service and verify no exceptions
        $service = app(TranscriptionService::class);
        expect($service)->toBeInstanceOf(TranscriptionService::class);
    });

    it('respects feature extraction configuration', function () {
        config(['parser.features.extractedFeatures' => ['Gender']]);

        $service = app(TranscriptionService::class);

        $token = [
            'morph' => [
                'Gender' => 'Masc',
                'Number' => 'Sing',  // Should be filtered out
            ],
        ];

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('buildFeatureBundle');
        $method->setAccessible(true);

        $features = $method->invoke($service, $token['morph']);

        expect($features['lexical'])->toHaveKey('Gender');
        expect($features['lexical'])->not->toHaveKey('Number');
    });
});

describe('TranscriptionService Feature Bundle Structure', function () {
    it('always creates lexical and derived keys', function () {
        $service = app(TranscriptionService::class);

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('buildFeatureBundle');
        $method->setAccessible(true);

        $features = $method->invoke($service, []);

        expect($features)->toHaveKeys(['lexical', 'derived']);
    });

    it('stores features as nested structure', function () {
        $service = app(TranscriptionService::class);

        $token = ['morph' => ['Gender' => 'Neut', 'Case' => 'Acc']];

        $reflection = new ReflectionClass($service);
        $method = $reflection->getMethod('buildFeatureBundle');
        $method->setAccessible(true);

        $features = $method->invoke($service, $token['morph']);

        expect($features)->toBeArray();
        expect($features['lexical'])->toBeArray();
        expect($features['derived'])->toBeArray();
        expect($features['lexical'])->toHaveCount(2);
    });
});
