<?php

namespace App\Data\CE;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public ?int $idConstruction,
        public ?string $name,
        public ?int $idColor,
        public ?int $idUser,
        public ?string $entry,
        public string $_token = '',
    )
    {
        $this->idUser = AppService::getCurrentIdUser();
        $this->entry = strtolower('ce_' . $this->name);
        $this->_token = csrf_token();
    }
}
