<?php

namespace App\Http\Controllers\Microframe;

use App\Data\Microframe\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Microframe\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('master')]
class BrowseController extends Controller
{
    #[Get(path: '/microframe')]
    public function browse(SearchData $search)
    {
        $frames = BrowseService::browseMicroFrameBySearch($search);

        return view('Microframe.browse', [
            'data' => $frames,
        ]);
    }

    #[Post(path: '/microframe/search')]
    public function tree(SearchData $search)
    {
        $data = BrowseService::browseMicroFrameBySearch($search);

        return view('Microframe.browse', [
            'data' => $data,
        ])->fragment('search');

    }
}
