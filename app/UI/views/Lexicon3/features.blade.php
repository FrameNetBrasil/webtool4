<div
    id="gridFeatures"
    class="grid"
    hx-trigger="reload-gridFeatures from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/lexicon3/feature/{{$lexicon->idLexicon}}"
>
    @foreach($features as $feature)
        <div class="col-3">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete Feature"
                            x-data \n  @click.prevent="messenger.confirmDelete(`Removing Feature '{{$feature->name}}' from form.`, '/lexicon3/feature/{{$feature->idLexicon}}/{{$feature->idUDFeature}}')"
                        ></x-delete>
                    </span>
                    <div
                        hx-get="/lexicon3/feature/{{$feature->idUDFeature}}"
                        hx-target="#editArea"
                        hx-swap="innerHTML"
                        class="header cursor-pointer name"
                    >
                        <x-element::udfeature :name="$feature->name"></x-element::udfeature>
                    </div>
                    <div
                        class="meta"
                    >
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
