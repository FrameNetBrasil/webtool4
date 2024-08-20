@php
    use App\Database\Criteria;use App\Repositories\Project;
    $projectIcon = view('components.icon.project')->render();
    $corpusIcon = view('components.icon.corpus')->render();
    $documentIcon = view('components.icon.document')->render();
    // get the documents allowed to this user
    $taskDocs = Project::getAllowedDocsForUser([
        'Pedro_pelo_mundo',
    ]);
    $projects = array_map(fn($item) => [
       'id'=> 'p'.$item->idProject,
       'text' => $projectIcon . $item->projectName,
       'state' => 'open',
       'type' => 'project'
    ], $taskDocs ?? []);
    $allowedCorpus = collect($taskDocs)->pluck('idCorpus')->all();
    $allowedDocuments = collect($taskDocs)->pluck('idDocument')->all();
    if ($search->document == '') {
        $corpus = Criteria::byFilterLanguage("view_corpus",["name","startswith", $search->corpus])
            ->whereIn("idCorpus", $allowedCorpus)
            ->orderBy("name")->get()->keyBy("idCorpus")->all();
        $ids = array_keys($corpus);
        $documents = Criteria::byFilterLanguage("view_document",["idCorpus","IN", $ids])
            ->whereIn("idDocument", $allowedDocuments)
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
            ->whereIn("idDocuments", $allowedDocuments)
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
                if (node.type === 'project') {
                    $("#corpusTree").tree('toggle', node.target);
                }
                if (node.type === 'corpus') {
                    $("#corpusTree").tree('toggle', node.target);
                }
                if (node.type === 'document') {
                    window.location = `/annotation/dynamicMode/${node.id}`;
                }
            }
        });
    });
</script>
