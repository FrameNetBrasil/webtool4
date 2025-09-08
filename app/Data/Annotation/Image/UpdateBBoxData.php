<?php

namespace App\Data\Annotation\Image;

use Spatie\LaravelData\Data;

class UpdateBBoxData extends Data
{
    public function __construct(
        public ?int $idBoundingBox = null,
        public ?array $bbox = [],
        public string $_token = '',
    ) {
        unset($this->bbox['visible']);
        unset($this->bbox['idStaticObjectObject']);
        $this->_token = csrf_token();
    }

}
