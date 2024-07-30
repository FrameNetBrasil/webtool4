<div class="form-field field">
    <label for="{{$id}}">{{$label}}</label>
    <select {{$attributes}} id="{{$id}}" name="{{$id}}" class="ui small dropdown">
        @foreach($options as $entry => $coreType)
            <option value="{{$entry}}">{{$coreType}}</option>
        @endforeach
    </select>
</div>
{{--<script>--}}
{{--    $('#{{$id}}').combobox({--}}
{{--        width:200,--}}
{{--        valueField: 'id',--}}
{{--        textField: 'name',--}}
{{--        editable: false,--}}
{{--        data: {{ Js::from($options) }},--}}
{{--        @if($value != '')--}}
{{--        value: '{{$value}}',--}}
{{--        @endif--}}
{{--        formatter: function (row) {--}}
{{--            return `<span>${row.name}</span>`;--}}
{{--        },--}}
{{--    });--}}
{{--</script>--}}
