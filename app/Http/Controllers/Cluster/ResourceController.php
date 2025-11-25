<?php

namespace App\Http\Controllers\Cluster;

use App\Data\Microframe\CreateData;
use App\Data\SemanticType\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Repositories\Microframe;
use App\Repositories\SemanticType;
use App\Services\AppService;
use App\Services\RelationService;
use App\Services\SemanticType\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('master')]
class ResourceController extends Controller
{
    #[Get(path: '/cluster/new')]
    public function new()
    {
        return view('Cluster.new');
    }

    #[Post(path: '/cluster')]
    public function store(CreateData $data)
    {
        try {
            $idFrame = Criteria::function('frame_create(?)', [$data->toJson()]);
            return $this->clientRedirect('/cluster/'. $idFrame);
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/cluster/{idFrame}')]
    public function delete(string $idFrame)
    {
        try {
            Criteria::function('frame_delete(?, ?)', [
                $idFrame,
                AppService::getCurrentIdUser(),
            ]);

            return $this->clientRedirect('/cluster');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Get(path: '/cluster/{id}')]
    public function get(string $id)
    {
        return view('Cluster.edit', [
            'frame' => Microframe::byId($id),
        ]);
    }

    #[Get(path: '/cluster/nextFrom/{id}')]
    public function nextFrom(string $id)
    {
        $current = Microframe::byId($id);
        $next = Criteria::table('view_microframe')
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->where('name', '>', $current->name)
            ->orderBy('name')
            ->first();

        return $this->clientRedirect("/cluster/{$next->idFrame}");
    }
}
