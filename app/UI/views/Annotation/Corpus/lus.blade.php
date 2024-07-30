<x-card class="mwe" title="Words for LU">
    <div class="hxRow  hxGutterless">
        @foreach($words as $i => $word)
            <div class="rowMWE hxCol  hxGutterless">
                <div class="colMWE">
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
            </div>
        @endforeach
    </div>
</x-card>

<script>
    function getWordData() {
        return JSON.stringify(
            _.map(
                _.filter(
                    document.querySelectorAll(".words"),
                    (x) => x.checked
                ),
                (y) => {return {
                    startChar:y.dataset.startchar,
                    endChar:y.dataset.endchar
                }}
            )
        );
    }
</script>

<x-datagrid
    id="gridLU"
    title="Candidate LU for word [{{$words[$idWord]['word']}}]"
    type="master"
    height="15rem"
>
    @foreach($lus as $lu)
        <tr
            hx-post="/annotation/corpus/createAnnotationSet"
            hx-vals='js:{"idSentence": {{$idSentence}},"idLU": {{$lu->idLU}}, "wordList": getWordData()}'
            hx-target="#workArea"
            hx-swap="innerHTML"
        >
            <td
                class="cursor-pointer"
                style="width:20%"
            >
                {{$lu->frameName}}
            </td>
            <td
                class="cursor-pointer"
                style="width:20%"
            >
                {{$lu->name}}
            </td>
            <td
                class="cursor-pointer"
                style="width:60%"
            >
                {{$lu->senseDescription}}
            </td>
        </tr>
    @endforeach
</x-datagrid>
