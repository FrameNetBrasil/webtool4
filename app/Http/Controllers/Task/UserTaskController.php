<?php

namespace App\Http\Controllers\Task;

use App\Data\Dataset\CreateData;
use App\Data\Dataset\ProjectData;
use App\Data\Dataset\SearchData;
use App\Data\Dataset\UpdateData;
use App\Data\Task\UserTaskData;
use App\Data\Task\UserTaskDocumentData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Dataset;
use App\Repositories\UserTask;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware("master")]
class UserTaskController extends Controller
{
//    #[Get(path: '/task')]
//    public function resource()
//    {
//        return view("Task.resource");
//    }
//
//    #[Get(path: '/task/new')]
//    public function new()
//    {
//        return view("Task.formNew");
//    }
//
//    #[Get(path: '/task/grid/{fragment?}')]
//    #[Post(path: '/task/grid/{fragment?}')]
//    public function grid(SearchData $search, ?string $fragment = null)
//    {
//        $users = Task::listUsersToGrid($search->user ?? '');
//        $tasks = Task::listToGrid($search?->task ?? '');
//        $view = view("Task.grid",[
//            'tasks' => $tasks,
//            'users' => $users
//        ]);
//        return (is_null($fragment) ? $view : $view->fragment('search'));
//    }

    #[Get(path: '/usertask/{id}/edit')]
    public function edit(string $id)
    {
        return view("UserTask.edit",[
            'usertask' => UserTask::byId($id)
        ]);
    }

    #[Get(path: '/usertask/{id}/documents')]
    public function documents(string $id)
    {
        return view("UserTask.documents",[
            'idUserTask' => $id
        ]);
    }

    #[Get(path: '/usertask/{id}/documents/formNew')]
    public function documentsNew(string $id)
    {
        return view("UserTask.documentsNew",[
            'idUserTask' => $id
        ]);
    }

    #[Get(path: '/usertask/{id}/documents/grid')]
    public function documentsGrid(string $id)
    {
        $documents = Criteria::table("usertask_document as utd")
            ->join("view_document as d","utd.idDocument","=","d.idDocument")
            ->select("d.idDocument","d.name")
            ->where("d.idLanguage","=", AppService::getCurrentIdLanguage())
            ->all();
        return view("UserTask.documentsGrid",[
            'idUserTask' => $id,
            'documents' => $documents
        ]);
    }

//    #[Post(path: '/task')]
//    public function update(UpdateData $data)
//    {
//        try {
//            Criteria::table("task")
//                ->where("idTask",$data->idTask)
//                ->update($data->toArray());
//            $this->trigger("reload-gridTask");
//            return $this->renderNotify("success", "Task updated.");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }

    #[Post(path: '/usertask/documents/new')]
    public function create(UserTaskDocumentData $data)
    {
        try {
            Criteria::table("usertask_document")
                ->insert($data->toArray());
            $this->trigger("reload-gridUserTaskDocuments");
            return $this->renderNotify("success", "Document added to UserTask.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/usertask/{idUserTask}/documents/{idDocument}')]
    public function delete(string $idUserTask, string $idDocument)
    {
        try {
            Criteria::table("usertask_document")
                ->where("idUserTask", $idUserTask)
                ->where("idDocument", $idDocument)
                ->delete();
            $this->trigger("reload-gridUserTaskDocuments");
            return $this->renderNotify("success", "Document removed from UserTask.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


}
