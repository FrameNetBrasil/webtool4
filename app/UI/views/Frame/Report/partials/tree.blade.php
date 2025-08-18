<div class="results-container view-cards"
>
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
                    <x-ui::tree
                        :title="$title ?? ''"
                        url="/report/frame/tree"
                        :data="$data"
                    ></x-ui::tree>
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
