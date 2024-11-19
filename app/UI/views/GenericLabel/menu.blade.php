@php
$items = [
    ['formEdit','Edit'],
];
@endphp
<x-objectmenu
    id="genericLabelMenu"
    :items="$items"
    :path="'genericlabel/' . $genericLabel->idGenericLabel"
></x-objectmenu>
