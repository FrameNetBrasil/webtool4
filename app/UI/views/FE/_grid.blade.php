<x-datagrid
    id="gridFE"
    title="Frame Elements"
    hx-trigger="reload-gridFE from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/fes/grid"
>
    @foreach($fes as $fe)
        <tr
            hx-target="#editMainArea"
            hx-swap="innerHTML"
            style="table-layout:auto"
        >
            <td class="wt-datagrid-action">
                <div
                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                    title="delete FE"
                    hx-delete="/fe/{{$fe['idFrameElement']}}"
                ></div>
            </td>
            <td
                hx-get="/fe/{{$fe['idFrameElement']}}/edit"
                class="cursor-pointer"
                style="width:1px;white-space:nowrap;"
            >
                <x-element.fe name="{{$fe['name'][0]}}" type="{{$fe['coreType']}}" idColor="{{$fe['idColor']}}"></x-element.fe>
            </td>
            <td
                hx-get="/fe/{{$fe['idFrameElement']}}/edit"
                class="cursor-pointer"
            >
                {{$fe['name'][1]}}
            </td>
        </tr>
    @endforeach
</x-datagrid>
