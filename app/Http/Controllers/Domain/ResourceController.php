<?php

namespace App\Http\Controllers\Domain;

use App\Data\Domain\CreateData;
use App\Data\Domain\SearchData;
use App\Data\Domain\UpdateData;
use App\Http\Controllers\Controller;
use App\Repositories\Domain;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;


#[Middleware("master")]
class ResourceController extends Controller
{
    #[Get(path: '/domain')]
    public function resource()
    {
        return view("Domain.resource");
    }

    #[Get(path: '/domain/new')]
    public function new()
    {
        return view("Domain.formNew");
    }

    #[Get(path: '/domain/grid/{fragment?}')]
    #[Post(path: '/domain/grid/{fragment?}')]
    public function grid(SearchData $search, ?string $fragment = null)
    {
        debug($search);
        $domains = Domain::listToGrid($search);
        //debug($users);
        $view = view("Domain.grid",[
            'domains' => $domains
        ]);
        return (is_null($fragment) ? $view : $view->fragment('search'));
    }

    #[Get(path: '/domain/{id}/edit')]
    public function edit(string $id)
    {
        debug($id);
        return view("Domain.edit",[
            'domain' => Domain::getById($id)
        ]);
    }

    #[Get(path: '/domain/{id}/formEdit')]
    public function formEdit(string $id)
    {
        return view("Domain.formEdit",[
            'domain' => Domain::getById($id)
        ]);
    }

    #[Post(path: '/domain')]
    public function update(UpdateData $data)
    {
        try {
            Domain::update($data);
            return $this->renderNotify("success", "Domain updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/domain/new')]
    public function create(CreateData $domain)
    {
        try {
            Domain::create($domain);
            $this->trigger("reload-gridDomain");
            return $this->renderNotify("success", "Domain created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/domain/{id}')]
    public function delete(string $id)
    {
        try {
            Domain::delete($id);
            return $this->clientRedirect("/domain");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
