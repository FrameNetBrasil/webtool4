<x-layout::index>
    <script src="/scripts/utils/jquery.parser.js"></script>
    <script src="/scripts/utils/jquery.draggable.js"></script>
    <script src="/scripts/utils/jquery.resizable.js"></script>
    <script type="text/javascript" src="/annotation/dynamicMode/script/objects"></script>
    <script type="text/javascript" src="/annotation/dynamicMode/script/components"></script>
    <div class="app-layout annotation-dynamicMode">
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/annotation','Annotation'],['/annotation/dynamicMode','Dynamic'],['',$document->name]]"
        ></x-layout::breadcrumb>
        <div class="annotation-canvas">
            <div class="annotation-video">
                <div class="annotation-player">
                @include("Annotation.DynamicMode.Panes.videoPane")
                </div>
                <div class="annotation-forms">
                    @include("Annotation.DynamicMode.Panes.formsPane")
                </div>
            </div>
            <div class="annotation-objects">
                @include("Annotation.DynamicMode.Panes.gridsPane")
            </div>
        </div>
    </div>
</x-layout::index>
