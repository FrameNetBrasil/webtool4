@php
$color ??= "primary";
$color = $color == "danger" ? "negative" : $color;
@endphp
<button
    {{$attributes->merge(['class' => 'ui medium ' . $color .' button'])}}
    {{$attributes}}
>
    @if(isset($icon))
        <i class="icon material">{{$icon}}</i>
    @endif
    {{$label}}
    {{$slot}}
</button>
