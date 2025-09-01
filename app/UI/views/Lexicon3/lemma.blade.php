<x-layout.index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/structure','Structure'],['/lexicon3','Lexicon'],['', 'Lemma #' . $lemma->idLexicon]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-header">
                <div class="page-header-content">
                    <div class="page-header-main">
                        <div class="page-title-section">
                            <div class="page-title">
                                <span>{{$lemma->fullNameUD}}</span>
                            </div>
                            <div
                                class="page-subtitle">
                                Lemma
                            </div>
                        </div>
                        <div class="page-actions">
                            <div class="ui label wt-tag-id">
                                #{{$lemma->idLexicon}}
                            </div>
                            <button
                                class="ui danger button"
                                x-data
                                @click.prevent="messenger.confirmDelete(`Removing Lemma '{{$lemma->fullNameUD}}'.`, '/lexicon3/lemma/{{$lemma->idLexicon}}')"
                            >Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-content object-page">
                <div class="ui container h-full">

{{--                    <div class="d-flex flex-col items-start h-full">--}}
{{--                        <div class="object-header d-flex">--}}
{{--                            <div class="col-12 sm:col-12 md:col-12 lg:col-7 xl:col-6">--}}
{{--                                <h2 class="ui header">--}}
{{--                                    <span>{{$lemma->fullNameUD}}</span>--}}
{{--                                    <div class="ui label">--}}
{{--                                        Lemma--}}
{{--                                    </div>--}}
{{--                                </h2>--}}
{{--                            </div>--}}
{{--                            <div--}}
{{--                                class="col-12 sm:col-12 md:col-12 lg:col-5 xl:col-6 flex gap-1 flex-wrap align-items-center justify-content-end">--}}
{{--                                <div class="ui label wt-tag-id">--}}
{{--                                    #{{$lemma->idLexicon}}--}}
{{--                                </div>--}}
{{--                                <x-button--}}
{{--                                    label="Delete"--}}
{{--                                    color="danger"--}}
{{--                                    x-data \n--}}
{{--                                    @click.prevent="messenger.confirmDelete(`Removing Lemma '{{$lemma->fullNameUD}}'.`, '/lexicon3/lemma/{{$lemma->idLexicon}}')"--}}
{{--                                ></x-button>--}}
{{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="object-description pl-2">--}}
                    {{--                        </div>--}}
                        <div class="flex flex-grow-1 mt-3">
                            <div
                                id="objectMainArea"
                                class="objectMainArea w-full"
                            >
                                <div id="lexiconEditWrapper">
{{--                                    <x-form--}}
{{--                                        title="Edit"--}}
{{--                                        onsubmit="return false;"--}}
{{--                                    >--}}
{{--                                        <x-slot:fields>--}}
{{--                                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>--}}
{{--                                            <x-hidden-field id="idLexicon"--}}
{{--                                                            :value="$lemma->idLexicon"></x-hidden-field>--}}
{{--                                            <x-hidden-field id="idLexiconGroup" :value="2"></x-hidden-field>--}}
{{--                                            <div class="two fields">--}}
{{--                                                <div class="field">--}}
{{--                                                    <x-text-field--}}
{{--                                                        label="Lemma"--}}
{{--                                                        id="form"--}}
{{--                                                        :value="$lemma->name"--}}
{{--                                                    ></x-text-field>--}}
{{--                                                </div>--}}
{{--                                                <div class="field">--}}
{{--                                                    <x-combobox::ud-pos--}}
{{--                                                        id="idUDPOS"--}}
{{--                                                        label="UDPOS"--}}
{{--                                                        :value="$lemma->idUDPOS"--}}
{{--                                                    ></x-combobox::ud-pos>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </x-slot:fields>--}}
{{--                                        <x-slot:buttons>--}}
{{--                                            <x-submit label="Update" hx-put="/lexicon3/lemma"></x-submit>--}}
{{--                                        </x-slot:buttons>--}}
{{--                                    </x-form>--}}
{{--                                    <div class="ui warning message">--}}
{{--                                        <div class="header">--}}
{{--                                            Warning!--}}
{{--                                        </div>--}}
{{--                                        If lemma is a MWE, each expression can be another lemma or a word.--}}
{{--                                        Choose wisely.--}}
{{--                                    </div>--}}
{{--                                    <x-form--}}
{{--                                        title="Add Expression"--}}
{{--                                    >--}}
{{--                                        <x-slot:fields>--}}
{{--                                            <x-hidden-field id="idLemmaBase"--}}
{{--                                                            :value="$lemma->idLexicon"></x-hidden-field>--}}
{{--                                            <div class="fields">--}}
{{--                                                <div class="field w-8rem">--}}
{{--                                                    <x-combobox::options--}}
{{--                                                        label="Type"--}}
{{--                                                        id="idLexiconGroup"--}}
{{--                                                        :options="[1 => 'word', 2 => 'lemma']"--}}
{{--                                                        value=""--}}
{{--                                                    ></x-combobox::options>--}}
{{--                                                </div>--}}
{{--                                                <div class="field">--}}
{{--                                                    <x-text-field--}}
{{--                                                        label="Form"--}}
{{--                                                        id="form"--}}
{{--                                                        value=""--}}
{{--                                                    ></x-text-field>--}}
{{--                                                </div>--}}
{{--                                                <div class="field">--}}
{{--                                                    <x-combobox::ud-pos--}}
{{--                                                        id="idUDPOSExpression"--}}
{{--                                                        label="UDPOS"--}}
{{--                                                        :value="$lemma->idUDPOS"--}}
{{--                                                    ></x-combobox::ud-pos>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="fields">--}}
{{--                                                <div class="field">--}}
{{--                                                    <x-text-field--}}
{{--                                                        label="Position"--}}
{{--                                                        id="position"--}}
{{--                                                        :value="1"--}}
{{--                                                    ></x-text-field>--}}
{{--                                                </div>--}}
{{--                                                <div class="field">--}}
{{--                                                    <x-checkbox--}}
{{--                                                        id="headWord"--}}
{{--                                                        name="head"--}}
{{--                                                        label="Is Head?"--}}
{{--                                                        :active="true"--}}
{{--                                                    ></x-checkbox>--}}
{{--                                                </div>--}}
{{--                                                <div class="field">--}}
{{--                                                    <x-checkbox--}}
{{--                                                        id="breakBefore"--}}
{{--                                                        label="Break before?"--}}
{{--                                                        :active="false"--}}
{{--                                                    ></x-checkbox>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </x-slot:fields>--}}
{{--                                        <x-slot:buttons>--}}
{{--                                            <x-submit label="Add"--}}
{{--                                                      hx-post="/lexicon3/expression/new"></x-submit>--}}
{{--                                        </x-slot:buttons>--}}
{{--                                    </x-form>--}}
                                    <h3 class="ui header">Expressions</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout.index>
