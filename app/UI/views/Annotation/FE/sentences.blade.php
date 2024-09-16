<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Sentences
            @if(isset($document))
                [<x-icon.corpus></x-icon.corpus>{{$document->corpusName}}
                <x-icon.document></x-icon.document>{{$document->name}}]
            @endif
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
                    <td
                        style="width:100px"
                    >
                        #{{$sentence->idDocumentSentence}}
                    </td>
                    @if(isset($sentence->startTime))
                        <td
                            style="width:100px"
                        >
                            <i class="material icon">schedule</i>{{$sentence->startTime}}
                        </td>
                    @endif
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

