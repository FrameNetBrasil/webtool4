@php
$items = [
    ['formEdit','Edit'],
//    ['projects','Projects'],
    ['corpus','Corpus'],
];
$id = uniqid("datasetMenu");
@endphp
<x-objectmenu
    :id="$id"
    :items="$items"
    :path="'dataset/' . $dataset->idDataset"
></x-objectmenu>
