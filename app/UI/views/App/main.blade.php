@php use App\Database\Criteria;use App\Repositories\User;use App\Services\AppService; use App\Services\MessageService; @endphp
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

<x-layout::index>
    <div class="app-layout no-tools">
        @include('layouts.header')
        @include("layouts.sidebar")
        <main class="app-main">
            @if($isManager)
                <x-ui::page-header
                    title="Projects"
                    subtitle="List of projects you are managing.">
                </x-ui::page-header>
            @else
                <x-ui::page-header
                    title="Activities"
                    subtitle="List of projects/tasks you are associated to.">
                </x-ui::page-header>
            @endif
            <div class="page-content">
                <div class="content-container">
                    @include("App.messages")
                    @if(!$isManager)
                        <div class="wt-card w-full">
                            <h2 class="ui header">Projects</h2>
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
                            <h2 class="ui header">My tasks</h2>
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
                                ->select("p.name as projectName","p.idProject","p.description as projectDescription")
                                ->where("u.idUser",$idUser)
                                ->where("p.idProject","<>", 1)
                                ->all();
                        @endphp
                        <div class="card-grid dense">
                            @foreach($projectsForManager as $project)
                                <div class="ui card data-card" data-entity-id="{{$project->idProject}}">
                                    <div class="content">
                                        <div class="data-card-header">
                                            <div class="data-card-title">
                                                <div class="header">{{$project->projectName}}</div>
                                            </div>
                                        </div>
                                        {{--                                    <div class="description">--}}
                                        {{--                                        {{$project->projectDescription}}--}}
                                        {{--                                    </div>--}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</x-layout::index>

