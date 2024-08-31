<div class="flex flex-row">
    <div class="" style="width:150px">
        <div class="rowNI">
            @foreach($it as $i => $type)
                @if(($type->entry != 'int_normal') && ($type->entry != 'int_apos'))
                    <div class="colNI">
                        @php($height = 24 + (isset($nis[$type->idTypeInstance]) ? (count($nis[$type->idTypeInstance]) * 24) : 0))
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
                                        <span
                                            class="line color_{{$entities[$idEntityFE]->idColor}}"
                                            style="top:{{$topLine}}px"
                                        >
                                                            <span
                                                                class="feLabel color_{{$entities[$idEntityFE]->idColor}}"
                                                                style="top:0px"
                                                            >{{$niFE['label']}}
                                                            </span>
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
            @php($topLine = 21 + ($i * 24))
            <div
            >{{$layerType->name}}
            </div>
        @endforeach
    </div>
    <div
        class="annotationSentence flex flex-column" x-data="asData"
    >
        <div class="rowWord">
            {{--            <template x-for="word,index in asData.words">--}}
            {{--                <div--}}
            {{--                    :class="(word.word == ' ') ? 'colSpace' : 'colWord'"--}}
            {{--                    x-data="{asData.isTarget: (index >= asData.target.startWord) && (index <= asData.target.endWord)}"--}}
            {{--                >--}}
            {{--                    <span--}}
            {{--                        :class="'word ' + (asData.isTarget ? 'target':'')"--}}
            {{--                        :id="'word_' + index"--}}
            {{--                        data-type="word"--}}
            {{--                        :data-i="index"--}}
            {{--                        :data-startchar="word.startChar"--}}
            {{--                        :data-endchar="word.endChar"--}}
            {{--                        style="height:174px"--}}
            {{--                        x-text="(word.word == ' ') ? '&nbsp;' : word.word"--}}
            {{--                    >--}}
            {{--                    </span>--}}
            {{--                </div>--}}
            {{--            </template>--}}
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
        <div class="rowAnnotation" x-data="{layerTypes: asData.layerTypes, spans: asData.spans}">
            <template x-for="word,index in asData.words">
                <div
                    :class="'flex flex-column ' + ((word.word == ' ') ? 'colSpace' : 'colWord')"
                    {{--                    x-data="{labelsAtWord: asData.spans.length, height: 174}"--}}
                >
                    <div
                        style="height:0;overflow-y:hidden"
                        x-text="(word.word == ' ') ? '&nbsp;' : word.word"
                    >
                    </div>
                    <template x-for="layerType in layerTypes">
                        <div class="label">
                            <template x-if="asData.spans[layerType.idLayerType]">
                                <div
                                    x-data="{span: asData.spans[layerType.idLayerType]}"
                                >
                                    <div

                                        :class="'line color_' + span.idColor"
                                    >
                                        {{--                                    <span--}}
                                        {{--                                        :class="'feLabel color_' + span.idColor"--}}
                                        {{--                                        style="top:0"--}}
                                        {{--                                        x-text="span.label"--}}
                                        {{--                                    >--}}
                                        {{--                                    </span>--}}
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{--                    <div--}}
                    {{--                                    class="word"--}}
                    {{--                                    :id="'word_anno_' + index"--}}
                    {{--                                    data-type="word"--}}
                    {{--                                    :style="'height:' + height + 'px'"--}}
                    {{--                                >--}}
                    {{--                                    <template x-for="(span,idLayer) in asData.spans[index]" :key="idLayer">--}}
                    {{--                                            <template x-if="span.idEntity || false">--}}
                    {{--                                            <div--}}
                    {{--                                                :class="'line color_' + asData.entities[span.idEntity].idColor"--}}
                    {{--                                                :style="'top:' + (span.index * 24) + 'px'"--}}
                    {{--                                            >--}}
                    {{--                                            </div>--}}
                    {{--                                            </template>--}}
                    {{--                                    </template>--}}
                    {{--                                </div>--}}
                </div>
            </template>

            {{--            @foreach($words as $i => $word)--}}
            {{--                <div class="{!! ($word['word'] != ' ') ? 'colWord' : 'colSpace' !!}">--}}
            {{--                    @php($isTarget = ($i >= $target->startWord) && ($i <= $target->endWord))--}}
            {{--                    @php($labelsAtWord = ($spans[$i] ?? []))--}}
            {{--                    @php($height = 24 + ($isTarget ? 0 : ((count($labelsAtWord) - 1) * 24)))--}}
            {{--                    <span--}}
            {{--                        class="word"--}}
            {{--                        id="word_{{$i}}"--}}
            {{--                        data-type="word"--}}
            {{--                        data-i="{{$i}}"--}}
            {{--                        data-startchar="{{$word['startChar']}}"--}}
            {{--                        data-endchar="{{$word['endChar']}}"--}}
            {{--                        style="height:{{$height}}px"--}}
            {{--                    ><span style="visibility: hidden">{!! ($word['word'] != ' ') ? $word['word'] : '&nbsp;' !!}</span>--}}
            {{--                                        @foreach($idLayers as $l => $idLayer)--}}
            {{--                            @php($topLine = 0 + ($l * 24))--}}
            {{--                            @php($annotation = $spans[$i][$idLayer])--}}
            {{--                            @if(!is_null($annotation))--}}
            {{--                                @if(isset($annotation['idEntity']))--}}
            {{--                                    @php($idEntity = $annotation['idEntity'])--}}
            {{--                                    <span class="line color_{{$entities[$idEntity]->idColor}}"--}}
            {{--                                          style="top:{{$topLine}}px">--}}
            {{--                                                    @if($annotation['label'])--}}
            {{--                                            <span--}}
            {{--                                                class="feLabel color_{{$entities[$idEntity]->idColor}}"--}}
            {{--                                                style="top:0"--}}
            {{--                                            >{{$annotation['label']}}--}}
            {{--                                                            </span>--}}
            {{--                                        @endif--}}
            {{--                                                    </span>--}}
            {{--                                @endif--}}
            {{--                            @endif--}}
            {{--                        @endforeach--}}
            {{--                    </span>--}}
            {{--                </div>--}}
            {{--            @endforeach--}}
        </div>
    </div>
</div>
