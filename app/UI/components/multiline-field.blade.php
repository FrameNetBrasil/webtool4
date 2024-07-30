<div class="form-field field">
    <label for="{{$id}}">{{$label}}</label>
        <textarea
            id="{{$id}}"
            name="{{$id}}"
            placeholder="{{$placeholder}}"
            class="w-full"
            rows="{{$rows}}"
        >{{$value}}
        </textarea>
{{--    <hx-textarea-control>--}}
{{--        <textarea--}}
{{--            id="{{$id}}"--}}
{{--            name="{{$id}}"--}}
{{--            type="text"--}}
{{--            class="h-{{$rows}}rem"--}}
{{--        >{!! $value !!}</textarea>--}}
{{--        <label--}}
{{--            for="{{$id}}">--}}
{{--            {{$label}}--}}
{{--        </label>--}}
{{--    </hx-textarea-control>--}}
</div>
