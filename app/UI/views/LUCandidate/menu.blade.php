@php
$items = [
    ['formEdit','Edit'],
];
@endphp
<x-objectmenu
    id="luCandidateMenu_{{$luCandidate->idLUCandidate}}"
    :items="$items"
    :path="'luCandidate/' . $luCandidate->idLUCandidate"
></x-objectmenu>
