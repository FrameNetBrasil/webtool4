@props([
    'url' => '#',
])
<a href="{{$url}}">
    <i
        class="red times icon cursor-pointer"
        {{$attributes}}
    ></i>
</a>
