<div
    x-data="videoComponent()"
    style="position:relative; width:852px;height:480px"
>
    <video-js
        id="videoContainer"
        class="video-js"
        src="{!! config('webtool.mediaURL') . "/" . $video->currentURL !!}"
    >
    </video-js>
    <canvas id="canvas" width=0 height=0></canvas>
    <div id="boxesContainer">
    </div>
</div>
