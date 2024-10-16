<div class="h-full">
    <div style="position:relative;height:100%;overflow:auto">
        <table class="ui striped small compact table" style="position:absolute;top:0;left:0;bottom:0;right:0">
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
