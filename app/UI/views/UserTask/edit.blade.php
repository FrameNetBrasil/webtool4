<x-layout.edit>
    <x-slot:head>
        <x-breadcrumb
            :sections="[['/','Home'],['/task','Tasks'],['','UserTask #' . $usertask->idUserTask]]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
                <x-layout.object>
                    <x-slot:name>
                        UserTask #{{$usertask->idUserTask}}
                    </x-slot:name>
                    <x-slot:detail>
                        <x-button
                            label="Delete"
                            color="danger"
                            onclick="manager.confirmDelete(`Removing UserTask '{{$usertask->idUserTask}}'.`, '/usertask/{{$usertask->idUserTask}}')"
                        ></x-button>
                    </x-slot:detail>
                    <x-slot:description>
                        <span>User:{{$usertask->userName}} / Task: {{$usertask->taskName}}</span>
                    </x-slot:description>
                    <x-slot:main>
                        @include("UserTask.menu")
                    </x-slot:main>
                </x-layout.object>
            </div>
        </div>
    </x-slot:main>
</x-layout.edit>
