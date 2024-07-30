<div
    id="gridFEInternalRelation"
    class="grid"
    hx-trigger="reload-gridFEInternalRelation from:body"
    hx-target="this" hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/feRelations/grid"
>
    @foreach($relations as $relation)
        <div class="col-6">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete FE Relation"
                            onclick="manager.confirmDelete(`Removing FE Relation '{{$relation->name}}'.`, '/relation/feinternal/{{$relation->idEntityRelation}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        <span class="color_{{$relation->relationType}}">{{$relation->name}}</span>
                    </div>
                    <x-element.fe
                        name="{{$relation->feName}}"
                        type="{{$relation->feCoreType}}"
                        idColor="{{$relation->feIdColor}}"
                    ></x-element.fe>
                    <x-element.fe
                        name="{{$relation->relatedFEName}}"
                        type="{{$relation->relatedFECoreType}}"
                        idColor="{{$relation->relatedFEIdColor}}"
                    ></x-element.fe>
                </div>
            </div>
        </div>
    @endforeach
</div>
