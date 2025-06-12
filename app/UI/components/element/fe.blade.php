@php
    $icon = config("webtool.fe.icon")[$type]
@endphp
<span {{$attributes->merge(['class' => 'fe color_'. $idColor])}}>
    <span class="inline-block"><i class="{{$icon}} icon" style="visibility: visible;font-size:0.875em"></i>{{$name}}</span>
</span>
