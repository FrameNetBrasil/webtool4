<x-layout.browser>
    <x-slot:head>
        <x-layout::breadcrumb :sections="[['/','Home'],['',$page]]"></x-layout::breadcrumb>
    </x-slot:head>
    <x-slot:title>
        <div class="page-title-section">
            <div class="page-title">
                xxx
            </div>
            <div
                class="page-subtitle">
                subtitle
            </div>
        </div>
        <div class="page-actions">
        </div>
    </x-slot:title>
    <x-slot:main>
        <div class="ui container h-full">
            <div class="ui card h-full w-full p-2">
                <div class="flex-grow-0 content h-4rem">
                    <div class="flex flex align-items-center justify-content-between">
                        <div><h2 class="ui header">{{$page}}</h2></div>
                    </div>
                </div>
                <div class="app-search">
                    <div class="search-section"
                         x-data="searchFormComponent()"
                         @htmx:before-request="onSearchStart"
                         @htmx:after-request="onSearchComplete"
                         @htmx:after-swap="onResultsUpdated"
                    >
                        <div class="search-input-group">
                            <form class="ui form"
                                  hx-post="/annotation/browse/searchSentence"
                                  hx-target="#gridArea"
                                  hx-swap="innerHTML"
                                  hx-trigger="submit, input delay:500ms"
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
                                    <div class="field">
                                        <div class="ui left icon input w-full">
                                            <i class="search icon"></i>
                                            <input
                                                type="search"
                                                name="idDocumentSentence"
                                                placeholder="Search #idSentence"
                                                autocomplete="off"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="gridArea">
                        <div class="results-container view-cards">
                            <div class="results-wrapper">
                                <div class="tree-view" x-transition>
                                    <div
                                        class="search-results-tree"
                                        x-data
                                        @tree-item-selected.document="(event) => {
                                                    let type =  event.detail.type;
                                                    let idNode = type + '_' + event.detail.id;
                                                    if ((type === 'corpus') || (type === 'document')) {
                                                        event.detail.tree.toggleNodeState(idNode);
                                                    } else if (type === 'sentence') {
                                                        window.open(`{{$url}}/${event.detail.id}`, '_blank');
                                                    }
                                                }"
                                    >
                                        <div id="treeArea">
                                            @include("Annotation.treeSentences")
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout.browser>
