<?php

namespace App\Data\SemanticType;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public int $idSemanticType,
        public int $idEntity,
        public ?int $idUser
    )
    {
        $user = AppService::getCurrentUser();
        $this->idUser = $user ? $user->idUser : 0;
    }
}
