<?php

namespace App\Data\Annotation\Deixis;

use Spatie\LaravelData\Data;

class CreateObjectData extends Data
{
    public function __construct(
        public ?int   $idLayerType = null,
        public ?int   $idDocument = null,
        public ?int   $currentFrame = null,
        public string $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
