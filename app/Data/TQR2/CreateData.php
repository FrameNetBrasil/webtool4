<?php

namespace App\Data\TQR2;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public ?int $idFrame = null,
        public ?int $idQualiaRelation = null,
//        public ?int $idUser = null
    )
    {
//        $this->idUser = AppService::getCurrentIdUser();
    }


}
