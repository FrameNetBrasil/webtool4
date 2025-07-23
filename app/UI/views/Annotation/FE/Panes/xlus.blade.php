<div class="content-container">
    <div class="ui card w-full">
        <div class="content">
            <div class="description">
                <div
                    class="font-semibold"
                >
                    Define span for LU
                </div>
                <hr>
                <div class="flex-container wrap">
                    @foreach($words as $i => $word)
                        <div class="pr-3">
                            <input
                                type="checkbox"
                                class="words"
                                id="words_{{$i}}"
                                name="words[{{$i}}]"
                                value="{{$i}}" {{($i == $idWord) ? 'checked' : ''}}
                                data-startchar="{{$word['startChar']}}"
                                data-endchar="{{$word['endChar']}}"
                            >
                            <span class="">{{$word['word']}}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function getWordData() {
            return JSON.stringify(
                _.map(
                    _.filter(
                        document.querySelectorAll(".words"),
                        (x) => x.checked
                    ),
                    (y) => {
                        return {
                            startChar: y.dataset.startchar,
                            endChar: y.dataset.endchar
                        };
                    }
                )
            );
        }
    </script>

    <h3>Candidate LU for word [{{$words[$idWord]['word']}}]</h3>
    <div class="card-grid dense">
        @foreach($lus as $lu)
        <div
            class="ui card option-card cursor-pointer"
            hx-post="/annotation/fe/create"
            hx-vals='js:{"idDocumentSentence": {{$idDocumentSentence}},"idLU": {{$lu->idLU}}, "wordList": getWordData()}'
            hx-target=".annotation-workarea"
            hx-swap="innerHTML"
        >
            <div class="content overflow-hidden">
                <div class="header">
                    <x-ui::element.frame name="{{$lu->frameName}}"></x-ui::element.frame>
                </div>
                <div class="description">
                    <x-ui::element.lu name="{{$lu->lu}}"></x-ui::element.lu>
                    {{$lu->senseDescription}}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{--<x-datagrid--}}
{{--    id="gridLU"--}}
{{--    title="Candidate LU for word [{{$words[$idWord]['word']}}]"--}}
{{--    type="master"--}}
{{--    height="450px"--}}
{{-->--}}
{{--    @foreach($lus as $lu)--}}
{{--        <tr--}}
{{--            hx-post="/annotation/fe/create"--}}
{{--            hx-vals='js:{"idDocumentSentence": {{$idDocumentSentence}},"idLU": {{$lu->idLU}}, "wordList": getWordData()}'--}}
{{--            hx-target="#workArea"--}}
{{--            hx-swap="innerHTML"--}}
{{--        >--}}
{{--            <td--}}
{{--                class="cursor-pointer"--}}
{{--                style="width:20%"--}}
{{--            >--}}
{{--                {{$lu->frameName}}--}}
{{--            </td>--}}
{{--            <td--}}
{{--                class="cursor-pointer"--}}
{{--                style="width:20%"--}}
{{--            >--}}
{{--                {{$lu->lu}}--}}
{{--            </td>--}}
{{--            <td--}}
{{--                class="cursor-pointer"--}}
{{--                style="width:60%"--}}
{{--            >--}}
{{--                {{$lu->senseDescription}}--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--    @endforeach--}}
{{--</x-datagrid>--}}

