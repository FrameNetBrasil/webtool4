@if($placeholder == '')
    <div class="form-field">
        <label for="{{$id}}">{{$label}}</label>
        <input {{$attributes}} id="{{$id}}" name="{{$id}}">
    </div>
@else
    <input {{$attributes}} id="{{$id}}" name="{{$id}}">
@endif
<script>
    $(function() {
        $('#{{$id}}').combobox({
            valueField: "id",
            textField: "text",
            editable: false,
            @if($value != '')
            value: '{{$value}}',
            @endif
            prompt: '{{$placeholder}}',
            data: {{ Js::from($options) }}
        });
    });
</script>
