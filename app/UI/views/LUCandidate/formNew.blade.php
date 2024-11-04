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
        <div class="formgroup-inline">
            <div class="field mr-1">
                <x-combobox.frame
                    id="idFrame"
                    label="Suggested frame"
                    placeholder="Frame (min: 3 chars)"
                    style="width:250px"
                    class="mb-2"
                    :value="$object?->idFrame ?? 0"
                    :name="$object->frame ?? ''"
                    :hasDescription="false"
                    onSelect="htmx.ajax('GET','/luCandidate/fes/' + result.idFrame,'#fes');"
                ></x-combobox.frame>
            </div>
            <div id="fes">
                <div class="field w-20rem mr-1">
                    <x-combobox.fe-frame
                        id="idFrameElement"
                        name="idFrameElement"
                        label="FE"
                        style="width:250px"
                        :value="$object?->idFrameElement ?? 0"
                        :idFrame="$object?->idFrame ?? 0"
                        :hasNull="false"
                    ></x-combobox.fe-frame>
                </div>
            </div>
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

