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
            onclick="manager.confirmDelete(`Removing Form '{{$lexicon->form}}'.`, '/lexicon3/form/{{$lexicon->idLexicon}}')"
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
                            <x-combobox.lexicon-group
                                id="idLexiconGroup"
                                label="Group"
                                :value="$lexicon->idLexiconGroup"
                            ></x-combobox.lexicon-group>
                        </div>
                    </div>
                </x-slot:fields>
                <x-slot:buttons>
                    <x-submit label="Update" hx-put="/lexicon3/lexicon"></x-submit>
                </x-slot:buttons>
            </x-form>
        </div>
    </x-slot:main>
</x-layout.object>
