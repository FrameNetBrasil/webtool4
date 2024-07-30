<x-layout.content>
    <x-inline-form
        id="formNew"
        title="New Frame Element"
        center="true"
    >
        <x-slot:fields>
            <x-hidden-field
                id="idFrame"
                :value="$idFrame"
            ></x-hidden-field>
            <x-text-field
                id="nameEn"
                label="English Name"
                value=""
            ></x-text-field>
            <x-combobox.fe-coreness
                id="coreType"
                label="Coreness"
            ></x-combobox.fe-coreness>
            <x-combobox.color
                id="idColor"
                label="Color"
                value=""
            ></x-combobox.color>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit
                label="Add FE"
                hx-post="/fe"
            ></x-submit>
        </x-slot:buttons>
    </x-inline-form>
</x-layout.content>
