@php
$items = [
    ['entries','Translations'],
    ['semanticTypes','SemanticTypes'],
];
@endphp
<x-objectmenu
    id="semanticTypeMenu"
    :items="$items"
    :path="'semanticType/' . $semanticType->idSemanticType"
></x-objectmenu>
