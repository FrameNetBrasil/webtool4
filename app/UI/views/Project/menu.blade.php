@php
$items = [
    ['formEdit','Edit'],
    ['datasets','Datasets'],
    ['users','Managers'],
];
@endphp
<x-objectmenu
    id="projectMenu_{{$project->idProject}}"
    :items="$items"
    :path="'project/' . $project->idProject"
></x-objectmenu>
