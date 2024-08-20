<x-layout.edit-full>
    <x-slot:title>
        Dynamic Annotation
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        <div id="dynamicAnnotationPane" class="dynamicAnnotationPane">
            <div class="west">
                <div class="video">
                    @include("Annotation.DynamicMode.Panes.videoPane")
                </div>
                <div class="controls">
{{--                        @include('Annotation.StaticEvent.Panes.sentencePane')--}}
                </div>
                <div class="comment">
{{--                    @include('Annotation.StaticEvent.Panes.commentPane')--}}
                </div>
            </div>
            <div class="center">
                <div class="header">
                    <div class="tag">
                        <div class="ui label tag wt-tag-id">
                            {{$corpus->name}}
                        </div>
                        <div class="ui label tag wt-tag-id">
                            {{$document->name}}
                        </div>
                        <div class="ui label tag wt-tag-id">
                            <x-icon.video></x-icon.video>{{$video->title}}
                        </div>
                        <div class="ui label tag wt-tag-id">
                            #{{$idDocument}}
                        </div>
                    </div>
{{--                    <div class="navigation">--}}
{{--                        @if($idPrevious)--}}
{{--                            <div class="previous">--}}
{{--                                <x-element.previous--}}
{{--                                    url="/annotation/staticEvent/sentence/{{$idPrevious}}"></x-element.previous>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if($idNext)--}}
{{--                            <div class="next">--}}
{{--                                <x-element.next url="/annotation/staticEvent/sentence/{{$idNext}}"></x-element.next>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
                </div>
                <div class="annotation">
{{--                    @include('Annotation.StaticEvent.Panes.framePane')--}}
                </div>
                <script type="text/javascript" src="/scripts/vatic/dist/compatibility.js"></script>
                <script type="text/javascript" src="/scripts/vatic/dist/jszip.js"></script>
                <script type="text/javascript" src="/scripts/vatic/dist/StreamSaver.js"></script>
                <script type="text/javascript" src="/scripts/vatic/dist/polyfill.js"></script>
                <script type="text/javascript" src="/scripts/vatic/dist/jsfeat.js"></script>
                <script type="text/javascript" src="/scripts/vatic/dist/nudged.js"></script>
                <script type="text/javascript" src="/scripts/vatic/dist/pouchdb.min.js"></script>
                <script type="text/javascript" src="/scripts/vatic/vatic.js"></script>
                <script type="text/javascript" src="/scripts/vatic/FramesManager.js"></script>
                <script type="text/javascript" src="/scripts/vatic/OpticalFlow.js"></script>
                <script type="text/javascript" src="/scripts/vatic/BoundingBox.js"></script>
                <script type="text/javascript" src="/scripts/vatic/Frame.js"></script>
                <script type="text/javascript" src="/scripts/vatic/DynamicObject.js"></script>
                <script type="text/javascript" src="/scripts/vatic/ObjectsTracker.js"></script>
                <script type="text/javascript">
                    window.annotation = {
                        _token: "{{ csrf_token() }}",
                        document: {{ Js::from($document) }},
                        video: {{ Js::from($video) }},
                        objectList: [],
                    }
                    @include("Annotation.DynamicMode.Scripts.api")
                    @include("Annotation.DynamicMode.Scripts.video")
                    @include("Annotation.DynamicMode.Scripts.drawBox")
                    @include("Annotation.DynamicMode.Scripts.objects")
                    @include("Annotation.DynamicMode.Scripts.gridObjects")
                    @include("Annotation.DynamicMode.Scripts.gridSentences")
                    @include("Annotation.DynamicMode.Scripts.formObject")
                    @include("Annotation.DynamicMode.Scripts.store")


                    $(function() {


                    })
                </script>

            </div>
        </div>
    </x-slot:main>
</x-layout.edit-full>


