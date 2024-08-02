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
                            title="delete LexemeEntry"
                            onclick="manager.confirmDelete(`Removing LexemeEntry '{{$lexemeentry->lexeme}}' from lemma.`, '/lexicon/lexemeentries/{{$lexemeentry->idLexemeEntry}}')"
                        ></x-delete>
                    </span>
                            <div
                                class="header"
                            >
                                <div
                                    class="cursor-pointer"
                                >
                                    {{$lexemeentry->lexeme}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
