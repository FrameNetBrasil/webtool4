<x-layout.resource>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Generic Label']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:title>
        Generic Label
    </x-slot:title>
    <x-slot:actions>
        <x-button
            label="New Generic Label"
            color="secondary"
            hx-get="/genericlabel/new"
            hx-target="#editArea"
            hx-swap="innerHTML"
        ></x-button>
    </x-slot:actions>
    <x-slot:search>
        <x-form-search
            hx-post="/genriclabel/grid"
            hx-target="#genericLabelTreeWrapper"
            hx-swap="innerHTML"
        >
            <div class="field">
                <x-search-field
                    id="genericLabel"
                    placeholder="Search Generic Label"
                    hx-trigger="input changed delay:500ms, search"
                ></x-search-field>
            </div>
            <div class="field">
                <x-combobox.language
                    id="idLanguageSearch"
                    value=""
                    placeholder="Language"
                    hx-trigger="changed delay:500ms, search"
                ></x-combobox.language>
            </div>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div
            id="gridArea"
            class="h-full"
        >
            @include("GenericLabel.grid")
        </div>
    </x-slot:grid>
    <x-slot:edit>
        <div id="editArea">

        </div>
    </x-slot:edit>
</x-layout.resource>
