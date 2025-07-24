<div
    class="h-full"
>
    <div class="ui card w-full">
        <div class="content">
            <div class="header">
                <div class="flex-container between">
                    <div>
                        LU: {{$lu->frame->name}}.{{$lu->name}}
                    </div>
                    <div class="text-right">
                        <div class="ui compact menu">
                            <div class="ui simple dropdown item">
                                Alternative LUs
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    @foreach($alternativeLU as $lu)
                                        <div class="item">{{$lu->frameName}}.{{$lu->lu}}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="ui label wt-tag-id">
                            #{{$idAnnotationSet}}
                        </div>
                        <button
                            class="ui button negative"
                            onclick="manager.confirmDelete(`Removing AnnotationSet #{{$idAnnotationSet}}'.`, '/annotation/fe/annotationset/{{$idAnnotationSet}}', null, '#workArea')"
                        >
                            Delete this AnnotationSet
                        </button>

                    </div>
                </div>
            </div>
            <hr>
            <div
                x-data="annotationSetComponent({{$idAnnotationSet}},'{{$word}}')"
                @selectionchange.document="selectionRaw =  document.getSelection()"
                class="h-full"
            >
                <div class="annotationSet">
                    @include("Annotation.FE.Panes.annotation")
                </div>
                @include("Annotation.FE.Panes.labels")
            </div>
        </div>
    </div>
</div>
