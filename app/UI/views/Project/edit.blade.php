<x-layout.object :center="false">
    <x-slot:name>
        <span class="color_user">{{$project->name}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$project->idProject}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Project '{{$project?->name}}'.`, '/project/{{$project->idProject}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
    </x-slot:description>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/project/{{$project->idProject}}/formEdit"
                hx-target="#objectMainArea"
            >
                Edit
            </a>
            <a
                class="item"
                hx-get="/project/{{$project->idProject}}/formDataset"
                hx-target="#objectMainArea"
            >
                Datasets
            </a>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
