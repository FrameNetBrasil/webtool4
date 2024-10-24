<x-layout.resource>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','SemanticType']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:title>
        SemanticType
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New SemanticType"
            color="secondary"
            hx-get="/semanticType/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:search>
        <x-form-search>
            <div class="field">
                <x-search-field
                    id="semanticType"
                    placeholder="Search SemanticType"
                    hx-post="/semanticType/grid"
                    hx-trigger="input changed delay:500ms, search"
                    hx-target="#semanticTypeTreeWrapper"
                    hx-swap="innerHTML"
                ></x-search-field>
            </div>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-post="/semanticType/grid"
        ></div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
