@props([
    'id' => '',
    'label' => '',
    'value' => '',
    'defaultText' => 'Select item'
])
@if($label != '')
    <label for="{{$id}}">{{$label}}</label>
@endif
<div
    x-init="$($el).dropdown()"
    class="ui selection upward dropdown"
    style="overflow:initial;"
>
    <input type="hidden" name="{{$id}}" value="{{$value}}">
    <i class="dropdown icon"></i>
    <div class="default text">{{$defaultText}}</div>
    <div class="menu">
        {{$menu}}
    </div>
</div>
