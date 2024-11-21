@php
//    use App\Database\Criteria;use Carbon\Carbon;
//    $luIcon = view('components.icon.lu')->render();
//    $lus = Criteria::byFilterLanguage("view_lucandidate",["name","startswith", $search->lu])
//            ->select('idLUCandidate','name','createdAt')
//            ->selectRaw("IFNULL(frameName, frameCandidate) as frameName")
//            ->orderBy("name")->orderBy("name")->all();
//    $data = array_map(fn($item) => [
//           'id'=> $item->idLUCandidate,
//           'text' => $luIcon . $item->name,
//           'frame' => $item->frameName,
//           'createdAt' => $item->createdAt ? Carbon::parse($item->createdAt)->format("d/m/Y") : '-',
//           'state'=> 'open',
//           'type' => 'lu'
//    ], $lus);
@endphp
<div
    class="h-full"
    hx-trigger="reload-gridLUCandidate from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/luCandidate/grid"
>
    <div class="relative h-full overflow-auto">
        <div id="luTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="luTree">
                </ul>
                <script>
                    $(function() {
                        $("#luTree").datagrid({
                            url:"/luCandidate/data",
                            method:"get",
                            fit: true,
                            singleSelect:true,
                            showHeader: true,
                            rownumbers: false,
                            idField: "id",
                            treeField: "text",
                            showFooter: false,
                            border: false,
                            columns: [[
                                {
                                    field: "name",
                                    width: "30%",
                                    title:"LU candidate",
                                    sortable: true,
                                },
                                {
                                    field: "frameName",
                                    width: "40%",
                                    title:"Suggested frame",
                                    sortable: true,
                                },
                                {
                                    field: "createdAt",
                                    width: "30%",
                                    title: "Created At",
                                    sortable: true,
                                },
                            ]],
                            onClickRow: (index, row) => {
                                if (row.type === "lu") {
                                    htmx.ajax("GET", `/luCandidate/${row.id}/edit`, "#editArea");
                                }
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
