<x-layout::page>
    <x-slot:breadcrumb>
        <x-breadcrumb :sections="[]"></x-breadcrumb>
    </x-slot:breadcrumb>
    <x-slot:main>
        <x-ui::page-header
            title="Frame Report"
            subtitle="Select the frame to show the report.">
        </x-ui::page-header>
        <div class="page-content">
            <div class="content-container">
                <div class="app-search">
                    <!-- Search Section -->
                    <div class="search-section">
                        <div class="search-input-group">
                            <x-ui::form-search
                                hx-post="/report/frame/grid"
                                hx-target="#gridArea"
                                id="frame"
                                placeholder="Search Frame"
                            ></x-ui::form-search>
                        </div>
                    </div>
                    <div
                        id="gridArea"
                        class="h-full"
                    >
                        @include("Frame.Report.grid")
                    </div>
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout::page>


{{--<x-layout.report>--}}
{{--    <x-slot:head>--}}
{{--        <x-breadcrumb :sections="[['/','Home'],['','Frame Report']]"></x-breadcrumb>--}}
{{--    </x-slot:head>--}}
{{--    <x-slot:search>--}}
{{--        <x-form-search--}}
{{--            id="frameSearch"--}}
{{--            hx-post="/report/frame/grid"--}}
{{--            hx-target="#gridArea"--}}
{{--        >--}}
{{--            <input type="hidden" name="_token" value="{{ csrf_token() }}" />--}}
{{--            <x-search-field--}}
{{--                id="frame"--}}
{{--                value="{{$search->frame}}"--}}
{{--                placeholder="Search Frame"--}}
{{--                class="w-full"--}}
{{--            ></x-search-field>--}}
{{--        </x-form-search>--}}
{{--    </x-slot:search>--}}
{{--    <x-slot:grid>--}}
{{--        <div--}}
{{--            id="gridArea"--}}
{{--            class="h-full"--}}
{{--        >--}}
{{--            @include("Frame.Report.grid")--}}
{{--        </div>--}}
{{--    </x-slot:grid>--}}
{{--    <x-slot:pane>--}}
{{--        <div--}}
{{--            id="reportArea"--}}
{{--        >--}}
{{--            @includeWhen(!is_null($idFrame),"Frame.Report.report")--}}
{{--        </div>--}}
{{--    </x-slot:pane>--}}
{{--</x-layout.report>--}}
