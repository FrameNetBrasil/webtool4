<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['',$page]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container browse-page">
                    <div class="app-search">
                        <div class="search-section"
                             x-data="searchFormComponent()"
                             @htmx:before-request="onSearchStart"
                             @htmx:after-request="onSearchComplete"
                             @htmx:after-swap="onResultsUpdated"
                        >
                            <div class="search-input-group">
                                <form class="ui form"
                                      hx-post="/annotation/browse/searchDocument"
                                      hx-target="#gridArea"
                                      hx-swap="innerHTML"
                                      hx-trigger="submit, input delay:500ms"
                                >
                                    <input type="hidden" name="taskGroupName" value="{{$taskGroupName}}">
                                    <div class="three fields">
                                        <div class="field">
                                            <div class="ui left icon input w-full">
                                                <i class="search icon"></i>
                                                <input
                                                    type="search"
                                                    name="corpus"
                                                    placeholder="Search Corpus"
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
                                                    autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="gridArea">
                            @fragment("search")
                                <div class="results-container view-cards">
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
                                                        window.open(`{{$url}}/${event.detail.id}`, '_blank');
                                                    }
                                                }"
                                            >
                                                <x-ui::tree
                                                    :title="$title ?? ''"
                                                    url="/annotation/browse/treeDocument"
                                                    :data="$data"
                                                ></x-ui::tree>
                                            </div>
                                        </div>
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
