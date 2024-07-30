<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Wordforms for [{{$lexemeName}}]
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="wordformTable">
            <tbody
            >
            @foreach($wordforms as $idWordform => $wordform)
                <tr>
                    <td>
                        {!! $wordform->form !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

