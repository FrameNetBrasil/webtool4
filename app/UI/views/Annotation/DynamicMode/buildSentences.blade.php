<x-layout.edit-full>
    <x-slot:title>
        Dynamic Annotation - Build Sentences
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        <div id="dynamicAnnotationBuildSentencesPane" class="dynamicAnnotationBuildSentencesPane">
            <div class="west">
                <div class="video">
                    @include("Annotation.DynamicMode.Panes.videoBuildSentencesPane")
                </div>
                <div
                    id="gridWord"
                    class="gridWord"
                    hx-trigger="load"
                    hx-get="/annotation/dynamicMode/gridWords/{{$idDocument}}"
                >
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
                </div>
                <div class="flex flex-column flex-grow-1" x-data="$store.doStore">
                    <div
                        id="formSentence"
                        class="form"
                        hx-trigger="load"
                        hx-get="/annotation/dynamicMode/formSentence/0"
                    >
                    </div>
{{--                    @include("Annotation.DynamicMode.Panes.gridsPane")--}}
                </div>
                <script type="text/javascript">
                    window.annotation = {
                        _token: "{{ csrf_token() }}",
                        document: {{ Js::from($document) }},
                        video: {{ Js::from($video) }},
                    }
                    @include("Annotation.DynamicMode.Scripts.api")
                    @include("Annotation.DynamicMode.Scripts.videoBuildSentences")
                    @include("Annotation.DynamicMode.Scripts.storeBuildSentences")
                    $(function() {
                    })
                </script>

            </div>
        </div>
    </x-slot:main>
</x-layout.edit-full>


