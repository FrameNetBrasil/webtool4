<?php

namespace App\Http\Controllers\TQR2;

use App\Data\ComboBox\QData;
use App\Data\TQR2\CreateArgumentData;
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
            'search' => $search
        ]);
        return (is_null($fragment) ? $view : $view->fragment('search'));
    }

    #[Get(path: '/tqr2/{id}/edit')]
    public function edit(string $id)
    {
        $structure = Criteria::byId("qualiastructure", "idQualiaStructure", $id);
        $relation = Criteria::byId("qualiarelation", "idQualiaRelation", $structure->idQualiaRelation);
        $frame = Frame::byId($structure->idFrame);
        return view("TQR2.edit", [
            "structure" => $structure,
            'frame' => $frame,
            'relation' => $relation,
            'arguments' => $this->getArguments($id),
            "types" => $this->getTypes()
        ]);
    }

    public function getTypes(): array
    {
        return ['A' => 'Agentive', 'T' => 'Telic', 'C1' => 'Constitutive_1', 'C2' => 'Constitutive_2', 'C3' => 'Constitutive_3'];
    }

    public function getArguments(int $idQualiaStructure): array
    {
        debug($idQualiaStructure);
        return Criteria::byFilterLanguage("view_qualiaargument", [
            "idQualiaStructure", "=", $idQualiaStructure
        ])->select("idQualiaArgument", "order", "type", "feName", "feCoreType", "feIdColor")
            ->orderby("order")
            ->all();
    }

//    #[Get(path: '/tqr2/{id}/formEdit')]
//    public function formEdit(string $id)
//    {
//        return view("TQR2.formEdit", [
//            'arguments' => $this->getArguments($id)
//        ]);
//    }

//    #[Post(path: '/tqr2')]
//    public function update(UpdateData $data)
//    {
//        try {
//            Criteria::function('dataset_update(?)', [$data->toJson()]);
//            $this->trigger("reload-gridDataset");
//            return $this->renderNotify("success", "Dataset updated.");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }

    #[Get(path: '/tqr2/new')]
    public function new()
    {
        return view("TQR2.formNew");
    }

    #[Post(path: '/tqr2/new')]
    public function create(CreateData $data)
    {
        try {
            $idQualiaStructure = Criteria::create("qualiastructure", $data->toArray());
//            $this->trigger("reload-gridTQR2");
//            return $this->renderNotify("success", "Structure created.");
            return $this->edit($idQualiaStructure);
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/tqr2/{id}')]
    public function delete(string $id)
    {
        try {
            Criteria::deleteById("qualiaargument", "idQualiaStructure", $id);
            Criteria::deleteById("qualiastructure", "idQualiaStructure", $id);
            return $this->clientRedirect("/tqr2");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/tqr2/argument/new')]
    public function createArgument(CreateArgumentData $data)
    {
        try {
            Criteria::create("qualiaargument", $data->toArray());
            $this->trigger("reload-gridTQR2Argument");
            return $this->renderNotify("success", "Argument created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/tqr2/{id}/arguments')]
    public function arguments(string $id)
    {
        $structure = Criteria::byId("qualiastructure", "idQualiaStructure", $id);
        return view("TQR2.arguments", [
            'structure' => $structure,
            'arguments' => $this->getArguments($id),
            "types" => $this->getTypes(),
        ]);
    }

    #[Delete(path: '/tqr2/arguments/{id}')]
    public function deleteArgument(string $id)
    {
        try {
            Criteria::deleteById("qualiaargument", "idQualiaArgument", $id);
            $this->trigger("reload-gridTQR2Argument");
            return $this->renderNotify("success", "Argument removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/tqr2/qualialu/new')]
    public function formNewQualiaLU()
    {
        return view("TQR2.formNewQualiaLU");
    }

    #[Get(path: '/tqr2/list/forSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        $results = Criteria::table("view_qualiastructure")
            ->whereRaw("lower(concat(name,'::',relation)) like lower('%$name%')")
            ->where("idLanguage", AppService::getCurrentIdLanguage())
            ->selectRaw("idQualiaStructure, concat(name,'::',relation) as name")
            ->orderBy("name")
            ->all();
        return ['results' => $results];
    }
}
