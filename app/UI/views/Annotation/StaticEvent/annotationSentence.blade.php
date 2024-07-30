@php
    $panelWestWidth = $image->width + 30;
    $panelImageHeight = $image->height + 40;
@endphp
<x-layout.edit-full>
    <x-slot:title>
        Static Event
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        @include('Annotation.StaticEvent.Panes.annotation')
        <div id="staticEventAnnotationPane" class="staticEventAnnotationPane">
            <div class="west">
                <div class="image">
                    @include('Annotation.StaticEvent.Panes.imagePane')
                </div>
                <div class="sentence">
                    @include('Annotation.StaticEvent.Panes.sentencePane')
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
                            <x-icon.image></x-icon.image>{{$image->name}}
                        </div>
                        <div class="ui label tag wt-tag-id">
                            #{{$idDocumentSentence}}
                        </div>
                    </div>
                    <div class="navigation">
                        @if($idPrevious)
                            <div class="previous">
                                <x-element.previous url="/annotation/staticEvent/sentence/{{$idPrevious}}"></x-element.previous>
                            </div>
                        @endif
                        @if($idNext)
                            <div class="next">
                                <x-element.next url="/annotation/staticEvent/sentence/{{$idNext}}"></x-element.next>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="annotation">
                    @include('Annotation.StaticEvent.Panes.framePane')
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout.edit-full>