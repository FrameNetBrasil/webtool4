<x-layout.report>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','LU Report']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:search>
        <x-form-search
            id="luSearch"
            hx-post="/report/lu/grid"
            hx-target="#gridArea"
        >
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <x-search-field
                id="lu"
                :value="$search->lu"
                placeholder="Search LU"
                class="w-full"
            ></x-search-field>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div
            id="gridArea"
            class="h-full"
            hx-trigger="load"
            hx-post="/report/lu/grid"
        >
        </div>
    </x-slot:grid>
    <x-slot:pane>
        <div
            id="reportArea"
            class="h-full overflow-y-auto"
            @if(isset($idLU))
                hx-trigger="load"
                hx-get="/report/lu/content/{{$idLU}}"
            @endif
        >
        </div>
        <script>
            let reportLU = {};
        </script>
    </x-slot:pane>
</x-layout.report>
