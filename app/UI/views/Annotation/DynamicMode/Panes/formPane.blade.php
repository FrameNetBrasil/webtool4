<div id="form" x-data="$store.doStore">
    {{--    <template x-if="currentObject?.object?.order">--}}
    <x-form id="formObject" title="" center="true">
        <x-slot:fields>
            <div class="field ">
                <template x-if="currentObject?.object?.order">
                    <span x-text="'Current Object: #' + currentObject.object.order"></span>
                </template>
                <template x-if="!currentObject?.object?.order">
                    <span class="field" x-text="'Current Object: #none'"></span>
                </template>
            </div>
            <div class="flex flex-row flex-wrap gap-2">
                <x-combobox.frame
                    id="idFrame"
                    label="Frame"
                    placeholder="Frame (min: 3 chars)"
                    style="width:250px"
                    class="mb-2"
                    onSelect="htmx.ajax('GET','/annotation/dynamicMode/fes/' + result.idFrame,'#fes');"
                ></x-combobox.frame>
                <div id="fes">
                    <x-combobox.fe-frame
                        id="idFrameElement"
                        name="idFrameElement"
                        label="FE"
                        value=""
                        :idFrame="0"
                        :hasNull="false"
                    ></x-combobox.fe-frame>
                </div>
                <x-combobox.lu
                    id="idLU"
                    label="LU"
                    placeholder="LU (min: 2 chars)"
                    class="w-23rem mb-2"
                ></x-combobox.lu>
            </div>
            <x-button label="Save" @click="updateObject({idLU: $('#idLUTest').attr('value')})"></x-button>
        </x-slot:fields>
    </x-form>
</div>
