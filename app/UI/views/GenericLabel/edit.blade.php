<x-layout.object>
    <x-slot:name>
        <span>{{$genericLabel?->name}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$genericLabel->idGenericLabel}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing GenericLabel '{{$genericLabel?->name}}'.`, '/genericLabel/{{$genericLabel->idGenericLabel}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
        {{$genericLabel->definition}}
    </x-slot:description>
    <x-slot:main>
        @include("GenericLabel.menu")
    </x-slot:main>
</x-layout.object>
