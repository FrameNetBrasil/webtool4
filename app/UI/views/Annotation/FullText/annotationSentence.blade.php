<x-layout.edit-full>
    <x-slot:title>
        FullText Annotation
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        @include('Annotation.FullText.Panes.annotation')
        <div id="fullTextAnnotationPane" class="fullTextAnnotationPane">
                <div class="header">
                    <div class="tag">
                        <div class="ui label tag wt-tag-id">
                            {{$corpus->name}}
                        </div>
                        <div class="ui label tag wt-tag-id">
                            {{$document->name}}
                        </div>
                        <div class="ui label tag wt-tag-id">
                            #{{$idDocumentSentence}}
                        </div>
                    </div>
                    <div class="navigation">
                        @if($idPrevious)
                            <div class="previous">
                                <x-element.previous url="/annotation/fullText/sentence/{{$idPrevious}}"></x-element.previous>
                            </div>
                        @endif
                        @if($idNext)
                            <div class="next">
                                <x-element.next url="/annotation/fullText/sentence/{{$idNext}}"></x-element.next>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="annotations">
                    @include('Annotation.FullText.Panes.annotations')
                    <div id="workArea" class="workArea">
                    </div>
                </div>
        </div>
    </x-slot:main>
</x-layout.edit-full>
