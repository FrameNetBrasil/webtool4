<div
    x-data="boxComponent('videoContainer_html5_api')"
    @drawing-disable.document="onDisableDrawing"
    @drawing-enable.document="onEnableDrawing"
    @bbox-create.document="onBBoxCreate"
    @bbox-created.document="onBBoxCreated"
    @bbox-change-blocked.document="onBBoxChangeBlocked"
    @video-update-state.document="onVideoUpdateState"
    @auto-tracking-start.document="onStartTracking"
    @auto-tracking-stop.document="onStopTracking"
    @object-loaded.document="onObjectLoaded"
    id="boxesContainer"
    style="position: absolute; top: 0; left: 0; width:852px; height:480px; background-color: transparent"
    hx-swap-oob="true"
>
    <div
        class="bbox" style="display:none"
    >
        <div class="objectId" x-text="idObject"></div>
    </div>
</div>
