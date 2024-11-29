@php
    $id = uniqid("frameGrid");
@endphp
<div
    class="h-full"
>
    <div class="relative h-full overflow-auto">
        <div id="frameTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="{{$id}}">
                </ul>
                <script>
                    $(function() {
                        $("#{{$id}}").datagrid({
                            url:"/report/frame/data",
                            queryParams: {
                                frame: '{{$search->frame}}'
                            },
                            method:'get',
                            fit: true,
                            showHeader: false,
                            rownumbers: false,
                            showFooter: false,
                            border: false,
                            columns: [[
                                {
                                    field: "text",
                                    width: "100%",
                                }
                            ]],
                            onClickRow: (index,row) => {
                                htmx.ajax("GET", `/report/frame/${row.idFrame}`, "#reportArea");
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>
