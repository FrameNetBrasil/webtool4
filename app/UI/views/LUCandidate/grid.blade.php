@php
    use App\Database\Criteria;use Carbon\Carbon;
    $luIcon = view('components.icon.lu')->render();
    $lus = Criteria::byFilterLanguage("view_lucandidate",["name","startswith", $search->lu])
            ->select('idLUCandidate','name','createdAt')
            ->selectRaw("IFNULL(frameName, frameCandidate) as frameName")
            ->orderBy("name")->orderBy("name")->all();
    $data = array_map(fn($item) => [
           'id'=> $item->idLUCandidate,
           'text' => $luIcon . $item->name,
           'frame' => $item->frameName,
           'createdAt' => $item->createdAt ? Carbon::parse($item->createdAt)->format("d/m/Y") : '-',
           'state'=> 'open',
           'type' => 'lu'
    ], $lus);
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
                        $("#luTree").treegrid({
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
                                    width: "30%",
                                },
                                {
                                    field: "frame",
                                    width: "40%",
                                },
                                {
                                    field: "createdAt",
                                    width: "30%",
                                },
                            ]],
                            onClickRow: (row) => {
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
