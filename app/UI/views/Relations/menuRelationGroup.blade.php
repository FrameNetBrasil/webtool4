@php
$items = [
    ['entries','Translation'],
];
@endphp
<x-objectmenu
    id="relationGroupMenu"
    :items="$items"
    :path="'relations/relationgroup/' . $relationGroup->idRelationGroup"
></x-objectmenu>
