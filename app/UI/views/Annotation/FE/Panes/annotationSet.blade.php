<div class="ui card w-full">
    <div class="content">
        <div class="header">
            <div class="grid">
                <div class="col-8">
                    LU: {{$lu->frame->name}}.{{$lu->name}}
                </div>
                <div class="col-4 text-right">
                    <div class="ui label tag wt-tag-id">
                        #{{$idAnnotationSet}}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="description">
            <div class="flex flex-row">
                <div class="" style="width:150px">
                    <div class="rowNI">
                        @foreach($it as $i => $type)
                            @if(($type->entry != 'int_normal') && ($type->entry != 'int_apos'))
                                <div class="colNI">
                                    @php($height = 24 + (isset($nis[$type->idTypeInstance]) ? (count($nis[$type->idTypeInstance]) * 30) : 0))
                                    <span
                                        class="ni"
                                        id="ni_{{$i}}"
                                        data-type="ni"
                                        data-name="{{$type->name}}"
                                        data-id="{{$type->idTypeInstance}}"
                                        style="height:{{$height}}px"
                                    >{{$type->name}}
                                        @foreach($nis as $idInstantiationType => $niFEs)
                                            @php($topLine = 30)
                                            @if($type->idTypeInstance == $idInstantiationType)
                                                @foreach($niFEs as $niFE)
                                                    @php($idEntityFE = $niFE['idEntityFE'])
                                                    {{--                                                                            <span class="line" style="background:#{{$fes[$idEntityFE]->rgbBg}}; top:{{$topLine}}px">--}}
                                                    <span class="line color_{{$fes[$idEntityFE]->idColor}}"
                                                          style="top:{{$topLine}}px">
                                        <span class="feLabel color_{{$fes[$idEntityFE]->idColor}}"
                                              style="top:0px">{{$niFE['label']}}</span>
                            </span>
                                                    @php($topLine += 24)
                                                @endforeach
                                            @endif
                                        @endforeach
                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
                <div>
                    <div class="rowWord">

                        @foreach($words as $i => $word)
{{--                            @if($word['word'] != ' ')--}}
                                <div class="{!! ($word['word'] != ' ') ? 'colWord' : 'colSpace' !!}">
                                    @php($isTarget = ($i >= $target->startWord) && ($i <= $target->endWord))
                                    @php($topLine = 30)
                                    @php($labelsAtWord = ($spans[$i] ?? []))
                                    @php($height = 24 + ($isTarget ? 0 : (count($labelsAtWord) * 30)))
                                    <span
                                        class="word {{$isTarget ? 'target' : ''}} {{$word['hasFE'] ? 'hasFE' : ''}}"
                                        id="word_{{$i}}"
                                        data-type="word"
                                        data-i="{{$i}}"
                                        data-startchar="{{$word['startChar']}}"
                                        data-endchar="{{$word['endChar']}}"
                                        style="height:{{$height}}px"
                                    >{{$word['word']}}
                                        @foreach($idLayers as $l => $idLayer)
                                            @php($label = $spans[$i][$idLayer])
{{--                                        @foreach($labelsAtWord as $label)--}}
                                            @if(!is_null($label))
                                                @php($idEntityFE = $label['idEntityFE'])
                                                {{--                                <span class="line" style="background:#{{$fes[$idEntityFE]->rgbBg}}; top:{{$topLine}}px">--}}
                                                <span class="line color_{{$fes[$idEntityFE]->idColor}}"
                                                      style="top:{{$topLine}}px">
                                                @if($label['label'])
                                                        <span class="feLabel color_{{$fes[$idEntityFE]->idColor}}"
                                                              style="top:0px">{{$label['label']}}</span>
                                                @endif
                                                </span>
                                            @else
                                                <span></span>
                                            @endif
                                            @php($topLine += 24)
{{--                                        @endforeach--}}
                                        @endforeach
                    </span>
                                </div>
{{--                            @endif--}}
                        @endforeach

                    </div>

                </div>
            </div>


            <div class="rowFE">
                @foreach($fes as $fe)
                    <div class="colFE">
                        <button
                            class="ui right labeled icon button color_{{$fe->idColor}}"
                            hx-post="/annotation/fe/annotate/"
                            hx-target="#workArea"
                            hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idFrameElement:{{$fe->idFrameElement}}, selection: annotationFE.selection}'
                        >
                            <i
                                class="delete icon"
                                hx-on:click="event.stopPropagation()"
                                hx-delete="/annotation/fe/frameElement"
                                hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idFrameElement:{{$fe->idFrameElement}}}'
                                hx-target="#workArea"
                            >
                            </i>
                            <x-element.fe
                                name="{{$fe->name}}"
                                type="{{$fe->coreType}}"
                                idColor="{{$fe->idColor}}"
                            ></x-element.fe>
                        </button>
                    </div>
                @endforeach
            </div>
            <hr />
            <div class="rowDanger flex">
                <button
                    class="ui button negative"
                    onclick="manager.confirmDelete(`Removing AnnotationSet #{{$idAnnotationSet}}'.`, '/annotation/fe/annotationset/{{$idAnnotationSet}}', null, '#workArea')"
                    hx-indicator="#htmx-indicator"
                >
                    Delete this AnnotationSet
                </button>
                <div id="htmx-indicator" class="htmx-indicator">
                    <div class="ui page">
                            <div class="ui loader active tiny text inverted">Processing</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    annotationFE.selection = {
        type: "",
        id: "",
        start: 0,
        end: 0
    };
</script>
