<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/reports','Reports'],['','Frames']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container browse-page">
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
                                    <div class="field">
                                        <div class="ui left icon input w-full">
                                            <i class="search icon"></i>
                                            <input
                                                type="search"
                                                name="frame"
                                                placeholder="Search Frame"
                                                x-model="searchQuery"
                                                autocomplete="off"
                                            >
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
                                                if (type === 'frame') {
                                                    window.open(`/report/frame/${event.detail.id}`, '_blank');
                                                }
                                            }"
                                        >
                                            @if(count($data) > 0)
                                                <table class="ui striped compact table">
                                                    <tbody>
                                                    @foreach($data as $frame)
                                                        <tr
                                                            class="cursor-pointer"
                                                            @click="window.location.assign('/report/frame/{{$frame['id']}}')"
                                                        >
                                                            <td>{!! $frame['text'] !!}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
{{--                                                <x-ui::tree--}}
{{--                                                    :title="$title ?? ''"--}}
{{--                                                    url="/report/frame/data"--}}
{{--                                                    :data="$data"--}}
{{--                                                ></x-ui::tree>--}}
                                            @else
                                                <div class="empty-state" id="emptyState">
                                                    <i class="search icon empty-icon"></i>
                                                    <h3 class="empty-title">No results found.</h3>
                                                    <p class="empty-description">
                                                        Enter your search term above to find frames.
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
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
