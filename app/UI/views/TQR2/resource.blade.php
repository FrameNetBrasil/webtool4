<x-layout.resource>
    <x-slot:title>
        TQR2
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New Structure"
            color="secondary"
            hx-get="/tqr2/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:grid>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/tqr2/grid"
        ></div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
