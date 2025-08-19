<x-layout.edit>
    <x-slot:head>
        <x-breadcrumb
            :sections="[['/','Home'],['/task','Tasks'],['','Task #' . $task->idTask]]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
                <x-layout.object>
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
                            x-data \n  @click.prevent="messenger.confirmDelete(`Removing Task '{{$task->name}}'.`, '/task/{{$task->idTask}}')"
                        ></x-button>
                    </x-slot:detail>
                    <x-slot:description>
                    </x-slot:description>
                    <x-slot:main>
                        @include("Task.menu")
                    </x-slot:main>
                </x-layout.object>
            </div>
        </div>
    </x-slot:main>
</x-layout.edit>
