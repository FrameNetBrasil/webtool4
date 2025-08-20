<x-form
    title="New Document"
    hx-post="/document/new"
>
    <x-slot:fields>
        <div class="field">
            <x-text-field
                label="Name"
                id="name"
                value=""
            ></x-text-field>
        </div>
        <div class="field">
            <x-combobox::corpus
                id="idCorpus"
                label="Corpus [min 3 chars]"
            >
            </x-combobox::corpus>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
