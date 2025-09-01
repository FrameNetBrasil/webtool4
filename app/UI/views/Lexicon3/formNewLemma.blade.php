<x-layout.index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/structure','Structure'],['/lexicon3','Lexicon'],['','New Lemma']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content h-full">
                <div class="ui container h-full">
                        <x-form
                            title="New Lemma"
                            hx-post="/lexicon3/lemma/new"
                        >
                            <x-slot:fields>
                                <x-hidden-field id="idLexiconGroup" :value="2"></x-hidden-field>
                                <div class="two fields">
                                    <div class="field">
                                        <x-text-field
                                            label="Lemma"
                                            id="form"
                                            value=""
                                        ></x-text-field>
                                    </div>
                                    <div class="field">
                                        <x-combobox::ud-pos
                                            id="idUDPOS"
                                            label="POS"
                                            value=""
                                        ></x-combobox::ud-pos>
                                    </div>
                                </div>
                            </x-slot:fields>
                            <x-slot:buttons>
                                <x-submit label="Save"></x-submit>
                            </x-slot:buttons>
                        </x-form>
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout.index>

