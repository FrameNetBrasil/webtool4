<div
    class="h-full"
>
    <div class="ui card w-full">
        <div class="content">
            <div class="header">
                <div class="d-flex justify-between">
                    <div>
                        LU: <span class="color_frame">{{$lu->frame->name}}</span>.<span
                            class="color_lu">{{$lu->name}}</span>
                    </div>
                    <div class="text-right">
                        {{--                        <div class="ui small compact menu">--}}
                        {{--                            <div class="ui simple dropdown item">--}}
                        {{--                                Alternative LUs--}}
                        {{--                                <i class="dropdown icon"></i>--}}
                        {{--                                <div class="menu">--}}
                        {{--                                    @foreach($alternativeLU as $lu)--}}
                        {{--                                        <div class="item">{{$lu->frameName}}.{{$lu->lu}}</div>--}}
                        {{--                                    @endforeach--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="ui label wt-tag-id">
                            #{{$idAnnotationSet}}
                        </div>
                        <button
                            class="ui button negative"
                            onclick="messenger.confirmDelete(`Removing AnnotationSet #{{$idAnnotationSet}}'.`, '/annotation/fe/annotationset/{{$idAnnotationSet}}', null, '#workArea')"
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
                class="h-full w-full"
            >
                <div class="annotationSet">
                    @include("Annotation.FE.Panes.asAnnotation")
                </div>
                <div class="ui grid">
                    <div class="twelve wide column">
                        @include("Annotation.FE.Panes.asLabels")
                    </div>
                    <div class="four wide column">
                        <div class="ui secondary menu">
                            <div class="item">
                            Alternative LUs
                            </div>
                        </div>
                        @foreach($alternativeLU as $lu)
                            <div class="mb-2">
                                <button
                                    class="ui button basic"
                                    onclick="messenger.confirmPost(`Change AnnotationSet to LU '{{$lu->frameName}}.{{$lu->lu}}' ?`, '/annotation/fe/annotationset/{{$idAnnotationSet}}/change')"
                                ><span class="color_frame">{{$lu->frameName}}</span>.<span class="color_lu">{{$lu->lu}}</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
</div>
