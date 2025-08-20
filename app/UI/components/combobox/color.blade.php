@props([
    'id' => '',
    'label' => '',
    'value' => ''
])
@if($label != '')
    <label for="{{$id}}">{{$label}}</label>
@endif
<div
    x-init="$($el).dropdown()"
    class="ui selection dropdown"
    style="overflow:initial;"
>
    <input type="hidden" name="{{$id}}" value="{{$value}}">
    <i class="dropdown icon"></i>
    <div class="default text">Select Color</div>
    <div class="menu">
        @foreach($options as $option)
            <div
                data-value="{{$option['id']}}"
                class="item {{$option['color']}}"
            >
                <div
                    class="{{$option['color']}} cursor-pointer">{{$option['text']}}
                </div>
            </div>
        @endforeach
    </div>
</div>
