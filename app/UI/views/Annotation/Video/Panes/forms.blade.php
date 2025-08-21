<div
    id="formsPane"
    x-data="formsComponent({{$idDocument}})"
    @video-update-state.document="onVideoUpdateState"
    @bbox-toggle-tracking.document="onBBoxToggleTracking"
    @bbox-drawn.document="onBBoxDrawn"
    @bbox-update.document="onBBoxUpdate"
>
    @if ($idObject == 0)
        @include("Annotation.Video.Forms.formNewObject")
    @else
        <div
            hx-trigger="load"
            hx-target="#formsPane"
            hx-get="/annotation/video/object"
            hx-vals='{"idObject": {{$idObject}}, "annotationType":"{{$annotationType}}" }'
            hx-swap="innerHTML"
        ></div>
    @endif
</div>
<div
{{--    x-data="boxesComponent('videoContainer_html5_api', {!! Js::from($object) !!})"--}}
    x-data="boxesComponent('videoContainer_html5_api')"
    @disable-drawing.document="onDisableDrawing"
    @enable-drawing.document="onEnableDrawing"
    @bbox-create.document="onBBoxCreate"
    @bbox-created.document="onBBoxCreated"
    @bbox-change-blocked.document="onBBoxChangeBlocked"
    @video-update-state.document="onVideoUpdateState"
    @auto-tracking-start.document="onStartTracking"
    @auto-tracking-stop.document="onStopTracking"
    {{--        @bbox-toggle-tracking.document="onBBoxToggleTracking"--}}
    id="boxesContainer"
    style="position: absolute; top: 0; left: 0; width:852px; height:480px; background-color: transparent"
    hx-swap-oob="true"
>
    <div
        class="bbox" style="display:none"
    >
        <div class="objectId">{{$object->idObject}}</div>
    </div>
</div>
