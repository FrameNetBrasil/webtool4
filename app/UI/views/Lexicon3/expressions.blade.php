<div
    id="gridExpressions"
    class="grid"
    hx-trigger="reload-gridExpressions from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/lexicon3/lemma/{{$lemma->idLexicon}}/expressions"
>
    @foreach($expressions as $expression)
        <div class="col-3">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete Expression"
                            x-data \n  @click.prevent="messenger.confirmDelete(`Removing Expression '{{$expression->form}}' from lemma.`, '/lexicon3/expression/{{$expression->idLexiconExpression}}')"
                        ></x-delete>
                    </span>
                    <div
                        hx-get="/lexicon3/expression/{{$expression->idForm}}"
                        hx-target="#editArea"
                        hx-swap="innerHTML"
                        class="header cursor-pointer name"
                    >
                        <x-element.expression :name="$expression->form"></x-element.expression>
                    </div>
                    <div
                        class="meta"
                    >
                        <span>{{$expression->form}}</span>
                        <span>Position: {{$expression->position}}</span>
                        <span>{{$expression->head ? ' head ': ''}}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
