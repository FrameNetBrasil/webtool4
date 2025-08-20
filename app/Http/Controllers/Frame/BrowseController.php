<?php

namespace App\Http\Controllers\Frame;

use App\Data\ComboBox\QData;
use App\Data\Frame\CxnData;
use App\Data\Frame\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Repositories\SemanticType;
use App\Services\AppService;
use App\Services\Frame\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Illuminate\Database\Query\JoinClause;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/frame')]
    public function browse(SearchData $search)
    {
        $frames = BrowseService::browseFrameBySearch($search);

        return view("Frame.browse", [
            'data' => $frames
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
