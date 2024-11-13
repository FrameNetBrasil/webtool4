<div
    class="h-full"
    hx-trigger="reload-gridGenericLabel from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/genricLabel/grid"
>
    <div class="relative h-full overflow-auto">
        <div id="genericLabelTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="genericLabelTree">
                </ul>
                <script>
                    $(function() {
                        $("#genericLabelTree").treegrid({
                            url:"/genericlabel/data",
                            queryParams: {
                                genericLabel: '{{$search->genericLabel}}'
                            },
                            method:'get',
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
                                if (row.type === "genericLabel") {
                                    htmx.ajax("GET", `/genericlabel/${row.idGenericLabel}/edit`, "#editArea");
                                }
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
