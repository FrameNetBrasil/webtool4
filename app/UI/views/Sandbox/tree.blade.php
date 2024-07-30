<x-layout.browser>
    <x-slot:title>
        Frames
    </x-slot:title>
    <x-slot:actions>
        <x-button label="New" color="secondary" href="/frame/new"></x-button>
    </x-slot:actions>
    <x-slot:main>
        <section class="search">
            <div class="flex flex-row">
                <x-form-search
                    id="frameSearch"
                    hx-post="/sandbox/tree/grid"
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
                    ></x-submit>
                </x-form-search>
                <x-button
                    class="mb-2"
                    label="Domains"
                    hx-post="/sandbox/tree/grid"
                    hx-vals="js:{frame:'',lu:'',byGroup:'domain'}"
                    hx-target="#gridArea"
                ></x-button>
                <x-button
                    class="mb-2 ml-2"
                    label="Types"
                    hx-post="/sandbox/tree/grid"
                    hx-vals="js:{frame:'',lu:'',byGroup:'type'}"
                    hx-target="#gridArea"
                ></x-button>
            </div>
        </section>
        <div
            class="flex-grow-1"
            id="gridArea"
            class="h-full"
            hx-trigger="load"
            hx-post="/sandbox/tree/grid"
        >
        </div>
        <style>

        </style>
    </x-slot:main>
</x-layout.browser>
