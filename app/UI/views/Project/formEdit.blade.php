<x-form
    title="Edit Project"
    hx-post="/project"
>
    <x-slot:fields>
        <x-hidden-field id="idProject" value="{{$project->idProject}}"></x-hidden-field>
        <div class="field">
            <x-text-field
                label="Name"
                id="name"
                value="{{$project->name}}"
            ></x-text-field>
        </div>
        <div class="field">
            <x-multiline-field
                label="Description"
                id="description"
                value="{{$project->description}}"
            ></x-multiline-field>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
