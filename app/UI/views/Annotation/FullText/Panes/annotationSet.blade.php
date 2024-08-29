<div class="annotationSet">
    <div class="ui card w-full">
        <div class="content">
            <div class="header">
                <div class="grid">
                    <div class="col-8">
                        LU: {{$lu->frame->name}}.{{$lu->name}}
                    </div>
                    <div class="col-4 text-right">
                        <div class="ui dropdown alternativeLU">
                            <div class="text">Alternative LUs</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                @foreach($alternativeLU as $lu)
                                    <div class="item">{{$lu->frameName}}.{{$lu->lu}}</div>
                                @endforeach
                            </div>
                        </div>
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
                                    @php($labelsAtWord = ($spans[$i] ?? []))
                                    @php($height = 24 + ($isTarget ? 0 : ((count($labelsAtWord) - 1) * 30)))
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
                                            @php($topLine = 6 + ($l * 24))
                                            @php($label = $spans[$i][$idLayer])
                                            @if(!is_null($label))
                                                @if(isset($label['idEntityFE']))
                                                    @php($idEntityFE = $label['idEntityFE'])
                                                    <span class="line color_{{$fes[$idEntityFE]->idColor}}"
                                                          style="top:{{$topLine}}px">
                                                @if($label['label'])
                                                            <span class="feLabel color_{{$fes[$idEntityFE]->idColor}}"
                                                                  style="top:0">{{$label['label']}}</span>
                                                        @endif
                                                </span>
                                                @endif
                                                @if(isset($label['idEntityGF']))
                                                    @php($idEntityGF = $label['idEntityGF'])
                                                    <span class="line color_{{$gfs[$idEntityGF]->idColor}}"
                                                          style="top:{{$topLine}}px">
                                                        @if($label['label'])
                                                            <span class="feLabel color_{{$gfs[$idEntityGF]->idColor}}"
                                                                  style="top:0">{{$label['label']}}</span>
                                                        @endif
                                                </span>
                                                @endif
                                                @if(isset($label['idEntityPT']))
                                                    @php($idEntityPT = $label['idEntityPT'])
                                                    <span class="line color_{{$pts[$idEntityPT]->idColor}}"
                                                          style="top:{{$topLine}}px">
                                                        @if($label['label'])
                                                            <span class="feLabel color_{{$pts[$idEntityPT]->idColor}}"
                                                                  style="top:0">{{$label['label']}}</span>
                                                        @endif
                                                </span>
                                                @endif
                                            @else
                                                <span></span>
                                            @endif
                                            {{--                                            @php($topLine += 24)--}}
                                        @endforeach
                                </span>
                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grids flex flex-column flex-grow-1">
    <div class="ui secondary menu tabs">
        <a class="item" data-tab="fe">FE</a>
        <a class="item" data-tab="gf">GF</a>
        <a class="item" data-tab="pt">PT</a>
        <a class="item" data-tab="other">Other</a>
        <a class="item" data-tab="pos">POS</a>
        <a class="item" data-tab="sent">Sent</a>
    </div>
    <script type="text/javascript">
        $(".tabs .item")
            .tab()
        ;
    </script>

    <div class="gridLabels">
        <div class="labels">
            <div class="ui card w-full tab" data-tab="fe">
                <div class="content">
                    <div class="rowFE">
                        @foreach($fes as $fe)
                            <div class="colFE">
                                <button
                                    class="ui right labeled icon button color_{{$fe->idColor}}"
                                    hx-post="/annotation/fullText/annotate/"
                                    hx-target="#workArea"
                                    hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idFrameElement:{{$fe->idFrameElement}}, selection: annotationFE.selection}'
                                >
                                    <i
                                        class="delete icon"
                                        hx-on:click="event.stopPropagation()"
                                        hx-delete="/annotation/fullText/frameElement"
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
                </div>
            </div>
            <div class="ui card w-full tab" data-tab="gf">
                <div class="content">
                    <div class="rowFE">
                        @foreach($gfs as $gf)
                            <div class="colFE">
                                <button
                                    class="ui right labeled icon button color_{{$gf->idColor}}"
                                    hx-post="/annotation/fullText/annotate/"
                                    hx-target="#workArea"
                                    hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$gf->idGenericLabel}}, selection: annotationFullText.selection}'
                                >
                                    <i
                                        class="delete icon"
                                        hx-on:click="event.stopPropagation()"
                                        hx-delete="/annotation/fullText/genericLabel"
                                        hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$gf->idGenericLabel}}}'
                                        hx-target="#workArea"
                                    >
                                    </i>
                                    <x-element.gl
                                        name="{{$gf->name}}"
                                        idColor="{{$gf->idColor}}"
                                    ></x-element.gl>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="ui card w-full tab" data-tab="pt">
                <div class="content">
                    <div class="rowFE">
                        @foreach($pts as $pt)
                            <div class="colFE">
                                <button
                                    class="ui right labeled icon button color_{{$pt->idColor}}"
                                    hx-post="/annotation/fullText/annotate/"
                                    hx-target="#workArea"
                                    hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$pt->idGenericLabel}}, selection: annotationFullText.selection}'
                                >
                                    <i
                                        class="delete icon"
                                        hx-on:click="event.stopPropagation()"
                                        hx-delete="/annotation/fullText/genericLabel"
                                        hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$pt->idGenericLabel}}}'
                                        hx-target="#workArea"
                                    >
                                    </i>
                                    <x-element.gl
                                        name="{{$pt->name}}"
                                        idColor="{{$pt->idColor}}"
                                    ></x-element.gl>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="ui card w-full tab" data-tab="other">
                <div class="content">
                    <div class="rowFE">
                        @foreach($others as $other)
                            <div class="colFE">
                                <button
                                    class="ui right labeled icon button color_{{$other->idColor}}"
                                    hx-post="/annotation/fullText/annotate/"
                                    hx-target="#workArea"
                                    hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$other->idGenericLabel}}, selection: annotationFullText.selection}'
                                >
                                    <i
                                        class="delete icon"
                                        hx-on:click="event.stopPropagation()"
                                        hx-delete="/annotation/fullText/genericLabel"
                                        hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$other->idGenericLabel}}}'
                                        hx-target="#workArea"
                                    >
                                    </i>
                                    <x-element.gl
                                        name="{{$other->name}}"
                                        idColor="{{$other->idColor}}"
                                    ></x-element.gl>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="ui card w-full tab" data-tab="pos">
                <div class="content">
                    <div class="rowFE">
                        @foreach($pos as $p)
                            <div class="colFE">
                                <button
                                    class="ui right labeled icon button color_{{$p->idColor}}"
                                    hx-post="/annotation/fullText/annotate/"
                                    hx-target="#workArea"
                                    hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$p->idGenericLabel}}, selection: annotationFullText.selection}'
                                >
                                    <i
                                        class="delete icon"
                                        hx-on:click="event.stopPropagation()"
                                        hx-delete="/annotation/fullText/genericLabel"
                                        hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$p->idGenericLabel}}}'
                                        hx-target="#workArea"
                                    >
                                    </i>
                                    <x-element.gl
                                        name="{{$p->name}}"
                                        idColor="{{$p->idColor}}"
                                    ></x-element.gl>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="ui card w-full tab" data-tab="sent">
                <div class="content">
                    <div class="rowFE">
                        @foreach($sents as $sent)
                            <div class="colFE">
                                <button
                                    class="ui right labeled icon button color_{{$sent->idColor}}"
                                    hx-post="/annotation/fullText/annotate/"
                                    hx-target="#workArea"
                                    hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$sent->idGenericLabel}}, selection: annotationFullText.selection}'
                                >
                                    <i
                                        class="delete icon"
                                        hx-on:click="event.stopPropagation()"
                                        hx-delete="/annotation/fullText/genericLabel"
                                        hx-vals='js:{idAnnotationSet: {{$idAnnotationSet}}, idGenericLabel:{{$sent->idGenericLabel}}}'
                                        hx-target="#workArea"
                                    >
                                    </i>
                                    <x-element.gl
                                        name="{{$sent->name}}"
                                        idColor="{{$sent->idColor}}"
                                    ></x-element.gl>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{--        <div class="ui card w-full">--}}
            {{--            <div class="content">--}}
            {{--                <div class="rowDanger flex">--}}
            {{--                    <button--}}
            {{--                        class="ui button negative"--}}
            {{--                        onclick="manager.confirmDelete(`Removing AnnotationSet #{{$idAnnotationSet}}'.`, '/annotation/fullText/annotationset/{{$idAnnotationSet}}', null, '#workArea')"--}}
            {{--                        hx-indicator="#htmx-indicator"--}}
            {{--                    >--}}
            {{--                        Delete this AnnotationSet--}}
            {{--                    </button>--}}
            {{--                    <div id="htmx-indicator" class="htmx-indicator">--}}
            {{--                        <div class="ui page">--}}
            {{--                            <div class="ui loader active tiny text inverted">Processing</div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
        </div>
    </div>
</div>

<script type="text/javascript">
    annotationFullText.selection = {
        type: "",
        id: "",
        start: 0,
        end: 0
    };

    $(function() {
        $(".alternativeLU")
            .dropdown({
                action: "hide"
            });
    });
</script>
