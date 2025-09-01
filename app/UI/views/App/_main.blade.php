@php use App\Database\Criteria;use App\Repositories\User;use App\Services\AppService; use App\Services\MessageService; @endphp
<x-layout.page>
    <x-slot:head>
        <x-layout::breadcrumb :sections="[['','Home']]"></x-layout::breadcrumb>
    </x-slot:head>
    <x-slot:main>
        @php
            $idUser = AppService::getCurrentIdUser();
            $user = User::byId($idUser);
            $isManager = User::isManager($user);
            $messages = MessageService::getMessagesToUser($idUser);
            $rows = Criteria::table("view_usertask_docs as utd")
                ->join("view_project_docs as pd","pd.idCorpus","=","utd.idCorpus")
                ->join("project_manager as pm","pd.idProject","=","pm.idProject")
                ->join("user as u","u.idUser","=","pm.idUser")
                ->select("utd.taskName","utd.documentName","utd.corpusName")
                ->selectRaw("GROUP_CONCAT(DISTINCT u.email SEPARATOR ',') as email")
                ->groupByRaw("utd.taskName,utd.documentName,utd.corpusName")
                ->where("utd.idUser",$idUser)
                ->where("pd.idProject","<>", 1)
                ->where("pd.idLanguage",AppService::getCurrentIdLanguage())
                ->where("utd.idLanguage",AppService::getCurrentIdLanguage())
                ->all();
            $projects = collect($rows)->groupBy('projectName')->toArray();
        @endphp
        @include("App.messages")
        @if(!$isManager)
            <div class="relative h-full overflow-auto">
                <div class="absolute top-0 left-0 bottom-0 right-0">
                    <div class="ui container">
                        <div class="ui card w-full">
                            <div class="flex-grow-0 content h-4rem bg-gray-100">
                                <h2 class="ui header">My projects</h2>
                            </div>
                            <div class="flex-grow-1 content bg-white">
                                <table class="ui striped small compact table">
                                    <tbody>
                                    @foreach($projects as $project => $data)
                                        <tr>
                                            <td>
                                                {{$project}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="ui card w-full">
                            <div class="flex-grow-0 content h-4rem bg-gray-100">
                                <h2 class="ui header">My tasks</h2>
                            </div>
                            <div class="flex-grow-1 content bg-white">
                                <table class="ui striped small compact table">
                                    <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Document</th>
                                        <th>Manager</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($rows as $project)
                                        <tr>
                                            <td>
                                                {{$project->taskName}}
                                            </td>
                                            <td>
                                                {{$project->documentName}}
                                            </td>
                                            <td>
                                                {{$project->email}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @php
                $projectsForManager =  Criteria::table("project_manager as pm")
                    ->join("project as p","p.idProject","=","pm.idProject")
                    ->join("user as u","u.idUser","=","pm.idUser")
                    ->select("p.name as projectName","p.idProject")
                    ->where("u.idUser",$idUser)
                    ->where("p.idProject","<>", 1)
                    ->all();
            @endphp
            <div class="relative h-full overflow-auto">
                <div class="absolute top-0 left-0 bottom-0 right-0">
                    <div class="ui container">
                        <div class="ui card w-full">
                            <div class="flex-grow-0 content h-4rem bg-gray-100">
                                <h2 class="ui header">Managed projects</h2>
                            </div>
                            <div class="flex-grow-1 content bg-white">
                                <table class="ui striped small compact table">
                                    <thead>
                                    <tr>
                                        <th>Project</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($projectsForManager as $project)
                                        <tr>
                                            <td>
                                                {{$project->projectName}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
{{--                        <div class="ui card w-full">--}}
{{--                            <div class="flex-grow-0 content h-4rem bg-gray-100">--}}
{{--                                <h2 class="ui header">Tasks attributed</h2>--}}
{{--                            </div>--}}
{{--                            <div class="flex-grow-1 content bg-white">--}}
{{--                                <table class="ui striped small compact table">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th>Document</th>--}}
{{--                                        <th>Annotator</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    @foreach($projectsForManager as $project)--}}
{{--                                        <tr>--}}
{{--                                            <td colspan="2">--}}
{{--                                                {{$project->projectName}}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        @php--}}
{{--                                            $tasksAttributed = Criteria::table("view_usertask_docs as utd")--}}
{{--                                                ->join("view_project_docs as pd","pd.idCorpus","=","utd.idCorpus")--}}
{{--                                                ->join("user as u","u.idUser","=","utd.idUser")--}}
{{--                                                ->select("utd.taskName","utd.documentName","u.email")--}}
{{--                                                ->where("pd.idProject","=", $project->idProject)--}}
{{--                                                ->where("pd.idLanguage",AppService::getCurrentIdLanguage())--}}
{{--                                                ->all();--}}
{{--                                        @endphp--}}
{{--                                        @foreach($tasksAttributed as $task)--}}
{{--                                            <tr>--}}
{{--                                                <td>--}}
{{--                                                    {{$task->documentName}}--}}
{{--                                                </td>--}}
{{--                                                <td>--}}
{{--                                                    {{$task->email}}--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                    @endforeach--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                    </div>
                </div>
            </div>
        @endif
    </x-slot:main>
</x-layout.page>

