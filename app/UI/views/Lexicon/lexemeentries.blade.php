<div
    id="gridLexemeEntry"
    class="grid"
    hx-trigger="reload-gridLexemeEntry from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/lexicon/lemma/{{$lemma->idLemma}}/lexemeentries"
>
    @foreach($lexemeentries as $lexemeentry)
        <div class="col-3">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete Lexeme"
                            onclick="manager.confirmDelete(`Removing Lexeme '{{$lexemeentry->lexeme}}' from lemma.`, '/lexicon/lexemeentries/{{$lexemeentry->idLexemeEntry}}')"
                        ></x-delete>
                    </span>
                    <div
                        hx-get="/lexicon/lexeme/{{$lexemeentry->idLexeme}}"
                        hx-target="#lexiconEditContainer"
                        hx-swap="innerHTML"
                        class="header cursor-pointer name"
                    >
                        <x-element.lexeme :name="$lexemeentry->lexeme"></x-element.lexeme>
                    </div>
                    <div
                        class="meta"
                    >
                        <span>Order: {{$lexemeentry->lexemeOrder}}</span>
                        <span>{{$lexemeentry->headWord ? ' headWord ': ''}}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
