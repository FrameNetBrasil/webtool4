@if($label != '')
    <label for="{{$id}}">{{$label}}</label>
@endif
<div id="{{$id}}_search" class="ui very short search">
    <div class="ui left icon small input">
        <input type="hidden" id="{{$id}}" name="{{$idName ?? $id}}" value="{{$value}}">
        <input class="prompt" type="search" placeholder="{{$placeholder}}" value="{{$name}}">
        <i class="search icon" onclick="$('#{{$id}}').val(0);console.log('s');"></i>
    </div>
    <div class="results"></div>
</div>
<script>
    $(function() {
        $('#{{$id}}_search')
            .search({
                apiSettings: {
                    url: "/construction/list/forSelect?q={query}"
                },
                fields: {
                    title: "fullName",
                    description: "{{$description}}"
                },
                maxResults: 20,
                minCharacters: 3,
                onSelect: (result) => {
                    $('#{{$id}}').val(result.idConstruction);
                    {!! $onSelect !!}
                ;}
            })
        ;
    });
</script>
