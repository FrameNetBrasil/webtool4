<x-layout.edit>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['/lexicon3','Lexicon'],['',$lexicon?->form]]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
                <x-layout.object>
                    <x-slot:name>
                        <span>{{$lexicon->form}}</span>
                        <div class="ui label">
                            {{$lexicon->group->name}}
                        </div>
                    </x-slot:name>
                    <x-slot:detail>
                        <div class="ui label wt-tag-id">
                            #{{$lexicon->idLexicon}}
                        </div>
                        <x-button
                            label="Delete"
                            color="danger"
                            x-data \n  @click.prevent="messenger.confirmDelete(`Removing Form '{{$lexicon->form}}'.`, '/lexicon3/form/{{$lexicon->idLexicon}}')"
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
                                    <x-hidden-field id="idLexicon" :value="$lexicon->idLexicon"></x-hidden-field>
                                    <div class="two fields">
                                        <div class="field">
                                            <x-text-field
                                                label="Form"
                                                id="form"
                                                :value="$lexicon->form"
                                            ></x-text-field>
                                        </div>
                                        <div class="field">
                                            <x-combobox::lexicon-group
                                                id="idLexiconGroup"
                                                label="Group"
                                                :value="$lexicon->idLexiconGroup"
                                            ></x-combobox::lexicon-group>
                                        </div>
                                    </div>
                                </x-slot:fields>
                                <x-slot:buttons>
                                    <x-submit label="Update" hx-put="/lexicon3/lexicon"></x-submit>
                                </x-slot:buttons>
                            </x-form>
                            <x-form
                                title="Add Feature"
                            >
                                <x-slot:fields>
                                    <x-hidden-field id="idLexiconBase" :value="$lexicon->idLexicon"></x-hidden-field>
                                    <div class="fields">
                                        <div class="field">
                                            <x-combobox::ud-feature
                                                id="idUDFeature"
                                                label="UD Feature"
                                                :value="0"
                                            ></x-combobox::ud-feature>
                                        </div>
                                    </div>
                                </x-slot:fields>
                                <x-slot:buttons>
                                    <x-submit label="Add" hx-post="/lexicon3/feature/new"></x-submit>
                                </x-slot:buttons>
                            </x-form>
                            <h3 class="ui header">Features</h3>
                            @include("Lexicon3.features")

                        </div>
                    </x-slot:main>
                </x-layout.object>
            </div>
        </div>
    </x-slot:main>
</x-layout.edit>
