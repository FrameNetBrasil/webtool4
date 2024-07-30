<?php

namespace App\Data\Frame;

use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public string $nameEn,
        public string $_token = '',
    )
    {
    }
}
