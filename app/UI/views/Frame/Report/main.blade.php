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
            <div
                class="w-full"
            >
                <x-search-field
                    id="frame"
                    value="{{$search->frame}}"
                    placeholder="Search Frame"
                    {{--                class="w-13rem"--}}
                ></x-search-field>
            </div>
            {{--            <x-button--}}
            {{--                label="Search"--}}
            {{--                icon="search"--}}
            {{--                class="mb-2"--}}
            {{--            ></x-button>--}}
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>

        <div
            id="gridArea"
            class="h-full"
            {{--            hx-trigger="load"--}}
            {{--            hx-post="/report/frame/grid"--}}
        >
            @include("Frame.Report.grid")
        </div>

    </x-slot:grid>
    <x-slot:pane>
        <div
            id="reportArea"
            class="h-full overflow-y-auto"
            {{--            @if(isset($idFrame))--}}
            {{--            hx-trigger="load"--}}
            {{--            hx-get="/report/frame/content/{{$idFrame}}"--}}
            {{--            hx-get="/report/frame/{{$idFrame}}"--}}
            {{--            @endif--}}
        >
            @includeWhen(!is_null($idFrame),"Frame.Report.report")
        </div>
    </x-slot:pane>
</x-layout.report>
