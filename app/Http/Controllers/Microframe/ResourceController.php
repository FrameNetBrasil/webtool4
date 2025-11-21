<?php

namespace App\Http\Controllers\Microframe;

use App\Data\Microframe\CreateData;
use App\Data\SemanticType\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Repositories\Microframe;
use App\Services\AppService;
use App\Services\SemanticType\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('master')]
class ResourceController extends Controller
{
    #[Get(path: '/microframe/new')]
    public function new()
    {
        $search = new SearchData(semanticType: 'microframe_type');
        $data = BrowseService::browseSemanticTypeBySearch($search);
        return view('Microframe.new',[
            "data" => $data
        ]);
    }

    #[Post(path: '/microframe')]
    public function store(CreateData $data)
    {
        try {
            $idFrame = Criteria::function('frame_create(?)', [$data->toJson()]);

            return $this->clientRedirect("/microframe/{$idFrame}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/microframe/{idFrame}')]
    public function delete(string $idFrame)
    {
        try {
            Criteria::function('frame_delete(?, ?)', [
                $idFrame,
                AppService::getCurrentIdUser(),
            ]);

            return $this->clientRedirect('/microframe');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Get(path: '/microframe/{id}')]
    public function get(string $id)
    {
        return view('Microframe.edit', [
            'frame' => Microframe::byId($id),
        ]);
    }

    #[Get(path: '/microframe/nextFrom/{id}')]
    public function nextFrom(string $id)
    {
        $current = Microframe::byId($id);
        $next = Criteria::table('view_microframe')
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->where('name', '>', $current->name)
            ->orderBy('name')
            ->first();

        return $this->clientRedirect("/microframe/{$next->idFrame}");
    }
}
