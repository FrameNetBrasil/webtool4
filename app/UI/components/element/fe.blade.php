{{--@php--}}
{{--    $icon = $icon ?? config("webtool.fe.icon")[$type]--}}
{{--@endphp--}}
{{--<span style="display:inline-block;padding:0px 4px;" {{$attributes->merge(['class' => 'color_'. $idColor])}}>--}}
{{--    <span class="inline-block"><i class="{{$icon}} icon" style="visibility: visible;font-size:0.875em"></i>{{$name}}</span>--}}
{{--</span>--}}
@php
    $icon = $icon ?? config("webtool.fe.icon")[$type]
@endphp
{{--<span {{$attributes->merge(['class' => 'fe color_'. $idColor])}}>--}}
    <span {{$attributes->merge(['class' => 'fe color_'. $idColor])}} style="display:inline-block;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;padding: 0 2px;">
        <i class="{{$icon}} icon" style="visibility: visible;font-size:0.875em"></i>
        {{$name}}
    </span>
{{--</span>--}}
