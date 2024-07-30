<div
    id="gridFrameRelation"
    class="grid"
    hx-trigger="reload-gridFrameRelation from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/relations/grid"
>
    @foreach($relations as $relation)
        <div class="col-3">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete Relation"
                            onclick="manager.confirmDelete(`Removing Relation '{{$relation->name}} {{$relation->related}}'.`, '/relation/frame/{{$relation->idEntityRelation}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        <div
                            hx-target="#editMainArea"
                            hx-swap="innerHTML"
                            hx-get="/fe/relations/{{$relation->idEntityRelation}}"
                            class="cursor-pointer"
                        >
                            <span class="color_{{$relation->relationType}}">{{$relation->name}}</span>
                        </div>
                    </div>
                    <div class="description">
                        <a
                            href="/frame/{{$relation->idFrameRelated}}"
                        >
                            <x-element.frame name="{{$relation->related}}"></x-element.frame>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @endforeach
</div>
