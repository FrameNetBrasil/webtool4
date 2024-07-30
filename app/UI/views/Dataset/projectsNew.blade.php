<x-form id="formNewDatasetProject" title="Add to Project" :center="false"  hx-post="/dataset/{{$idDataset}}/projects/new">
    <x-slot:fields>
        <x-hidden-field id="idDataset" value="{{$idDataset}}"></x-hidden-field>
        <x-combobox.project
            id="idProject"
            label="Associated project"
            value="0"
        >
        </x-combobox.project>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Add"></x-submit>
    </x-slot:buttons>
</x-form>
