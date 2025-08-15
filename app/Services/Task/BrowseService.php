<?php

namespace App\Services\Task;

use App\Database\Criteria;
use App\Repositories\AnnotationSet;
use App\Repositories\Project;

class BrowseService
{
    public static function browseTaskBySearch(object $search, bool $leaf = false): array
    {
        $result = [];
        $tasks = Criteria::table("task as t")
            ->join("taskgroup as tg", "t.idTaskGroup", "=", "tg.idTaskGroup")
            ->select("t.idTask","t.name as taskName","tg.name as taskGroupName")
            ->where('t.name', "startswith", $search->task)
            ->orderBy('t.name')->all();
        foreach ($tasks as $task) {
            $result[$task->idTask] = [
                'id' => $task->idTask,
                'type' => 'task',
                'text' => view('Task.partials.task',(array)$task)->render(),
                'leaf' => $leaf,
            ];
        }
        return $result;
    }

    public static function browseUserBySearch(object $search, bool $leaf = true): array
    {
        $result = [];
        $usertasks = Criteria::table("view_usertask")
            ->select("idUser","email","userName as name")
            ->distinct()
            ->where('userName', "startswith", $search->user)
            ->orderBy('userName')
            ->all();
        foreach ($usertasks as $usertask) {
            $result[$usertask->idUser] = [
                'id' => $usertask->idUser,
                'type' => 'user',
                'text' => view('Task.partials.user',(array)$usertask)->render(),
                'leaf' => false,
            ];
        }
        return $result;
    }

    public static function browseUserForTaskBySearch(object $search, bool $leaf = true): array
    {
        $result = [];
        $usertasks = Criteria::table("view_usertask")
            ->select("idUserTask","idUser","email","userName as name")
            ->where('idTask', $search->idTask)
            ->orderBy('userName')
            ->all();
        foreach ($usertasks as $usertask) {
            $result[$usertask->idUserTask] = [
                'id' => $usertask->idUserTask,
                'type' => 'usertask',
                'text' => view('Task.partials.user',(array)$usertask)->render(),
                'leaf' => true,
            ];
        }
        return $result;
    }

    public static function browseTaskForUserBySearch(object $search, bool $leaf = true): array
    {
        $result = [];
        $usertasks = Criteria::table("view_usertask")
            ->select("idUserTask","taskName","taskGroupName")
            ->where('idUser', $search->idUser)
            ->orderBy('taskName')
            ->all();
        foreach ($usertasks as $usertask) {
            $result[$usertask->idUserTask] = [
                'id' => $usertask->idUserTask,
                'type' => 'usertask',
                'text' => view('Task.partials.task',(array)$usertask)->render(),
                'leaf' => true,
            ];
        }
        return $result;
    }

}
