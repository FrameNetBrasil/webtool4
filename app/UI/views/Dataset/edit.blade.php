<x-layout.object :center="false">
    <x-slot:name>
        <span class="color_user">{{$dataset->name}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$dataset->idDataset}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Dataset '{{$dataset->name}}'.`, '/dataset/{{$dataset->idDataset}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
    </x-slot:description>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/dataset/{{$dataset->idDataset}}/formEdit"
                hx-target="#objectMainArea"
            >
                Edit
            </a>
            <a
                class="item"
                hx-get="/dataset/{{$dataset->idDataset}}/projects"
                hx-target="#objectMainArea"
            >
                Projects
            </a>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
