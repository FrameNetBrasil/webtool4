<x-layout.edit>
    <x-slot:head>
        <x-breadcrumb
            :sections="[['/','Home'],['/annotation/fe','FE Annotation'],['',$document->name]]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        @include('Annotation.FE.Panes.annotation')
        <div id="feAnnotationPane" class="feAnnotationPane">
                <div class="header">
                    <div class="tag">
                        <div class="ui label wt-tag-id">
                            {{$corpus->name}}
                        </div>
                        <div class="ui label wt-tag-id">
                            {{$document->name}}
                        </div>
                        <div class="ui label wt-tag-id">
                            #{{$idDocumentSentence}}
                        </div>
                    </div>
                    <div class="navigation">
                        @if($idPrevious)
                            <div class="previous">
                                <x-element.previous url="/annotation/fe/sentence/{{$idPrevious}}"></x-element.previous>
                            </div>
                        @endif
                        @if($idNext)
                            <div class="next">
                                <x-element.next url="/annotation/fe/sentence/{{$idNext}}"></x-element.next>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="annotations">
                    @include('Annotation.FE.Panes.annotations')
                    <div id="workArea" class="workArea">
                    </div>
                </div>
        </div>
    </x-slot:main>
</x-layout.edit>
