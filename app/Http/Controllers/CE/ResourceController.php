<?php

namespace App\Http\Controllers\CE;

use App\Data\CreateFEData;
use App\Data\CE\CreateData;
use App\Data\CE\UpdateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\ConstructionElement;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\ViewConstraint;
use App\Repositories\ViewFrameElement;
use App\Services\AppService;
use App\Services\EntryService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class ResourceController extends Controller
{
    #[Post(path: '/ce')]
    public function newFE(CreateData $data)
    {
        debug($data);
        try {
            Criteria::function('fe_create(?, ?, ?, ?, ?)', [
                $data->idFrame,
                $data->nameEn,
                $data->coreType,
                $data->idColor,
                $data->idUser
            ]);
            $this->trigger('reload-gridFE');
            return $this->renderNotify("success", "FrameElement created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/ce/{id}/edit')]
    public function edit(string $id)
    {
        return view("CE.edit", [
            'constructionElement' => ConstructionElement::byId($id)
        ]);
    }

    #[Get(path: '/ce/{id}/main')]
    public function main(string $id)
    {
        $this->data->_layout = 'main';
        return $this->edit($id);
    }


    #[Delete(path: '/ce/{id}')]
    public function delete(string $id)
    {
        try {
            Criteria::function('fe_delete(?, ?)', [
                $id,
                AppService::getCurrentUser()->idUser
            ]);
            $this->trigger('reload-gridFE');
            return $this->renderNotify("success", "FrameElement deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/ce/{id}/formEdit')]
    public function formEdit(string $id)
    {
        return view("FE.formEdit", [
            'frameElement' => FrameElement::byId($id)
        ]);
    }

    #[Put(path: '/ce/{id}')]
    public function update(string $id, UpdateData $data)
    {
        FrameElement::update($data);
        $this->trigger('reload-objectFE');
        return $this->renderNotify("success", "FrameElement updated.");
    }

}
