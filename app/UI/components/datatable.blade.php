@props([
    'title' => '',
    'extraTitle' => null,
    'id' => null,
    'height' => 'auto',
    'zebra' => true,
])

<div class="datatable">
    @if($title != '')
    <div class="header">
        <div class="title">{{$title}}{!! $extraTitle !!}</div>
    </div>
    @endif
    <div class="table {!! $zebra ? 'zebra' : ''!!}">
        <table>
{{--            {{$header}}--}}
            {{$thead}}
            <tbody id="{{$id}}" style="height:{{$height}}">
            {{$slot}}
            </tbody>
        </table>
    </div>
</div>
