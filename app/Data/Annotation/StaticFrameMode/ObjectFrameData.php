<?php

namespace App\Data\Annotation\StaticFrameMode;

use Spatie\LaravelData\Data;

class ObjectFrameData extends Data
{
    public function __construct(
        public ?array $idStaticObjectSentenceMM = [],
        public string  $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
