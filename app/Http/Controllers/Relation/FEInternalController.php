<?php

namespace App\Http\Controllers\Relation;

use App\Data\CreateRelationFEData;
use App\Data\CreateRelationFrameData;
use App\Data\Relation\CreateData;
use App\Data\Relation\FEData;
use App\Data\Relation\FEInternalData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\EntityRelation;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\Relation;
use App\Repositories\RelationType;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class FEInternalController extends Controller
{
    #[Delete(path: '/relation/feinternal/{idEntityRelation}')]
    public function deleteFERelation(int $idEntityRelation)
    {
        try {
            Criteria::deleteById("entityrelation","idEntityRelation", $idEntityRelation);
            $this->trigger('reload-gridFEInternalRelation');
            return $this->renderNotify("success", "Relation deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/relation/feinternal')]
    public function newFERelation(FEInternalData $data)
    {
        try {
            $idFrameElementRelated = (array)$data->idFrameElementRelated;
            if (count($idFrameElementRelated)) {
                $idFirst = array_shift($idFrameElementRelated);
                $first = FrameElement::byId($idFirst);
                foreach ($idFrameElementRelated as $idNext) {
                    $next = FrameElement::byId($idNext);
                    RelationService::create($data->relationTypeEntry, $first->idEntity, $next->idEntity);
                }
            }
            return response()
                ->view("Relation.feInternalFormNew", [
                    'idFrame' => $data->idFrame,
                    'idFrameElementRelated' => $data->idFrameElementRelated,
                    'relationType' => $data->relationType
                ])->header('HX-Trigger', $this->notify("success", "Relation created."))
                ->header('HX-Trigger','reload-gridFEInternalRelation');

        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
