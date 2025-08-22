<?php

namespace App\Data\Annotation\Browse;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $corpus = '',
        public ?string $document = '',
        public ?string $idDocumentSentence = null,
        public ?string $taskGroupName = null,
        public string $_token = '',
    ) {
        $this->_token = csrf_token();
    }

}
