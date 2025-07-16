@php

    use App\Repositories\Frame;

    if (($value != '') && ($value != 0)) {
        $frame = Frame::byId($value);
        $placeholder = $frame->name;
    } else {
        $placeholder = "Search Frame";
    }
    $description = $hasDescription ? 'description' : '';

@endphp

@if($label != '')
    <label for="{{$id}}">{{$label}}</label>
@endif
<div id="{{$id}}_search" class="ui very short search">
    <div class="ui left icon small input">
        <input type="hidden" id="{{$id}}" name="{{$name ?? $id}}" value="{{$value}}">
        <input class="prompt" type="search" placeholder="{{$placeholder}}">
        <i class="search icon"></i>
    </div>
    <div class="results"></div>
</div>
<script>
    $(function() {
        $('#{{$id}}_search')
            .search({
                apiSettings: {
                    url: "/frame/list/forSelect?q={query}"
                },
                fields: {
                    title: "name",
                    description: "{{$description}}"
                },
                maxResults: 20,
                direction: 'upward',
                minCharacters: 3,
                onSelect: (result) => {
                    $('#{{$id}}').val(result.idFrame);
                    {!! $onSelect !!}
                    ;
                },
                onResultsClose: function() {
                    setTimeout(function() {
                        if ($('#{{$id}}_search').search("get value") == "") {
                            $('#{{$id}}').val(0);
                        }
                    }, 500);
                }
            })
        ;
    });
</script>
