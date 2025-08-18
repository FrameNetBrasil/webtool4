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
                        <!-- Search Section -->
                        <div class="search-section"
                             x-data="searchFormComponent()"
                        >
                            <div class="search-input-group">
                                <form class="ui form"
                                      @submit="onSearchStart"
                                      @input.debounce.500ms="performSearch"
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
                                                    x-model="searchParams.lemma"
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
                                                    x-model="searchParams.form"
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
                                                    console.log(event.detail);
                                                    if (type === 'lemma') {
                                                        window.location.assign(`/lexicon3/lemma/${event.detail.id}`);
                                                    }
                                                    if (type === 'form') {
                                                        window.location.assign(`/lexicon3/form/${event.detail.id}`);
                                                    }
                                                }"
                                        >
                                            @if(count($data) > 0)
                                                <x-ui::tree
                                                    :title="$title ?? ''"
                                                    url="/lexicon3/data"
                                                    :data="$data"
                                                ></x-ui::tree>
                                            @else
                                                <div class="empty-state" id="emptyState">
                                                    <i class="search icon empty-icon"></i>
                                                    <h3 class="empty-title">No results found.</h3>
                                                    <p class="empty-description">
                                                        Enter your search term above to find lemmas/forms.
                                                    </p>
                                                </div>
                                            @endif
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
