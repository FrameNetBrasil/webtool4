<?php

use App\Data\Parser\ConstructionMatch;
use App\Services\Parser\SemanticActions\DateParserAction;
use App\Services\Parser\SemanticActions\GenericSlotExtractor;
use App\Services\Parser\SemanticActions\PortugueseNumberAction;
use App\Services\Parser\SemanticCalculator;

describe('SemanticCalculator', function () {
    beforeEach(function () {
        $this->calculator = new SemanticCalculator;
    });

    it('registers default actions on construction', function () {
        expect($this->calculator->hasAction('portuguese_number'))->toBeTrue();
        expect($this->calculator->hasAction('date'))->toBeTrue();
        expect($this->calculator->hasAction('slot_extractor'))->toBeTrue();
    });

    it('returns action by name', function () {
        $action = $this->calculator->getAction('portuguese_number');

        expect($action)->toBeInstanceOf(PortugueseNumberAction::class);
    });

    it('returns null for unknown action', function () {
        $action = $this->calculator->getAction('unknown_action');

        expect($action)->toBeNull();
    });

    it('calculates semantic value for match', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 1,
            matchedTokens: [(object) ['word' => 'dois']],
            slots: [],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $semantics = [
            'type' => 'portuguese_number',
        ];

        $result = $this->calculator->calculate($match, $semantics);

        expect($result->semanticValue)->toBe(2);
    });

    it('validates semantics configuration', function () {
        $validation = $this->calculator->validate([
            'type' => 'portuguese_number',
        ]);

        expect($validation['valid'])->toBeTrue();
        expect($validation['errors'])->toBeEmpty();
    });

    it('fails validation for missing type', function () {
        $validation = $this->calculator->validate([]);

        expect($validation['valid'])->toBeFalse();
        expect($validation['errors'])->toContain('Semantics must specify "type" or "method"');
    });

    it('fails validation for unknown action', function () {
        $validation = $this->calculator->validate([
            'type' => 'unknown_action',
        ]);

        expect($validation['valid'])->toBeFalse();
        expect($validation['errors'][0])->toContain('Unknown semantic action: unknown_action');
    });
});

describe('PortugueseNumberAction', function () {
    beforeEach(function () {
        $this->action = new PortugueseNumberAction;
    });

    it('has correct name', function () {
        expect($this->action->getName())->toBe('portuguese_number');
    });

    it('parses simple units', function () {
        $match = createNumberMatch('dois');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(2);
    });

    it('parses tens', function () {
        $match = createNumberMatch('vinte');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(20);
    });

    it('parses hundreds', function () {
        $match = createNumberMatch('duzentos');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(200);
    });

    it('parses compound numbers with "e"', function () {
        $match = createNumberMatch('vinte e cinco');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(25);
    });

    it('parses hundreds with tens and units', function () {
        $match = createNumberMatch('trezentos e quarenta e cinco');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(345);
    });

    it('parses thousands', function () {
        $match = createNumberMatch('dois mil');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(2000);
    });

    it('parses thousands with hundreds', function () {
        $match = createNumberMatch('mil e quinhentos');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(1500);
    });

    it('parses complex numbers', function () {
        $match = createNumberMatch('dois mil, quatrocentos e vinte e dois');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(2422);
    });

    it('parses numeric strings', function () {
        $match = createNumberMatch('42');

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(42);
    });

    it('derives numeric features', function () {
        $features = $this->action->deriveFeatures(42);

        expect($features)->toBe([
            'NumType' => 'Card',
            'numericValue' => 42,
        ]);
    });

    it('validates semantics configuration', function () {
        $validation = $this->action->validateSemantics([]);

        expect($validation['valid'])->toBeTrue();
    });
});

describe('DateParserAction', function () {
    beforeEach(function () {
        $this->action = new DateParserAction;
    });

    it('has correct name', function () {
        expect($this->action->getName())->toBe('date');
    });

    it('parses day from slot', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 1,
            matchedTokens: [],
            slots: ['NUM' => '25'],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $semantics = [
            'slots' => [
                'day' => ['extract' => 0],
            ],
        ];

        $result = $this->action->calculate($match, $semantics);

        expect($result['day'])->toBe(25);
    });

    it('converts month names to numbers', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 3,
            matchedTokens: [],
            slots: ['MONTH' => 'dezembro'],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $semantics = [
            'slots' => [
                'month' => ['extract' => 0, 'lookup' => 'month_to_number'],
            ],
        ];

        $result = $this->action->calculate($match, $semantics);

        expect($result['month'])->toBe(12);
    });

    it('formats date as ISO string', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 5,
            matchedTokens: [],
            slots: ['DAY' => '25', 'MONTH' => 'dezembro', 'YEAR' => '2024'],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $semantics = [
            'slots' => [
                'day' => ['extract' => 0],
                'month' => ['extract' => 1, 'lookup' => 'month_to_number'],
                'year' => ['extract' => 2],
            ],
            'output' => 'iso',
        ];

        $result = $this->action->calculate($match, $semantics);

        expect($result)->toBe('2024-12-25');
    });

    it('derives date features', function () {
        $features = $this->action->deriveFeatures([
            'day' => 25,
            'month' => 12,
            'year' => 2024,
        ]);

        expect($features)->toBe([
            'Day' => 25,
            'Month' => 12,
            'Year' => 2024,
        ]);
    });

    it('validates semantics requires slots', function () {
        $validation = $this->action->validateSemantics([]);

        expect($validation['valid'])->toBeFalse();
        expect($validation['errors'])->toContain('Date semantics must specify "slots" configuration');
    });

    it('validates semantics with slots', function () {
        $validation = $this->action->validateSemantics([
            'slots' => ['day' => []],
        ]);

        expect($validation['valid'])->toBeTrue();
    });
});

describe('GenericSlotExtractor', function () {
    beforeEach(function () {
        $this->action = new GenericSlotExtractor;
    });

    it('has correct name', function () {
        expect($this->action->getName())->toBe('slot_extractor');
    });

    it('returns all slots when no extract specified', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 2,
            matchedTokens: [],
            slots: ['NOUN' => 'casa', 'ADJ' => 'grande'],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $result = $this->action->calculate($match, []);

        expect($result)->toBe(['NOUN' => 'casa', 'ADJ' => 'grande']);
    });

    it('extracts single slot by name', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 2,
            matchedTokens: [],
            slots: ['NOUN' => 'casa', 'ADJ' => 'grande'],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $result = $this->action->calculate($match, ['extract' => 'NOUN']);

        expect($result)->toBe('casa');
    });

    it('extracts multiple slots as array', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 2,
            matchedTokens: [],
            slots: ['NOUN' => 'casa', 'ADJ' => 'grande', 'VERB' => 'correr'],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $result = $this->action->calculate($match, [
            'extract' => ['NOUN', 'ADJ'],
        ]);

        expect($result)->toBe(['NOUN' => 'casa', 'ADJ' => 'grande']);
    });

    it('extracts slots with aliases', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 2,
            matchedTokens: [],
            slots: ['PROPN' => 'João', 'NOUN' => 'senhor'],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $result = $this->action->calculate($match, [
            'extract' => ['name' => 'PROPN', 'title' => 'NOUN'],
        ]);

        expect($result)->toBe(['name' => 'João', 'title' => 'senhor']);
    });

    it('returns null for missing slot', function () {
        $match = new ConstructionMatch(
            idConstruction: 1,
            name: 'test',
            startPosition: 0,
            endPosition: 1,
            matchedTokens: [],
            slots: ['NOUN' => 'casa'],
            semanticType: 'Head',
            semanticValue: null,
            features: []
        );

        $result = $this->action->calculate($match, ['extract' => 'ADJ']);

        expect($result)->toBeNull();
    });

    it('validates all semantics configurations', function () {
        $validation = $this->action->validateSemantics([]);

        expect($validation['valid'])->toBeTrue();
    });
});

// Helper function to create number match
function createNumberMatch(string $text): ConstructionMatch
{
    return new ConstructionMatch(
        idConstruction: 1,
        name: 'number',
        startPosition: 0,
        endPosition: 1,
        matchedTokens: [(object) ['word' => $text]],
        slots: [],
        semanticType: 'Head',
        semanticValue: null,
        features: []
    );
}
