@php
$items = [
    ['formEdit','Edit'],
];
$id = uniqid($luCandidate->idLUCandidate);
@endphp
<x-objectmenu
    id="luCandidateMenu_{{$id}}"
    :items="$items"
    :path="'luCandidate/' . $luCandidate->idLUCandidate"
></x-objectmenu>
