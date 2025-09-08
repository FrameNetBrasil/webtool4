@php
    $originalWidth = intval($image->width);
    $originalHeight = intval($image->height);
    $canvasWidth = 860;
    $canvasHeight = 800;
    $scaleWidth = $canvasWidth / $originalWidth;
    $scaleHeight = $canvasHeight / $originalHeight;
    $scale = ($scaleHeight < $scaleWidth) ? $scaleHeight : $scaleWidth;
    $imageWidth = intval($originalWidth * $scale);
    $imageHeight = intval($originalHeight * $scale);
    debug("original width: ". $originalWidth);
    debug("original height: ". $originalHeight);
    debug("canvas width: ". $canvasWidth);
    debug("canvas height: ". $canvasHeight);
    debug("scale width: ". $scaleWidth);
    debug("scale height: ". $scaleHeight);
    debug("scale: ". $scale);
    debug("image width: ". $imageWidth);
    debug("image height: ". $imageHeight);
@endphp
<div class="annotation-contols">

</div>
<div
    style="position:relative;width:{{$canvasWidth}}px;height:{{$canvasHeight}}px;"
>
    <img
        alt="{{$image->name}}"
        width="{{$imageWidth}}"
        height="{{$imageHeight}}"
        id="imageContainer"
        src="{!! config('webtool.mediaURL') . "/" . $image->currentURL !!}"
    >
    </img>
    <canvas
        id="canvas"
        width=0
        height=0
    ></canvas>
    @include("Annotation.Image.Panes.bbox")
</div>
