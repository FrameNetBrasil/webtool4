<div class="d-flex">
    <div style="width:150px">
        <div class="rowNI">
            @foreach($it as $i => $type)
                @if(($type->entry != 'int_normal') && ($type->entry != 'int_apos'))
                    <div class="colNI">
                        <span
                            class="ni"
                            id="ni_{{$i}}"
                            data-type="ni"
                            data-name="{{$type->name}}"
                            data-id="{{$type->idInstantiationType}}"
                            @click="onSelectNI($el)"
                        >{{$type->name}}
                        </span>
                    </div>
                    @foreach(($groupedLayers['nis'] ?? []) as $idInstantiationType => $niFEs)
                        @if($type->idInstantiationType == $idInstantiationType)
                            @foreach($niFEs as $niFE)
                                <div
                                    class="colNILabel"
                                >
                                    <span
                                        class="feLabel color_{{$fes[$niFE->idEntity]->idColor}}"
                                    >{{$niFE->name}}
                                    </span>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

    </div>
    <div class="annotationSentenceFE">
        <div class="rowWord">
            @foreach($words as $i => $w)
                <div class="{!! ($w['word'] != ' ') ? 'colWord' : 'colSpace' !!}">
                    @php
                        $target = $groupedLayers['lty_target'][0][0];
                        $isTarget = ($i >= $target->startWord) && ($i <= $target->endWord);
                        // height = número de linhas para lty_fe
                        $lines = count($groupedLayers['lty_target']);
                        $height = 24;// + ($isTarget ? 0 : ($lines * 30))
                    @endphp
                    <span
                        class="word {{$isTarget ? 'target' : ''}} {{$w['hasFE'] ? 'hasFE' : ''}}"
                        id="word_{{$i}}"
                        data-type="word"
                        data-i="{{$i}}"
                        data-startchar="{{$w['startChar']}}"
                        data-endchar="{{$w['endChar']}}"
                        style="height:{{$height}}px"
                    >{{$w['word']}}
                    </span>
                </div>
            @endforeach
        </div>
        @foreach(($groupedLayers['lty_fe'] ?? []) as $line => $objects)
            @php
             $topLine = $line * 24;
            @endphp
            <div
                class="rowObject"
                style="top:{{$topLine}}px;position:relative;"
            >
                @foreach($objects as $object)
                    @php
                        $left = 10.5 * $object->startChar;
                        $width = 10.5 * ($object->endChar - $object->startChar + 1);
                    @endphp
                    <span
                        class="color_{{$object->idColor}} feLabel"
                        style="left:{{$left}}px;width:{{$width}}px;"
                    >{{$object->name}}</span>
                @endforeach
            </div>
        @endforeach

    </div>
</div>
<hr/>
