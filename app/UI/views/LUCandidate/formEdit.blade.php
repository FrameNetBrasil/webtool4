<x-form
>
    <x-slot:fields>
        <x-hidden-field id="idLUCandidate" :value="$luCandidate->idLUCandidate"></x-hidden-field>
        <x-hidden-field id="idLemma" :value="$luCandidate->idLemma"></x-hidden-field>
        <div class="field">
            <x-multiline-field
                label="Sense Description"
                id="senseDescription"
                :value="$luCandidate->senseDescription"
            ></x-multiline-field>
        </div>
        <div class="field">
            <x-combobox.frame
                id="idFrame"
                label="Suggested frame"
                :value="$luCandidate->idFrame"
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
                :value="$luCandidate->frameCandidate"
            ></x-text-field>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-button
            label="Update LU Candidate"
            hx-put="/luCandidate"
        ></x-button>
        @if($isManager)
        <x-button
            label="Create LU"
            hx-post="/luCandidate/createLU"
            color="secondary"
        ></x-button>
        @endif
    </x-slot:buttons>
</x-form>

