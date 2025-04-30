<x-layout.resource>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Lexicon-3']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:title>
        Lexicon
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New Lemma"
            color="secondary"
            hx-get="/lexicon3/lemma/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
        <x-button
            label="New Form"
            color="secondary"
            hx-get="/lexicon3/form/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:search>
        <x-form-search>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="field">
                <x-search-field
                    id="lemma"
                    value="{{$search->lemma}}"
                    placeholder="Search Lemma"
                    hx-post="/lexicon3/grid"
                    hx-trigger="input changed delay:500ms, search"
                    hx-target="#gridArea"
                    hx-swap="innerHTML"
                ></x-search-field>
            </div>
            <div class="field">
                <x-search-field
                    id="wordform"
                    value="{{$search->wordform}}"
                    placeholder="Search Form"
                    hx-post="/lexicon3/grid/search"
                    hx-trigger="input changed delay:500ms, search"
                    hx-target="#lexiconTreeWrapper"
                    hx-swap="innerHTML"
                ></x-search-field>
            </div>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div id="gridArea" class="h-full">
            <div
                hx-trigger="load"
                hx-target="this"
                hx-swap="outerHTML"
                hx-post="/lexicon3/grid"
            ></div>
        </div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
