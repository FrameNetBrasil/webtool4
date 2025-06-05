@php use App\Database\Criteria;use App\Repositories\User;use App\Services\AppService; use App\Services\MessageService; @endphp
<x-layout::page>
    <x-slot:breadcrumb>
        <x-breadcrumb :sections="[['','Home']]"></x-breadcrumb>
    </x-slot:breadcrumb>
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
                <div class="wt-card w-full">
                    <div class="header">
                        <h1>My projects</h1>
                    </div>
                    <div class="body">
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
                <div class="wt-card">
                    <div class="header">
                        <h1>My tasks</h1>
                    </div>
                    <div class="body">
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
                <div class="wt-card">
                    <div class="header">
                        <h1>Managed projects</h1>
                    </div>
                    <div class="body">
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

        @endif
    </x-slot:main>
</x-layout::page>

