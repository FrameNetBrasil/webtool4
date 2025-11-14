<?php

namespace App\Http\Controllers\Class;

use App\Data\Class\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Class\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('master')]
class BrowseController extends Controller
{
    #[Get(path: '/class')]
    public function browse(SearchData $search)
    {
        $frames = BrowseService::browseClassBySearch($search);

        return view('Class.browse', [
            'data' => $frames,
        ]);
    }

    #[Post(path: '/class/search')]
    public function tree(SearchData $search)
    {
        $data = BrowseService::browseClassBySearch($search);

        return view('Class.browse', [
            'data' => $data,
        ])->fragment('search');

    }
}
