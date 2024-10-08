<x-layout.browser>
    <x-slot:title>
        Static Event Annotation
    </x-slot:title>
    <x-slot:actions>

    </x-slot:actions>
    <x-slot:main>
        <x-slot:search>
            <x-form-search
                id="corpusSearch"
                hx-post="/annotation/staticEvent/grid"
                hx-target="#gridArea"
            >
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <x-search-field
                    id="corpus"
                    value="{{$search->corpus}}"
                    placeholder="Search Corpus"
                ></x-search-field>
                <x-search-field
                    id="document"
                    value="{{$search->document}}"
                    placeholder="Search Document"
                ></x-search-field>
                <x-search-field
                    id="idSentence"
                    value="{{$search->idSentence}}"
                    placeholder="Search ID Sentence"
                ></x-search-field>
                <x-submit
                    label="Search"
                    class="mb-2"
                ></x-submit>
            </x-form-search>
        </x-slot:search>
        <x-slot:grid>
            <div
                id="gridArea"
                class="h-full"
                hx-trigger="load"
                hx-post="/annotation/staticEvent/grid"
            >
            </div>
        </x-slot:grid>
    </x-slot:main>
</x-layout.browser>
