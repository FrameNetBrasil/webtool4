<x-form id="formEditGroup" title="Group" :center="false" hx-post="/group">
    <x-slot:fields>
        <x-hidden-field
            id="idGroup"
            :value="$group->idGroup"
        ></x-hidden-field>
        <x-text-field
            label="Name"
            id="name"
            :value="$group->name"
        ></x-text-field>
        <x-text-field
            label="Description"
            id="description"
            :value="$group->description"
        ></x-text-field>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save" ></x-submit>
    </x-slot:buttons>
</x-form>
