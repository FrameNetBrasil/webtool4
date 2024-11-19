@php
$items = [
    ['entries','Translations'],
    ['formEdit','Edit'],
];
@endphp
<x-objectmenu
    id="layerTypeMenu"
    :items="$items"
    :path="'layers/layertype/' . $layerType->idLayerType"
></x-objectmenu>
