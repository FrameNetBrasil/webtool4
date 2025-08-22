<?php

namespace App\Data\Project;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class DatasetData extends Data
{
    public function __construct(
        public ?int $idProject = null,
        public ?int $idDataset = null,
        public ?int $name = null,
        public ?string $description=''
    )
    {
    }

}
