<div {{ $attributes->merge(['class' => 'form-field field wt-combotreegrid']) }}>
    <label for="{{$id}}">{{$label}}</label>
    <input {{$attributes}} type="search" id="{{$id}}" name="{{$id}}">
</div>
<script>
    $(function () {
        $('#{{$id}}').combotreegrid({
            width: '100%',
            data: {{ Js::from($list) }},
            idField: 'idSemanticType',
            treeField: 'html',
            textField: 'name',
            showHeader: false,
            columns: [[{
                field: 'html',
                title: 'Name',
                width: '100%',
            }
            ]],
        });
        //$('#{{$id}}').combotreegrid('getPanel').addClass('wt-datagrid-no-lines');
        $('#{{$id}}').combotreegrid('grid').treegrid('getPanel').addClass('wt-combotreegrid-panel');;	// get treegrid object
        // var panel = grid.treegrid('getPanel').addClass('wt-datagrid-no-lines');
        // console.log(panel);
    });
</script>
