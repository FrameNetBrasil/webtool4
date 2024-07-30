<x-form id="formEdit" title="User" :center="false" hx-post="/dataset">
    <x-slot:fields>
        <x-hidden-field
            id="idDataset"
            :value="$dataset->idDataset"
        ></x-hidden-field>
        <x-text-field
            label="Name"
            id="name"
            :value="$dataset->name"
        ></x-text-field>
        <x-multiline-field
            label="Description"
            id="description"
            :value="$dataset->description"
        ></x-multiline-field>
        <x-combobox.project
            id="idProject"
            label="Source project"
            :value="$dataset->idProject"
        >
        </x-combobox.project>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save" ></x-submit>
    </x-slot:buttons>
</x-form>
