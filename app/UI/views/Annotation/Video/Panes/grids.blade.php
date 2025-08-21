<div class="ui pointing secondary menu tabs">
    <a
        class="item active"
        data-tab="timeline"
    >Timeline</a>
    <a
        class="item"
        data-tab="objects"
    >Objects</a>
</div>
<div class="gridBody">
    <div
        id="timelinePane"
        class="ui tab timeline h-full w-full active"
        data-tab="timeline"
    >
        @include("Annotation.Video.Panes.timeline")
    </div>
    <div class="ui tab objects h-full w-full" data-tab="objects">
        @include("Annotation.Video.Panes.search", ['idDocument' => $idDocument])
    </div>
</div>
<script type="text/javascript">
    $(".tabs .item")
        .tab()
    ;
</script>
