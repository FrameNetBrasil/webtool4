<div class="grids flex flex-column flex-grow-1">
    <div class="ui pointing secondary menu tabs">
        <a class="item" data-tab="objects">Objects</a>
        <a class="item" data-tab="sentences">Sentences</a>
    </div>
    <div class="gridBody">
        @include("Annotation.DynamicMode.Panes.objectsPane")
        <div
            class="ui tab sentences p-2"
            data-tab="sentences"
            hx-trigger="load"
            hx-get="/annotation/dynamicMode/sentences/{{$idDocument}}"
        >
            sentences
        </div>
    </div>
    <script type="text/javascript">
        $(".tabs .item")
            .tab()
        ;
    </script>
</div>