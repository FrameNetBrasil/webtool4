<?php

namespace App\Data\Lexicon;

use App\Database\Criteria;
use App\Services\AppService;
use Spatie\LaravelData\Data;

class CreateLemmaData extends Data
{
    public function __construct(
        public ?string $lemma = '',
        public ?int $idPOS = null,
        public ?int $idLanguage = null,
        public ?string $addName = '',
        public string $_token = '',
    )
    {
        if ($this->lemma == '') {
            $this->lemma = $this->addName;
        } else {
            $this->addName = $this->lemma;
        }
        if (is_null($this->idLanguage)) {
            $this->idLanguage = AppService::getCurrentIdLanguage();
        }
        if (is_null($this->idPOS)) {
            if (str_contains($this->addName,'.')) {
                $parts = explode('.',$this->addName);
                $pos = Criteria::table("pos")
                    ->whereRaw("upper(pos) = '" . strtoupper($parts[1]) . "'")
                    ->first();
                $this->idPOS = $pos->idPOS;
            } else {
                $pos = Criteria::byId("pos","POS","N");
                $this->idPOS = $pos->idPOS;
            }
        }
    }
}
