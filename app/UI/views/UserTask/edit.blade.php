<x-layout.object :center="false">
    <x-slot:name>
        <span class="color_user">User:{{$usertask->userName}} / Task: {{$usertask->taskName}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$usertask->idUserTask}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing UserTask '{{$usertask->idUserTask}}'.`, '/usertask/{{$usertask->idUserTask}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
    </x-slot:description>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/usertask/{{$usertask->idUserTask}}/documents"
                hx-target="#objectMainArea"
            >
                Documents
            </a>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
