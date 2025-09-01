<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/structure','Structure'],['','Reframing']]"
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
                                      hx-post="/reframing/search"
                                      hx-target="#gridArea"
                                      hx-swap="innerHTML"
                                      hx-trigger="submit, input delay:500ms"
                                >
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
                                            >
                                                @if(count($data) > 0)
                                                    <table class="ui selectable striped compact table">
                                                        <tbody>
                                                        @foreach($data as $lu)
                                                            <tr>
                                                                <td>
                                                                    <a
                                                                        href="/reframing/lu/{{$lu['id']}}"
                                                                        hx-boost="true"
                                                                    >
                                                                        {!! $lu['text'] !!}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @else
                                                    <div class="empty-state" id="emptyState">
                                                        <i class="search icon empty-icon"></i>
                                                        <h3 class="empty-title">No results found.</h3>
                                                        <p class="empty-description">
                                                            Enter your search term above to find LU.
                                                        </p>
                                                    </div>
                                                @endif
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
