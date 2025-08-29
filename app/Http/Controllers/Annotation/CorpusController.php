<?php

namespace App\Http\Controllers\Annotation;

use App\Http\Controllers\Controller;
use App\Services\Annotation\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;

#[Middleware(name: 'auth')]
class CorpusController extends Controller
{

    #[Get(path: '/annotation/corpus/script/{folder}')]
    public function jsObjects(string $folder)
    {
        return response()
            ->view("Annotation.Corpus.Scripts.{$folder}")
            ->header('Content-type', 'text/javascript');
    }

}
