<?php

namespace App\Data\Video;

use App\Services\AppService;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public ?string       $title = '',
        public ?string       $sha1Name = '',
        public ?string       $originalFile = '',
        public ?UploadedFile $file = null,
        public ?int          $idLanguage = null,
        public ?int          $idUser = null
    )
    {
        $this->idUser = AppService::getCurrentIdUser();
        $this->originalFile = $file->getClientOriginalName();
        $this->sha1Name = sha1($file->getClientOriginalName());
        $extension = $file->getClientOriginalExtension();
        $fileName = $this->sha1Name . '_original' . '.' . $extension;
        $file->storeAs('videos', $fileName);

    }

}
