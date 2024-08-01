@php
    use App\Database\Criteria;
    $limit = 500;
    $idLanguage = \App\Services\AppService::getCurrentIdLanguage();
    $lemmaIcon = view('components.icon.lemma')->render();
    $lexemeIcon = view('components.icon.lexeme')->render();
    if ($search->lexeme == '') {
        if ($search->lemma == '') {
            $search->lemma = '--none';
        }
        $lemmas = Criteria::byFilter("view_lexicon",[
                ["lemma","startswith", $search->lemma],
                ['idLanguageLM',"=", $idLanguage]
            ])->select("idLemma","lemma")
            ->distinct()
            ->limit($limit)
            ->orderBy("lemma")->get()->keyBy("idLemma")->all();
        $ids = array_keys($lemmas);
        $lexemes = Criteria::byFilter("view_lexicon",[
                ["idLemma","IN", $ids],
                ['idLanguageLX',"=", $idLanguage]
            ])->select("idLexeme","lexeme","idLemma","posLX")
            ->distinct()
            ->limit($limit)
            ->orderBy("lexeme")
            ->get()->groupBy("idLemma")
            ->toArray();
        $data = [];
        foreach($lemmas as $lemma) {
           $children = array_map(fn($item) => [
             'id'=> $item->idLexeme,
             'text' => $lexemeIcon . $item->lexeme . " [{$item->posLX}]",
             'state' => 'closed',
             'type' => 'lexeme'
            ], $lexemes[$lemma->idLemma] ?? []);
            $data[] = [
                'id' => $lemma->idLemma,
                'text' => $lemmaIcon . $lemma->lemma,
                'state' => 'closed',
                'type' => 'lemma',
                'children' => $children
            ];
        }
    } else {
        $lexemes = Criteria::byFilter("view_lexicon",[
                ["lexeme","startswith", $search->lexeme],
                ['idLanguageLX',"=", $idLanguage]
            ])->select('idLexeme','lexeme','lemma',"posLX")
             ->distinct()
            ->limit($limit)
            ->orderBy("lemma")->orderBy("lexeme")->all();
        $data = array_map(fn($item) => [
           'id'=> $item->idLexeme,
           'text' => $lexemeIcon . $item->lemma . ' / ' . $item->lexeme,
           'state' => 'closed',
           'type' => 'document'
        ], $lexemes);
    }
@endphp
<div class="wt-datagrid flex flex-column h-full">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Lemma/Lexeme
        </div>
    </div>
    <div id="lemmaTreeWrapper">
        <ul id="lemmaTree" class="wt-treegrid">
        </ul>
    </div>
</div>
<script>
    $(function() {
        $("#lemmaTree").tree({
            data: {{Js::from($data)}},
            onClick: function(node) {
                if (node.type === 'lemma') {
                    $("#lemmaTree").tree('toggle', node.target);
                }
                if (node.type === 'lexeme') {
                    htmx.ajax("GET", `/lexicon/lexeme/${node.id}`, "#lexiconEditContainer");
                }
            }
        });
    });
</script>
