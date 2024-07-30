<x-layout.object :center="false">
    <x-slot:name>
        <h1><span class="color_domain">{{$domain->name}}</span></h1>
    </x-slot:name>
    <x-slot:detail>
        <x-tag label="#{{$domain->idDomain}}"></x-tag>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Domain '{{$domain?->name}}'.`, '/domain/{{$domain->idDomain}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
        {{$domain->description}}
    </x-slot:description>
    <x-slot:nav>
        <x-link-button
            id="menuEntries"
            label="Translations"
            hx-get="/domain/{{$domain->idDomain}}/entries"
            hx-target="#objectMainArea"
        ></x-link-button>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
