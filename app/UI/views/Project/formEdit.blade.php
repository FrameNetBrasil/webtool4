<x-form id="formEditProject" title="Edit Project" :center="false" hx-post="/project">
    <x-slot:fields>
        <x-hidden-field id="idProject" value="{{$project->idProject}}"></x-hidden-field>
        <x-text-field
            label="Name"
            id="name"
            value="{{$project->name}}"
        ></x-text-field>
        <x-multiline-field
            label="Description"
            id="description"
            value="{{$project->description}}"
        ></x-multiline-field>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
