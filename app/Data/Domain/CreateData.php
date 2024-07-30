<?php

namespace App\Data\Domain;

use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public ?string $nameEn = '',
    )
    {
    }

}
