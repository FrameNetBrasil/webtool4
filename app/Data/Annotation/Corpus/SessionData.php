<?php

namespace App\Data\Annotation\Corpus;

use App\Services\AppService;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class SessionData extends Data
{
    public function __construct(
        public ?int $idDocumentSentence = null,
        public ?string $timeStamp = '',
        public ?int $idUser = null,
        public string  $_token = '',
    )
    {
        $this->idUser = AppService::getCurrentIdUser();
        $this->timeStamp = Carbon::now();
        $this->_token = csrf_token();
    }

}
