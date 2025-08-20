@props([
    'url' => '#',
    'message' => ''
])
@if ($message == '')
<a href="{{$url}}">
    <i
        class="red times icon cursor-pointer"
        {{$attributes}}
    ></i>
</a>
@else
    <i
        class="red times icon cursor-pointer"
        x-data
        @click.prevent="messenger.confirmDelete(`{{$message}}`, '{{$url}}')"
        {{$attributes}}
    ></i>
@endif
