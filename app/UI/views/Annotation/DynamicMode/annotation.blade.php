<x-layout::index>
    <script src="/scripts/utils/jquery.parser.js"></script>
    <script src="/scripts/utils/jquery.draggable.js"></script>
    <script src="/scripts/utils/jquery.resizable.js"></script>
    <script type="text/javascript" src="/annotation/video/script/objects"></script>
    <script type="text/javascript" src="/annotation/video/script/components"></script>
    <div class="app-layout annotation-video">
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/annotation','Annotation'],['/annotation/dynamicMode','Dynamic'],['',$document->name]]"
        ></x-layout::breadcrumb>
        <div class="annotation-canvas">
            <div class="annotation-video">
                <div class="annotation-player">
                    @include("Annotation.Video.Panes.video")
                </div>
                <div class="annotation-forms">
                    @include("Annotation.DynamicMode.Panes.forms")
                </div>
                @include("Annotation.Video.Panes.bbox")
            </div>
            <div class="annotation-objects">
                @include("Annotation.Video.Panes.grids")
            </div>
        </div>
    </div>
</x-layout::index>
