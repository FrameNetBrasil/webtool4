<?php

namespace App\Data\Lexicon;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $lemma = '',
        public ?string $form = '',
        public ?string $id = '',
        public ?string $type = '',
        public ?int $idLemma = 0,
        public ?int $idLexicon = 0,
        public string  $_token = '',
    )
    {
        if ($type == 'lemma') {
            $this->idLemma = $id;
        } elseif ($type == 'form') {
            $this->idLexicon = $id;
        }
//        if ($this->id != '') {
//            if ($this->id[0] == 'l') {
//                $this->idLemma = substr($this->id, 1);
//                $this->idLexicon = substr($this->id, 1);
//            }
//            if ($this->id[0] == 'x') {
//                $this->idLexeme = substr($this->id, 1);
//            }
//            if ($this->id[0] == 'f') {
//                $this->idLexicon = substr($this->id, 1);
//            }
//        }
        $this->_token = csrf_token();
    }

}
