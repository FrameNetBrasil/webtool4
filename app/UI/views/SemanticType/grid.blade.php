<div
        class="h-full"
        hx-trigger="reload-gridSemanticType from:body"
        hx-target="this"
        hx-swap="outerHTML"
        hx-get="/semanticType/grid"
>
    <div class="relative h-full overflow-auto">
        <div id="semanticTypeTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="semanticTypeTree">
                </ul>
                <script>
                    $(function() {
                        $("#semanticTypeTree").treegrid({
                            fit: true,
                            url: "/semanticType/listForTree",
                            queryParams: {{ Js::from(['_token' => $search->_token,'semanticType' => $search->semanticType,'domain' => $search->domain]) }},
                            method:"post",
                            showHeader: false,
                            rownumbers: false,
                            idField: 'id',
                            treeField: 'text',
                            showFooter:false,
                            border: false,
                            columns: [[
                                {
                                    field: "text",
                                    width: "100%",
                                }
                            ]],
                            // onClickRow: (row) => {
                            //     if (row.type === "domain") {
                            //         $("#semanticTypeTree").treegrid("toggle", row.id);
                            //     }
                            //     if (row.type === "semanticType") {
                            //         htmx.ajax("GET", `/semanticType/${row.id}/edit`, "#editArea");
                            //     }
                            // }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
