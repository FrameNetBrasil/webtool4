@php
    use App\Database\Criteria;use App\Services\AnnotationFEService;
    $corpusIcon = view('components.icon.corpus')->render();
    $documentIcon = view('components.icon.document')->render();
    if ($search->document == '') {
        $corpus = Criteria::byFilterLanguage("view_corpus",["name","startswith", $search->corpus])
            ->orderBy("name")->all();
        $data = [];
        foreach($corpus as $c) {
            $documents = array_map(fn($item) => [
                    'id'=> $item->idDocument,
                    'text' => $documentIcon . $item->name,
                    'state' => 'closed',
                    'type' => 'document'
            ], Criteria::byFilterLanguage("view_document",["idCorpus","=", $c->idCorpus])->orderBy("name")->all());
            $data[] = [
                'id' => 'c' . $c->idCorpus,
                'text' => $corpusIcon . $c->name,
                'state' => 'closed',
                'type' => 'corpus',
                'children' => $documents
            ];
        }
    } else {
        $documents = Criteria::byFilterLanguage("view_document",["name","startswith", $search->document])
            ->select('idDocument','name','corpusName')
            ->orderBy("corpusName")->orderBy("name")->all();
        $data = array_map(fn($item) => [
           'id'=> $item->idDocument,
           'text' => $documentIcon . $item->corpusName . ' / ' . $item->name,
           'state' => 'closed',
           'type' => 'document'
        ], $documents);
    }
@endphp
<div class="wt-datagrid flex flex-column h-full">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Corpus/Document
        </div>
    </div>
    <div id="corpusTreeWrapper">
        <ul id="corpusTree" class="wt-treegrid">
        </ul>
    </div>
</div>
<script>
    $(function() {
        $("#corpusTree").tree({
            data: {{Js::from($data)}},
            onClick: function(node) {
                if (node.type === 'corpus') {
                    $("#corpusTree").tree('toggle', node.target);
                }
                if (node.type === 'document') {
                    htmx.ajax("GET",`/annotation/fullText/grid/${node.id}/sentences`,"#sentenceTableContainer");
                }
            }
        });
    });
</script>
