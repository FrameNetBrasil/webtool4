<?php

namespace App\Data\Grapher;

use Spatie\LaravelData\Data;

class FrameData extends Data
{
    public function __construct(
        public ?int $idFrame,
        public array $idRelationType = [],
        public string  $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

}
