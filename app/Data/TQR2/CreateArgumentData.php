<?php

namespace App\Data\TQR2;

use Spatie\LaravelData\Data;

class CreateArgumentData extends Data
{
    public function __construct(
        public ?int    $idQualiaStructure = null,
        public ?int    $idFrameElement = null,
        public ?string $type = '',
        public ?int $order = 1
//        public ?int $idUser = null
    )
    {
//        $this->idUser = AppService::getCurrentIdUser();
    }

}
