<x-layout.edit>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['/lexicon3','Lexicon'],['',$lemma?->fullNameUD]]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
                <x-layout.object>
                    <x-slot:name>
                        <span>{{$lemma->fullNameUD}}</span>
                        <div class="ui label">
                            Lemma
                        </div>
                    </x-slot:name>
                    <x-slot:detail>
                        <div class="ui label wt-tag-id">
                            #{{$lemma->idLexicon}}
                        </div>
                        <x-button
                            label="Delete"
                            color="danger"
                            onclick="manager.confirmDelete(`Removing Lemma '{{$lemma->fullNameUD}}'.`, '/lexicon3/lemma/{{$lemma->idLexicon}}')"
                        ></x-button>
                    </x-slot:detail>
                    <x-slot:description>

                    </x-slot:description>
                    <x-slot:main>
                        <div id="lexiconEditWrapper">
                            <x-form
                                title="Edit"
                                onsubmit="return false;"
                            >
                                <x-slot:fields>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <x-hidden-field id="idLexicon" :value="$lemma->idLexicon"></x-hidden-field>
                                    <x-hidden-field id="idLexiconGroup" :value="2"></x-hidden-field>
                                    <div class="two fields">
                                        <div class="field">
                                            <x-text-field
                                                label="Lemma"
                                                id="form"
                                                :value="$lemma->name"
                                            ></x-text-field>
                                        </div>
                                        <div class="field">
                                            <x-combobox.ud-pos
                                                id="idUDPOS"
                                                label="UDPOS"
                                                :value="$lemma->idUDPOS"
                                            ></x-combobox.ud-pos>
                                        </div>
                                    </div>
                                </x-slot:fields>
                                <x-slot:buttons>
                                    <x-submit label="Update" hx-put="/lexicon3/lemma"></x-submit>
                                </x-slot:buttons>
                            </x-form>
                            <div class="ui warning message">
                                <div class="header">
                                    Warning!
                                </div>
                                If lemma is a MWE, each expression can be another lemma or a word. Choose wisely.
                            </div>
                            <x-form
                                title="Add Expression"
                            >
                                <x-slot:fields>
                                    <x-hidden-field id="idLemmaBase" :value="$lemma->idLexicon"></x-hidden-field>
                                    <div class="fields">
                                        <div class="field w-8rem">
                                            <x-combobox.options
                                                label="Type"
                                                id="idLexiconGroup"
                                                :options="[1 => 'word', 2 => 'lemma']"
                                                value=""
                                            ></x-combobox.options>
                                        </div>
                                        <div class="field">
                                            <x-text-field
                                                label="Form"
                                                id="form"
                                                value=""
                                            ></x-text-field>
                                        </div>
                                        <div class="field">
                                            <x-combobox.ud-pos
                                                id="idUDPOSExpression"
                                                label="UDPOS"
                                                :value="$lemma->idUDPOS"
                                            ></x-combobox.ud-pos>
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field">
                                            <x-text-field
                                                label="Position"
                                                id="position"
                                                :value="1"
                                            ></x-text-field>
                                        </div>
                                        <div class="field">
                                            <x-checkbox
                                                id="headWord"
                                                name="head"
                                                label="Is Head?"
                                                :active="true"
                                            ></x-checkbox>
                                        </div>
                                        <div class="field">
                                            <x-checkbox
                                                id="breakBefore"
                                                label="Break before?"
                                                :active="false"
                                            ></x-checkbox>
                                        </div>
                                    </div>
                                </x-slot:fields>
                                <x-slot:buttons>
                                    <x-submit label="Add" hx-post="/lexicon3/expression/new"></x-submit>
                                </x-slot:buttons>
                            </x-form>
                            <h3 class="ui header">Expressions</h3>
                            @include("Lexicon3.expressions")
                        </div>
                    </x-slot:main>
                </x-layout.object>
            </div>
        </div>
    </x-slot:main>
</x-layout.edit>
