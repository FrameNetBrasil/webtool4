@php
$items = [
    ['formEdit','Edit'],
];
@endphp
<x-objectmenu
    id="layerGroupMenu"
    :items="$items"
    :path="'layers/layergroup/' . $layerGroup->idLayerGroup"
></x-objectmenu>
