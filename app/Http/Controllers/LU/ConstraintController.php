<?php

namespace App\Http\Controllers\LU;

use App\Data\CreateFEData;
use App\Data\FE\UpdateData;
use App\Http\Controllers\Controller;
use App\Repositories\Constraint;
use App\Repositories\EntityRelation;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Repositories\FrameElement;
use App\Repositories\LU;
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
class ConstraintController extends Controller
{
    #[Get(path: '/lu/{id}/constraints')]
    public function constraints(string $id)
    {
        return view("Constraint.luChild",[
            'idLU' => $id
        ]);
    }

    #[Get(path: '/lu/{id}/constraints/formNew/{fragment?}')]
    public function constraintsFormNew(int $id, ?string $fragment = null)
    {
        $view = view("Constraint.luFormNew", [
            'idLU' => $id,
            'lu' => LU::byId($id),
            'fragment' => $fragment ?? ''
        ]);
        return (is_null($fragment) ? $view : $view->fragment($fragment));
    }

    #[Get(path: '/lu/{id}/constraints/grid')]
    public function constraintsGrid(int $id)
    {
        $lu = LU::byId($id);
        return view("Constraint.luGrid", [
            'idLU' => $id,
            'constraints' => Constraint::listByIdConstrained($lu->idEntity)
        ]);
    }


}
