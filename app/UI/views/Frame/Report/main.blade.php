<x-layout.report>
    <x-slot:title>
        Frame Report
    </x-slot:title>
    <x-slot:search>
        <x-form-search
            id="frameSearch"
            hx-post="/report/frame/grid"
            hx-target="#gridArea"
        >
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <x-search-field
                id="frame"
                value="{{$search->frame}}"
                placeholder="Search Frame"
                class="w-12rem"
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
            hx-post="/report/frame/grid"
        >
        </div>

    </x-slot:grid>
    <x-slot:pane>
        <div
            id="reportArea"
            class="h-full overflow-y-auto"
            @if(isset($idFrame))
            hx-trigger="load"
            hx-get="/report/frame/content/{{$idFrame}}"
            @endif
        >
        </div>
    </x-slot:pane>
</x-layout.report>
