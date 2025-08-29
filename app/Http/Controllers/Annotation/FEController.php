<?php

namespace App\Http\Controllers\Annotation;


use App\Data\Annotation\Browse\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Annotation\BrowseService;
use App\Services\Annotation\CorpusService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;

#[Middleware("auth")]
class FEController extends Controller
{
    #[Get(path: '/annotation/fe')]
    public function browse(SearchData $search)
    {
        $data = BrowseService::browseCorpusBySearch($search, [], "CorpusAnnotation");

        return view('Annotation.browseSentences', [
            'page' => "FE Annotation",
            'url' => "/annotation/fe/sentence",
            'data' => $data,
        ]);
    }

    #[Get(path: '/annotation/fe/sentence/{idDocumentSentence}/{idAnnotationSet?}')]
    public function annotation(int $idDocumentSentence,int $idAnnotationSet = null)
    {
        $data = CorpusService::getResourceData($idDocumentSentence, $idAnnotationSet, "fe");
        $page = "FE Annotation";
        $url = "/annotation/fe/sentence";
        return view("Annotation.Corpus.annotation", array_merge($data,compact("page", "url")));
    }

}

