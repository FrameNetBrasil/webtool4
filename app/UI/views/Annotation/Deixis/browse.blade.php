<x-layout.browser>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Deixis Annotation']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
                <div class="app-search">
                    <!-- Search Section -->
                    <div class="search-section"
                         x-data="searchFormComponent()"
                    >
                        <div class="search-input-group">
                            <form class="ui form"
                                  @submit="onSearchStart"
                                  @input.debounce.500ms="performSearch"
                                  >
                                <div class="three fields">
                                    <div class="field">
                                        <div class="ui left icon input w-full">
                                            <i class="search icon"></i>
                                            <input
                                                type="search"
                                                name="corpus"
                                                placeholder="Search Corpus"
                                                autocomplete="off"
                                                x-model="searchParams.corpus"
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
                                                autocomplete="off"
                                                x-model="searchParams.document"
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
                            <div class="results-container view-cards"
                            >
                                <div class="results-wrapper">
                                        <div class="tree-view" x-transition>
                                            <div
                                                class="search-results-tree"
                                                x-data
                                                @tree-item-selected.document="(event) => {
                                                    let type =  event.detail.type;
                                                    let idNode = type + '_' + event.detail.id;
                                                    if (type === 'corpus') {
                                                        event.detail.tree.toggleNodeState(idNode);
                                                    } else if (type === 'document') {
                                                        window.open(`/annotation/deixis/${event.detail.id}`, '_blank');
                                                    }
                                                }"
                                            >
                                                @if(count($data) > 0)
                                                    <x-ui::tree
                                                        :title="$title ?? ''"
                                                        url="/annotation/deixis/data"
                                                        :data="$data"
                                                    ></x-ui::tree>
                                                @else
                                                    <div class="empty-state" id="emptyState">
                                                        <i class="search icon empty-icon"></i>
                                                        <h3 class="empty-title">No results found.</h3>
                                                        <p class="empty-description">
                                                            Enter your search term above to find frames.
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout.browser>
