<x-form
    title="New LU candidate"
>
    <x-slot:fields>
        <div class="field">
            <x-combobox.lemma
                id="idLemma"
                label="Lemma [min: 3 chars]"
                value=""
            ></x-combobox.lemma>
        </div>
        <div class="field">
            <x-text-field
                label="Sense Description"
                id="senseDescription"
                value=""
            ></x-text-field>
        </div>
        <div class="field">
            <x-combobox.frame
                id="idFrame"
                label="Suggested frame"
                placeholder="Frame (min: 2 chars)"
                style="width:250px"
                class="mb-2"
            ></x-combobox.frame>
        </div>
        <div class="field">
            <x-combobox.frame
                id="idFrame"
                label="Suggested frame"
                placeholder="Frame (min: 2 chars)"
                style="width:250px"
                class="mb-2"
            ></x-combobox.frame>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-button
            label="Add LU"
            hx-post="/luCandidate"
        ></x-button>
    </x-slot:buttons>
</x-form>

