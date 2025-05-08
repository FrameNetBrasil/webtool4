<x-form
    title="New Lemma"
    hx-post="/lexicon3/lemma/new"
>
    <x-slot:fields>
        <x-hidden-field id="idLexiconGroup" :value="2"></x-hidden-field>
        <div class="two fields">
            <div class="field">
                <x-text-field
                    label="Lemma"
                    id="form"
                    value=""
                ></x-text-field>
            </div>
            <div class="field">
                <x-combobox.ud-pos
                    id="idUDPOS"
                    label="POS"
                    value=""
                ></x-combobox.ud-pos>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
