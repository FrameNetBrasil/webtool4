<x-layout::index>
    <div class="app-layout no-tools">
        @include('layouts.header')
        @include("layouts.sidebar")
        <main class="app-main">
            <x-ui::breadcrumb :sections="[['/','Home'],['','FE Annotation']]"></x-ui::breadcrumb>
            <div class="page-content">
                <div class="content-container">
                    <div class="app-search">
                        <!-- Search Section -->
                        <div class="search-section"
                             x-data="searchForm()"
                             @htmx:before-request="onSearchStart"
                             @htmx:after-request="onSearchComplete"
                             @htmx:after-swap="onResultsUpdated"
                        >
                            <div class="search-input-group">
                                <form class="ui form"
                                      hx-post="/annotation/fe/tree"
                                      hx-target=".search-results-tree"
                                      hx-swap="innerHTML"
                                      hx-trigger="submit, input delay:500ms from:input[name='frame']">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <div class="three fields">
                                        <div class="field">
                                            <div class="ui left icon input w-full">
                                                <i class="search icon"></i>
                                                <input
                                                    type="search"
                                                    name="corpus"
                                                    placeholder="Search Corpus"
{{--                                                    x-model="searchQuery"--}}
                                                    autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui left icon input w-full">
                                                <i class="search icon"></i>
                                                <input
                                                    type="search"
                                                    name="document"
                                                    placeholder="Search Document"
{{--                                                    x-model="searchQuery"--}}
                                                    autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui left icon input w-full">
                                                <i class="search icon"></i>
                                                <input
                                                    type="search"
                                                    name="idDocumentSentence"
                                                    placeholder="Search #idSentence"
{{--                                                    x-model="searchQuery"--}}
                                                    autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                        <button type="submit" class="ui medium primary button">
                                            Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="gridArea" class="h-full">
                            @fragment("search")
                                <div class="results-container"
                                     class="results-container view-cards"
                                >

{{--                                    <div class="results-header">--}}
{{--                                        <div class="results-info">--}}
{{--                                            <div class="results-count" id="resultsCount">{!! count($corpus) !!}--}}
{{--                                                results--}}
{{--                                            </div>--}}
{{--                                            <div class="search-query-display" id="queryDisplay"></div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <!-- Empty State -->--}}
{{--                                    @if(count($corpus) == 0)--}}
{{--                                        <div class="empty-state" id="emptyState">--}}
{{--                                            <i class="search icon empty-icon"></i>--}}
{{--                                            <h3 class="empty-title">Ready to search</h3>--}}
{{--                                            <p class="empty-description">--}}
{{--                                                Enter your search term above to find frames.--}}
{{--                                            </p>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}

                                    @if(count($corpus) > 0)
                                        <div class="tree-view" x-transition>
                                            <div class="search-results-tree">
                                                <x-ui::tree title="" url="/annotation/fe/tree"
                                                            :data="$corpus"></x-ui::tree>
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
        $(function() {
            document.addEventListener("tree-item-selected", function(event) {
                if ((event.detail.type === "corpus") || (event.detail.type === "document")) {
                    event.detail.tree.toggleNodeState(event.detail.id);
                } else if (event.detail.type === "sentence") {
                    window.open(`/annotation/fe/sentence/${event.detail.id}`, "_blank");
                }
            });
        });
    </script>
</x-layout::index>
