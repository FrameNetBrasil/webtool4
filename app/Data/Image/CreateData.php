<?php

namespace App\Data\Image;

use App\Services\AppService;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateData extends Data
{
    public function __construct(
        public ?string       $name = '',
        public ?string       $currentURL = '',
        public ?string       $originalFile = '',
        public ?int       $width = 0,
        public ?int       $height = 0,
        public ?int       $depth = 0,
        public ?UploadedFile $file = null,
        public ?int          $idLanguage = null,
        public ?int          $idUser = null
    )
    {
        $this->idUser = AppService::getCurrentIdUser();
        $this->originalFile = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = $this->name;
        $file->storeAs('images', $fileName);
        $dimensions = $file->dimensions();
        $this->width = $dimensions[0];
        $this->height = $dimensions[1];
        $this->depth = $dimensions['bits'];
    }

}
