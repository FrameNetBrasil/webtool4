@php
    use App\Database\Criteria;
    $projectIcon = view('components.icon.corpus')->render();
    $datasetIcon = view('components.icon.document')->render();
    if ($search->dataset == '') {
        $project = strtolower($search->project);
        $projects = Criteria::table("project")
            ->whereRaw("lower(name) like '{$project}%'")
            ->orderBy("name")->get()->keyBy("idProject")->all();
        $ids = array_keys($projects);
        $datasets = Criteria::table("dataset")
            ->join("project_dataset","dataset.idDataset","=","project_dataset.idDataset")
            ->join("project","project_dataset.idProject","=","project.idProject")
            ->select('project.idProject', 'dataset.idDataset', 'dataset.name')
            ->whereIn("project.idProject", $ids)
            ->orderBy('project.idProject')
            ->orderBy('dataset.name')
            ->get()->groupBy("idProject")
            ->toArray();
        $data = [];
        foreach($projects as $p) {
           $children = array_map(fn($item) => [
             'id'=> $item->idDataset,
             'text' => $datasetIcon . $item->name,
             'state' => 'closed',
             'type' => 'dataset',
             'children' => []
            ], $datasets[$p->idProject] ?? []);
            $data[] = [
                'id' => $p->idProject,
                'text' => $projectIcon . $p->name,
                'state' => 'closed',
                'type' => 'project',
                'children' => $children
            ];
        }
    } else {
        $dataset = strtolower($search->dataset);
        $datasets = Criteria::table("dataset")
            ->whereRaw("lower(name) like '{$dataset}%'")
            ->select('idDataset','name')
            ->orderBy("name")->all();
        $data = array_map(fn($item) => [
           'id'=> $item->idDataset,
           'text' => $datasetIcon . $item->name,
           'state' => 'closed',
           'type' => 'document'
        ], $datasets);
    }
    $id = uniqid("projectTree");
@endphp
<div
        class="h-full"
        hx-trigger="reload-gridProject from:body"
        hx-target="this"
        hx-swap="outerHTML"
        hx-get="/project/grid"
>
    <div class="relative h-full overflow-auto">
        <div id="projectTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="{{$id}}">
                </ul>
                <script>
                    $(function() {
                        $("#{{$id}}").treegrid({
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
                                    width: "100%"
                                }
                            ]],
                            onClickRow: (row) => {
                                if (row.type === "project") {
                                    htmx.ajax("GET", `/project/${row.id}/edit`, "#editArea");
                                }
                                if (row.type === "dataset") {
                                    htmx.ajax("GET", `/dataset/${row.id}/edit`, "#editArea");
                                }
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
