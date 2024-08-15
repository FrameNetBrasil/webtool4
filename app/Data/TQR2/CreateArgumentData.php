<?php

namespace App\Data\TQR2;

use App\Repositories\FrameElement;
use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateArgumentData extends Data
{
    public function __construct(
        public ?int    $idQualiaStructure = null,
        public ?int    $idFrameElement = null,
        public ?int    $idEntity = null,
        public ?string $type = '',
//        public ?int $idUser = null
    )
    {
        $fe = FrameElement::byId($this->idFrameElement);
        $this->idEntity = $fe->idEntity;

//        $this->idUser = AppService::getCurrentIdUser();
    }

    public function toCreate(): array
    {
        return [
            'idQualiaStructure' => $this->idQualiaStructure,
            'idEntity' => $this->idEntity,
            'type' => $this->type,
        ];
    }


}
