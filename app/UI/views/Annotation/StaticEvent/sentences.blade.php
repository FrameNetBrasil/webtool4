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
                    class="sentence"
                    hx-target="#gridArea"
                    hx-swap="innerHTML"
                >
                    <td
                        style="width:80px"
                    >
                        #{{$sentence->idSentence}}
                    </td>
                    <td>
                        <a
                            href="/annotation/staticEvent/sentence/{{$sentence->idDocumentSentence}}"
                        >
                                {!! $sentence->text !!}
                        </a>
                    </td>
                    <td
                        style="width:120px"
                    >
                            {!! $sentence->imageName !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

