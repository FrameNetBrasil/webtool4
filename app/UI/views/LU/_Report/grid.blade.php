@php
    $id = uniqid("luGrid");
@endphp
<div
    class="h-full"
>
    <div class="relative h-full overflow-auto">
        <div id="luTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
            @fragment('search')
                <ul id="{{$id}}">
                </ul>
                <script>
                    $(function() {
                        let luIcon = `{!! view('components.icon.lu')->render() !!} `;
                        $("#{{$id}}").datagrid({
                            url:"/report/lu/data",
                            queryParams: {
                                lu: '{{$search->lu}}'
                            },
                            method:'get',
                            fit: true,
                            showHeader: false,
                            rownumbers: false,
                            showFooter: false,
                            border: false,
                            columns: [[
                                {
                                    field: "name",
                                    width: "100%",
                                    formatter: function(value,row,index){
                                        return luIcon + value + ' [' + row.frameName + ']';
                                    }
                                }
                            ]],
                            onClickRow: (index,row) => {
                                htmx.ajax("GET", `/report/lu/content/${row.idLU}`, "#reportArea");
                            }
                        });
                    });
                </script>
            @endfragment
        </div>
    </div>
</div>

{{--<div class="h-full">--}}
{{--    <div style="position:relative;height:100%;overflow:auto">--}}
{{--        <table class="ui striped small compact table" style="position:absolute;top:0;left:0;bottom:0;right:0">--}}
{{--            <tbody>--}}
{{--            @foreach($lus as $idLU => $lu)--}}
{{--                <tr--}}
{{--                    hx-get="/report/lu/content/{{$idLU}}"--}}
{{--                    class="cursor-pointer name"--}}
{{--                    hx-target="#reportArea"--}}
{{--                    hx-swap="innerHTML"--}}
{{--                >--}}
{{--                    <td>--}}
{{--                        <x-element.lu name="{{$lu['name'][0]}}" frame="{{$lu['frameName']}}"></x-element.lu>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--            @endforeach--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}
{{--</div>--}}
