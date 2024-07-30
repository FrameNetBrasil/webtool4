<?php

namespace App\Http\Controllers\Relation;

use App\Data\CreateRelationFEData;
use App\Data\CreateRelationFrameData;
use App\Data\Relation\CreateData;
use App\Data\Relation\FEData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\EntityRelation;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\Relation;
use App\Repositories\RelationType;
use App\Services\AppService;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class FEController extends Controller
{
    #[Delete(path: '/relation/fe/{idEntityRelation}')]
    public function deleteFERelation(string $idEntityRelation)
    {
        try {
            Criteria::deleteById("entityrelation","idEntityRelation", $idEntityRelation);
            $this->trigger('reload-gridFERelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/relation/fe')]
    public function newFERelation(FEData $data)
    {
        try {
            $relation = Criteria::byId("view_relation","idEntityRelation", $data->idEntityRelation);
            $fe = FrameElement::byId($data->idFrameElement);
            $feRelated = FrameElement::byId($data->idFrameElementRelated);
            RelationService::create($relation->relationType, $fe->idEntity, $feRelated->idEntity, null, $data->idEntityRelation);
            $this->trigger('reload-gridFERelation');
            return $this->renderNotify("success", "Relation created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
