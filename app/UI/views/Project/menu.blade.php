@php
$items = [
    ['formEdit','Edit'],
    ['datasets','Datasets'],
    ['users','Managers'],
];
$id = uniqid("projectMenu")
@endphp
<x-objectmenu
    id="{{$id}}"
    :items="$items"
    :path="'project/' . $project->idProject"
></x-objectmenu>
