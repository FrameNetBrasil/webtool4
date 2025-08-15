@php
    use App\Database\Criteria;use App\Repositories\User;use App\Services\AppService; use App\Services\MessageService;
    $idUser = AppService::getCurrentIdUser();
    $user = User::byId($idUser);
    $isManager = User::isManager($user);
    $messages = MessageService::getMessagesToUser($idUser);
    $tasks = Criteria::table("view_usertask as ut")
        ->join("view_task_manager as tm","ut.idTask","=","tm.idTask")
        ->select("ut.projectName","ut.taskName","ut.taskGroupName")
        ->selectRaw("GROUP_CONCAT(DISTINCT tm.userName SEPARATOR ',') as manager")
        ->groupByRaw("ut.projectName,ut.taskName,ut.taskGroupName")
        ->where("ut.idUser",$idUser)
        ->where("ut.idProject","<>", 1)
        ->all();
    $tasksForManager =  Criteria::table("view_task_manager as tm")
        ->select("tm.projectName","tm.taskName","tm.taskGroupName")
        ->where("tm.idUser",$idUser)
        ->orderBy("tm.projectName")
        ->orderBy("tm.taskName")
        ->all();

@endphp
<x-layout.page>
    <x-slot:head>
        <x-breadcrumb :sections="[['','Home']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
                <div class="container is-fluid">
                    <div class="content">
                        @include("App.messages")

                        @if(count($tasksForManager) > 0)
                            <div class="block">
                                <h2 class="title is-4">Managed project/tasks</h2>
                                <div class="table-container">
                                    <table class="table is-striped is-narrow is-fullwidth">
                                        <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th>Task</th>
                                            <th>Task group</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($tasksForManager as $task)
                                            <tr>
                                                <td>{{$task->projectName}}</td>
                                                <td>{{$task->taskName}}</td>
                                                <td>{{$task->taskGroupName}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if(!$isManager)
                            <div class="block">
                                <h2 class="title is-4">My tasks</h2>
                                <div class="table-container">
                                    <table class="table is-striped is-narrow is-fullwidth">
                                        <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th>Task</th>
                                            <th>Task group</th>
                                            <th>Manager(s)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($tasks as $task)
                                            <tr>
                                                <td>{{$task->projectName}}</td>
                                                <td>{{$task->taskName}}</td>
                                                <td>{{$task->taskGroupName}}</td>
                                                <td>{{$task->manager}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout.page>
