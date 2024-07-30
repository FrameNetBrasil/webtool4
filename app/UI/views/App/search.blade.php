<x-layout.browser>
    <x-slot:title>
        Search
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        <x-slot:search>
            <x-form-search
                id="frameSearch"
                action="/app/search"
                method="POST"
            >
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <x-search-field
                    id="frame"
                    value="{{$search->frame}}"
                    placeholder="Search Frame/LU"
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
            >
                <div class="grid h-full">
                    <div id="frameTableContainer" class="col-6">
                        @include("Frame.treeFrame")
                    </div>
                    <div id="feluTableContainer" class="col-6">
                        @include("Frame.treeFELU")
                    </div>
                </div>
            </div>
        </x-slot:grid>
    </x-slot:main>
</x-layout.browser>
