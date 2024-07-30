<div class="w-20rem">
    <div class="form-field field" style="overflow:initial">
        <label for="{{$id}}">Group</label>
        <div id="{{$id}}_dropdown" class="ui tiny selection dropdown" style="overflow:initial">
            <input type="hidden" name="{{$id}}" value="{{$value}}">
            <i class="dropdown icon"></i>
            <div class="default text"></div>
            <div class="menu">
                @foreach($options as $group)
                        <div data-value="{{$group['id']}}"
                             class="item p-1 min-h-0"
                        >
                            {{$group['text']}}
                        </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#{{$id}}_dropdown').dropdown({
            onChange: function(value, text, $choice) {
                $('#{{$id}}').val($(value).data('value'));
            }
        });
    });
</script>

{{--<div class="form-field">--}}
{{--    <label for="{{$id}}">{{$label}}</label>--}}
{{--    <input {{$attributes}} id="{{$id}}" name="{{$id}}">--}}
{{--</div>--}}
{{--<script>--}}
{{--    $(function() {--}}
{{--        $('#{{$id}}').combobox({--}}
{{--            valueField: "idGroup",--}}
{{--            textField: "name",--}}
{{--            mode: "remote",--}}
{{--            method: "get",--}}
{{--            editable:false,--}}
{{--            url: "/group/listForSelect"--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
