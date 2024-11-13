<?php

namespace App\Http\Controllers\GenericLabel;

use App\Data\GenericLabel\CreateData;
use App\Data\GenericLabel\SearchData;
use App\Data\GenericLabel\UpdateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\GenericLabel;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class ResourceController extends Controller
{

    #[Get(path: '/genericlabel')]
    public function resource(int|string $idConcept = '', string $lang = '')
    {
        $search = session('searchGenericLabel') ?? SearchData::from();
        $data = [];
        return view("GenericLabel.resource", [
            'search' => $search,
            'idGenericLabel' => null,
            'data' => $data,
        ]);
    }

    #[Get(path: '/genericlabel/grid')]
    public function grid()
    {
        $search = session('searchGenericLabel') ?? SearchData::from();
        $data = [];
        return view("GenericLabel.grid", [
            'search' => $search,
            'idGenericLabel' => null,
            'data' => $data,
        ]);
    }

    #[Get(path: '/genericlabel/data')]
    public function data(SearchData $search)
    {
        debug($search);
        $genericLabelIcon = view('components.icon.genericlabel')->render();
        $tree = [];
        $labels = Criteria::table("genericlabel")
            ->join("language","genericlabel.idLanguage","=","language.idLanguage")
            ->where("genericlabel.idLanguage",$search->idLanguageSearch)
            ->where("name","startswith", $search->genericLabel)
            ->select("genericlabel.idGenericLabel","genericlabel.name","genericlabel.idEntity","language.language")
            ->orderBy("genericlabel.name")
            ->all();
        foreach ($labels as $label) {
            $n = [];
            $n['id'] = $label->idEntity;
            $n['idGenericLabel'] = $label->idGenericLabel;
            $n['type'] = 'genericLabel';
            $n['text'] =  $genericLabelIcon . $label->name . ' [' . $label->language . ']';
            $n['state'] = 'open';
            $n['children'] = [];
            $tree[] = $n;
        }
        return $tree;
    }
    #[Get(path: '/genericlabel/{id}/edit')]
    public function get(string $id)
    {
        return view("GenericLabel.edit", [
            'genericLabel' => GenericLabel::byId($id)
        ]);
    }


    #[Get(path: '/genericlabel/{id}/formEdit')]
    public function formEdit(string $id)
    {
        return view("GenericLabel.formEdit", [
            'genericLabel' => GenericLabel::byId($id)
        ]);
    }

    #[Delete(path: '/genericlabel/{idGenericLabel}')]
    public function delete(int $idGenericLabel)
    {
        try {
            Criteria::deleteById("genericlabel", "idGenericLabel", $idGenericLabel);
            $this->trigger("reload-gridGenericLabel");
            return $this->renderNotify("success", "Generic Label deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/genericlabel/new')]
    public function formNew()
    {
        return view("GenericLabel.formNew");
    }

    #[Post(path: '/genericlabel/new')]
    public function new(CreateData $data)
    {
        try {
            $json = json_encode([
                'idDomain' => $data->idDomain,
                'nameEn' => $data->semanticTypeName,
                'idUser' => $data->idUser
            ]);
            $idSemanticType = Criteria::function('semantictype_create(?)', [$json]);
            return $this->renderNotify("success", "Semantic Type created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/genericlabel')]
    public function update(UpdateData $data)
    {
        try {
            Criteria::table("genericlabel")
                ->where("idGenericLabel", $data->idGenericLabel)
                ->update($data->toArray());
            return $this->renderNotify("success", "GenericLabel updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
