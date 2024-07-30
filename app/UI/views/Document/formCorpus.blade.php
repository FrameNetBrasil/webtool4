<x-form id="formDocumentCorpus" title="Corpus" :center="false" hx-post="/document">
    <x-slot:fields>
        <x-hidden-field
            id="idDocument"
            :value="$document->idDocument"
        ></x-hidden-field>
        <x-combobox.corpus
            id="idCorpus"
            label="Corpus [min 3 chars]"
            :value="$document->idCorpus"
        >
        </x-combobox.corpus>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save" ></x-submit>
    </x-slot:buttons>
</x-form>