<?php

namespace App\Http\Controllers\TQR2;

use App\Data\TQR2\CreateData;
use App\Data\TQR2\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class ResourceController extends Controller
{
    #[Get(path: '/tqr2')]
    public function resource()
    {
        return view("TQR2.resource");
    }

    #[Get(path: '/tqr2/grid/{fragment?}')]
    #[Post(path: '/tqr2/grid/{fragment?}')]
    public function grid(SearchData $search, ?string $fragment = null)
    {
        $view = view("TQR2.grids", [
            'search' => $search,
            'sentences' => [],
        ]);
        return (is_null($fragment) ? $view : $view->fragment('search'));
    }

    #[Get(path: '/tqr2/{id}/edit')]
    public function edit(string $id)
    {
        $structure = Criteria::byId("qualiastructure", "idQualiaStructure", $id);
        $frame = Frame::byId($structure->idFrame);
        return view("TQR2.edit", [
            "structure" => $structure,
            'frame' => $frame
        ]);
    }

    #[Get(path: '/tqr2/{id}/formEdit')]
    public function formEdit(string $id)
    {
        $arguments = Criteria::byFilter("qualiaargument", [
            ["idQualiaStructure", "=", $id]
        ]);
        return view("TQR2.formEdit", [
            'arguments' => $arguments
        ]);
    }

    #[Post(path: '/tqr2')]
    public function update(UpdateData $data)
    {
        try {
            Criteria::function('dataset_update(?)', [$data->toJson()]);
            $this->trigger("reload-gridDataset");
            return $this->renderNotify("success", "Dataset updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/tqr2/new')]
    public function new()
    {
        return view("TQR2.formNew");
    }

    #[Post(path: '/tqr2/new')]
    public function create(CreateData $data)
    {
        try {
            Criteria::create("qualiastructure", [
                'idFrame' => $data->idFrame
            ]);
            $this->trigger("reload-gridTQR2");
            return $this->renderNotify("success", "Structure created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/tqr2/{id}')]
    public function delete(string $id)
    {
        try {
            Criteria::function('dataset_delete(?, ?)', [
                $id,
                AppService::getCurrentIdUser()
            ]);
            return $this->clientRedirect("/tqr2");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/tqr2/listForSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        return ['results' => Criteria::byFilterLanguage("view_corpus", ["name", "startswith", $name])->orderby("name")->all()];
    }
}
