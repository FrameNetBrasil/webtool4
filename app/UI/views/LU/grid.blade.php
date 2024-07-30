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

        {{--        <div class="col-3">--}}
        {{--            <x-card title="">--}}
        {{--                <div--}}
        {{--                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete vertical-align-text-sub cursor-pointer"--}}
        {{--                    title="delete FE"--}}
        {{--                    hx-delete="/lu/{{$lu->idLU}}"--}}
        {{--                ></div>--}}
        {{--                <span--}}
        {{--                    hx-get="/lu/{{$lu->idLU}}/object"--}}
        {{--                    hx-target="#editMainArea"--}}
        {{--                    hx-swap="innerHTML"--}}
        {{--                    class="cursor-pointer"--}}
        {{--                >--}}
        {{--                <spanc class="color_lexicon font-medium">{{$lu->name}}</spanc>--}}
        {{--                {{$lu->senseDescription}}--}}
        {{--                </span>--}}
        {{--            </x-card>--}}
        {{--        </div>--}}

        {{--        <tr--}}
        {{--            hx-target="#editMainArea"--}}
        {{--            hx-swap="innerHTML"--}}
        {{--        >--}}
        {{--            <td class="wt-datagrid-action">--}}
        {{--                <div--}}
        {{--                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"--}}
        {{--                    title="delete LU"--}}
        {{--                    hx-delete="/lu/{{$lu->idLU}}"--}}
        {{--                ></div>--}}
        {{--            </td>--}}
        {{--            <td--}}
        {{--                hx-get="/lu/{{$lu->idLU}}/object"--}}
        {{--                class="cursor-pointer"--}}
        {{--            >--}}
        {{--                <span>{{$lu->name}}</span>--}}
        {{--            </td>--}}
        {{--            <td--}}
        {{--                hx-get="/lu/{{$lu->idLU}}/object"--}}
        {{--                class="cursor-pointer"--}}
        {{--            >--}}
        {{--                {{$lu->senseDescription}}--}}
        {{--            </td>--}}
        {{--        </tr>--}}
    @endforeach
</div>
