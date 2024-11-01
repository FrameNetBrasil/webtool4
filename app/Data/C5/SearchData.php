<?php

namespace App\Data\C5;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $concept = '',
        public ?int $idConcept = 0,
        public ?int $id = 0,
        public string  $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
