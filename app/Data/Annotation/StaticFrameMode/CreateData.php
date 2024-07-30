<?php

namespace App\Data\Annotation\StaticFrameMode;

use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public ?int $idStaticSentenceMM = null,
        public ?int $idFrame = null,
        public ?int $idLU = null,
        public string  $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
