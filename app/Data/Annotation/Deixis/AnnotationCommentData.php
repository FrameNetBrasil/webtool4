<?php

namespace App\Data\Annotation\Deixis;

use Spatie\LaravelData\Data;

class AnnotationCommentData extends Data
{
    public function __construct(
        public ?int $idDocumentVideo = null,
        public ?string $comment = '',
        public string $_token = '',
    ) {
        $this->_token = csrf_token();
    }

}
