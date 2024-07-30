<x-layout.object :center="false">
    <x-slot:name>
        <span class="color_user">{{$task->name}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$task->idTask}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Task '{{$task->name}}'.`, '/task/{{$task->idTask}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
    </x-slot:description>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/task/{{$task->idTask}}/formEdit"
                hx-target="#objectMainArea"
            >
                Edit
            </a>
            <a
                class="item"
                hx-get="/task/{{$task->idTask}}/users"
                hx-target="#objectMainArea"
            >
                Users
            </a>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
