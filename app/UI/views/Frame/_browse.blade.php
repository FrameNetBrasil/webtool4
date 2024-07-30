<x-layout.browser>
    <x-slot:title>
        Frames
    </x-slot:title>
    <x-slot:actions>
        <x-button label="New" color="secondary" href="/frame/new"></x-button>
    </x-slot:actions>
    <x-slot:search>
        <x-form-search
            id="frameSearch"
            hx-post="/frame/grid"
            hx-target="#gridPanel"
        >
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <x-combobox.framal-domain
                id="idFramalDomain"
                placeholder="Domain"
                value="{{$search->idFramalDomain}}"
            ></x-combobox.framal-domain>
            <x-search-field
                id="frame"
                value="{{$search->frame}}"
                placeholder="Search Frame"
            ></x-search-field>
            <x-search-field
                id="fe"
                value="{{$search->fe}}"
                placeholder="Search FE"
            ></x-search-field>
            <x-search-field
                id="lu"
                value="{{$search->lu}}"
                placeholder="Search LU"
            ></x-search-field>
            <x-combobox.framal-type
                id="idFramalType"
                placeholder="Type"
                value="{{$search->idFramalType}}"
            ></x-combobox.framal-type>
            <x-submit
                label="Search"
            ></x-submit>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div id="gridPanel">
            @include('Frame.gridEdit')
        </div>
    </x-slot:grid>
</x-layout.browser>
