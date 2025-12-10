<?php

use App\Services\Parser\ConstructionService;
use App\Services\Parser\TranscriptionService;

describe('TranscriptionService V3 Integration', function () {
    beforeEach(function () {
        $this->service = app(TranscriptionService::class);
        $this->constructionService = app(ConstructionService::class);
    });

    it('converts UD tokens to PhrasalCENodes', function () {
        $tokens = [
            ['id' => 1, 'word' => 'casa', 'lemma' => 'casa', 'pos' => 'NOUN', 'morph' => 'Number=Sing'],
            ['id' => 2, 'word' => 'grande', 'lemma' => 'grande', 'pos' => 'ADJ', 'morph' => 'Number=Sing'],
        ];

        $nodes = $this->service->transcribeV3($tokens, 1, 5);

        expect($nodes)->toHaveCount(2);
        expect($nodes[0]->word)->toBe('casa');
        expect($nodes[0]->pos)->toBe('NOUN');
        expect($nodes[1]->word)->toBe('grande');
        expect($nodes[1]->pos)->toBe('ADJ');
    })->skip('Integration test - requires full database setup');

    it('detects and merges construction matches', function () {
        // Create Portuguese number construction if not exists
        try {
            $this->constructionService->compileAndStore(
                idGrammarGraph: 1,
                name: 'test_number_simple',
                pattern: '{NUM} e {NUM}',
                metadata: [
                    'description' => 'Simple two-part number',
                    'semantics' => ['type' => 'portuguese_number'],
                    'priority' => 10,
                ]
            );
        } catch (\Exception $e) {
            // Construction already exists, that's fine
        }

        $tokens = [
            ['id' => 1, 'word' => 'vinte', 'lemma' => 'vinte', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 2, 'word' => 'e', 'lemma' => 'e', 'pos' => 'CCONJ', 'morph' => ''],
            ['id' => 3, 'word' => 'cinco', 'lemma' => 'cinco', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
        ];

        $nodes = $this->service->transcribeV3($tokens, 1, 5);

        // Should merge into single node
        expect($nodes)->toHaveCount(1);
        expect($nodes[0]->word)->toBe('vinte e cinco');
        expect($nodes[0]->isMWE)->toBeTrue();
        expect($nodes[0]->pos)->toBe('NUM');
    })->skip('Integration test - requires full database setup');

    it('applies semantic calculation to matched constructions', function () {
        $tokens = [
            ['id' => 1, 'word' => 'vinte', 'lemma' => 'vinte', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 2, 'word' => 'e', 'lemma' => 'e', 'pos' => 'CCONJ', 'morph' => ''],
            ['id' => 3, 'word' => 'cinco', 'lemma' => 'cinco', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
        ];

        $nodes = $this->service->transcribeV3($tokens, 1, 5);

        expect($nodes[0]->features['derived']['semanticValue'])->toBe(25);
        expect($nodes[0]->features['lexical']['NumType'])->toBe('Card');
        expect($nodes[0]->features['lexical']['numericValue'])->toBe(25);
    })->skip('Integration test - requires full database setup');

    it('handles sentences with no construction matches', function () {
        $tokens = [
            ['id' => 1, 'word' => 'casa', 'lemma' => 'casa', 'pos' => 'NOUN', 'morph' => 'Number=Sing'],
            ['id' => 2, 'word' => 'grande', 'lemma' => 'grande', 'pos' => 'ADJ', 'morph' => 'Number=Sing'],
        ];

        $nodes = $this->service->transcribeV3($tokens, 1, 5);

        // No merging should occur
        expect($nodes)->toHaveCount(2);
        expect($nodes[0]->isMWE)->toBeFalse();
        expect($nodes[1]->isMWE)->toBeFalse();
    })->skip('Integration test - requires full database setup');

    it('handles multiple non-overlapping constructions', function () {
        $tokens = [
            ['id' => 1, 'word' => 'vinte', 'lemma' => 'vinte', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 2, 'word' => 'e', 'lemma' => 'e', 'pos' => 'CCONJ', 'morph' => ''],
            ['id' => 3, 'word' => 'cinco', 'lemma' => 'cinco', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 4, 'word' => 'ou', 'lemma' => 'ou', 'pos' => 'CCONJ', 'morph' => ''],
            ['id' => 5, 'word' => 'trinta', 'lemma' => 'trinta', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 6, 'word' => 'e', 'lemma' => 'e', 'pos' => 'CCONJ', 'morph' => ''],
            ['id' => 7, 'word' => 'dois', 'lemma' => 'dois', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
        ];

        $nodes = $this->service->transcribeV3($tokens, 1, 5);

        // Should have: "vinte e cinco", "ou", "trinta e dois"
        expect($nodes)->toHaveCount(3);
        expect($nodes[0]->word)->toBe('vinte e cinco');
        expect($nodes[1]->word)->toBe('ou');
        expect($nodes[2]->word)->toBe('trinta e dois');
    })->skip('Integration test - requires full database setup');

    it('preserves phrasal CE types', function () {
        $tokens = [
            ['id' => 1, 'word' => 'vinte', 'lemma' => 'vinte', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 2, 'word' => 'e', 'lemma' => 'e', 'pos' => 'CCONJ', 'morph' => ''],
            ['id' => 3, 'word' => 'cinco', 'lemma' => 'cinco', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
        ];

        $nodes = $this->service->transcribeV3($tokens, 1, 5);

        // NUM should map to Mod phrasalCE
        expect($nodes[0]->phrasalCE->value)->toBe('Mod');
    })->skip('Integration test - requires full database setup');

    it('stores construction metadata in derived features', function () {
        $tokens = [
            ['id' => 1, 'word' => 'vinte', 'lemma' => 'vinte', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 2, 'word' => 'e', 'lemma' => 'e', 'pos' => 'CCONJ', 'morph' => ''],
            ['id' => 3, 'word' => 'cinco', 'lemma' => 'cinco', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
        ];

        $nodes = $this->service->transcribeV3($tokens, 1, 5);

        $derived = $nodes[0]->features['derived'];
        expect($derived)->toHaveKey('construction');
        expect($derived)->toHaveKey('semanticValue');
        expect($derived)->toHaveKey('slots');
        expect($derived['semanticValue'])->toBe(25);
    })->skip('Integration test - requires full database setup');

    it('handles complex Portuguese numbers with thousands', function () {
        $tokens = [
            ['id' => 1, 'word' => 'dois', 'lemma' => 'dois', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 2, 'word' => 'mil', 'lemma' => 'mil', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
            ['id' => 3, 'word' => 'e', 'lemma' => 'e', 'pos' => 'CCONJ', 'morph' => ''],
            ['id' => 4, 'word' => 'quinhentos', 'lemma' => 'quinhentos', 'pos' => 'NUM', 'morph' => 'NumType=Card'],
        ];

        $nodes = $this->service->transcribeV3($tokens, 1, 5);

        expect($nodes)->toHaveCount(1);
        expect($nodes[0]->word)->toBe('dois mil e quinhentos');
        expect($nodes[0]->features['derived']['semanticValue'])->toBe(2500);
    })->skip('Integration test - requires full database setup');
});
