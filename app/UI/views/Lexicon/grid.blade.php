@php
    use App\Database\Criteria;
    $limit = 300;
    $idLanguage = \App\Services\AppService::getCurrentIdLanguage();
    $data = [];
    if ($search->lexeme == '') {
        if ($search->lemma == '') {
            $search->lemma = '--none';
        }
        $lemmas = Criteria::byFilter("view_lexicon", [
            ["lemma", "startswith", $search->lemma],
            ['idLanguageLM', "=", $idLanguage]
        ])->select("idLemma", "lemma","idLexeme","lexeme","posLX")
            ->distinct()
            ->limit($limit)
            ->orderBy("lemma")->get()->groupBy(["idLemma","lemma"])->toArray();
        $data = [];
        foreach($lemmas as $idLemma => $lemma) {
           $name = array_key_first($lemma);
           $children = array_map(fn($item) => [
             'id'=> $item->idLexeme,
             'text' => $item->lexeme . " [{$item->posLX}]",
             'state' => 'closed',
             'type' => 'lexeme',
             'children' => []
            ], $lemma[$name] ?? []);
            $data[] = [
                'id' => $idLemma,
                'text' => $name,
                'state' => 'closed',
                'type' => 'lemma',
                'children' => $children
            ];
        }
    } else {
        $lexemes = Criteria::byFilter("view_lexicon", [
            ["lexeme", "startswith", $search->lexeme],
            ['idLanguageLX', "=", $idLanguage]
        ])->select('idLexeme', 'lexeme', 'lemma', "posLX")
            ->distinct()
            ->limit($limit)
            ->orderBy("lemma")->orderBy("lexeme")->all();
        foreach($lexemes as $lexeme) {
            $data[] = [
                'id' => $lexeme->idLexeme,
                'text' => $lexeme->lexeme . " [{$lexeme->posLX}]",
                'state' => 'closed',
                'type' => 'lexeme',
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
@endphp
<div
        class="h-full"
        hx-trigger="reload-gridLexicon from:body"
        hx-target="this"
        hx-swap="innerHTML"
        hx-post="/lexicon/grid"
>
    <div class="relative h-full overflow-auto">
        <div id="lexiconTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="lexiconTree">
                </ul>
                <script>
                    $(function() {
                        $("#lexiconTree").treegrid({
                            data: {{Js::from($data)}},
                            fit: true,
                            showHeader: false,
                            rownumbers: false,
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
                                    htmx.ajax("GET", `/lexicon/lemma/${row.id}/content`, "#editArea");
                                }
                                if (row.type === "lexeme") {
                                    htmx.ajax("GET", `/lexicon/lexeme/${row.id}/content`, "#editArea");
                                }
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
