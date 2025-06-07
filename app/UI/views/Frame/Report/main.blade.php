<x-layout::page>
    <x-slot:breadcrumb>
        <x-breadcrumb :sections="[['','Frame Report']]"></x-breadcrumb>
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
                            <i class="search icon search-icon"></i>
                            <input
                                    type="text"
                                    class="main-search-input"
                                    placeholder="Search for frames, lexical units, constructions..."
                                    id="searchInput"
                                    autocomplete="off"
                            >
                        </div>

                        {{--                    <div class="search-actions">--}}
                        {{--                        <button class="ui primary button" id="searchBtn">--}}
                        {{--                            <i class="search icon"></i>--}}
                        {{--                            Search--}}
                        {{--                        </button>--}}
                        {{--                        <button class="ui button" id="advancedBtn">--}}
                        {{--                            <i class="sliders horizontal icon"></i>--}}
                        {{--                            Advanced Search--}}
                        {{--                        </button>--}}
                        {{--                        <div class="ui dropdown button">--}}
                        {{--                            <i class="filter icon"></i>--}}
                        {{--                            Filter by Type--}}
                        {{--                            <i class="dropdown icon"></i>--}}
                        {{--                            <div class="menu">--}}
                        {{--                                <div class="item" data-value="all">All Types</div>--}}
                        {{--                                <div class="item" data-value="frame">Frames</div>--}}
                        {{--                                <div class="item" data-value="lu">Lexical Units</div>--}}
                        {{--                                <div class="item" data-value="construction">Constructions</div>--}}
                        {{--                                <div class="item" data-value="semantictype">Semantic Types</div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                    </div>--}}

                        {{--                    <div class="search-suggestions">--}}
                        {{--                        <div class="suggestions-label">Popular searches:</div>--}}
                        {{--                        <div class="suggestion-tags">--}}
                        {{--                            <a href="#" class="suggestion-tag" data-query="motion frames">motion frames</a>--}}
                        {{--                            <a href="#" class="suggestion-tag" data-query="communication">communication</a>--}}
                        {{--                            <a href="#" class="suggestion-tag" data-query="emotional response">emotional response</a>--}}
                        {{--                            <a href="#" class="suggestion-tag" data-query="physical contact">physical contact</a>--}}
                        {{--                            <a href="#" class="suggestion-tag" data-query="cognitive verbs">cognitive verbs</a>--}}
                        {{--                        </div>--}}
                        {{--                    </div>--}}
                    </div>

                    <!-- Results Container -->
                    <div class="search-results" id="resultsContainer">
                        <div class="results-header">
                            <div class="results-info">
                                <div class="results-count" id="resultsCount">0 results</div>
                                <div class="search-query-display" id="queryDisplay"></div>
                            </div>
                            <div class="results-actions">
                                <div class="view-toggle">
                                    <button class="view-btn active" data-view="cards">
                                        <i class="th large icon"></i>
                                        Cards
                                    </button>
                                    <button class="view-btn" data-view="table">
                                        <i class="table icon"></i>
                                        Table
                                    </button>
                                    <button class="view-btn" data-view="list">
                                        <i class="list icon"></i>
                                        List
                                    </button>
                                </div>
                                <button class="ui mini button">
                                    <i class="download icon"></i>
                                    Export
                                </button>
                            </div>
                        </div>

                        <div class="results-content">
                            <!-- Empty State -->
                            <div class="empty-state" id="emptyState">
                                <i class="search icon empty-icon"></i>
                                <h3 class="empty-title">Ready to search</h3>
                                <p class="empty-description">
                                    Enter your search terms above to find frames, lexical units, constructions, and
                                    other linguistic data.
                                </p>
                            </div>

                            <!-- Loading State -->
                            <div class="loading-state" id="loadingState" style="display: none;">
                                <div class="loading-spinner"></div>
                                <h3 class="loading-title">Searching...</h3>
                                <p class="loading-description">
                                    Searching through linguistic databases...
                                </p>
                            </div>

                            <!-- Demo Results -->
                            <div id="searchResults" style="display: none;">
                                <div class="search-results-demo">
                                    <i class="database icon demo-icon"></i>
                                    <h3 class="demo-title">Search Results</h3>
                                    <p>Search results would appear here using the card grid or table patterns from your
                                        flat search design.</p>
                                    <p class="demo-description">
                                        This container follows the same layout structure as your Reports page and is
                                        ready to display frames, lexical units, constructions, and other linguistic data
                                        in the formats you've already established.
                                    </p>
                                </div>
                            </div>
                        </div>
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
