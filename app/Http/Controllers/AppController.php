<?php

namespace App\Http\Controllers;

use App\Data\Frame\SearchData;
use App\Database\Criteria;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Illuminate\Http\Request;
use Orkester\Security\MAuth;

#[Middleware(name: 'web')]
class AppController extends Controller
{
    #[Get(path: '/')]
    public function main()
    {
        if (MAuth::isLogged()) {
            return view('App.main');
        } else {
            if (config('webtool.login.handler') == 'auth0') {
                return view('App.auth0Login');
            } else {
                return view('App.login');
            }
        }
    }

    #[Get(path: '/changeLanguage/{language}')]
    public function changeLanguage(Request $request, string $language)
    {
        $currentURL = $request->header("Hx-Current-Url");
        $data = Criteria::byFilter("language", ['language', '=', $language])->first();
        AppService::setCurrentLanguage($data->idLanguage);
        return $this->redirect($currentURL);
    }

    #[Post(path: '/app/search')]
    public function appSearch(SearchData $search)
    {
        $lus =[];
        $frames = [];
        $searchString = '%' . $search->frame;
        if ($searchString != '') {
            $lus = self::listLUSearch($searchString);
            $frames = self::listFrame($searchString);
        }
        return view("App.search", [
            'search' => $search,
            'frames' => $frames,
            'currentFrame' => $search->frame . '*',
            'fes' => [],
            'lus' => $lus,
        ]);
    }

    public static function listFrame(string $name)
    {
        $result = [];
        $frames = Criteria::byFilterLanguage("view_frame",[
            ["name","startswith", $name]
        ])->all();
        foreach ($frames as $row) {
            $result[$row->idFrame] = [
                'id' => 'f' . $row->idFrame,
                'idFrame' => $row->idFrame,
                'type' => 'frame',
                'name' => [$row->name, $row->description],
            ];
        }
        return $result;
    }

    public static function listLUSearch(string $name)
    {
        $result = [];
        $lus = Criteria::byFilterLanguage("view_lu",[
            ["name","startswith", $name]
        ])->all();
        foreach ($lus as $lu) {
            $result[$lu->idLU] = [
                'id' => 'l' . $lu->idLU,
                'idLU' => $lu->idLU,
                'type' => 'lu',
                'name' => [$lu->name, $lu->senseDescription],
                'frameName' => $lu->frameName,
            ];
        }
        return $result;
    }


}
