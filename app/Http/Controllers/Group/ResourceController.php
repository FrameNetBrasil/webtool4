<?php

namespace App\Http\Controllers\Group;

use App\Data\Group\CreateData;
use App\Data\Group\UpdateData;
use App\Http\Controllers\Controller;
use App\Repositories\Group;
use App\Repositories\User;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware("master")]
class ResourceController extends Controller
{
//    #[Get(path: '/group')]
//    public function resource()
//    {
//        return view("User.resource");
//    }

    #[Get(path: '/group/listForSelect')]
    public function listForSelect()
    {
        return Group::listForSelect();
    }

    #[Get(path: '/group/new')]
    public function new()
    {
        return view("Group.formNew");
    }

//    #[Get(path: '/group/grid/{fragment?}')]
//    #[Post(path: '/group/grid/{fragment?}')]
//    public function grid(SearchData $search, ?string $fragment = null)
//    {
//        debug($search);
//        $users = User::listToGrid($search);
//        //debug($users);
//        $groups = array_filter(
//            Group::listForGrid($search?->group ?? ''),
//            fn($key) => isset($users[$key]),
//            ARRAY_FILTER_USE_KEY
//        );
//        $view = view("User.grid",[
//            'groups' => $groups,
//            'users' => $users
//        ]);
//        return (is_null($fragment) ? $view : $view->fragment('search'));
//    }

    #[Get(path: '/group/{id}/edit')]
    public function edit(string $id)
    {
        debug($id);
        return view("Group.edit",[
            'group' => Group::getById($id)
        ]);
    }

    #[Get(path: '/group/{id}/formEdit')]
    public function formEdit(string $id)
    {
        debug(Group::getById($id));
        return view("Group.formEdit",[
            'group' => Group::getById($id)
        ]);
    }

//    #[Put(path: '/group/{id}/authorize')]
//    public function authorizeUser(string $id)
//    {
//        try {
//            User::authorize($id);
//            $this->trigger("reload-gridUser");
//            return $this->renderNotify("success", "User authorized.");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }
//
    #[Post(path: '/group')]
    public function update(UpdateData $data)
    {
        try {
            Group::update($data);
            $this->trigger("reload-gridUser");
            return $this->renderNotify("success", "Group updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/group/new')]
    public function create(CreateData $group)
    {
        try {
            Group::create($group);
            $this->trigger("reload-gridUser");
            return $this->renderNotify("success", "Group created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/group/{id}')]
    public function delete(string $id)
    {
        try {
            Group::delete($id);
            return $this->clientRedirect("/user");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }
}
