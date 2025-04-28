<x-form
    title="New Lemma"
    hx-post="/lexicon3/lemma/new"
>
    <x-slot:fields>
        <div class="two fields">
            <div class="field">
                <x-text-field
                    label="Lemma"
                    id="name"
                    value=""
                ></x-text-field>
            </div>
            <div class="field">
                <x-combobox.udpos
                    id="idUDPOS"
                    label="POS"
                    value=""
                ></x-combobox.udpos>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
