<a href="{{$href}}" id="{{$id}}" {{$attributes}}>{{$label}}</a>
<script>
    $(function () {
        $('#{{$id}}').linkbutton({
            plain: {{$plain}},
            @if($icon != '')
            iconCls: "material-icons wt-button-icon wt-icon-{{$icon}}"
            @endif
        });
    });
</script>
