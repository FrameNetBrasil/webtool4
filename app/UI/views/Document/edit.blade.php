<x-layout.object :center="false">
    <x-slot:name>
        <span class="color_corpus">{{$document->name}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$document->idDocument}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Document '{{$document->name}}'.`, '/document/{{$document->idDocument}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
    </x-slot:description>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/document/{{$document->idDocument}}/formCorpus"
                hx-target="#objectMainArea"
            >
                Corpus
            </a>
            <a
                class="item"
                hx-get="/document/{{$document->idDocument}}/entries"
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
