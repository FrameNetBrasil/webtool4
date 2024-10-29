@php
$items = [
    ['formEdit','Edit'],
    ['document','Documents'],
    ['formUpload','Upload'],
];
@endphp
<x-objectmenu
    id="videoMenu"
    :items="$items"
    :path="'video/' . $video->idVideo"
></x-objectmenu>
