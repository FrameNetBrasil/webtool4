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
