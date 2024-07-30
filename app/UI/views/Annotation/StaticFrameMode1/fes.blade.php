@php
    $sentence = trim($sentence->text);
    $words = explode(' ', $sentence);
    $title = "";//($idFrame ? "Frame: " . $frame->name : "No Frame")
@endphp
<x-layout.content>
    <div id="annotationStaticFrameMode1FETabs">
        @if(count($frames) > 0)

            @foreach($frames as $idFrame => $frame)
                @php($idObject = 0)
                <div title="{{$frame['name']}}" data-options="tools:'#tabTools_{{$idFrame}}'">
                    <form>
                        @foreach($objects as $i => $object)

                            @php($phrase = '')
                            @for($w = $object->startWord - 1; $w < $object->endWord; $w++)
                                @php($phrase .= ' '. $words[$w])
                            @endfor
                            @if((count($object->bboxes) > 0) || ($object->name == 'scene'))
                                <x-card class="m-2"
                                        title="Object #{{++$idObject}}: <span class='wt-anno-box-color-{{$idObject}}'>{{$phrase}}</span> ">
                                    @php($value = isset($frame['objects'][$i]) ? $frame['objects'][$i]->idFrameElement : null)
                                    <x-combobox.fe-frame
                                        id="idStaticObjectSentenceMM_{{$idFrame}}_{{$object->idStaticObjectSentenceMM}}"
                                        name="idStaticObjectSentenceMM[{{$idFrame}}][{{$object->idStaticObjectSentenceMM}}]"
                                        label=""
                                        :value="$value"
                                        :idFrame="$idFrame"
                                        :hasNull="true"
                                        style="width:250px"
                                    ></x-combobox.fe-frame>
                                </x-card>
                            @else
                                @php(++$idObject)
                            @endif
                        @endforeach
                        <hr>
                        <div class="ml-2 mb-2">
                            <x-button
                                id="btnSubmitFE{{$i}}"
                                label="Submit FEs"
                                hx-put="/annotation/staticFrameMode1/fes/{{$idStaticSentenceMM}}/{{$idFrame}}"
                            ></x-button>
                        </div>
                    </form>
                </div>
            @endforeach
        @else
            <div title="No Frame">
            </div>
        @endif
        <script>
            $(function() {
                $("#annotationStaticFrameMode1FETabs").tabs({
                    border: true,
                    // fit:true
                    width: "100%"
                });
                @if(isset($idFrame))
                console.log("selecting");
                $("#annotationStaticFrameMode1FETabs").tabs("select", '{{$frames[$idFrame]['name']}}');
                @endif
            });
        </script>
    </div>
    @if(count($frames) > 0)
        @foreach($frames as $idFrame => $frame)
            <div id="tabTools_{{$idFrame}}">
                <a
                    href="javascript:void(0)"
                    title="delete frame"
                    hx-delete="/annotation/staticFrameMode1/fes/{{$idStaticSentenceMM}}/{{$idFrame}}"
                    hx-target="body"
                >
                    <span
                        class="easyui-tooltip material-icons-outlined wt-icon wt-icon-delete"
                    ></span>
                </a>
            </div>
        @endforeach
    @endif

</x-layout.content>
