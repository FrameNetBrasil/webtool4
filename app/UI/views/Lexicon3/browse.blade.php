<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['','Lexicon']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container browse-page">
                    <div class="new-section">
                        <a href="/lexicon3/lemma/new"
                           rel="noopener noreferrer"
                           class="ui button secondary">
                            New Lemma
                        </a>
                        <a href="/lexicon3/form/new"
                           rel="noopener noreferrer"
                           class="ui button secondary">
                            New Form
                        </a>
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
                                      hx-post="/lexicon3/search"
                                      hx-target="#treeArea"
                                      hx-swap="innerHTML"
                                      hx-trigger="submit, input delay:500ms"
                                >
                                    <div class="two fields">
                                        <div class="field">
                                            <div class="ui left icon input w-full">
                                                <i class="search icon"></i>
                                                <input
                                                    type="search"
                                                    name="lemma"
                                                    placeholder="Search Lemma"
                                                    autocomplete="off"
                                                >
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui left icon input w-full">
                                                <i class="search icon"></i>
                                                <input
                                                    type="search"
                                                    name="form"
                                                    placeholder="Search Form"
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
                                                    if (type === 'lemma') {
                                                        window.location.assign(`/lexicon3/lemma/${event.detail.id}`);
                                                    }
                                                    if (type === 'form') {
                                                        window.location.assign(`/lexicon3/form/${event.detail.id}`);
                                                    }
                                                }"
                                        >
                                            <div id="treeArea">
                                                @include("Lexicon3.tree")
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
