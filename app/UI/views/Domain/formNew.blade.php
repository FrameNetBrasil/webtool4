<x-form id="formNew" title="New Domain" :center="false"  hx-post="/domain/new">
    <x-slot:fields>
        <x-text-field
            label="English Name"
            id="nameEn"
            value=""
        ></x-text-field>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
