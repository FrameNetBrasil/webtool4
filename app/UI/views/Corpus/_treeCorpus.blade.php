<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Corpus
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="corpusTable">
            <tbody
            >
            @foreach($corpus as $idCorpus => $corpusObj)
                <tr
                    hx-target="#gridArea"
                    hx-swap="innerHTML"
                >
                    <td
                        hx-post="/corpus/grid"
                        hx-vals='{"idCorpus":{{$idCorpus}}}'
                        class="cursor-pointer"
                    >
                        {{$corpusObj->name}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

