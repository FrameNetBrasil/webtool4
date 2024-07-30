<div class="w-20rem">
    <div class="form-field field" style="overflow:initial">
        <label for="{{$id}}">{{$label}}</label>
        <div id="{{$id}}_dropdown" class="ui tiny clearable selection dropdown" style="overflow:initial">
            <input type="hidden" name="{{$name}}" value="{{$value}}">
            <i class="dropdown icon"></i>
            <div class="default text">Select FE</div>
            <div class="menu">
                @foreach($options as $fe)
                    <div data-value="{{$fe['idFrameElement']}}"
                         class="item p-1 min-h-0">
                        @if($fe['coreType'] != '')
                            <x-element.fe name="{{$fe['name']}}" type="{{$fe['coreType']}}"
                                          idColor="{{$fe['idColor']}}"></x-element.fe>
                        @else
                            <span>{{$fe['name']}}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('#{{$id}}_dropdown').dropdown();
    });
</script>
