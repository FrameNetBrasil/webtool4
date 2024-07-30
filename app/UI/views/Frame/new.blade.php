<x-layout.edit>
    <x-slot:title>
        Frame
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="secondary" href="/frame"></x-button>
    </x-slot:actions>
    <x-slot:main>
        <x-card title="New Frame">
        <x-form id="formNew" title="" center="true">
            <x-slot:fields>
                <x-text-field id="nameEn" label="English Name" value=""></x-text-field>
            </x-slot:fields>
            <x-slot:buttons>
                <x-submit label="Add Frame" hx-post="/frame"></x-submit>
            </x-slot:buttons>
        </x-form>
        </x-card>
    </x-slot:main>
</x-layout.edit>
