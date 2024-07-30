<x-form id="formNewDocument" title="New Document" :center="false"  hx-post="/document/new">
    <x-slot:fields>
        <x-text-field
            label="Name"
            id="name"
            value=""
        ></x-text-field>
        <x-combobox.corpus
            id="idCorpus"
            label="Corpus [min 3 chars]"
        >
        </x-combobox.corpus>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
