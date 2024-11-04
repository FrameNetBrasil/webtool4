@php
    use App\Database\Criteria;
    $luIcon = view('components.icon.lu')->render();
    $lus = Criteria::byFilterLanguage("view_lucandidate",["name","startswith", $search->lu])
            ->select('idLUCandidate','name')
            ->orderBy("name")->orderBy("name")->all();
    $data = array_map(fn($item) => [
           'id'=> $item->idLUCandidate,
           'text' => $luIcon . $item->name,
           'state'=> 'closed',
           'type' => 'lu'
    ], $lus);
@endphp
<div
        class="h-full"
        hx-trigger="reload-gridLuCandidate from:body"
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
                                    width: "100%",
                                }
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
