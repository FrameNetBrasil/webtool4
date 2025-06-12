<x-layout::index>
    <div class="app-layout no-tools">
        @include('layouts.header')
        @include("layouts.sidebar")
        <main class="app-main">
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
        </main>
    </div>
</x-layout::index>
