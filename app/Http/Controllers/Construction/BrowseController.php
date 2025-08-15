<?php

namespace App\Http\Controllers\Construction;

use App\Data\Construction\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Construction\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/cxn')]
    public function browse(SearchData $search)
    {
        $cxns = BrowseService::browseCxnBySearch($search);

        return view("Construction.browse", [
            'data' => $cxns
        ]);
    }

    #[Post(path: '/cxn/tree')]
    public function tree(SearchData $search)
    {
        $data = BrowseService::browseCxnBySearch($search);

        return view("Construction.partials.tree", [
            'data' => $data
        ]);

    }
}
