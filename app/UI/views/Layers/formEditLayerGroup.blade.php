<x-form
    title="Edit LayerGroup"
    hx-put="/layers/layergroup"
>
    <x-slot:fields>
        <x-hidden-field id="idLayerGroup" value="{{$layerGroup->idLayerGroup}}"></x-hidden-field>
        <div class="field">
            <x-text-field
                label="Name"
                id="name"
                value="{{$layerGroup->name}}"
            ></x-text-field>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
