<x-layout.resource>
    <x-slot:title>
        Project/Dataset
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New Project"
            color="secondary"
            hx-get="/project/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
        <x-button
            label="New Dataset"
            color="secondary"
            hx-get="/dataset/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:grid>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/dataset/grid"
        ></div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
