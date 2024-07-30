<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Lemmas
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="lemmaTable">
            <tbody
            >
            @foreach($lemmas as $idLemma => $lemma)
                <tr
                    hx-target="#lxwfTableContainer"
                    hx-swap="innerHTML"
                >
                    <td
                        hx-post="/lexicon/grid"
                        hx-vals='{"idLemma":{{$idLemma}}}'
                        class="cursor-pointer"
                    >
                        {{$lemma->name}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

