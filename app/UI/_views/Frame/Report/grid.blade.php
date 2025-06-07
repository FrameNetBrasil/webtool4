@php
    $id = uniqid("frameGrid");
@endphp
<div
    class="wt-box h-full"
>
    @fragment('search')
        <ul id="{{$id}}">
        </ul>
        <script>
            $(function() {
                let frameIcon = `{!! view('components.icon.frame')->render() !!} `;
                $("#{{$id}}").datagrid({
                    url: "/report/frame/data",
                    queryParams: {
                        frame: '{{$search->frame}}'
                    },
                    method: "get",
                    fit: true,
                    showHeader: false,
                    showFooter: false,
                    rownumbers: false,
                    border: false,
                    striped: false,
                    singleSelect: true,
                    columns: [[
                        {
                            field: "name",
                            width: "100%",
                            formatter: function(value, row, index) {
                                return frameIcon + value;
                            }
                        }
                    ]],
                    onClickRow: (index, row) => {
                        htmx.ajax("GET", `/report/frame/content/${row.idFrame}`, "#reportArea");
                        document.getElementById("reportArea").scrollIntoView();
                    }
                });
            });
        </script>
    @endfragment
</div>
