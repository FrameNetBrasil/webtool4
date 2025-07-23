<x-layout::index>
    <script type="text/javascript" src="/annotation/dynamic/script/objects"></script>
    <script type="text/javascript" src="/annotation/dynamic/script/components"></script>
    <div class="app-layout annotation-deixis">
        <div class="annotation-header">
            <div class="flex-container between">
                <div class="flex-item">
                    <x-ui::breadcrumb
                        :sections="[['/','Home'],['/annotation/dynamic','Deixis Annotation'],['',$document->name]]"></x-ui::breadcrumb>
                </div>
            </div>
        </div>
        <div class="annotation-canvas">
            <div class="annotation-video">
                <div class="annotation-player">
                @include("Annotation.Dynamic.Panes.videoPane")
                    @include("Annotation.Dynamic.Panes.navigationPane")
                </div>
                <div class="annotation-forms">
                    @include("Annotation.Dynamic.Panes.formsPane")
                </div>
            </div>
            <div class="annotation-objects">
                @include("Annotation.Dynamic.Panes.gridsPane")
            </div>
        </div>
    </div>
</x-layout::index>
