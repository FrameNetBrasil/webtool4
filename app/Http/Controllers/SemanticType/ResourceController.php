<?php

namespace App\Http\Controllers\SemanticType;

use App\Data\SemanticType\CreateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\EntityRelation;
use App\Repositories\Relation;
use App\Repositories\SemanticType;
use App\Services\EntryService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class ResourceController extends Controller
{
    /***
     * Child
     */
    #[Get(path: '/semanticType/{idEntity}/childAdd/{root}')]
    public function childFormAdd(string $idEntity, string $root)
    {
        return view("SemanticType.childAdd", [
            'idEntity' => $idEntity,
            'root' => $root
        ]);
    }

    #[Get(path: '/semanticType/{idEntity}/childGrid')]
    public function childGrid(string $idEntity)
    {
        $relations = SemanticType::listRelations($idEntity);
        return view("SemanticType.childGrid", [
            'idEntity' => $idEntity,
            'relations' => $relations
        ]);
    }

    #[Post(path: '/semanticType/{idEntity}/add')]
    public function childAdd(CreateData $data)
    {
        try {
            $st = SemanticType::byId($data->idSemanticType);
            RelationService::create(
                'rel_hassemtype',
                $data->idEntity,
                $st->idEntity,
                null,
                null,
                $data->idUser
            );
            $this->trigger('reload-gridChildST');
            return $this->renderNotify("success", "Semantic Type added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/semanticType/{idEntityRelation}')]
    public function childDelete(int $idEntityRelation)
    {
        try {
            Criteria::table("entityrelation")->where("idEntityRelation", $idEntityRelation)->delete();
            $this->trigger('reload-gridChildST');
            return $this->renderNotify("success", "Semantic Type deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/semanticType/{idEntity}/childAddSubType/{root}')]
    public function childFormAddSubType(string $idEntity, string $root)
    {
        return view("SemanticType.childAddSubType", [
            'idEntity' => $idEntity,
            'root' => $root
        ]);
    }

    #[Get(path: '/semanticType/{idEntity}/childGridSubType')]
    public function childGridSubType(string $idEntity)
    {
        return view("SemanticType.childGridSubType", [
            'idEntity' => $idEntity,
            'relations' => SemanticType::listRelations($idEntity)->all()
        ]);
    }


    /***
     * Master
     */

    #[Get(path: '/semanticType/{id}')]
    public function get(string $id)
    {
        return view("SemanticType.edit", [
            'semanticType' => SemanticType::getById($id),
        ]);
    }
}
