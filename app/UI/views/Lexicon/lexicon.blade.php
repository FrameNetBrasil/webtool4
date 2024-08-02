@php
    use App\Database\Criteria;
    $lemmaData = [];
    $lexemeData = [];
    $limit = 300;
    $idLanguage = \App\Services\AppService::getCurrentIdLanguage();
//    $lemmaIcon = view('components.icon.lemma')->render();
//    $lexemeIcon = view('components.icon.lexeme')->render();
    if ($search->lexeme == '') {
        if ($search->lemma == '') {
            $search->lemma = '--none';
        }
        $lemmaData = Criteria::byFilter("view_lemma", [
            ["name", "startswith", $search->lemma],
            ['idLanguage', "=", $idLanguage]
        ])->select("idLemma", "name")
            ->distinct()
            ->limit($limit)
            ->orderBy("name")->get()->keyBy("idLemma")->all();
//        $ids = array_keys($lemmas);
//        $lexemes = Criteria::byFilter("view_lexicon", [
//            ["idLemma", "IN", $ids],
//            ['idLanguageLX', "=", $idLanguage]
//        ])->select("idLexeme", "lexeme", "idLemma", "posLX")
//            ->distinct()
//            ->limit($limit)
//            ->orderBy("lexeme")
//            ->get()->groupBy("idLemma")
//            ->toArray();
//        foreach ($lemmas as $lemma) {
//            $children = array_map(fn($item) => [
//                'id' => 'x' . $item->idLexeme,
//                'idLexeme' => $item->idLexeme,
//                'text' => $lexemeIcon . $item->lexeme . " [{$item->posLX}]",
//                'state' => 'closed',
//                'type' => 'lexeme',
//                'children' => null
//            ], $lexemes[$lemma->idLemma] ?? []);
//            $data[] = [
//                'id' => 'l' . $lemma->idLemma,
//                'idLemma' => $lemma->idLemma,
//                'text' => $lemmaIcon . $lemma->name,
//                'state' => 'closed',
//                'type' => 'lemma',
//                'children' => $children
//            ];
//        }
    } else {
        $lexemes = Criteria::byFilter("view_lexicon", [
            ["lexeme", "startswith", $search->lexeme],
            ['idLanguageLX', "=", $idLanguage]
        ])->select('idLexeme', 'lexeme', 'lemma', "posLX")
            ->distinct()
            ->limit($limit)
            ->orderBy("lemma")->orderBy("lexeme")->all();
        $lexemeData = array_map(fn($item) => (object)[
            'idLexeme' => $item->idLexeme,
            'name' => $item->lemma . ' / ' . $item->lexeme,
        ], $lexemes);
//        $data = array_map(fn($item) => [
//            'id' => 'x' . $item->idLexeme,
//            'idLexeme' => $item->idLexeme,
//            'text' => $lexemeIcon . $item->lemma . ' / ' . $item->lexeme,
//            'state' => 'closed',
//            'type' => 'document',
//            'children' => null
//        ], $lexemes);
    }

@endphp
<div
    class="wt-datagrid flex flex-column"
    style="height:100%"
    hx-trigger="reload-gridLexicon from:body"
    hx-target="#gridArea"
    hx-swap="innerHTML"
    hx-post="/lexicon/grid"
>
    <div class="datagrid-header">
        <div class="datagrid-title">
            {!! (count($lexemeData) > 0) ? 'Lemma/Lexeme' : 'Lemma' !!}
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="lexiconTable">
            <tbody>
            @if(!empty($lemmaData))
                @foreach($lemmaData as $lemma)
                    <tr>
                        <td
                            hx-get="/lexicon/lemma/{{$lemma->idLemma}}"
                            hx-target="#lexiconEditContainer"
                            hx-swap="innerHTML"
                            class="cursor-pointer name"
                        >
                            <x-element.lemma :name="$lemma->name"></x-element.lemma>
                        </td>
                    </tr>
                @endforeach
            @endif
            @if(!empty($lexemeData))
                @foreach($lexemeData as $lexeme)
                    <tr>
                        <td
                            hx-get="/lexicon/lexeme/{{$lexeme->idLexeme}}"
                            hx-target="#lexiconEditContainer"
                            hx-swap="innerHTML"
                            class="cursor-pointer name"
                        >
                            <x-element.lexeme :name="$lexeme->name"></x-element.lexeme>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>


{{--<div class="wt-datagrid flex flex-column h-full">--}}
{{--    <div class="datagrid-header">--}}
{{--        <div class="datagrid-title">--}}
{{--            Lemma/Lexeme--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div id="lemmaTreeWrapper">--}}
{{--        <ul id="lemmaTree" class="wt-treegrid">--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<script>--}}
{{--    $(function() {--}}
{{--        $("#lemmaTree").tree({--}}
{{--            --}}{{--data: {{Js::from($data)}},--}}
{{--            url: "/lexicon/list/forTree",--}}
{{--            method: 'get',--}}
{{--            queryParams: { lemma: '{{$search->lemma}}', lexeme: '{{$search->lexeme}}' },--}}
{{--            onClick: function(node) {--}}
{{--                if (node.type === 'lemma') {--}}
{{--                    $("#lemmaTree").tree('toggle', node.target);--}}
{{--                    htmx.ajax("GET", `/lexicon/lemma/${node.idLemma}`, "#lexiconEditContainer");--}}
{{--                }--}}
{{--                if (node.type === 'lexeme') {--}}
{{--                    htmx.ajax("GET", `/lexicon/lexeme/${node.idLexeme}`, "#lexiconEditContainer");--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--        document.body.addEventListener("refreshTree", function(evt) {--}}
{{--            console.log('refreshTree');--}}
{{--            $("#lemmaTree").tree('reload');--}}
{{--        })--}}
{{--    });--}}
{{--</script>--}}
