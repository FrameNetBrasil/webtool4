<div id="lexiconEditWrapper">
    <x-form
        id="lexemeEdit"
        title="Edit Lexeme"
        center="true"
        onsubmit="return false;"
    >
        <x-slot:fields>
            <x-hidden-field id="idLexeme" :value="$lexeme->idLexeme"></x-hidden-field>
            <div class="grid">
                <div class="col">
                    <x-text-field
                        label="Lexeme"
                        id="name"
                        :value="$lexeme->name"
                    ></x-text-field>
                </div>
                <div class="col">
                    <x-combobox.pos
                        id="idPOS"
                        label="POS"
                        :value="$lexeme->idPOS"
                    ></x-combobox.pos>
                </div>
            </div>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update" hx-put="/lexicon/lexeme/{{$lexeme->idLexeme}}"></x-submit>
            <x-button label="Delete" color="negative" onclick="manager.confirmDelete(`Removing Lexeme '{{$lexeme->name}}'.`, '/lexicon/lexeme/{{$lexeme->idLexeme}}')"></x-button>
        </x-slot:buttons>
    </x-form>
    <x-form
        id="wordformAdd"
        title="Add Wordform"
        center="true"
    >
        <x-slot:fields>
            <x-hidden-field id="idLexemeWordform" :value="$lexeme->idLexeme"></x-hidden-field>
            <x-text-field
                label="Wordform"
                id="form"
                value=""
            ></x-text-field>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add" hx-post="/lexicon/wordform/new"></x-submit>
        </x-slot:buttons>
    </x-form>
    <h2>Wordforms</h2>
    @include("Lexicon.wordforms")
</div>
