<x-layout.browser>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Task/User']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
                <div class="app-search">
                    <!-- Search Section -->
                    <div class="search-section"
                         x-data="searchFormComponent()"
                         @htmx:before-request="onSearchStart"
                         @htmx:after-request="onSearchComplete"
                         @htmx:after-swap="onResultsUpdated"
                    >
                        <div class="search-input-group">
                            <form class="ui form"
                                  hx-post="/task/tree"
                                  hx-target=".search-results-tree"
                                  hx-swap="innerHTML"
                                  hx-trigger="submit, input delay:500ms from:input[name='task']">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="three fields">
                                    <div class="field">
                                        <div class="ui left icon input w-full">
                                            <i class="search icon"></i>
                                            <input
                                                type="search"
                                                name="task"
                                                placeholder="Search Task"
                                                autocomplete="off"
                                            >
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui left icon input w-full">
                                            <i class="search icon"></i>
                                            <input
                                                type="search"
                                                name="user"
                                                placeholder="Search User"
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

                    <div id="gridArea">
                        @fragment("search")
                            <div class="results-container view-cards"
                            >
                                <div class="results-wrapper">

                                        <div class="tree-view" x-transition>
                                            <div class="search-results-tree"
                                                 x-data
                                                 @tree-item-selected.document="(event) => {
                                                    let type =  event.detail.type;
                                                    let idNode = type + '_' + event.detail.id;
                                                    if (type === 'task') {
                                                        window.location.assign(`/task/${event.detail.id}`);
                                                    }
                                                    if (type === 'user') {
                                                        event.detail.tree.toggleNodeState(idNode);
                                                    }
                                                    if (type === 'usertask') {
                                                        window.location.assign(`/usertask/${event.detail.id}`);
                                                    }
                                                }"
                                            >
                                                @include("Task.partials.tree")
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
