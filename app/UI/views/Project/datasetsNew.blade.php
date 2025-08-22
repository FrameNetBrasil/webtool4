<x-form id="formNewDataset" title="New Dataset" :center="false" hx-post="/dataset/new">
    <x-slot:fields>
        <x-hidden-field
            id="idProject"
            :value="$idProject"
        ></x-hidden-field>
        <div class="field">
            <x-text-field
                label="Name"
                id="name"
                value=""
            ></x-text-field>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
