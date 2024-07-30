<div class="form-field">
    <label for="{{$id}}">{{$label}}</label>
    <div {{$attributes}} id="{{$id}}">
        @foreach($options as $i => $fe)
            <div class="p-1">
                <input type="checkbox" name="{{$id}}[{{$i}}]" value="{{$fe->idFrameElement}}" {{isset($value[$i]) ? 'checked' : ''}}>
                <x-element.fe name="{{$fe->name}}" idColor="{{$fe->idColor}}" type="{{$fe->coreType}}"></x-element.fe>
{{--                <span style="padding-top:7px" class="{{$option['icon']}}"></span>--}}
{{--                <span class="{{$option['color']}}">{{$option['name']}}</span>--}}
            </div>
        @endforeach
    </div>
</div>
