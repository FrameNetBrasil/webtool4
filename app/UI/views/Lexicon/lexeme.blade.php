<div class="flex flex-column h-full">
    <div>
<x-form
    id="lexemeEdit"
    title="Edit Lexeme"
    center="true"
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
    </x-slot:buttons>
</x-form>
    </div>
    <div>
        @include("Lexicon.wordforms")
    </div>
</div>
