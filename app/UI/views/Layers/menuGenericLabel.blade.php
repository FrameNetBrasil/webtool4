@php
$items = [
    ['formEdit','Edit'],
];
@endphp
<x-objectmenu
    id="genericLabelMenu"
    :items="$items"
    :path="'layers/genericlabel/' . $genericLabel->idGenericLabel"
></x-objectmenu>
