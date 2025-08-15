<x-layout.browser>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Lexicon']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full d-flex flex-col">
                <div class="text-right mb-2 flex-none">
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
                                  hx-trigger="submit, input delay:500ms from:input[name='lemma'], input delay:500ms from:input[name='form']">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
                                    <div class="tree-view" x-transition>
                                        <div
                                            class="search-results-tree"
                                            x-data
                                            @tree-item-selected.document="(event) => {
                                                    let type =  event.detail.type;
                                                    let idNode = type + '_' + event.detail.id;
                                                    console.log(event.detail);
                                                    if (type === 'lemma') {
                                                        window.location.assign(`/lexicon3/lemma/${event.detail.id}`);
                                                    }
                                                    if (type === 'form') {
                                                        window.location.assign(`/lexicon3/form/${event.detail.id}`);
                                                    }
                                                }"
                                        >
                                            @include("Lexicon3.partials.tree")
                                        </div>
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
