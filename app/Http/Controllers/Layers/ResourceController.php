<?php

namespace App\Http\Controllers\Layers;

use App\Data\Layers\CreateGenericLabelData;
use App\Data\Layers\CreateLayerGroupData;
use App\Data\Layers\CreateLayerTypeData;
use App\Data\Layers\SearchData;
use App\Data\Layers\UpdateGenericLabelData;
use App\Data\Layers\UpdateLayerGroupData;
use App\Data\Layers\UpdateLayerTypeData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\GenericLabel;
use App\Repositories\LayerGroup;
use App\Repositories\LayerType;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware("master")]
class ResourceController extends Controller
{

    #[Get(path: '/layers')]
    public function browse()
    {
        $search = session('searchLayers') ?? SearchData::from();
        return view("Layers.resource", [
            'search' => $search
        ]);
    }

    #[Get(path: '/layers/grid/{fragment?}')]
    #[Post(path: '/layers/grid/{fragment?}')]
    public function grid(SearchData $search, ?string $fragment = null)
    {
        $view = view("Layers.grid", [
            'search' => $search,
        ]);
        return (is_null($fragment) ? $view : $view->fragment('search'));
    }

    /*------
      Layergroup
      ------ */

    #[Get(path: '/layers/layergroup/new')]
    public function formNewLayerGroup()
    {
        return view("Layers.formNewLayerGroup");
    }

    #[Get(path: '/layers/layergroup/{idLayerGroup}/edit')]
    public function layergroup(int $idLayerGroup)
    {
        $layerGroup = LayerGroup::byId($idLayerGroup);
        return view("Layers.editLayerGroup", [
            'layerGroup' => $layerGroup,
        ]);
    }

    #[Get(path: '/layers/layergroup/{idLayerGroup}/formEdit')]
    public function formEditLayerGroup(int $idLayerGroup)
    {
        $layerGroup = LayerGroup::byId($idLayerGroup);
        return view("Layers.formEditLayerGroup", [
            'layerGroup' => $layerGroup,
        ]);
    }

    #[Post(path: '/layers/layergroup/new')]
    public function newLayerGroup(CreateLayerGroupData $data)
    {
        try {
            $exists = Criteria::table("layergroup")
                ->whereRaw("name = '{$data->name}' collate 'utf8mb4_bin'")
                ->first();
            if (!is_null($exists)) {
                throw new \Exception("LayerGroup already exists.");
            }
            $newLayerGroup = [
                'name' => $data->name,
            ];
            $idLayerGroup = Criteria::create("layergroup", $newLayerGroup);
            $layerGroup = LayerGroup::byId($idLayerGroup);
            $view = view("Layers.layerGroup", [
                'layerGroup' => $layerGroup,
            ]);
            return $view->fragment("content");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/layers/layergroup')]
    public function updateLayerGroup(UpdateLayerGroupData $data)
    {
        try {
            Criteria::table("layergroup")
                ->where("idLayerGroup", $data->idLayerGroup)
                ->update([
                    'name' => $data->name,
                ]);
            return $this->renderNotify("success", "LayerGroup updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/layers/layergroup/{idLayerGroup}')]
    public function deleteLayerGroup(string $idLayerGroup)
    {
        try {
            Criteria::deleteById("layergroup","idLayerGroup", $idLayerGroup);
            $this->trigger('reload-gridLayers');
            return $this->renderNotify("success", "LayerGroup removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


    /*------
      LayerType
      ------ */

    #[Get(path: '/layers/layertype/new')]
    public function formNewLayerType()
    {
        return view("Layers.formNewLayerType");
    }

    #[Get(path: '/layers/layertype/{idLayerType}/edit')]
    public function layertype(int $idLayerType)
    {
        $layerType = LayerType::byId($idLayerType);
        return view("Layers.editLayerType", [
            'layerType' => $layerType,
        ]);
    }

    #[Get(path: '/layers/layertype/{idLayerType}/formEdit')]
    public function formEditLayerType(int $idLayerType)
    {
        $layerType = LayerType::byId($idLayerType);
        return view("Layers.formEditLayerType", [
            'layerType' => $layerType,
        ]);
    }

    #[Post(path: '/layers/layertype/new')]
    public function newLayerType(CreateLayerTypeData $data)
    {
        try {
            $exists = Criteria::table("layertype")
                ->whereRaw("name = '{$data->name}' collate 'utf8mb4_bin'")
                ->first();
            if (!is_null($exists)) {
                throw new \Exception("LayerType already exists.");
            }
            $newLayerType = [
                'name' => $data->name,
            ];
            $idLayerType = Criteria::create("layertype", $newLayerType);
            $layerType = LayerType::byId($idLayerType);
            $view = view("Layers.layerType", [
                'layerType' => $layerType,
            ]);
            return $view->fragment("content");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/layers/layertype/{idLayerType}')]
    public function updateLayerType(UpdateLayerTypeData $data)
    {
        try {
            Criteria::table("layertype")
                ->where("idLayerType", $data->idLayerType)
                ->update([
                    'name' => $data->name,
                ]);
            return $this->renderNotify("success", "LayerType updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/layers/layertype/{idLayerType}')]
    public function deleteLayerType(string $idLayerType)
    {
        try {
            Criteria::deleteById("layertype","idLayerType", $idLayerType);
            $this->trigger('reload-gridLayers');
            return $this->renderNotify("success", "LayerType removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    /*-----------
      GenericLabel
      ----------- */

    #[Get(path: '/layers/genericlabel/new')]
    public function formNewGenericLabel()
    {
        return view("Layers.formNewLayerType");
    }

    #[Get(path: '/layers/genericlabel/{idLayerType}/{fragment?}')]
    public function genericLabel(int $idLayerType, string $fragment = null)
    {
        $layerType = LayerType::byId($idLayerType);
        $view = view("Layers.layerType", [
            'layerType' => $layerType,
        ]);
        return (is_null($fragment) ? $view : $view->fragment($fragment));
    }

    #[Post(path: '/layers/genericlabel/new')]
    public function newGenericLabel(CreateGenericLabelData $data)
    {
        try {
            $exists = Criteria::table("genericlabel")
                ->whereRaw("name = '{$data->name}' collate 'utf8mb4_bin'")
                ->first();
            if (!is_null($exists)) {
                throw new \Exception("GenericLabel already exists.");
            }
            $newGenericLabel = [
                'name' => $data->name,
            ];
            $idGenericLabel = Criteria::create("genericlabel", $newGenericLabel);
            $genericLabel = GenericLabel::byId($idGenericLabel);
            $view = view("Layers.genericLabel", [
                'genricLabel' => $genericLabel,
            ]);
            return $view->fragment("content");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/layers/genericlabel/{idLayerType}')]
    public function updateGenericLabel(UpdateGenericLabelData $data)
    {
        try {
            Criteria::table("genericlabel")
                ->where("idGenericLabel", $data->idGenericLabel)
                ->update([
                    'name' => $data->name,
                ]);
            return $this->renderNotify("success", "GenericLabel updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/layers/genericlabel/{idGenericLabel}')]
    public function deleteGenericLabel(string $idGenericLabel)
    {
        try {
            Criteria::deleteById("genericlabel","idGenericLabel", $idGenericLabel);
            $this->trigger('reload-gridLayers');
            return $this->renderNotify("success", "GenericLabel removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
