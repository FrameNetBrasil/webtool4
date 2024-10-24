<?php

namespace App\Http\Controllers\DecisionTree;

use App\Data\ComboBox\QData;
use App\Data\DecisionTree\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FE\BrowseController as FEController;
use App\Http\Controllers\LU\BrowseController as LUController;
use App\Repositories\Frame;
use App\Repositories\Relation;
use App\Repositories\SemanticType;
use App\Repositories\ViewFrame;
use App\Services\AppService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/decisiontree')]
    public function browse()
    {
        return view("DecisionTree.browse");
    }

    #[Post(path: '/decisiontree/grid')]
    public function grid()
    {
        return view("DecisionTree.grid");
    }

    #[Get(path: '/decisiontree/frame/{entry}')]
    public function entry(string $entry)
    {
        $frame = Criteria::table("frame")
            ->where("entry", $entry)
            ->first();
        return view("DecisionTree.frames",[
            'frame' => $frame,
        ]);
    }

}
