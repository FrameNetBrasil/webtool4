@php
$items = [
    ['formEdit','Edit'],
    ['users','Users'],
];
@endphp
<x-objectmenu
    id="taskMenu__{{$task->idTask}}"
    :items="$items"
    :path="'task/' . $task->idTask"
></x-objectmenu>
