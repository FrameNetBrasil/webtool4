<x-layout.browser>
    <x-slot:title>
        Lexicon
    </x-slot:title>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        <x-slot:search>
            <div class="grid">
                <div class="col">
                    <x-form-search
                        id="lexiconSearch"
                        hx-post="/lexicon/grid"
                        hx-target="#gridArea"
                    >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <x-search-field
                            id="lemma"
                            value="{{$search->lemma}}"
                            placeholder="Search Lemma"
                        ></x-search-field>
                        <x-search-field
                            id="lexeme"
                            value="{{$search->lexeme}}"
                            placeholder="Search Lexeme"
                        ></x-search-field>
                        <x-submit
                            label="Search"
                            class="mb-2"
                        ></x-submit>
                    </x-form-search>
                </div>
                <div class="col">
                    <form class="flex gap-2 justify-content-end">
                        <div>
                            <x-text-field
                                label=""
                                id="lemma"
                                value=""
                            >
                            </x-text-field>
                        </div>
                        <div>
                            <x-button
                                label="Add Lemma"
                                color="secondary"
                                hx-post="/lexicon/lemma/new"
                                hx-target="#lexiconEditContainer"
                            ></x-button>
                        </div>
                    </form>
                </div>
            </div>
        </x-slot:search>
        <x-slot:grid>
            <div
                id="gridArea"
                class="h-full"
                hx-trigger="load"
                hx-post="/lexicon/grid"
            >
            </div>
        </x-slot:grid>
    </x-slot:main>
</x-layout.browser>
