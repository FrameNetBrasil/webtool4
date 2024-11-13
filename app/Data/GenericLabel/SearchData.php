<?php

namespace App\Data\GenericLabel;

use App\Services\AppService;
use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public function __construct(
        public ?string $genericLabel = '',
        public ?int $idGenericLabel = 0,
        public ?int $idLanguageSearch = 0,
        public ?string $id = '',
        public string  $_token = '',
    )
    {
        if ($this->idLanguageSearch == 0) {
            $this->idLanguageSearch = AppService::getCurrentIdLanguage();
        }
        $this->_token = csrf_token();
    }

}
