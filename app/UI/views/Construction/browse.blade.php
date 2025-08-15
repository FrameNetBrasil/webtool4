@php
use App\Services\AppService;
@endphp
<x-layout.browser>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Constructions']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
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
                                  hx-post="/cxn/tree"
                                  hx-target=".search-results-tree"
                                  hx-swap="innerHTML"
                                  hx-trigger="submit, input delay:500ms from:input[name='cxn']">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="two fields">
                                    <div class="field">
                                        <div class="ui left icon input w-full">
                                            <i class="search icon"></i>
                                            <input
                                                type="search"
                                                name="cxn"
                                                placeholder="Search Construction"
                                                autocomplete="off"
                                            >
                                        </div>
                                    </div>
                                    <div class="field">
                                        <x-combobox.cxn-language
                                            id="cxIdLanguage"
                                            :value="AppService::getCurrentIdLanguage()"
                                        ></x-combobox.cxn-language>
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
                                                    if (type === 'cxn') {
                                                        window.open(`/cxn/${event.detail.id}`, '_blank');
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
    </x-slot:main>
</x-layout.browser>
