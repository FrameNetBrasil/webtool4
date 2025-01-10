<?php

namespace App\Data\Annotation\StaticBBox;

use App\Services\AppService;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class CommentData extends Data
{
    public function __construct(
        public ?int   $idStaticObject = null,
        public ?string $comment = '',
        public ?int $idUser = null,
        public ?string $createdAt = '',
        public ?string $updatedAt = '',
        public string $_token = '',
    )
    {
        $this->idUser = AppService::getCurrentIdUser();
        if ($this->createdAt == '') {
            $this->createdAt = Carbon::now();
        }
        $this->updatedAt = Carbon::now();
        $this->_token = csrf_token();
    }

}
