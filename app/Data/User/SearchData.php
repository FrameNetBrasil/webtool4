<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $group = '',
        public ?string $user = '',
        public ?int $id = 0,
        public ?string $type = '',
    ) {}

}
