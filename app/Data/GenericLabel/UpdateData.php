<?php

namespace App\Data\GenericLabel;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class UpdateData extends Data
{
    public function __construct(
        public ?int $idGenericLabel = null,
        public ?string $name = null,
        public ?int $idLanguage = null,
        public ?int $idColor = null,
        public ?string $definition = '',
    )
    {
    }
}
