<x-layout.browser>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','LU Candidate']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full d-flex flex-col">
                <div class="text-right mb-2 flex-none">
                    <a href="/luCandidate/new"
                       rel="noopener noreferrer"
                       class="ui button secondary">
                        New LU Candidate
                    </a>
                </div>
                <div class="app-search flex-1">
                    <!-- Search Section -->
                    <div class="search-section"
                         x-data="browseSearchComponent()"
                         @htmx:before-request="onSearchStart"
                         @htmx:after-request="onSearchComplete"
                         @htmx:after-swap="onResultsUpdated"
                    >
                        <div class="search-input-group">
                            <form class="ui form"
                                  hx-post="/lexicon3/tree"
                                  hx-target=".search-results-tree"
                                  hx-swap="innerHTML"
                                  hx-trigger="submit, input delay:500ms from:input[name='lu']">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="fields">
                                    <div class="field">
                                        <div class="ui left icon input w-full">
                                            <i class="search icon"></i>
                                            <input
                                                type="search"
                                                name="lu"
                                                placeholder="Search LU"
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
                            <div class="results-container view-cards">
                                <div class="results-wrapper">
                                        <div
                                            class="search-results-tree h-full"
                                        >
                                            @include("LUCandidate.partials.datagrid")
                                        </div>
                                    </div>
                            </div>
                        @endfragment
                    </div>
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout.browser>
