<div
    id="gridFE"
    class="grid"
    hx-trigger="reload-gridFE from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/fes/grid"
>
    @foreach($fes as $fe)
        <div class="col-3">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete FE"
                            onclick="manager.confirmDelete(`Removing FrameElement '{{$fe->name}}'.`, '/fe/{{$fe->idFrameElement}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        <div
                            hx-target="#editMainArea"
                            hx-swap="innerHTML"
                            hx-get="/fe/{{$fe->idFrameElement}}/edit"
                            class="cursor-pointer"
                        >
                            <x-element.fe
                                name="{{$fe->name}}"
                                type="{{$fe->coreType}}"
                                idColor="{{$fe->idColor}}"
                            ></x-element.fe>
                        </div>
                    </div>
                    <div class="description">
                        {{$fe->description}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
