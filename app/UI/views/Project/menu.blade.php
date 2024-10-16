@php
$items = [
    ['formEdit','Edit'],
];
@endphp
<x-objectmenu
    id="projectMenu"
    :items="$items"
    :path="'project/' . $project->idProject"
></x-objectmenu>
