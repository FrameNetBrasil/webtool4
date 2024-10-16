@php
$items = [
    ['documents','Documents'],
];
@endphp
<x-objectmenu
    id="usertaskMenu"
    :items="$items"
    :path="'usertask/' . $usertask->idUserTask"
></x-objectmenu>
