@php
$items = [
    ['formEdit','Edit'],
];
@endphp
<x-objectmenu
    id="luCandidateMenu"
    :items="$items"
    :path="'luCandidate/' . $luCandidate->idLUCandidate"
></x-objectmenu>
