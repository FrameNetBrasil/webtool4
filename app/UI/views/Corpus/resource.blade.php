<x-layout.resource>
    <x-slot:title>
        Corpus/Document
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New Corpus"
            color="secondary"
            hx-get="/corpus/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
        <x-button
            label="New Document"
            color="secondary"
            hx-get="/document/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:grid>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/corpus/grid"
        ></div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
