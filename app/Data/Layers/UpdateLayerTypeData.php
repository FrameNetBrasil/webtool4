<?php

namespace App\Data\Layers;

use Spatie\LaravelData\Data;

class UpdateLayerTypeData extends Data
{
    public function __construct(
        public string $idLemma,
        public string $name,
        public int $idPOS,
    )
    {
    }
}
