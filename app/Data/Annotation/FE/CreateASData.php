<?php

namespace App\Data\Annotation\FE;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Normalizers\FormRequestNormalizer;
use Spatie\LaravelData\Normalizers\JsonNormalizer;

class CreateASData extends Data
{
    public function __construct(
        public ?int          $idDocumentSentence,
        public ?int          $idLU,
        public mixed       $wordList,
    )
    {
        $this->wordList = json_decode($this->wordList);
    }

}
