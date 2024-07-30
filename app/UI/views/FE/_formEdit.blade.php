<x-layout.content>
    <x-form id="feFormEdit" title="Edit Frame Element" center="true">
        <x-slot:fields>
            <x-hidden-field
                id="idFrameElement"
                :value="$frameElement->idFrameElement"
            ></x-hidden-field>
            <x-combobox.fe-coreness
                id="coreType"
                label="Coreness"
                :value="$frameElement->coreType"
            ></x-combobox.fe-coreness>
            <x-combobox.color
                id="idColor"
                label="Color"
                :value="$frameElement->idColor"
            ></x-combobox.color>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update FE" hx-put="/fe/{{$frameElement->idFrameElement}}"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
