@php
$items = [
    ['formEdit','Edit'],
    ['projects','Projects'],
    ['corpus','Corpus'],
];
@endphp
<x-objectmenu
    id="datasetMenu"
    :items="$items"
    :path="'dataset/' . $dataset->idDataset"
></x-objectmenu>
