<x-layout.object :center="false">
    <x-slot:name>
        <h1><span class="color_user">{{$group->name}}</span></h1>
    </x-slot:name>
    <x-slot:detail>
        <x-tag label="#{{$group->idGroup}}"></x-tag>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Group '{{$group?->name}}'.`, '/group/{{$group->idGroup}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
        {{$group->description}}
    </x-slot:description>
    <x-slot:nav>
        <x-link-button
            id="menuEdit"
            label="Edit"
            hx-get="/group/{{$group->idGroup}}/formEdit"
            hx-target="#objectMainArea"
        ></x-link-button>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
