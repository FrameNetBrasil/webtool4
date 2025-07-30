<div
    x-data="videoComponent()"
    @object-selected.document="onObjectSelected"
    @video-seek-frame.document="onVideoSeekFrame"
    @video-toggle-play.document="onVideoTogglePlay"
    style="position:relative; width:852px;height:480px"
>
    <video-js
        id="videoContainer"
        class="video-js"
        src="{!! config('webtool.mediaURL') . "/" . $video->currentURL !!}"
    >
    </video-js>
    <canvas id="canvas" width=0 height=0></canvas>
    <div
        x-data="boxesComponent('videoContainer_html5_api', 852, 480)"
        @object-selected.document="onObjectSelected"
        @disable-drawing.document="onDisableDrawing"
        @enable-drawing.document="onEnableDrawing"
        @bbox-create.document="onBBoxCreate"
        @bbox-drawn.document="onBboxDrawn"
        @video-update-state.document="onVideoUpdateState"
        @change-bbox-blocked.document="onBBoxBlocked"
        @tracking-start.document="onStartTracking"
        @tracking-stop.document="onStopTracking"
        id="boxesContainer"
    >
    </div>
</div>
