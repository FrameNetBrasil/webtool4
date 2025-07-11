<div class="form-field field">
    <label for="{{$id}}">{{$label}}</label>
    <div id="{{$id}}_search" class="ui very short search">
        <div class="ui left icon small input">
            <input type="hidden" id="{{$id}}" name="{{$id}}" value="{{$value}}">
            <input class="prompt" type="search" placeholder="{{$placeholder}}" value="{{$name}}">
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
                    url: "/lexicon3/morpheme/listForSelect?q={query}&idLanguage={{$idLanguage}}"
                },
                fields: {
                    title: "name"
                },
                maxResults: 20,
                minCharacters: 1,
                onSelect: (result) => {
                    $('#{{$id}}').val(result.idLexicon);
                }
            })
        ;
    });
</script>
