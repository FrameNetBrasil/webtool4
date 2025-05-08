<x-form
    title="New Form"
    hx-post="/lexicon3/form/new"
>
    <x-slot:fields>
        <div class="two fields">
            <div class="field">
                <x-text-field
                    label="Form"
                    id="form"
                    value=""
                ></x-text-field>
            </div>
            <div class="field">
                <x-combobox.lexicon-group
                    id="idLexiconGroup"
                    label="Group"
                    :value="0"
                ></x-combobox.lexicon-group>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
