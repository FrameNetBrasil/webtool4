@php
$items = [
    ['entries','Translations'],
    ['subTypes','SubTypes'],
];
@endphp
<x-objectmenu
    id="semanticTypeMenu"
    :items="$items"
    :path="'semanticType/' . $semanticType->idSemanticType"
></x-objectmenu>
