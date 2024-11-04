<?php

namespace App\Data\LUCandidate;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public ?string $name = '',
        public ?string $senseDescription = '',
        public ?int $idLemma = null,
        public ?int $idFrame = null,
        public ?string $frameCandidate = '',
        public ?int $idUser = null
    )
    {
        $this->idUser = AppService::getCurrentIdUser();
    }


}
