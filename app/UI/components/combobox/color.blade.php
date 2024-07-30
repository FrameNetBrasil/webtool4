<div class="grid form-field w-25rem field" style="overflow:initial">
    <div class="col" style="overflow:initial">
        <div class="form-field " style="overflow:initial">
            <label for="{{$id}}">{{$label}}</label>
            <div id="{{$id}}_dropdown" class="ui small selection dropdown" style="overflow:initial">
                <input type="hidden" name="{{$id}}" value="{{$value}}">
                <i class="dropdown icon"></i>
                <div class="default text">{{$defaultText}}</div>
                <div class="menu">
                    @foreach($options as $option)
                        <div data-value="{{$option['id']}}"
                             class="item {{$option['color']}}">{{$option['text']}}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="form-field">
            <label>Color example</label>
            <div id="{{$id}}Sample">FrameElement</div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#{{$id}}_dropdown').dropdown({
            onChange: function (value, text, $choice) {
                $('#{{$id}}Sample').html(`<span class="p-1 color_${value}">FrameElement</span>`)
            }
        })
    });


    {{--    $('#{{$id}}').combobox({--}}
    {{--        valueField: 'id',--}}
    {{--        textField: 'text',--}}
    {{--        data: {{ Js::from($options) }},--}}
    {{--        editable: false,--}}
    {{--        @if($value != '')--}}
    {{--        value: '{{$value}}',--}}
    {{--        @endif--}}
    {{--        formatter: function (row) {--}}
    {{--            return `<span class="${row.color}">${row.text}</span>`;--}}
    {{--        },--}}
    {{--        onSelect(row) {--}}
    {{--            $('#{{$id}}Sample').html(`<span class="${row.color}">FrameElement</span>`);--}}
    {{--        }--}}
    {{--    });--}}
</script>
