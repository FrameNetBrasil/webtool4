<?php

namespace App\Data\Lexicon;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $lemma = '',
        public ?string $form = '',
        public string  $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
