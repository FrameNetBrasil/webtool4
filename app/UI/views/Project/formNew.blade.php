<x-form id="formNewProject" title="New Project" :center="false" hx-post="/project/new">
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
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
