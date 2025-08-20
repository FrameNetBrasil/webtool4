<div
    id="formsPane"
    x-data="formsComponent({{$idDocument}})"
    @video-update-state.document="onVideoUpdateState"
    @bbox-toggle-tracking.document="onBBoxToggleTracking"
    @bbox-drawn.document="onBBoxDrawn"
    @bbox-update.document="onBBoxUpdate"
>
    @if ($idDynamicObject == 0)
        @include("Annotation.DynamicMode.Forms.formNewObject")
    @else
        <div
            hx-trigger="load"
            hx-target="#formsPane"
            hx-get="/annotation/dynamicMode/object"
            hx-vals='{"idDynamicObject": {{$idDynamicObject}} }'
            hx-swap="innerHTML"
        ></div>
    @endif
</div>
