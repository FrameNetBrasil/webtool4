@php
$items = [
    ['entries','Translations'],
];
@endphp
<x-objectmenu
    id="layerTypeMenu"
    :items="$items"
    :path="'layers/layertype/' . $layerType->idLayerType"
></x-objectmenu>
