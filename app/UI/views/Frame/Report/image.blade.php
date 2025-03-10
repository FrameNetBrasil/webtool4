@php
debug($bboxes);
debug($fes);
        $imageWidth = $canvasWidth = intval($image->width);
        $imageHeight = $canvasHeight = intval($image->height);

//        $originalWidth = intval($image->width);
//        $originalHeight = intval($image->height);
//            debug($originalWidth,$originalHeight);
//        $canvasWidthBBox = 860;
//        $canvasHeightBBox = 700;
//        $scaleWidth = $canvasWidthBBox / $originalWidth;
//        $scaleHeight = $canvasHeightBBox / $originalHeight;
//        debug($scaleWidth,$scaleHeight);
//        $scaleExpand = ($scaleHeight < $scaleWidth) ? $scaleHeight : $scaleWidth;
//
//
//        $canvasWidth = 860;
//        $canvasHeight = 500;
//        $scaleWidth = $canvasWidth / $originalWidth;
//        $scaleHeight = $canvasHeight / $originalHeight;
//        $scaleReduce = ($scaleHeight < $scaleWidth) ? $scaleHeight : $scaleWidth;
//        $imageWidth = intval($originalWidth * $scaleReduce);
//        $imageHeight = intval($originalHeight * $scaleReduce);
//    //
//    //    //debug($scale, $originalWidth,$originalHeight,$scaleWidth ,$scaleHeight,$imageWidth,$imageHeight );
//    //
//        $visualBBoxes = [];
//        foreach($bboxes as $bbox) {
//            $bboxWidth = intval($bbox->width);
//            $bboxHeight = intval($bbox->height);
//            $bboxX = intval($bbox->x / $scaleExpand);
//            $bboxY = intval($bbox->y / $scaleExpand);
//            $visualBBoxes[] = [
//                'idStaticObject' => $bbox->idStaticObject,
//                'width' => intval($bboxWidth * $scaleReduce),
//                'height' => intval($bboxHeight * $scaleReduce),
//                'x' => intval($bboxX * $scaleReduce),
//                'y' => intval($bboxY * $scaleReduce)
//            ];
//        }

@endphp
<div class="flex flex-row h-2">
    @foreach($fes as $fe)
        <div style="height:16px;width:16px;background-color:#{{$fe[0]->color}};margin-right:2px"></div>
        <div class="mr-3">{{$fe[0]->fe}}</div>
    @endforeach
</div>
<div
    style="position:relative;width:{{$canvasWidth}}px;height:{{$canvasHeight}}px;"
>
    <image
        id="imageStaticBBox"
        width="{{$imageWidth}}"
        height="{{$imageHeight}}"
        id="imageContainer"
        src="{!! config('webtool.mediaURL') . "/" . $image->currentURL !!}"
    >
    </image>
    <canvas
        id="canvas"
        width=0
        height=0
    ></canvas>
    <div id="boxesContainer">
    </div>
</div>
<script>
    @foreach($bboxes as $bbox)
    dom = document.createElement("div");
    dom.className = "bbox";
    dom.style.position = "absolute";
    dom.style.display = "block";
    dom.style.width = "{{$bbox->width}}" + "px";
    dom.style.height = "{{$bbox->height}}" + "px";
    dom.style.left = "{{$bbox->x}}" + "px";
    dom.style.top = "{{$bbox->y}}" + "px";
    dom.style.borderColor = "#{{$fes[$bbox->idStaticObject][0]->color}}";
    dom.style.borderStyle = "solid";
    dom.style.borderWidth = "4px";
    dom.style.backgroundColor = "transparent";
    dom.style.opacity = 1;
    document.querySelector("#boxesContainer").appendChild(dom);
    @endforeach
</script>
