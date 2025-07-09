<x-layout::index>
    <div class="app-layout fullscreen">
        <div class="annotation-header">
            <div class="flex-container between">
                <div class="flex-item">
                    <x-ui::breadcrumb
                        :sections="[['/','Home'],['/annotation/fe','FE Annotation'],['',$document->name]]"></x-ui::breadcrumb>
                </div>
                <div class="flex-item">
                    <div class="flex-container">
                        <div class="flex-item">
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
                            </div>
                        </div>
                        <div class="flex-item">
                            <div class="navigation">
                                @if($idPrevious)
                                    <div class="previous">
                                        <x-element.previous
                                            url="/annotation/fe/sentence/{{$idPrevious}}"></x-element.previous>
                                    </div>
                                @endif
                                @if($idNext)
                                    <div class="next">
                                        <x-element.next url="/annotation/fe/sentence/{{$idNext}}"></x-element.next>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="annotation-toolbar bg-gray-200">
        </div>
        <div class="annotation-canvas bg-gray-200">
            @include('Annotation.FE.Panes.annotation')
            <div id="feAnnotationPane" class="feAnnotationPane flex flex-column h-full">

                <div class="annotations flex-grow-1">
                    @include('Annotation.FE.Panes.annotations')
                </div>
            </div>
        </div>
    </div>
</x-layout::index>
