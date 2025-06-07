<div {{$attributes->class(["wt-datagrid"])}} >
    @if($title != '')
    <div class="header">
        <div class="title">{{$title}}{!! $extraTitle !!}</div>
    </div>
    @endif
    <div class="table">
        <table>
            {{$header}}
            {{$thead}}
            <tbody id="{{$id}}" style="height:{{$height}}">
            {{$slot}}
            </tbody>
        </table>
    </div>
</div>
