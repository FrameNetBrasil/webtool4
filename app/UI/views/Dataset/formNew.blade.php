<x-form id="formNewDataset" title="New Dataset" :center="false"  hx-post="/dataset/new">
    <x-slot:fields>
        <x-text-field
            label="Name"
            id="name"
            value=""
        ></x-text-field>
        <x-multiline-field
            label="Description"
            id="description"
            value=""
        ></x-multiline-field>
        <x-combobox.project
            id="idProject"
            label="Source project"
            value="0"
        >
        </x-combobox.project>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
