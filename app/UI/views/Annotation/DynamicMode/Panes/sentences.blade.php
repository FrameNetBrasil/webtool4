{{--<div class="wt-datagrid flex flex-column" style="height:100%">--}}
{{--    <div class="table" style="position:relative;height:100%">--}}
        <table id="sentenceTable"  class="ui compact striped table">
            <thead> <tr>
            <th>idSentence</th>
            <th>startTime</th>
            <th>endTime</th>
                <th colspan="4">play</th>
            <th>text</th>
            </tr>
            </thead>
            <tbody
            >
            @foreach($sentences as $idSentence => $sentence)
                <tr>
                    <td>
                        #{{$sentence->idDocumentSentence}}
                    </td>
                    <td>{{$sentence->startTime}}</td>
                    <td>{{$sentence->endTime}}</td>
                    <td><i class="icon material text-lg">play_arrow</i></td>
                    <td><i class="icon material text-lg">timer</i></td>
                    <td><i class="icon material text-lg">timer_3_alt_1</i></td>
                    <td><i class="icon material text-lg">timer_5</i></td>
                    <td>
                            {!! $sentence->text !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
{{--    </div>--}}
{{--</div>--}}

