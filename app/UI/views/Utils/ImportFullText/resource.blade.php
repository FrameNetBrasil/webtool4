<x-layout.resource>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Import FullText']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:title>
        Import FullText
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:search>
        <x-form-search>
            <div class="field">
                <x-search-field
                    id="corpus"
                    placeholder="Search Corpus"
                    hx-post="/utils/importFullText/grid/search"
                    hx-trigger="input changed delay:500ms, search"
                    hx-target="#corpusTreeWrapper"
                    hx-swap="innerHTML"
                ></x-search-field>
            </div>
            <div class="field">
                <x-search-field
                    id="document"
                    placeholder="Search Document"
                    hx-post="/utils/importFullText/grid/search"
                    hx-trigger="input changed delay:500ms, search"
                    hx-target="#corpusTreeWrapper"
                    hx-swap="innerHTML"
                ></x-search-field>
            </div>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/utils/importFullText/grid"
        ></div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
