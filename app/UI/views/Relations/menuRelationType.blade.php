@php
$items = [
    ['entries','Translations'],
    ['formEdit','Edit'],
];
@endphp
<x-objectmenu
    id="relationTypeMenu"
    :items="$items"
    :path="'relations/relationtype/' . $relationType->idRelationType"
></x-objectmenu>
