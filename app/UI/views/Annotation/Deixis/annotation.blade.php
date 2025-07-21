<x-layout::index>
    <script type="text/javascript" src="/annotation/deixis/script/objects"></script>
    <script type="text/javascript" src="/annotation/deixis/script/components"></script>
    <div class="app-layout annotation-deixis">
        <div class="annotation-header">
            <div class="flex-container between">
                <div class="flex-item">
                    <x-ui::breadcrumb
                        :sections="[['/','Home'],['/annotation/deixis','Deixis Annotation'],['',$document->name]]"></x-ui::breadcrumb>
                </div>
            </div>
        </div>
        <div class="annotation-canvas">
            <div class="annotation-video">
                <div class="annotation-player">
                @include("Annotation.Deixis.Panes.videoPane")
                    @include("Annotation.Deixis.Panes.navigationPane")
                </div>
                <div class="annotation-forms">
                    @include("Annotation.Deixis.Panes.formsPane")
                </div>
            </div>
            <div class="annotation-objects">
                @include("Annotation.Deixis.Panes.gridsPane")
            </div>
        </div>
    </div>
    {{--            <script type="text/javascript" src="/scripts/vatic/dist/compatibility.js"></script>--}}
    {{--            <script type="text/javascript" src="/scripts/vatic/dist/jszip.js"></script>--}}
    {{--            <script type="text/javascript" src="/scripts/vatic/dist/StreamSaver.js"></script>--}}
    {{--            <script type="text/javascript" src="/scripts/vatic/dist/polyfill.js"></script>--}}
                <script type="text/javascript" src="/scripts/vatic/dist/jsfeat.js"></script>
                <script type="text/javascript" src="/scripts/vatic/dist/nudged.js"></script>
    {{--            <script type="text/javascript" src="/scripts/vatic/dist/pouchdb.min.js"></script>--}}
{{--    <script type="text/javascript" src="/scripts/vatic/vatic.js"></script>--}}
    {{--            <script type="text/javascript" src="/scripts/vatic/FramesManager.js"></script>--}}
    {{--            <script type="text/javascript" src="/scripts/vatic/OpticalFlowObject.js"></script>--}}
    {{--            <script type="text/javascript" src="/scripts/vatic/BoundingBox.js"></script>--}}
    {{--            <script type="text/javascript" src="/scripts/vatic/DynamicObject.js"></script>--}}
    {{--            <script type="text/javascript" src="/scripts/vatic/ObjectTrackerObject.js"></script>--}}
</x-layout::index>

{{--<x-layout.annotation>--}}
{{--    <x-slot:head>--}}
{{--        <x-breadcrumb--}}
{{--            :sections="[['/','Home'],['/annotation/deixis','Deixis Annotation'],['',$document->name]]"></x-breadcrumb>--}}
{{--    </x-slot:head>--}}
{{--    <x-slot:main>--}}
{{--        <div--}}
{{--            id="deixisAnnotationPane"--}}
{{--            class="deixisAnnotationPane"--}}
{{--            x-data="$store.doStore"--}}
{{--        >--}}
{{--            <div class="north">--}}
{{--                <div class="west">--}}
{{--                    <div class="video">--}}
{{--                        @include("Annotation.Deixis.Panes.videoPane")--}}
{{--                    </div>--}}
{{--                    <div class="flex-grow-1">--}}
{{--                        <div--}}
{{--                            id="formObject"--}}
{{--                            class="form"--}}
{{--                            hx-trigger="load"--}}
{{--                            hx-get="/annotation/deixis/formAnnotation/0"--}}
{{--                        >--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="east">--}}
{{--                    <div class="header flex w-full">--}}
{{--                        <div class="font-bold">--}}
{{--                            <x-icon.video></x-icon.video>{{$video->title}}--}}
{{--                        </div>--}}
{{--                        <div class="flex flex-grow-1 justify-content-end">--}}
{{--                            <div class="tag pr-2">--}}
{{--                                <div class="ui label wt-tag-id">--}}
{{--                                    #{{$idDocument}}--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="flex flex-column flex-grow-0 pt-2">--}}
{{--                        @include("Annotation.Deixis.Panes.newObject")--}}
{{--                    </div>--}}
{{--                    @include("Annotation.Deixis.Panes.gridsPane")--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </x-slot:main>--}}
{{--</x-layout.annotation>--}}


