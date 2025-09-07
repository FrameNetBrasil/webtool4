<div class="app-search p-1">
    <!-- Search Section -->
    <div class="search-container"
         x-data="searchObjectComponent()"
         @htmx:before-request="onSearchStart"
         @htmx:after-request="onSearchComplete"
         @htmx:after-swap="onResultsUpdated"
    >
        <div class="search-input-section">
            <form class="ui form"
                  hx-post="/annotation/image/object/search"
                  hx-target=".search-result-section"
                  hx-swap="innerHTML">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="idDocument" value="{{ $idDocument ?? 0 }}"/>
                <input type="hidden" name="annotationType" value="{{ $annotationType }}"/>
                <div class="three fields">
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="search icon"></i>
                            <input
                                type="search"
                                name="frame"
                                placeholder="Search Frame/FE"
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
                                name="lu"
                                placeholder="Search CV name"
                                x-model="searchQueryLU"
                                autocomplete="off"
                            >
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="search icon"></i>
                            <input
                                type="search"
                                name="idObject"
                                placeholder="Search #idObject"
                                x-model="searchQueryIdDynamicObject"
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

        <div class="search-result-section flex-col">
            @fragment("search")
                @if(count($objects) > 0)
                    <div class="search-result-data pl-1 pr-1">
                        <div class="search-result-header">
                            <div class="result-info">
                                <div class="result-count" id="resultsCount">{!! count($objects ?? []) !!}
                                    results
                                </div>
                            </div>
                        </div>
                        <div class="card-container">
                            <div
                                class="search-results-grid card-grid dense"
                                hx-get="/annotation/image/object"
                                hx-target="#formsPane"
                                hx-swap="innerHTML"
                                hx-on::config-request="event.detail.parameters.append('idStaticObject', event.detail.triggeringEvent.target.dataset.id)"
                            >
                                @foreach($objects as $object)
                                    <div class="ui card fluid result-card"

                                         tabindex="0"
                                         role="button">
                                        <div
                                            class="content"
                                            data-id="{{$object->idObject}}"
                                        >
                                            <div
                                                class="header"
                                                data-id="{{$object->idObject}}"
                                            >
                                                Object: #{{$object->idObject}}
                                                {{--                                                {{$object->layerGroup}}/{{$object->nameLayerType}}--}}
                                            </div>
                                            <div
                                                class="meta"
                                                data-id="{{$object->idObject}}"
                                            >
                                                <x-element::frame name="{{$object->fe->frameName}}.{{$object->fe->name}}"></x-element::frame>
                                                <x-element::lu name="{{$object->lu->frameName}}.{{$object->lu->name}}"></x-element::lu>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="search-result-empty" id="emptyState">
                        <i class="search icon empty-icon"></i>
                        <h3 class="empty-title">No results found.</h3>
                        <p class="empty-description">
                            Enter your search above to find objects.
                        </p>
                    </div>
                @endif
            @endfragment
        </div>
    </div>
</div>
