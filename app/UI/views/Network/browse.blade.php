<x-layout.browser>
    <x-slot:title>
        Network
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        <x-slot:search>
            <x-form-search
                id="frameSearch"
                hx-post="/network/grid"
                hx-target="#gridArea"
            >
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <x-search-field
                    id="frame"
                    value="{{$search->frame}}"
                    placeholder="Search Frame"
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
                hx-post="/network/grid"
            >
            </div>
        </x-slot:grid>
    </x-slot:main>
</x-layout.browser>

