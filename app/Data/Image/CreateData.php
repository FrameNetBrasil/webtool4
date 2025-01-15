<?php

namespace App\Data\Image;

use App\Services\AppService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
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
        //$file->storeAs('images', $fileName);
        //$file->store($fileName, 'media');

        $client = new Client([
            'base_uri' => "http://host.docker.internal:8020",
            'timeout' => 300.0,
        ]);

        $image_path = $file->getPathname();
//        $image_mime = $file->getMimeType();
//        $image_org  = $file->getClientOriginalName();
//
//        $response = $client->post('/', [
//            'multipart' => [
//                [
//                    'name'     => 'image',
//                    'filename' => $image_org,
//                    'Mime-Type'=> $image_mime,
//                    'contents' => fopen( $image_path, 'r' ),
//                ],
//            ]
//        ]);

        debug($image_path);
//        $body = Utils::tryFopen($image_path, 'r');
//        $r = $client->request('POST', "http://host.docker.internal:8020", ['body' => $body]);

        debug(Utils::tryFopen($image_path, 'r'));

        $response = $client->request('POST', 'http://host.docker.internal:8020', [
            'multipart' => [
                [
                    'name'     => 'file_name',
                    'contents' => Utils::tryFopen($image_path, 'r'),
                    'filename' => 'filename.png',
                ]
            ]
        ]);
        $dimensions = $file->dimensions();
        $this->width = $dimensions[0];
        $this->height = $dimensions[1];
        $this->depth = $dimensions['bits'];
    }

}
