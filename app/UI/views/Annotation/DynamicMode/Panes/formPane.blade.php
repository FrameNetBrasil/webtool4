{{--<div id="form" x-data="$store.doStore">--}}
<div class="form">
    {{--    <template x-if="currentObject?.object?.order">--}}
    <x-form id="formObject" title="" center="true">
        <x-slot:fields>
            <div class="field ">
                <div class="flex">
                    @if($order == 0)
                        <div class="field title">Current Object: #none</div>
                    @else
                        <div class="field title">Current Object: #{{$order}}</div>
                        <div class="frame">
                            <span>{{$object->startFrame}}</span>
                            <span>/</span>
                            <span>{{$object->endFrame}}</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex flex-row flex-wrap gap-2">
                <x-combobox.frame
                    id="idFrame"
                    label="Frame"
                    placeholder="Frame (min: 3 chars)"
                    style="width:250px"
                    class="mb-2"
                    :value="$object?->idFrame ?? 0"
                    :name="$object->frame ?? ''"
                    :hasDescription="false"
                    onSelect="htmx.ajax('GET','/annotation/dynamicMode/fes/' + result.idFrame,'#fes');"
                ></x-combobox.frame>
                <div id="fes">
                    <x-combobox.fe-frame
                        id="idFrameElement"
                        name="idFrameElement"
                        label="FE"
                        :value="$object?->idFrameElement ?? 0"
                        :idFrame="$object?->idFrame ?? 0"
                        :hasNull="false"
                    ></x-combobox.fe-frame>
                </div>
                <x-combobox.lu
                    id="idLU"
                    label="LU"
                    placeholder="LU (min: 2 chars)"
                    class="w-23rem mb-2"
                    :value="$object?->idLU"
                    :name="$object?->lu ?? ''"
                ></x-combobox.lu>
            </div>
            <x-button
                type="button"
                label="Save"
                onclick="annotation.objects.updateObject({idLU: $('#idLU').attr('value'),idFrameElement: $('#idFrameElement').attr('value'),})"
            ></x-button>
        </x-slot:fields>
    </x-form>
</div>
