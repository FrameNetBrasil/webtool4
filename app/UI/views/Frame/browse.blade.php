<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['','Frames']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content h-full">
                <div class="ui container h-full">
                    <div class="app-search">
                        <!-- Search Section -->
                        <div class="search-section"
                             x-data="browseSearchComponent()"
                             @htmx:before-request="onSearchStart"
                             @htmx:after-request="onSearchComplete"
                             @htmx:after-swap="onResultsUpdated"
                        >
                            <div class="search-input-group">
                                <form class="ui form"
                                      hx-post="/frame/tree"
                                      hx-target=".search-results-tree"
                                      hx-swap="innerHTML"
                                      hx-trigger="submit, input delay:500ms from:input[name='frame']">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <div class="two fields">
                                        <div class="field">
                                            <div class="ui left icon input w-full">
                                                <i class="search icon"></i>
                                                <input
                                                    type="search"
                                                    name="frame"
                                                    placeholder="Search Frame"
                                                    autocomplete="off"
                                                >
                                            </div>
                                        </div>
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
                                <div class="results-container view-cards"
                                >
                                    <div class="results-wrapper">

                                        @if(count($data) > 0)
                                            <div class="tree-view" x-transition>
                                                <div
                                                    class="search-results-tree"
                                                    x-data
                                                    @tree-item-selected.document="(event) => {
                                                    let type =  event.detail.type;
                                                    let idNode = type + '_' + event.detail.id;
                                                    if (type === 'frame') {
                                                        window.open(`/frame/${event.detail.id}`, '_blank');
                                                    }
                                                    if (type === 'lu') {
                                                        window.open(`/lu/${event.detail.id}/edit`, '_blank');
                                                    }
                                                }"
                                                >
                                                    @include("Frame.partials.tree")
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endfragment
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
