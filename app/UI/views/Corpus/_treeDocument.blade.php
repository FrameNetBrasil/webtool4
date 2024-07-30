<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">
        <div class="datagrid-title">
            {{$corpusName}}
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="documentTable">
            <tbody
            >
            @foreach($documents as $idDocument => $document)
                <tr
                    hx-target="#gridArea"
                    hx-swap="innerHTML"
                >
                    <td
                        hx-post="/corpus/grid"
                        hx-vals='{"idDocument":{{$idDocument}}}'
                        class="cursor-pointer"
                    >
                        {!! ($display == 'document') ? $document->corpusName . '.'. $document->name : $document->name!!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

