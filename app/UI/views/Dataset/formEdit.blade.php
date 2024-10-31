<x-form
    title="Dataset"
    hx-post="/dataset"
>
    <x-slot:fields>
        <x-hidden-field
            id="idDataset"
            :value="$dataset->idDataset"
        ></x-hidden-field>
        <div class="field">
            <x-text-field
                label="Name"
                id="name"
                :value="$dataset->name"
            ></x-text-field>
        </div>
        <div class="field">
            <x-multiline-field
                label="Description"
                id="description"
                :value="$dataset->description"
            ></x-multiline-field>
        </div>
{{--        <div class="field">--}}
{{--            <x-combobox.project--}}
{{--                id="idProject"--}}
{{--                label="Source project"--}}
{{--                :value="$dataset->idProject"--}}
{{--            >--}}
{{--            </x-combobox.project>--}}
{{--        </div>--}}
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
