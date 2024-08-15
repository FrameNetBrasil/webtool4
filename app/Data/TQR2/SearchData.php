<?php

namespace App\Data\TQR2;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $frame = '',
        public ?int $idFrame = null,
        public string  $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
