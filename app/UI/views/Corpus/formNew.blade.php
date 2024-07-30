<x-form id="formNewCorpus" title="New Corpus" :center="false"  hx-post="/corpus/new">
    <x-slot:fields>
        <x-text-field
            label="Name"
            id="name"
            value=""
        ></x-text-field>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>