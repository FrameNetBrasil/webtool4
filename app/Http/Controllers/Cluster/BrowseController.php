<?php

namespace App\Http\Controllers\Cluster;

use App\Data\Cluster\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Cluster\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('master')]
class BrowseController extends Controller
{
    #[Get(path: '/cluster')]
    public function browse(SearchData $search)
    {
        $frames = BrowseService::browseClusterBySearch($search);

        return view('Cluster.browse', [
            'data' => $frames,
        ]);
    }

    #[Post(path: '/cluster/search')]
    public function tree(SearchData $search)
    {
        $data = BrowseService::browseClusterBySearch($search);

        return view('Cluster.browse', [
            'data' => $data,
        ])->fragment('search');

    }
}
