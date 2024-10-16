<x-layout.resource>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Image/Document']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:title>
        Image
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New Image"
            color="secondary"
            hx-get="/image/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:search>
        <x-search-field
            id="name"
            placeholder="Search Image"
            hx-post="/image/grid"
            hx-trigger="input changed delay:500ms, search"
            hx-target="#imageGrid"
            hx-swap="outerHTML"
        ></x-search-field>
    </x-slot:search>
    <x-slot:grid>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/image/grid"
        ></div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
