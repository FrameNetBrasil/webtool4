<div
    id="gridLU"
    class="grid"
    hx-trigger="reload-gridLU from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/lus/grid"
>
    @foreach($lus as $lu)
        <div class="col-3">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete LU"
                            onclick="manager.confirmDelete(`Removing LU '{{$lu->name}}'.`, '/lu/{{$lu->idLU}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        <div
                            hx-target="#editMainArea"
                            hx-swap="innerHTML"
                            hx-get="/lu/{{$lu->idLU}}/object"
                            class="cursor-pointer"
                        >
                            <x-element.lu name="{{$lu->name}}"></x-element.lu>
                        </div>
                    </div>
                    <div class="description">
                        {{$lu->senseDescription}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
