<?php

namespace App\Data\Microframe;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public string $name,
        public string $nameInverse,
        public ?int $idMicroFrameGroup = null,
        public ?int $idUser = 1,
        public string $_token = '',
    ) {
        $this->idUser = AppService::getCurrentIdUser();
    }
}
