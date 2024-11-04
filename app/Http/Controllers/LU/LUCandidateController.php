<?php

namespace App\Http\Controllers\LU;

use App\Data\LUCandidate\CreateData;
use App\Data\LUCandidate\SearchData;
use App\Data\LUCandidate\UpdateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Lemma;
use App\Repositories\LUCandidate;
use App\Repositories\User;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class LUCandidateController extends Controller
{

    #[Get(path: '/luCandidate')]
    public function resource()
    {
        return view("LUCandidate.resource");
    }

    #[Get(path: '/luCandidate/grid/{fragment?}')]
    #[Post(path: '/luCandidate/grid/{fragment?}')]
    public function grid(SearchData $search, ?string $fragment = null)
    {
        $view = view("LUCandidate.grid",[
            'search' => $search,
        ]);
        return (is_null($fragment) ? $view : $view->fragment('search'));
    }

    #[Get(path: '/luCandidate/new')]
    public function new()
    {
        return view("LUCandidate.formNew");
    }

    #[Post(path: '/luCandidate')]
    public function newLU(CreateData $data)
    {
        try {
            $lemma = Lemma::byId($data->idLemma);
            $data->name = $lemma->name;
            Criteria::table("lucandidate")
                ->insert([
                    'idLemma' => $data->idLemma,
                    'name' => $data->name,
                    'idFrame' => $data->idFrame,
                    'frameCandidate' => $data->frameCandidate,
                    'senseDescription' => $data->senseDescription
                ]);
            $this->trigger('reload-gridLUCandidate');
            return $this->renderNotify("success", "LU Candidate created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/luCandidate/{id}/edit')]
    public function edit(string $id)
    {
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        $isManager = User::isManager($user);
        return view("LUCandidate.edit", [
            'luCandidate' => LUCandidate::byId($id),
            'isManager' => $isManager,
        ]);
    }

    #[Get(path: '/luCandidate/{id}/formEdit')]
    public function formEdit(string $id)
    {
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        $isManager = User::isManager($user);
        return view("LUCandidate.formEdit", [
            'luCandidate' => LUCandidate::byId($id),
            'isManager' => $isManager,
        ]);
    }

    #[Get(path: '/luCandidate/fes/{idFrame}')]
    public function feCombobox(int $idFrame)
    {
        return view("LUCandidate.fes", [
            'idFrame' => $idFrame
        ]);
    }

    #[Delete(path: '/luCandidate/{id}')]
    public function delete(string $id)
    {
        try {
            Criteria::deleteById("lucandidate","idLUCandidate",$id);
            $this->trigger('reload-gridLUCandidate');
            return $this->renderNotify("success", "LU candidate deleted.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/luCandidate/{id}')]
    public function update(UpdateData $data)
    {
//        if ($data->idFrame == '') {
//            $lu = LU::byId($data->idLU);
//            $data->idFrame = $lu->idFrame;
//        }
//        LU::update($data);
        $this->trigger('reload-gridLUCandidate');
        return $this->renderNotify("success", "LU candidate updated.");
    }

    #[Post(path: '/luCandidate/createLU')]
    public function createLU(UpdateData $data)
    {
        try {
            $lemma = Lemma::byId($data->idLemma);
            $data->name = $lemma->name;
            Criteria::function('lu_create(?)', [$data->toJson()]);
            Criteria::deleteById("lucandidate","idLUCandidate",$data->idLUCandidate);
            return $this->renderNotify("success", "LU created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
        return $this->renderNotify("success", "LU created.");
    }

}
