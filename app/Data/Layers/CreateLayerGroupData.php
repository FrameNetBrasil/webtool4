<?php

namespace App\Data\Layers;

use App\Database\Criteria;
use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateLayerGroupData extends Data
{
    public function __construct(
        public ?string $name = '',
        public string $_token = '',
    )
    {
    }
}
