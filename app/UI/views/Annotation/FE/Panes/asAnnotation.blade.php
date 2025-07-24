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
                            @click="onSelectNI($el)"
                        >{{$type->name}}
                        </span>
                    </div>
                    @foreach($nis as $idInstantiationType => $niFEs)
                    <div
                        class="colNILabel"
                    >
                                @php($topLine = 30)
                                @if($type->idType == $idInstantiationType)
                                    @foreach($niFEs as $niFE)
                                        @php($idEntityFE = $niFE['idEntityFE'])
                                        <span class="line color_{{$fes[$idEntityFE]->idColor}}"
                                              style="top:{{$topLine}}px">
                                            <span class="feLabel color_{{$fes[$idEntityFE]->idColor}}"
                                                  style="top:0">{{$niFE['label']}}</span>
                                        </span>
{{--                                        @php($topLine += 24)--}}
                                    @endforeach
                                @endif
                    </div>
                    @endforeach
                @endif
            @endforeach
        </div>

    </div>
    <div class="annotationSentenceFE">
        <div class="rowWord">
            @foreach($words as $i => $w)
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
                            @if(!is_null($label))
                                @php($idEntityFE = $label['idEntityFE'])
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
                        @endforeach
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
