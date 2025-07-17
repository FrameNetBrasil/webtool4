<button
    id="btnCreateObject"
    class="ui button primary"
    :class="!canCreateBBox && 'disabled'"
    @click="createBBox()"
>
    <i class="plus square outline icon"></i>
    Create BBox
</button>
<button
    class="ui button primary toggle"
    @click="toggleTracking()"
><i :class="isTracking ? 'stop icon' : 'play icon'"></i>
    <span x-text="isTracking ? 'Stop' : 'Track'">

    </span>
</button>
{{--<button--}}
{{--    id="btnStopObject"--}}
{{--    :class="isPlayingTracking && 'disabled'"--}}
{{--    class="ui button primary"--}}
{{--    @click="stopTracking()"--}}
{{-->--}}
{{--    <i class="window stop icon"></i>--}}
{{--    Stop--}}
{{--</button>--}}
<button
    id="btnDeleteBBox"
    class="ui medium icon button negative"
    :class="isTracking && 'disabled'"
    title="Delete BBoxes from Object"
    @click.prevent="deleteBBox({{$object->idDynamicObject}})"
>
    <i class="trash alternate outline icon"></i>
    Delete All BBoxes
</button>
