<div
    class="ui card h-full w-full mb-2"
    hx-trigger="reload-gridFE from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/fes/grid"
>
    <div class="flex-grow-1 content bg-white">
        <div
            id="gridFE"
            class="grid"
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
                                <a href="/fe/{{$fe->idFrameElement}}/edit">
{{--                                <div--}}
{{--                                    hx-target="#editMainArea"--}}
{{--                                    hx-swap="innerHTML"--}}
{{--                                    hx-get="/fe/{{$fe->idFrameElement}}/edit"--}}
{{--                                    class="cursor-pointer"--}}
{{--                                >--}}
                                    <x-element.fe
                                        name="{{$fe->name}}"
                                        type="{{$fe->coreType}}"
                                        idColor="{{$fe->idColor}}"
                                    ></x-element.fe>
{{--                                </div>--}}
                                </a>
                            </div>
                            <div class="description">
                                {{$fe->description}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
