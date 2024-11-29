<?php

namespace App\Data\Construction;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $cxn = '',
        public ?string $ce = '',
        public ?string $listBy = '',
        public ?int $idLanguage= null,
        public ?string $id = '',
        public ?int    $idConstruction = 0,
        public string  $_token = '',
        public ?string $byLanguage = '',
        public ?string $language = '',
    )
    {
        $this->_token = csrf_token();
    }

}
