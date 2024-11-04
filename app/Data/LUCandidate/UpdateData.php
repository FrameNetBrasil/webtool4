<?php

namespace App\Data\LUCandidate;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class UpdateData extends Data
{
    public function __construct(
        public ?int $idLUCandidate = null,
        public ?string $senseDescription = '',
        public ?int $idLemma = null,
        public ?int $idFrame = null,
        public ?int $incorporatedFE = null,
        public ?string $name = '',
        public ?string $frameCandidate = '',
        public ?int $idUser = null
    )
    {
        $this->idUser = AppService::getCurrentIdUser();
    }


}
