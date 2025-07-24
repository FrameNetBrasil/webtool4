<div class="flex-container">
    <div style="width:150px">
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
                                        <span class="line color_{{$fes[$idEntityFE]->idColor}}"
                                              style="top:{{$topLine}}px">
                                            <span class="feLabel color_{{$fes[$idEntityFE]->idColor}}"
                                                  style="top:0">{{$niFE['label']}}</span>
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
    <div
        class="layers"
    >
        <div>&nbsp;</div>
        @foreach($layerTypes as $i => $layerType)
            <div
                @click="$dispatch('change-label-tab', '{{$layerType->entry}}')"
                class="cursor-pointer"
                style="top:{!! ($i * 24) + 21 !!}px"
            >{{$layerType->name}}</div>
        @endforeach
    </div>
    <div
        class="annotationSentenceFull flex flex-column"
    >
        <div class="rowWord">
            @foreach($words as $i => $word)
                <div class="{!! ($word['word'] != ' ') ? 'colWord' : 'colSpace' !!}">
                    @php($isTarget = ($i >= $target->startWord) && ($i <= $target->endWord))
                    @php($labelsAtWord = ($spans[$i] ?? []))
                    @php($height = 24)
                    <span
                        class="word {{$isTarget ? 'target' : ''}} {{$word['hasFE'] ? 'hasFE' : ''}}"
                        id="word_{{$i}}"
                        data-type="word"
                        data-i="{{$i}}"
                        data-startchar="{{$word['startChar']}}"
                        data-endchar="{{$word['endChar']}}"
                        style="height:{{$height}}px"
                    >{!! ($word['word'] != ' ') ? $word['word'] : '&nbsp;' !!}
                    </span>
                </div>
            @endforeach
        </div>
        <div class="rowAnnotation">

            @foreach($words as $i => $word)
                <div
                    class="{!! 'flex-container column gap-0 ' . (($word['word'] == ' ') ? 'colSpace' : 'colWord') !!}"
                >
                    <div
                        style="height:0;overflow-y:hidden"
                    >{!! ($word['word'] == ' ') ? '&nbsp;' : $word['word'] !!}
                    </div>
                    @foreach($layerTypes as $layerType)
                        <div
                            class="label"
                        >
                            @php($span = $spans[$i][$layerType->idLayer] ?? null)
                            @if($span)
                                <div
                                    class="line color_{{$span['idColor']}}"
                                >
                                    @if($span['label'])
                                        <span
                                            @click="$dispatch('change-label-tab', '{{$layerType->entry}}')"
                                            class="feLabel color_{{$span['idColor']}} cursor-pointer"
                                            style="top:0"
                                        >{{$span['label']}}
                                    </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
<hr />
