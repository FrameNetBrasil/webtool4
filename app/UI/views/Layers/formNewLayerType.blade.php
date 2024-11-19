<x-form
    title="New LayerType"
    hx-post="/layers/layertype/new"
>
    <x-slot:fields>
        <div class="field">
            <x-text-field
                label="English Name"
                id="name"
                value=""
            ></x-text-field>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
