<x-layout.edit>
    <x-slot:title>
        LU
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="secondary" href="/frame"></x-button>
    </x-slot:actions>
    <x-slot:main>
        @include('LU.object')
    </x-slot:main>
</x-layout.edit>
