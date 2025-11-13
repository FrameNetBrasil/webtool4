<?php

namespace App\Http\Controllers\Frame;

use App\Data\Frame\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Frame\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/frame')]
    public function browse(SearchData $search)
    {
        $frames = BrowseService::browseFrameBySearch($search);

        return view('Frame.browse', [
            'data' => $frames,
        ]);
    }

    #[Post(path: '/frame/search')]
    public function tree(SearchData $search)
    {
        $data = BrowseService::browseFrameBySearch($search);

        return view('Frame.browse', [
            'data' => $data,
        ])->fragment('search');

    }

}
