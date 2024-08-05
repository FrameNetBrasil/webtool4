<div class="form-field field" style="overflow:initial">
{{--    <label for="{{$id}}">{{$label}}</label>--}}
    <div id="{{$id}}_dropdown" class="ui tiny selection dropdown" style="overflow:initial">
        <i class="dropdown icon"></i>
        <div class="default text">{{$label}}</div>
        <div class="menu">
            @foreach($relations as $i => $relation)
                <div class="item">
                <input type="checkbox" name="{{$id}}[{{$i}}]" value="{{$relation->idRelationType}}" checked>
                <span class="color_{{$relation->entry}}">{{$relation->name}}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#{{$id}}_dropdown').dropdown({});
    });
</script>
