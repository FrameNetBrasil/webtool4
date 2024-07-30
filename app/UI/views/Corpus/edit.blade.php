<x-layout.object :center="false">
    <x-slot:name>
        <span class="color_corpus">{{$corpus->name}}</span>
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
    </x-slot:description>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/corpus/{{$corpus->idCorpus}}/entries"
                hx-target="#objectMainArea"
            >
                Translations
            </a>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
