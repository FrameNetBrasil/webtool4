<div class="form-field field">
    <label for="{{$id}}">{{$label}}</label>
    <div id="{{$id}}_search" class="ui very short search">
        <div class="ui left icon small input">
            <input type="hidden" id="{{$id}}" name="{{$id}}" value="">
            <input class="prompt" type="search" placeholder="">
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
                    url: "/lemma/listForSelect?q={query}"
                },
                fields: {
                    title: "name"
                },
                maxResults: 20,
                minCharacters: 3,
                onSelect: (result) => {
                    $('#{{$id}}').val(result.idLemma);
                }
            })
        ;
    });
</script>