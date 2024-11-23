<div class="form">
    <x-form>
        <x-slot:title>
            @if(is_null($object))
                <div class="flex">
                    <div class="title">Current Object: #none</div>
                </div>
            @else
                <div class="flex gap-2">
                    <div class="title">Current Object: #{{$object->idDynamicObject}}</div>
                    <div class="flex h-2rem gap-2">
                        <div class="ui label">
                            Range
                            <div class="detail">{{$object->startFrame}}/{{$object->endFrame}}</div>
                        </div>
                        <div class="ui label tag wt-tag-id">
                            #{{$object->idDynamicObject}}
                        </div>
                    </div>
                </div>
            @endif
        </x-slot:title>
        <x-slot:fields>
            @if(!is_null($object))
            <div class="formgroup-inline">
                <div class="field mr-1">
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
                </div>
                <div id="fes">
                    <div class="field mr-1">
                        <x-combobox.fe-frame
                            id="idFrameElement"
                            name="idFrameElement"
                            label="FE"
                            :value="$object?->idFrameElement ?? 0"
                            :idFrame="$object?->idFrame ?? 0"
                            :hasNull="false"
                        ></x-combobox.fe-frame>
                    </div>
                </div>
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
            </div>
                @endif
        </x-slot:fields>
        <x-slot:buttons>
            @if(is_null(!$object))
            <x-button
                type="button"
                label="Save"
                onclick="annotation.objects.updateObjectAnnotation({idLU: $('#idLU').attr('value'),idFrameElement: $('#idFrameElement').attr('value'),})"
            ></x-button>
            @endif
        </x-slot:buttons>
    </x-form>
</div>
