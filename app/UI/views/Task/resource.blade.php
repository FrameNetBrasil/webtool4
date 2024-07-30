<x-layout.resource>
    <x-slot:title>
        Task/User
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New Task"
            color="secondary"
            hx-get="/task/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:grid>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/task/grid"
        ></div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
