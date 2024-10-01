<x-layout.resource>
    <x-slot:title>
        Video/Document
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New Video"
            color="secondary"
            hx-get="/video/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:grid>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/video/grid"
        ></div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
