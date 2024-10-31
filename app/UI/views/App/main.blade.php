@php use App\Database\Criteria;use App\Repositories\User;use App\Services\AppService; @endphp
<x-layout.main>
    <x-slot:head>
        <x-breadcrumb :sections="[['','Home']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        @php
            $idUser = AppService::getCurrentIdUser();
            $user = User::byId($idUser);
            $isManager = User::isManager($user);
            $rows = Criteria::table("view_usertask_docs as utd")
                ->join("view_project_docs as pd","pd.idCorpus","=","utd.idCorpus")
                ->select("pd.projectName","utd.taskName","utd.documentName","utd.corpusName")
                ->distinct()
                ->where("utd.idUser",$idUser)
                ->where("pd.idProject","<>", 1)
                ->where("pd.idLanguage",AppService::getCurrentIdLanguage())
                ->where("utd.idLanguage",AppService::getCurrentIdLanguage())
                ->all();
            $projects = collect($rows)->groupBy('projectName')->toArray();
        @endphp
        @if(!$isManager)
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
                            <th>Project</th>
                            <th>Task</th>
                            <th>Document</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $project)
                            <tr>
                                <td>
                                    {{$project->projectName}}
                                </td>
                                <td>
                                    {{$project->taskName}}
                                </td>
                                <td>
                                    {{$project->documentName}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </x-slot:main>
</x-layout.main>

