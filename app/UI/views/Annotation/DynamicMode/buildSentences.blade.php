<x-layout.edit-full>
    <x-slot:title>
        Dynamic Annotation - Build Sentences
    </x-slot:title>
    <x-slot:actions>
        <x-button
            id="btnAnnotation"
            label="Annotation"
            color="secondary"
            href="/annotation/dynamicMode/{{$idDocument}}"
        >
        </x-button>
    </x-slot:actions>
    <x-slot:main>
        <div id="dynamicAnnotationBuildSentencesPane" class="dynamicAnnotationBuildSentencesPane">
            <div class="west">
                <div class="video">
                    @include("Annotation.DynamicMode.Panes.videoBuildSentencesPane")
                </div>
                <div
                    class="pb-1"
                >
                    <button
                        id="btnJoin"
                        class="ui button primary"
                        x-data @click="$store.doStore.join()"
                    >
                        <span x-data x-text="'Join words'"></span>
                    </button>
                    <button
                        id="btnClear selection"
                        class="ui button primary"
                        x-data @click="$store.doStore.clearSelection()"
                    >
                        <span x-data x-text="'Clear selection'"></span>
                    </button>
                </div>
                @include("Annotation.DynamicMode.Panes.gridWordPane")
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
                        hx-trigger="load"
                        hx-get="/annotation/dynamicMode/formSentence/{{$idDocument}}/0"
                    >
                    </div>
                    <div
                        id="gridSentences"
                        class="gridSentences"
                        hx-trigger="load"
                        hx-get="/annotation/dynamicMode/buildSentences/sentences/{{$idDocument}}"
                    >
                    </div>
                </div>
                <script type="text/javascript">
                    window.annotation = {
                        _token: "{{ csrf_token() }}",
                        document: {{ Js::from($document) }},
                        videoObject: {{ Js::from($video) }},
                        wordList: [],
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


