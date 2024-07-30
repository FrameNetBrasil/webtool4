<x-layout.content>
    <x-inline-form
        id="formNew"
        title="New LU"
        center="true"
    >
        <x-slot:fields>
            <x-hidden-field
                id="idFrame"
                :value="$idFrame"
            ></x-hidden-field>
            <x-combobox.lemma
                id="idLemma"
                label="Lemma [min: 3 chars]"
                value=""
            ></x-combobox.lemma>
            <div class="w-30rem">
            <x-text-field
                label="Sense Description"
                id="senseDescription"
                value=""
            ></x-text-field>
            </div>
            <x-combobox.fe-frame
                id="incorporatedFE"
                label="Incorporated FE"
                :idFrame="$idFrame"
                nullName="No incorporated FE"
                :hasNull="true"
            ></x-combobox.fe-frame>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit
                label="Add LU"
                hx-post="/lu"
            ></x-submit>
        </x-slot:buttons>
    </x-inline-form>
</x-layout.content>
