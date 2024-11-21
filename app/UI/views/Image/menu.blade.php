@php
$items = [
    ['editForm','Edit'],
    ['document','Documents'],
];
@endphp
<x-objectmenu
    id="imageMenu_{{$image->idImage}}"
    :items="$items"
    :path="'image/' . $image->idImage"
></x-objectmenu>
