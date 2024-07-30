<div class="grid h-full">
    <div id="luTableContainer" class="col">
        <div class="wt-datagrid flex flex-column" style="height:100%">
            <div class="table" style="position:relative;height:100%">
                <table id="luTable">
                    <tbody>
                    @foreach($lus as $idLU => $lu)
                        <tr
                            hx-get="/report/lu/content/{{$idLU}}"
                            class="cursor-pointer name"
                            hx-target="#reportArea"
                            hx-swap="innerHTML"
                        >
                            <td>
                                <x-element.lu name="{{$lu['name'][0]}}" frame="{{$lu['frameName']}}"></x-element.lu>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
