<x-layout.object>
    <x-slot:name>
        <span>{{$corpus->name}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$corpus->idCorpus}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Corpus '{{$corpus->name}}'.`, '/corpus/{{$corpus->idCorpus}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
        {{$corpus->description}}
    </x-slot:description>
    <x-slot:main>
        @include("Corpus.menu")
    </x-slot:main>
</x-layout.object>
