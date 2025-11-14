<?php

namespace App\Http\Controllers\MicroFrame;

use App\Data\Frame\CreateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('master')]
class ResourceController extends Controller
{
    #[Get(path: '/frame/new')]
    public function new()
    {
        return view('Frame.new');
    }

    #[Post(path: '/frame')]
    public function store(CreateData $data)
    {
        try {
            $idFrame = Criteria::function('frame_create(?)', [$data->toJson()]);

            return $this->clientRedirect("/frame/{$idFrame}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/frame/{idFrame}')]
    public function delete(string $idFrame)
    {
        try {
            Criteria::function('frame_delete(?, ?)', [
                $idFrame,
                AppService::getCurrentIdUser(),
            ]);

            return $this->clientRedirect('/frame');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Get(path: '/frame/{id}')]
    public function get(string $id)
    {
        return view('Frame.edit', [
            'frame' => Frame::byId($id),
            'classification' => Frame::getClassificationLabels($id),
        ]);
    }

    #[Get(path: '/frame/nextFrom/{id}')]
    public function nextFrom(string $id)
    {
        $current = Frame::byId($id);
        $next = Criteria::table('view_frame')
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->where('name', '>', $current->name)
            ->orderBy('name')
            ->first();

        return $this->clientRedirect("/frame/{$next->idFrame}");
    }
}
