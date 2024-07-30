<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Lexemes for [{{$lemmaName}}]
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="lexemeTable">
            <thead>
            <td>Name</td>
            <td>POS</td>
            <td>Order</td>
            <td>Head?</td>
            </thead>
            <tbody
            >
            @foreach($lexemes as $idLexeme => $lexeme)
                <tr
                    hx-target="#gridArea"
                    hx-swap="innerHTML"
                >
                    <td
                        hx-post="/lexicon/grid"
                        hx-vals='{"idLexeme":{{$idLexeme}}}'
                        class="cursor-pointer"
                    >
                        {{$lexeme->name}}
                    </td>
                    <td>{{$lexeme->POS}}</td>
                    <td>{{$lexeme->lexemeOrder}}</td>
                    <td>{{$lexeme->headWord ? 'yes' : 'no'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

