<?php

namespace App\Http\Controllers\Sentence;

use App\Data\Sentence\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Sentence\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('auth')]
class BrowseController extends Controller
{
    #[Get(path: '/sentence')]
    public function browse(SearchData $search)
    {
        $data = BrowseService::browseSentenceBySearch($search);
        return view('Sentence.browse', [
            'data' => $data,
        ]);
    }

    #[Post(path: '/sentence/search')]
    public function search(SearchData $search)
    {
        $data = BrowseService::browseSentenceBySearch($search);
        return view('Sentence.browse', [
            'data' => $data,
        ])->fragment('search');
    }

}
