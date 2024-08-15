<div
    id="gridArguments"
    class="grid"
    hx-trigger="reload-gridTQR2Argument from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/tqr2/{{$structure->idQualiaStructure}}/arguments"
>
    @foreach($arguments as $argument)
        <div class="col-4">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete Argument"
                            onclick="manager.confirmDelete(`Removing Argument '{{$argument->type}}'.`, '/tqr2/arguments/{{$argument->idQualiaArgument}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        <x-element.fe
                            :name="$argument->feName"
                            :idColor="$argument->feIdColor"
                            :type="$argument->feCoreType"
                        ></x-element.fe>
                    </div>
                    <div
                        class="meta"
                    >
                        <span>Order: {!! $argument->order !!}</span><br>
                        <span>Type: {!! $types[$argument->type] !!}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
