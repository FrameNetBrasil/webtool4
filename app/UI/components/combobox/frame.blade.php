<div class="form-field field">
    <label for="{{$id}}">{{$label}}</label>
    <div id="{{$id}}_search" class="ui very short search">
        <div class="ui left icon small input">
            <input type="hidden" id="{{$id}}" name="{{$id}}" value="">
            <input class="prompt" type="search" placeholder="{{$placeholder}}">
            <i class="search icon"></i>
        </div>
        <div class="results"></div>
    </div>
</div>
<script>
    $(function() {
        $('#{{$id}}_search')
            .search({
                apiSettings: {
                    url: "/frame/list/forSelect?q={query}"
                },
                fields: {
                    title: "name"
                },
                maxResults: 20,
                minCharacters: 3,
                onSelect: (result) => {
                    $('#{{$id}}').val(result.idFrame);
                }
            })
        ;
    });
</script>

{{--<div {{ $attributes->merge(['class' => 'form-field']) }}>--}}
{{--    <label for="{{$id}}">{{$label}}</label>--}}
{{--    <input {{$attributes}} id="{{$id}}" name="{{$id}}">--}}
{{--</div>--}}
{{--<script>--}}
{{--    $(function () {--}}
{{--        $('#{{$id}}').combobox({--}}
{{--            valueField: 'idFrame',--}}
{{--            textField: 'name',--}}
{{--            mode: 'remote',--}}
{{--            method: 'get',--}}
{{--            @if($value != '')--}}
{{--            value: '{{$value}}',--}}
{{--            @endif--}}
{{--            @if($placeholder != '')--}}
{{--            prompt: '{{$placeholder}}',--}}
{{--            @endif--}}
{{--                @if($onChange != '')--}}
{{--            onChange: {!! $onChange !!},--}}
{{--            @endif--}}
{{--                @if($onSelect != '')--}}
{{--            onSelect: {!! $onSelect !!},--}}
{{--            @endif--}}
{{--            url: "/frame/listForSelect"--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
