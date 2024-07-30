<?php

namespace App\Http\Controllers\Layer;

use App\Http\Controllers\Controller;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;

#[Middleware(name: 'admin')]

class LayerController extends Controller
{
    #[Get(path: '/layer')]
    public function browse()
    {
        $this->data->search ??= (object)[];
        $this->data->search->_token = csrf_token();
        return $this->render('browse');
    }
}
