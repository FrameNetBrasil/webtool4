@php
    use App\Database\Criteria;
    $limit = 300;
    $idLanguage = \App\Services\AppService::getCurrentIdLanguage();
    $data = [];
    if ($search->wordform == '') {
        if ($search->lemma == '') {
            $search->lemma = '--none';
        }
        $lemmas = Criteria::table("view_lexicon_lemma as lm")
            ->leftJoin("lexicon_expression as le","le.idLexicon","=","lm.idLexicon")
            ->leftJoin("lexicon as lx","le.idExpression","=","lx.idLexicon")
            ->where("lm.idLanguage", $idLanguage)
            ->whereRaw("lm.name LIKE '{$search->lemma}%' collate 'utf8mb4_bin'")
            ->select("lm.idLexicon", "lm.fullNameUD as lemma","le.idExpression","lx.form")
            ->distinct()
            ->limit($limit)
            ->orderBy("lemma")->orderBy("form")->get()->groupBy(["idLexicon","lemma"])->toArray();
        $data = [];
        foreach($lemmas as $idLemma => $lemma) {
            $name = array_key_first($lemma);
            $children = [];
            if ($lemma[$name][0]->idExpression) {
                $children = array_map(fn($item) => [
                    'id'=> 'f'. $item->idExpression,
                    'idLexicon' => $item->idExpression,
                    'text' => $item->form,
                    'state' => 'closed',
                    'type' => 'form',
                    'children' => []
                ], $lemma[$name] ?? []);
            }
            $data[] = [
                'id' => 'l'. $idLemma,
                'idLexicon' => $idLemma,
                'text' => $name,
                'state' => 'closed',
                'type' => 'lemma',
                'children' => $children
            ];
        }
//        debug($data);
    } else {
        $items = Criteria::byFilter("view_lexicon_items", [
            ["form", "startswith", $search->item],
            ['idLanguage', "=", $idLanguage]
        ])->select('idLexicon', 'form')
            ->distinct()
            ->limit($limit)
            ->orderBy("form")->all();
        foreach($items as $item) {
            $data[] = [
                'id' => 'f'.$item->idLexicon,
                'idLexicon' => $item->idLexicon,
                'text' => $item->form,
                'state' => 'closed',
                'type' => 'item',
            ];
        }
    }
    if (empty($data)) {
         $data[] = [
            'id' => 0,
            'text' => "No results",
            'state' => 'closed',
            'type' => 'result',
        ];
    }
    $id = uniqid("lexiconTree");
@endphp
<div
    class="h-full"
    hx-trigger="reload-gridLexicon3 from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-post="/lexicon3/grid"
>
    <div class="relative h-full overflow-auto">
        <div id="lexiconTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="{{$id}}">
                </ul>
                <script>
                    $(function() {
                        $("#{{$id}}").treegrid({
                            data: {{Js::from($data)}},
                            fit: true,
                            showHeader: false,
                            rowNumbers: false,
                            idField: "id",
                            treeField: "text",
                            showFooter: false,
                            border: false,
                            columns: [[
                                {
                                    field: "text",
                                    width: "100%",
                                }
                            ]],
                            onClickRow: (row) => {
                                if (row.type === "lemma") {
                                    htmx.ajax("GET", `/lexicon3/lemma/${row.idLexicon}/content`, "#editArea");
                                }
                                if (row.type === "form") {
                                    htmx.ajax("GET", `/lexicon3/form/${row.idLexicon}/content`, "#editArea");
                                }
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
