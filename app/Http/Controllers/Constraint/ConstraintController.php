<?php

namespace App\Http\Controllers\Constraint;

use App\Data\FE\ConstraintData as FEConstraintData;
use App\Data\LU\ConstraintData as LUConstraintData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\LU;
use App\Repositories\Qualia;
use App\Services\RelationService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class ConstraintController extends Controller
{
    #[Post(path: '/constraint/fe/{id}')]
    public function constraintFE(FEConstraintData $data)
    {
        debug($data);
        try {
            $fe = FrameElement::byId($data->idFrameElement);
            if ($data->idFrameConstraint > 0) {
                $constraintEntry = 'rel_constraint_frame';
                $idConstraint = Criteria::create("entity",["type" => "CON"]);
                $frame = Frame::byId($data->idFrameConstraint);
                RelationService::create($constraintEntry, $idConstraint, $fe->idEntity, $frame->idEntity);
                debug("Creating frame constraint");
            }
            if ($data->idFEQualiaConstraint > 0) {
                $constraintEntry = 'rel_qualia';
                $feQualia = FrameElement::byId($data->idFEQualiaConstraint);
                $qualia = Qualia::byId($data->idQualiaConstraint);
                RelationService::create($constraintEntry, $fe->idEntity, $feQualia->idEntity, $qualia->idEntity);
                debug("Creating qualia constraint");
            }
            if ($data->idFEMetonymConstraint > 0) {
                $constraintEntry = 'rel_festandsforfe';
                $feMetonym = FrameElement::byId($data->idFEMetonymConstraint);
                RelationService::create($constraintEntry, $fe->idEntity, $feMetonym->idEntity);
                debug("Creating fe metonym constraint");
            }
            if ($data->idLUMetonymConstraint > 0) {
                $constraintEntry = 'rel_festandsforlu';
                $luMetonym = LU::byId($data->idLUMetonymConstraint);
                RelationService::create($constraintEntry, $fe->idEntity, $luMetonym->idEntity);
                debug("Creating lu metonym constraint");
            }
            $this->trigger('reload-gridConstraintFE');
            return $this->renderNotify("success", "Constraint created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/constraint/fe/{idConstraintInstance}')]
    public function deleteConstraintFE(int $idConstraintInstance)
    {
        try {
            Criteria::table("entityrelation")->where("idEntityRelation", $idConstraintInstance)->delete();
            $this->trigger('reload-gridConstraintFE');
            return $this->renderNotify("success", "Constraint deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/constraint/lu/{id}')]
    public function constraintLU(LUConstraintData $data)
    {
        try {
            debug($data);
            $lu = LU::byId($data->idLU);
            if ($data->idLUMetonymConstraint > 0) {
                $constraintEntry = 'rel_lustandsforlu';
                $luMetonym = LU::byId($data->idLUMetonymConstraint);
                RelationService::create($constraintEntry, $lu->idEntity, $luMetonym->idEntity);
            }
            if ($data->idLUEquivalenceConstraint > 0 ) {
                $constraintEntry = 'rel_luequivalence';
                $luEquivalence = LU::byId($data->idLUEquivalenceConstraint);
                RelationService::create($constraintEntry, $lu->idEntity, $luEquivalence->idEntity);
            }
            $this->trigger('reload-gridConstraintLU');
            return $this->renderNotify("success", "Constraint created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/constraint/lu/{idConstraintInstance}')]
    public function deleteConstraintLU(int $idConstraintInstance)
    {
        try {
            Criteria::table("entityrelation")->where("idEntityRelation", $idConstraintInstance)->delete();
            $this->trigger('reload-gridConstraintLU');
            return $this->renderNotify("success", "Constraint deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
