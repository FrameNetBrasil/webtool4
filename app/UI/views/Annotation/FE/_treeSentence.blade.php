<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Sentence
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="sentenceTable">
            <tbody
            >
            @foreach($sentences as $idSentence => $sentence)
                <tr
                    hx-target="#gridArea"
                    hx-swap="innerHTML"
                >
                    <td>
                        <a
                            href="/annotation/fe/sentence/{{$idSentence}}"
                        >
                            <div class="sentence">
                                {!! $sentence->text !!}
                            </div>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

