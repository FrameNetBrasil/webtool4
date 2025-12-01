<?php

namespace App\Data\Parser;

use Spatie\LaravelData\Data;

class MWEData extends Data
{
    public function __construct(
        public int $idGrammarGraph,
        public string $phrase,
        public array $components,
        public string $semanticType,
    ) {}

    public static function rules(): array
    {
        return [
            'idGrammarGraph' => ['required', 'integer', 'exists:grammar_graph,idGrammarGraph'],
            'phrase' => ['required', 'string', 'min:3', 'max:255'],
            'components' => ['required', 'array', 'min:2'],
            'components.*' => ['required', 'string', 'min:1'],
            'semanticType' => ['required', 'string', 'in:E,V,A'],
        ];
    }
}
