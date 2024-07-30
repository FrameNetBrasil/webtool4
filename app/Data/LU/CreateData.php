<?php

namespace App\Data\LU;

use App\Repositories\Lemma;
use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public int $idFrame,
        public int $idLemma,
        public ?string $senseDescription,
        public ?int $incorporatedFE,
        public ?string $name,
        public ?int $active = 1,
        public ?int $idUser = 1,
        public ?int $idEntity = null
    )
    {
        $lemma = Lemma::byId($this->idLemma);
        $this->name = $lemma->name;
        $this->incorporatedFE = ($this->incorporatedFE < 0) ? null : $this->incorporatedFE;
        $this->idUser = AppService::getCurrentIdUser();

    }
}
