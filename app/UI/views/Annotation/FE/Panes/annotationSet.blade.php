<div
    x-data="annotationSetComponent({{$idAnnotationSet}},'{{$word}}')"
    @selectionchange.document="onSelectionChange"
    class="h-full"
    {{--    hx-trigger="reload-annotationSet from:body"--}}
    {{--    hx-target="#workArea"--}}
    {{--    hx-swap="innerHTML"--}}
    {{--    hx-get="/annotation/fe/as/{{$idAnnotationSet}}/{{$word}}"--}}
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
                            hx-indicator="#htmx-indicator"
                        >
                            Delete this AnnotationSet
                        </button>

                    </div>
                </div>
            </div>
            <hr>
            <div class="annotationSet">
                <div class="flex-container">
                    <div class="" style="width:150px">
                        <div class="rowNI">
                            @foreach($it as $i => $type)
                                @if(($type->entry != 'int_normal') && ($type->entry != 'int_apos'))
                                    <div class="colNI">
                                        @php($height = 24 + (isset($nis[$type->idType]) ? (count($nis[$type->idType]) * 30) : 0))
                                        <span
                                            class="ni"
                                            id="ni_{{$i}}"
                                            data-type="ni"
                                            data-name="{{$type->name}}"
                                            data-id="{{$type->idType}}"
                                            style="height:{{$height}}px"
                                        >{{$type->name}}
                                            @foreach($nis as $idInstantiationType => $niFEs)
                                                @php($topLine = 30)
                                                @if($type->idType == $idInstantiationType)
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
                    <div class="annotationSentence">
                        <div class="rowWord">
                            @foreach($words as $i => $w)
                                {{--                            @if($word['word'] != ' ')--}}
                                <div class="{!! ($w['word'] != ' ') ? 'colWord' : 'colSpace' !!}">
                                    @php($isTarget = ($i >= $target->startWord) && ($i <= $target->endWord))
                                    @php($topLine = 30)
                                    @php($labelsAtWord = ($spans[$i] ?? []))
                                    @php($height = 24 + ($isTarget ? 0 : (count($labelsAtWord) * 30)))
                                    <span
                                        class="word {{$isTarget ? 'target' : ''}} {{$w['hasFE'] ? 'hasFE' : ''}}"
                                        id="word_{{$i}}"
                                        data-type="word"
                                        data-i="{{$i}}"
                                        data-startchar="{{$w['startChar']}}"
                                        data-endchar="{{$w['endChar']}}"
                                        style="height:{{$height}}px"
                                    >{{$w['word']}}
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
            </div>
            <div class="annotationTab">
                <div class="ui pointing secondary menu tabs">
                    <a class="item" data-tab="labels">Labels</a>
                    <a class="item" data-tab="comment">Comment</a>
                </div>
                <div class="gridBody">
                    <div
                        class="ui tab active"
                        data-tab="labels"
                    >
                        @foreach($fesByType as $type => $fesData)
                            <div>{{$type}}</div>
                            <div class="rowFE">
                                @foreach($fesData as $fe)
                                    <div class="colFE">
                                        <button
                                            class="ui right labeled icon button color_{{$fe->idColor}}"
                                            @click.stop="onLabelAnnotate({{$fe->idFrameElement}})"
                                        >
                                            <i
                                                class="delete icon"
                                                @click.stop="onLabelDelete({{$fe->idFrameElement}})"
                                            >
                                            </i>
                                            <div class="d-flex">
                                                <i class="{!! config("webtool.fe.icon")[$fe->coreType] !!} icon text-small"></i>{{$fe->name}}
                                            </div>
{{--                                            <x-element.fe--}}
{{--                                                name="{{$fe->name}}"--}}
{{--                                                type="{{$fe->coreType}}"--}}
{{--                                                idColor="{{$fe->idColor}}"--}}
{{--                                            ></x-element.fe>--}}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    <div class="ui tab" data-tab="comment">
                        @include("Annotation.FE.Forms.formComment")
                    </div>
                </div>
                <script type="text/javascript">
                    $(".tabs .item")
                        .tab()
                    ;
                </script>
            </div>
        </div>
    </div>
</div>
