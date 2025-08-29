<?php

namespace App\Data\Annotation\_FullText;

use Spatie\LaravelData\Data;
class DeleteLabelData extends Data
{
    public function __construct(
        public int          $idAnnotationSet,
        public int          $idEntity,
        public ?string        $_token = '',
    )
    {
        $this->_token = csrf_token();
    }
}
