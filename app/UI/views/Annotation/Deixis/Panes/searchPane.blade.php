<div class="app-search p-1">
    <!-- Search Section -->
    <div class="search-section"
         x-data="searchObjectComponent()"
         @htmx:before-request="onSearchStart"
         @htmx:after-request="onSearchComplete"
         @htmx:after-swap="onResultsUpdated"
    >
        <div class="search-input-group">
            <form class="ui form"
                  hx-post="/annotation/deixis/object/search"
                  hx-target="#gridArea"
                  hx-swap="innerHTML">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="four fields">
                    <div class="field">
                        <x-form::combobox.layer-deixis
                            name="searchIdLayerType"
                            :value="0"
                        ></x-form::combobox.layer-deixis>
                    </div>
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="search icon"></i>
                            <input
                                type="search"
                                name="frame"
                                placeholder="Search Frame"
                                x-model="searchQueryFrame"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="search icon"></i>
                            <input
                                type="search"
                                name="lue"
                                placeholder="Search CV name"
                                x-model="searchQueryLU"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                    <div class="field button-field">
                        <button type="submit" class="ui icon button">
                            <i class="search icon"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="gridArea" class="h-full">
        @fragment("search")
            <div class="results-container"
                 class="results-container view-cards"
            >

                <div class="results-header">
                    <div class="results-info">
                        <div class="results-count" id="resultsCount">{!! count($searchResults) !!}
                            results
                        </div>
                        <div class="search-query-display" id="queryDisplay"></div>
                    </div>
                </div>

                <!-- Empty State -->
                @if(count($searchResults) == 0)
                    <div class="empty-state" id="emptyState">
                        <i class="search icon empty-icon"></i>
                        <h3 class="empty-title">Ready to search</h3>
                        <p class="empty-description">
                            Enter your search above to find objects.
                        </p>
                    </div>
                @endif

                @if(count($searchResults) > 0)
                    <!-- Card View -->
                    <div class="card-view" x-transition>
                        <div class="search-results-grid">
                            @foreach($searchResults as $object)
                                <div class="ui card fluid result-card"
                                     data-id="{{$object->idDynamicObject}}"
                                     @click="window.location.assign(`/annotation/deixis/{{$idDocument}}/{{$object->idDynamicObject}}`)"
                                     tabindex="0"
                                     @keydown.enter="window.location.assign(`/annotation/deixis/{{$idDocument}}/{{$object->idDynamicObject}}`)"
                                     role="button">
                                    <div class="content">
                                        <div class="header">
                                            {{$object->name}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endfragment
    </div>
</div>
