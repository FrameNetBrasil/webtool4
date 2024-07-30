<?php

namespace App\Data\Lexicon;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $lemma = '',
        public ?string $lexeme = '',
        public ?string $wordform = '',
        public ?int $idLemma = 0,
        public ?int $idLexeme = 0,

        public string  $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
