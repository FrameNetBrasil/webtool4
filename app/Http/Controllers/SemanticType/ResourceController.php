<?php

namespace App\Http\Controllers\SemanticType;

use App\Data\ComboBox\QData;
use App\Data\SemanticType\CreateData;
use App\Data\SemanticType\UpdateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\SemanticType;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class ResourceController extends Controller
{

    #[Get(path: '/semanticType/{id}/edit')]
    public function edit(string $id)
    {
        return view("SemanticType.edit",[
            'semanticType' => SemanticType::byId($id)
        ]);
    }

    #[Get(path: '/semanticType/{id}/formEdit')]
    public function formEdit(string $id)
    {
        $st = SemanticType::byId($id);
        return view("SemanticType.formEdit",[
            'semanticType' => $st
        ]);
    }

    #[Post(path: '/semanticType')]
    public function update(UpdateData $data)
    {
        try {
            debug($data);
            SemanticType::setParent($data->idSemanticType, $data->idSemanticTypeParent);
            return $this->renderNotify("success", "SemanticType updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/semanticType/new')]
    public function new()
    {
        return view("SemanticType.formNew");
    }

    #[Post(path: '/semanticType/new')]
    public function create(CreateData $data)
    {
        try {
            debug($data);
            $idSemanticType = Criteria::function('semantictype_create(?)', [$data->toJson()]);
            SemanticType::setParent($idSemanticType, $data->idSemanticTypeParent);
            return $this->renderNotify("success", "SemanticType created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/semanticType/{id}')]
    public function delete(string $id)
    {
        try {
            Criteria::function('semantictype_delete(?,?)', [$id, AppService::getCurrentIdUser()]);
            return $this->clientRedirect("/semanticType");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/semanticType/list/forSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->semanticType) > 2) ? $data->semanticType : 'none';
        return ['results' => Criteria::byFilterLanguage("view_semantictype",["name","startswith",$name])->orderby("name")->all()];
    }
}
