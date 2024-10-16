@php
    $items = [
        ['formEdit','Edit'],
        ['constraints','Constraints'],
        ['semanticTypes','SemanticTypes'],
    ];
@endphp
<x-objectmenu
    id="luMenu"
    :items="$items"
    :path="'/lu/' . $lu->idLU"
></x-objectmenu>
