<button
    id="btnCreateObject"
    class="ui button primary"
    @click="createBBox()"
>
    <i class="plus square outline icon"></i>
    Create BBox
</button>
<button
    id="btnStartTracking"
    class="ui button primary"
    x-data @click="$store.doStore.startTracking()"
>
    <i class="play icon"></i>
    Start
</button>
<button
    id="btnPauseTracking"
    class="ui button primary"
    x-data @click="$store.doStore.pauseTracking()"
>
    <i class="pause icon"></i>
    Pause
</button>
<button
    id="btnStopObject"
    class="ui button primary"
    @click="stopBBox()"
>
    <i class="window stop icon"></i>
{{--    <span x-data x-text="'Stop at #' + ($store.doStore.currentFrame || '')"></span>--}}
    Stop
</button>
<button
    id="btnDeleteBBox"
    class="ui medium icon button negative"
    title="Delete BBoxes from Object"
    @click.prevent="annotation.objects.deleteBBox({{$object->idDynamicObject}})"
>
    <i class="trash alternate outline icon"></i>
    Delete BBox
</button>
