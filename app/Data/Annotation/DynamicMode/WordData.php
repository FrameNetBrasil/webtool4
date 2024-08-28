<?php

namespace App\Data\Annotation\DynamicMode;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class WordData extends Data
{
    public function __construct(
        public ?int $idDocument = null,
        public ?array $words = [],
    )
    {
    }

}
