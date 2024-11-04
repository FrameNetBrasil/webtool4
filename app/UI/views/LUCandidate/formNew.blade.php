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
            <x-multiline-field
                label="Sense Description"
                id="senseDescription"
                value=""
            ></x-multiline-field>
        </div>
        <div class="field">
            <x-combobox.frame
                id="idFrame"
                label="Suggested frame"
                placeholder="Frame (min: 2 chars)"
                :hasDescription="false"
                style="width:250px"
                class="mb-2"
            ></x-combobox.frame>
        </div>
        <div class="field">
            <x-text-field
                label="OR suggest new frame"
                id="frameCandidate"
                value=""
            ></x-text-field>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-button
            label="Add LU Candidate"
            hx-post="/luCandidate"
        ></x-button>
    </x-slot:buttons>
</x-form>

