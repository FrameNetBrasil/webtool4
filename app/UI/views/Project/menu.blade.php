@php
$items = [
    ['formEdit','Edit'],
    ['users','Managers'],
];
@endphp
<x-objectmenu
    id="projectMenu"
    :items="$items"
    :path="'project/' . $project->idProject"
></x-objectmenu>
