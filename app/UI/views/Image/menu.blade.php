@php
$items = [
    ['editForm','Edit'],
    ['document','Documents'],
];
@endphp
<x-objectmenu
    id="imageMenu"
    :items="$items"
    :path="'image/' . $image->idImage"
></x-objectmenu>
