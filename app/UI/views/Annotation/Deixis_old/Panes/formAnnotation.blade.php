<div class="form">
    <x-form>
        <x-slot:title>
            @if(is_null($object))
                <div class="flex">
                    <div class="title">Current Object: #none</div>
                </div>
            @else
                <div class="flex gap-2">
                    <div class="title">Current Object</div>
                    <div class="flex h-2rem gap-2">
                        <div class="ui label">
                            <div class="detail">{{$object->nameLayerType}}</div>
                        </div>
                        {{--                        <div class="ui label">--}}
                        {{--                            Range--}}
                        {{--                            <div class="detail">{{$object->startFrame}}/{{$object->endFrame}}</div>--}}
                        {{--                        </div>--}}
                        <div class="ui label wt-tag-id">
                            #{{$object->idDynamicObject}}
                        </div>
                    </div>
                </div>
            @endif
        </x-slot:title>
        <x-slot:fields>
            @if(!is_null($object))
                @if(!is_null($object->idGenericLabel) || ($object->layerGroup == 'Deixis'))
                    <div class="fields">
                        <div class="field  w-15rem mr-2">
                            <x-combobox.gl
                                id="idGenericLabel"
                                name="idGenericLabel"
                                label="Label"
                                :value="$object?->idGenericLabel ?? 0"
                                :idLayerType="$object?->idLayerType ?? 0"
                                :hasNull="false"
                            ></x-combobox.gl>
                        </div>
                        <div class="field mr-2">
                            <label>Start Frame</label>
                            <div
                                class="ui label cursor-pointer"
                                @click.prevent="annotation.video.gotoFrame(Alpine.store('doStore').currentStartFrame)"
                            >
                                <div class="detail" x-text="currentStartFrame"></div>
                            </div>
                        </div>
                        <div
                            class="field mr-2"
                            @click.prevent="annotation.video.gotoFrame(Alpine.store('doStore').currentEndFrame)"
                        >
                            <label>End Frame</label>
                            <div class="ui label cursor-pointer">
                                <div class="detail" x-text="currentEndFrame"></div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(!is_null($object->idAnnotationLU)  || ($object->layerGroup == 'Deixis_lexical'))
                    <div class="fields">
                        {{--                        <div class="field mr-1">--}}
                        {{--                            <x-combobox.frame--}}
                        {{--                                id="idFrame"--}}
                        {{--                                label="Frame"--}}
                        {{--                                placeholder="Frame (min: 3 chars)"--}}
                        {{--                                style="width:250px"--}}
                        {{--                                class="mb-2"--}}
                        {{--                                :value="$object?->idFrame ?? 0"--}}
                        {{--                                :name="$object->frame ?? ''"--}}
                        {{--                                :hasDescription="false"--}}
                        {{--                                onSelect="htmx.ajax('GET','/annotation/dynamicMode/fes/' + result.idFrame,'#fes');"--}}
                        {{--                            ></x-combobox.frame>--}}
                        {{--                        </div>--}}
                        {{--                        <div id="fes" class="field w-15rem mr-1">--}}
                        {{--                            <x-combobox.fe-frame--}}
                        {{--                                id="idFrameElement"--}}
                        {{--                                name="idFrameElement"--}}
                        {{--                                label="FE"--}}
                        {{--                                :value="$object?->idFrameElement ?? 0"--}}
                        {{--                                :idFrame="$object?->idFrame ?? 0"--}}
                        {{--                                :hasNull="false"--}}
                        {{--                            ></x-combobox.fe-frame>--}}
                        {{--                        </div>--}}
                        <div class="field mr-1">
                            <x-combobox.lu
                                id="idLU"
                                label="LU"
                                placeholder="LU (min: 2 chars)"
                                class="w-23rem mb-2"
                                :value="$object?->idLU"
                                :name="$object?->lu ?? ''"
                            ></x-combobox.lu>
                        </div>
                        <div class="field mr-2">
                            <label>Start Frame</label>
                            <div
                                class="ui label cursor-pointer"
                                @click.prevent="annotation.video.gotoFrame(Alpine.store('doStore').currentStartFrame)"
                            >
                                <div class="detail" x-text="currentStartFrame"></div>
                            </div>
                        </div>
                        <div
                            class="field mr-2"
                            @click.prevent="annotation.video.gotoFrame(Alpine.store('doStore').currentEndFrame)"
                        >
                            <label>End Frame</label>
                            <div class="ui label cursor-pointer">
                                <div class="detail" x-text="currentEndFrame"></div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </x-slot:fields>
        <x-slot:buttons>
            @if(!is_null($object))
                @if(!is_null($object->idGenericLabel) || ($object->layerGroup == 'Deixis'))
                    <x-button
                        type="button"
                        label="Save"
                        onclick="annotation.objects.updateObjectAnnotation({idGenericLabel: $('#idGenericLabel').attr('value')})"
                    ></x-button>
                @endif
                @if(!is_null($object->idAnnotationLU)  || ($object->layerGroup == 'Deixis_lexical'))
                    <x-button
                        type="button"
                        label="Save"
                        onclick="annotation.objects.updateObjectAnnotation({idLU: $('#idLU').attr('value'),idFrameElement: $('#idFrameElement').attr('value'),})"
                    ></x-button>
                @endif
                <x-button
                    label="Delete"
                    color="danger"
                    @click.prevent="annotation.objects.deleteObject({{$object->idDynamicObject}})"
                ></x-button>
                <x-button
                    label="Play range"
                    color="secondary"
                    @click.prevent="annotation.video.playByFrameRange({{$object->startFrame}},{{$object->endFrame}},0)"
                ></x-button>
            @endif
        </x-slot:buttons>
    </x-form>
</div>
@if(!is_null($object))
@include("Annotation.Deixis.Panes.controlsPane")
@endif

