<x-layout::index>
    <div class="app-layout no-tools">
        @include('layouts.header')
        @include("layouts.sidebar")
        <main class="app-main">
            <div class="page-header">
                <div class="page-header-content">
                    <div class="page-area">Report</div>
                    <div class="page-title">Frame</div>
                </div>
            </div>
            <div class="page-content">
                <div class="content-container">
                    <div class="app-search">
                        <!-- Search Section -->
                        <div class="search-section"
                             x-data="searchForm()"
                             @htmx:before-request="onSearchStart"
                             @htmx:after-request="onSearchComplete"
                             @htmx:after-swap="onResultsUpdated">
                            <div class="search-input-group">
                                <form class="ui form"
                                      hx-post="/report/frame/grid"
                                      hx-target="#gridArea"
                                      hx-swap="innerHTML"
                                      hx-trigger="submit, input delay:500ms from:input[name='frame']">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <div class="field">
                                        <div class="ui left icon input w-full">
                                            <i class="search icon"></i>
                                            <input
                                                type="search"
                                                name="frame"
                                                placeholder="Search Frame"
                                                x-model="searchQuery"
                                                autocomplete="off"
                                            >
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="gridArea" class="h-full">
                            @fragment("search")
                                <div class="results-container"
                                     x-data="searchGrid()"
                                     x-init="init()"
                                     :class="`results-container view-${currentView}`"
                                     @keydown.window="handleKeyboard($event)">

                                    <div class="results-header">
                                        <div class="results-info">
                                            <div class="results-count" id="resultsCount">{!! count($frames) !!}
                                                results
                                            </div>
                                            <div class="search-query-display" id="queryDisplay"></div>
                                        </div>
                                        <div class="results-actions">
                                            <div class="view-toggle">
                                                <button class="view-btn"
                                                        :class="{ 'active': currentView === 'cards' }"
                                                        @click="setView('cards')">
                                                    <i class="th large icon"></i>
                                                    Cards
                                                </button>
                                                <button class="view-btn"
                                                        :class="{ 'active': currentView === 'table' }"
                                                        @click="setView('table')">
                                                    <i class="table icon"></i>
                                                    Table
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Empty State -->
                                    @if(count($frames) == 0)
                                        <div class="empty-state" id="emptyState">
                                            <i class="search icon empty-icon"></i>
                                            <h3 class="empty-title">Ready to search</h3>
                                            <p class="empty-description">
                                                Enter your search term above to find frames.
                                            </p>
                                        </div>
                                    @endif

                                    @if(count($frames) > 0)
                                        <!-- Card View -->
                                        <div class="card-view" x-show="currentView === 'cards'" x-transition>
                                            <div class="search-results-grid">
                                                @foreach($frames as $frame)
                                                    <div class="ui card fluid result-card"
                                                         data-id="{{$frame->idFrame}}"
                                                         @click="navigateToFrame({{$frame->idFrame}})"
                                                         tabindex="0"
                                                         @keydown.enter="navigateToFrame({{$frame->idFrame}})"
                                                         role="button">
                                                        <div class="content">
                                                            <div class="header">
                                                                <x-ui::element.frame
                                                                    name="{{$frame->name}}"></x-ui::element.frame>
                                                            </div>
                                                            <div class="description">
                                                                {{$frame->description}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Table View -->
                                        <div class="table-view" x-show="currentView === 'table'" x-transition>
                                            <div class="results-table-container">
                                                <table class="ui table results-table">
                                                    <thead>
                                                    <tr>
                                                        <th class="four wide">Name</th>
                                                        <th class="twelve wide">Description</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($frames as $frame)
                                                        <tr data-id="{{$frame->idFrame}}"
                                                            @click="navigateToFrame({{$frame->idFrame}})"
                                                            tabindex="0"
                                                            @keydown.enter="navigateToFrame({{$frame->idFrame}})"
                                                            role="button">
                                                            <td>
                                                                <div class="table-title">
                                                                    <x-ui::element.frame
                                                                        name="{{$frame->name}}"></x-ui::element.frame>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="table-content">
                                                                    {{$frame->description}}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endfragment
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Search form component for handling input and HTMX events
        function searchForm() {
            return {
                searchQuery: "",
                currentToast: null,

                onSearchStart(event) {
                    // Store the current query for later use
                    window.currentSearchQuery = this.searchQuery;

                    // Show Fomantic UI toast
                    this.showSearchToast();
                },

                onSearchComplete(event) {
                    console.log("Search completed");

                    // Hide the search toast
                    this.hideSearchToast();
                },

                onResultsUpdated(event) {
                    // Re-initialize the grid component on the new content
                    const gridArea = document.getElementById("gridArea");
                    if (gridArea) {
                        Alpine.initTree(gridArea);
                    }

                    // Update query display in the new content
                    this.updateQueryDisplay();
                },

                showSearchToast() {
                    // Close any existing toast first
                    this.hideSearchToast();

                    // Create and show the search toast
                    this.currentToast = $("body").toast({
                        message: "Searching frames...",
                        class: "info",
                        showIcon: "search",
                        displayTime: 0, // Don't auto-hide
                        position: "top center",
                        showProgress: false,
                        closeIcon: false,
                        silent: true
                    });
                },

                hideSearchToast() {
                    // Remove the search toast
                    if (this.currentToast) {
                        $(".ui.toast").toast("close");
                        this.currentToast = null;
                    }
                },

                updateQueryDisplay() {
                    const queryDisplay = document.getElementById("queryDisplay");
                    const query = window.currentSearchQuery || this.searchQuery;
                    if (queryDisplay && query && query.trim() !== "") {
                        queryDisplay.textContent = `Results for: "${query}"`;
                    } else if (queryDisplay) {
                        queryDisplay.textContent = "";
                    }
                }
            };
        }

        // Grid component (same as before, but with some HTMX integration)
        function searchGrid() {
            return {
                currentView: "cards",

                init() {
                    this.loadSearchState();
                    this.updateViewForScreen();

                    // Listen for window resize
                    window.addEventListener("resize", () => {
                        this.updateViewForScreen();
                    });
                },

                setView(view) {
                    this.currentView = view;
                    this.saveSearchState();
                },

                updateViewForScreen() {
                    const isMobile = window.innerWidth <= 768;
                    if (isMobile && this.currentView === "cards") {
                        this.currentView = "table";
                        this.saveSearchState();
                    }
                },

                handleKeyboard(event) {
                    if (event.ctrlKey || event.metaKey) {
                        switch (event.key) {
                            case "1":
                                event.preventDefault();
                                this.setView("cards");
                                break;
                            case "2":
                                event.preventDefault();
                                this.setView("table");
                                break;
                        }
                    }
                },

                navigateToFrame(frameId) {
                    window.location.href = `/report/frame/${frameId}`;
                },

                saveSearchState() {
                    const state = {
                        view: this.currentView,
                        timestamp: Date.now()
                    };
                    localStorage.setItem("search-state", JSON.stringify(state));
                },

                loadSearchState() {
                    const saved = localStorage.getItem("search-state");
                    if (saved) {
                        try {
                            const state = JSON.parse(saved);
                            if (state.view) {
                                this.currentView = state.view;
                            }
                        } catch (e) {
                            console.warn("Could not load search state:", e);
                        }
                    }
                }
            };
        }
    </script>
</x-layout::index>
