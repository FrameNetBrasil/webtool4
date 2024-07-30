@php
    use App\Database\Criteria;use App\Services\AnnotationFEService;
    $corpusIcon = view('components.icon.corpus')->render();
    $documentIcon = view('components.icon.document')->render();
    if ($search->document == '') {
        $corpus = Criteria::byFilterLanguage("view_corpus",["name","startswith", $search->corpus])
            ->orderBy("name")->get()->keyBy("idCorpus")->all();
        $ids = array_keys($corpus);
        $documents = Criteria::byFilterLanguage("view_document",["idCorpus","IN", $ids])
            ->orderBy("name")
            ->get()->groupBy("idCorpus")
            ->toArray();
        $data = [];
        foreach($corpus as $c) {
           $children = array_map(fn($item) => [
             'id'=> $item->idDocument,
             'text' => $documentIcon . $item->name,
             'state' => 'closed',
             'type' => 'document'
            ], $documents[$c->idCorpus] ?? []);
            $data[] = [
                'id' => $c->idCorpus,
                'text' => $corpusIcon . $c->name,
                'state' => 'closed',
                'type' => 'corpus',
                'children' => $children
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
<div
    class="wt-datagrid flex flex-column h-full"
    hx-trigger="reload-gridCorpus from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/corpus/grid"
    >
    <div class="datagrid-header-search flex">
        <div style="padding:4px 0px 4px 4px">
            <x-search-field
                id="corpus"
                placeholder="Search Corpus"
                hx-post="/corpus/grid/search"
                hx-trigger="input changed delay:500ms, search"
                hx-target="#corpusTreeWrapper"
                hx-swap="innerHTML"
            ></x-search-field>
        </div>
        <div style="padding:4px 0px 4px 4px">
            <x-search-field
                id="document"
                placeholder="Search Document"
                hx-post="/corpus/grid/search"
                hx-trigger="input changed delay:500ms, search"
                hx-target="#corpusTreeWrapper"
                hx-swap="innerHTML"
            ></x-search-field>
        </div>
    </div>
    <div id="corpusTreeWrapper">
        @fragment('search')
            <ul id="corpusTree" class="wt-treegrid">
            </ul>
            <script>
                $(function() {
                    $("#corpusTree").tree({
                        data: {{Js::from($data)}},
                        onClick: function(node) {
                            if (node.type === "corpus") {
                                htmx.ajax("GET", `/corpus/${node.id}/edit`, "#editArea");
                            }
                            if (node.type === "document") {
                                htmx.ajax("GET", `/document/${node.id}/edit`, "#editArea");
                            }
                        }
                    });
                });
            </script>
        @endfragment
    </div>
</div>
