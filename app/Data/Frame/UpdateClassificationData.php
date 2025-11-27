<?php

namespace App\Data\Frame;

use Spatie\LaravelData\Data;

class UpdateClassificationData extends Data
{
    public function __construct(
        public int $idFrame,
        public ?array $framalDomain = [],
        public ?array $framalType = [],
        public ?array $namespace = [],
        public ?int $idNamespace = null,
    )
    {
        $this->idNamespace = $this->namespace[array_key_first($namespace)] ?? 1;
    }
}
