<x-layout.browser>
    <x-slot:title>
        Frames
    </x-slot:title>
    <x-slot:actions>
        <x-button label="New" color="secondary" href="/frame/new"></x-button>
    </x-slot:actions>
    <x-slot:main>
        <x-slot:search>
            <x-form-search
                id="frameSearch"
                hx-post="/frame/grid"
                hx-target="#gridArea"
            >
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <x-search-field
                    id="frame"
                    value="{{$search->frame}}"
                    placeholder="Search Frame"
                ></x-search-field>
                <x-search-field
                    id="lu"
                    value="{{$search->lu}}"
                    placeholder="Search LU"
                ></x-search-field>
                <x-submit
                    label="Search"
                    class="mb-2"
                ></x-submit>
                <x-button
                    label="Domains"
                    hx-post="/frame/grid"
                    hx-vals="js:{frame:'',lu:'',byGroup:'domain'}"
                    hx-target="#gridArea"
                    hx-on:htmx:before-request="$('#frame').val('');$('#lu').val('');"
                ></x-button>
                <x-button
                    label="Types"
                    hx-post="/frame/grid"
                    hx-vals="js:{frame:'',lu:'',byGroup:'type'}"
                    hx-target="#gridArea"
                    hx-on:htmx:before-request="$('#frame').val('');$('#lu').val('');"
                ></x-button>
            </x-form-search>
        </x-slot:search>
        <x-slot:grid>
            <div
                id="gridArea"
                class="h-full"
                hx-trigger="load"
                hx-post="/frame/grid"
            >
            </div>
        </x-slot:grid>
    </x-slot:main>
</x-layout.browser>
