<x-layout.report>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','C5 Report']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:search>
        <x-form-search
            id="conceptSearch"
            hx-post="/report/c5/search"
            hx-target="#gridArea"
        >
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <x-search-field
                id="concept"
                value="{{$search->concept}}"
                placeholder="Search Concept"
                class="w-full"
            ></x-search-field>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div
            id="gridArea"
            class="h-full"
        >
            @include("C5.Report.grid")
        </div>
    </x-slot:grid>
    <x-slot:pane>
        <div
            id="reportArea"
            class="h-full overflow-y-auto"
        >
            @includeWhen(!is_null($idConcept),"C5.Report.report")
        </div>
    </x-slot:pane>
</x-layout.report>
