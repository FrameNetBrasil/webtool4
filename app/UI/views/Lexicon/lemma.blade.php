<div id="lexiconEditWrapper">
    <x-form
        id="lemmaEdit"
        title="Edit Lemma"
        center="true"
        onsubmit="return false;"
    >
        <x-slot:fields>
            <x-hidden-field id="idLemma" :value="$lemma->idLemma"></x-hidden-field>
            <div class="grid">
                <div class="col">
                    <x-text-field
                        label="Lemma"
                        id="name"
                        :value="$lemma->name"
                    ></x-text-field>
                </div>
                <div class="col">
                    <x-combobox.pos
                        id="idPOS"
                        label="POS"
                        :value="$lemma->idPOS"
                    ></x-combobox.pos>
                </div>
            </div>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Update" hx-put="/lexicon/lemma/{{$lemma->idLemma}}"></x-submit>
            <x-button label="Delete" color="negative" onclick="manager.confirmDelete(`Removing Lemma '{{$lemma->name}}'.`, '/lexicon/lemma/{{$lemma->idLemma}}')"></x-button>
        </x-slot:buttons>
    </x-form>
    <x-form
        id="lexemeentryFormAdd"
        title="Add Lexeme"
        center="true"
    >
        <x-slot:fields>
            <x-hidden-field id="idLemmaEntry" :value="$lemma->idLemma"></x-hidden-field>
            <x-text-field
                label="Lexeme"
                id="lexeme"
                value=""
            ></x-text-field>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add" hx-post="/lexicon/lexemeentry/new"></x-submit>
        </x-slot:buttons>
    </x-form>
    <h2>Lexemes</h2>
    @include("Lexicon.lexemeentries")
</div>
