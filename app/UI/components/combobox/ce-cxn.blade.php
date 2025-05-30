@if($label!='')
    <label for="{{$id}}">{{$label}}</label>
@endif
<div id="{{$id}}_dropdown" class="ui clearable selection dropdown frameElement" style="overflow:initial;">
    <input type="hidden" id="{{$id}}" name="{{$name}}" value="{{$value}}">
    <i class="dropdown icon"></i>
    <div class="default text">{{$defaultText}}</div>
    <div class="menu">
        @foreach($options as $ce)
            <div data-value="{{$ce['idConstructionElement']}}"
                 class="item p-1 min-h-0">
                 <span>{{$ce['name']}}</span>
            </div>
        @endforeach
    </div>
</div>
<script>
    $(function() {
        $('#{{$id}}_dropdown').dropdown({
            @if($onChange)
            onChange: (value) => {
                {!! $onChange !!}
            }
            @endif
        });
    });
</script>
