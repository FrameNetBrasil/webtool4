<?php

namespace App\Http\Controllers\Frame;

use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\ViewLU;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;

#[Middleware("master")]
class LUController extends Controller
{
    #[Get(path: '/frame/{id}/lus')]
    public function lus(string $id)
    {
        return view("Frame.lus",[
            'idFrame' => $id
        ]);
    }

    #[Get(path: '/frame/{id}/lus/formNew')]
    public function formNewLU(string $id)
    {
        return view("LU.formNew",[
            'idFrame' => $id
        ]);
    }

    #[Get(path: '/frame/{id}/lus/grid')]
    public function gridLU(string $id)
    {
        $lus = Criteria::table("view_lu")
            ->where('idFrame', "=", $id)
            ->where("idLanguage",AppService::getCurrentIdLanguage())
            ->orderBy('UDPOS')
            ->orderBy('name')
            ->get()->groupBy("UDPOS")->toArray();
        debug($lus);
        return view("LU.grid",[
            'idFrame' => $id,
            'lus' => $lus
        ]);
    }

}
