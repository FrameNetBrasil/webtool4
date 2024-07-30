<x-layout.browser>
    <x-slot:title>
        Static Annotation: Frame Mode 2
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:search>
        <x-form-search
            id="corpusFormSearch"
            hx-post="/annotation/grid/staticFrameMode2"
            hx-target="#gridPanel"
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
                id="image"
                value="{{$search->image}}"
                placeholder="Search Image"
            ></x-search-field>
            <x-submit label="Search"></x-submit>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div id="gridPanel">
            @include('Annotation.StaticFrameMode2.grid')
        </div>
    </x-slot:grid>
</x-layout.browser>
