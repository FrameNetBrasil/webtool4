@php
    use App\Database\Criteria;use App\Repositories\Project;
    $projectIcon = view('components.icon.project')->render();
    $corpusIcon = view('components.icon.corpus')->render();
    $documentIcon = view('components.icon.document')->render();
    // get projects for documents that has videos
    $listProjects = Criteria::table("view_document_image as i")
        ->join("view_project_docs as p","i.idDocument","=","p.idDocument")
        ->where("p.idLanguage",\App\Services\AppService::getCurrentIdLanguage())
        ->where("p.projectName","<>","Default Project")
        ->select("p.projectName")
        ->chunkResult("projectName","projectName");
    // get the documents allowed to this user
    debug($listProjects);
    $taskDocs = Project::getAllowedDocsForUser($listProjects);
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
<div
    class="h-full"
>
    <div class="relative h-full overflow-auto">
        <div id="corpusTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="corpusTree">
                </ul>
                <script>
                    $(function() {
                        $("#corpusTree").treegrid({
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
                                if (row.type === "corpus") {
                                    $("#corpusTree").treegrid("toggle", row.id);
                                }
                                if (row.type === "document") {
                                    window.location = `/annotation/staticBBox/${row.id}`;
                                }
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
