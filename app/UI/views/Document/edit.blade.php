<x-layout.object>
    <x-slot:name>
        <span>{{$document->name}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$document->idDocument}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            x-data \n  @click.prevent="messenger.confirmDelete(`Removing Document '{{$document->name}}'.`, '/document/{{$document->idDocument}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
        {{$document->description}}
    </x-slot:description>
    <x-slot:main>
        @include("Document.menu")
    </x-slot:main>
</x-layout.object>
