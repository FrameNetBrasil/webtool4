<x-layout.browser>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','FE Annotation']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="wt-page h-full w-full">
            <div class="header">
                <h1>FE Annotation</h1>
            </div>
            <div class="body">
                <div class="flex-grow-0 content bg-gray-100 pt-3 pl-3">
                    <x-form-search
                        hx-post="/annotation/fe/grid"
                        hx-target="#gridArea"
                    >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="field">
                            <x-search-field
                                id="corpus"
                                value="{{$search->corpus}}"
                                placeholder="Search Corpus"
                            ></x-search-field>
                        </div>
                        <div class="field">
                            <x-search-field
                                id="document"
                                value="{{$search->document}}"
                                placeholder="Search Document"
                            ></x-search-field>
                        </div>
                        <div class="field">
                            <x-search-field
                                id="idSentence"
                                value="{{$search->idSentence}}"
                                placeholder="Search by ID"
                            ></x-search-field>
                        </div>
                    </x-form-search>
                </div>
                <div class="flex-grow-1 content h-full">
                    <div
                        id="gridArea"
                        class="h-full"
                        hx-trigger="load"
                        hx-post="/annotation/fe/grid"
                    >
                    </div>
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout.browser>
