<?php

namespace App\Data\Image;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $name = '',
        public ?int $idImage = null,
        public string  $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
