<?php

namespace App\Http\Controllers;

use App\Data\Frame\SearchData;
use App\Repositories\Language;
use App\Repositories\ViewFrame;
use App\Repositories\ViewLU;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
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
    public function changeLanguage(string $language)
    {
        $data = Language::first([['language', '=', $language]]);
        AppService::setCurrentLanguage($data->idLanguage);
        return $this->redirect("/");
    }

    #[Post(path: '/app/search')]
    public function appSearch(SearchData $search)
    {
        $lus =[];
        $frames = [];
        debug($search);
        $searchLU = $search->frame;
        if ($searchLU != '') {
            $lus = self::listLUSearch($searchLU);
            $frames = self::listFrame($search);
        }
        return view("App.search", [
            'search' => $search,
            'frames' => $frames,
            'currentFrame' => $search->frame . '*',
            'fes' => [],
            'lus' => $lus,
        ]);
    }

    public static function listFrame(SearchData $search)
    {
        $result = [];
        $frames = ViewFrame::listByFilter($search)->all();
        foreach ($frames as $row) {
            $result[$row->idFrame] = [
                'id' => 'f' . $row->idFrame,
                'idFrame' => $row->idFrame,
                'type' => 'frame',
                'name' => [$row->name, $row->description],
                'iconCls' => 'material-icons-outlined wt-icon wt-icon-frame',
            ];
        }
        return $result;
    }

    public static function listLUSearch(string $lu)
    {
        $result = [];
        $lus = ViewLU::listByFilter((object)[
            'lu' => $lu,
            'idLanguage' => AppService::getCurrentIdLanguage()
        ])->all();
        foreach ($lus as $lu) {
            $result[$lu->idLU] = [
                'id' => 'l' . $lu->idLU,
                'idLU' => $lu->idLU,
                'type' => 'lu',
                'name' => [$lu->name, $lu->senseDescription],
                'frameName' => $lu->frameName,
                'iconCls' => 'material-icons-outlined wt-icon wt-icon-lu',
            ];
        }
        return $result;
    }


}
