<?php

namespace App\Data\Annotation\StaticFrameMode;

use Spatie\LaravelData\Data;

class SearchDataMode1 extends Data
{
    public function __construct(
        public ?string $corpus = '',
        public ?string $document = '',
        public ?string $image = '',
        public ?int $flickr30k = 3,
        public ?string $id = '',
        public ?int $idCorpus = null,
        public ?int $idDocument = null,
        public string  $_token = '',
    )
    {
        if ($this->id != '') {
            $type = $this->id[0];
            if ($type == 'c') {
                $this->idCorpus = substr($this->id, 1);
            }
            if ($type == 'd') {
                $this->idDocument = substr($this->id, 1);
            }
        }
        $this->_token = csrf_token();
    }

}
