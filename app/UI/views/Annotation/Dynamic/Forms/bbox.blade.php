@if($bbox)
<div
    x-init="storeCurrentBBox({{$data->frameNumber}},{!! Js::from($bbox) !!})"
    class="bbox"
    style="left:{{$bbox->x}}px;top:{{$bbox->y}}px;width:{{$bbox->width}}px;height:{{$bbox->height}}px;border: 4px solid #ffff00;"
>

</div>
@else
    <div x-init="createNewBBoxViaTracking({{$data->frameNumber}})"></div>
@endif
