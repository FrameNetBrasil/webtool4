<script type="text/javascript">

</script>

<div class="controls flex flex-row gap-3">
    <div>
        <x-button
            id="btnCreateObject"
            color="primary"
            label="Create object"
            icon="square"
            x-data @click="$store.doStore.createObject()"
        ></x-button>
    </div>
    <div>
        <x-button
            id="btnStartTracking"
            color="primary"
            label="Start Tracking"
            icon="square"
            x-data @click="$store.doStore.startTracking()"
        ></x-button>
    </div>
    <div>
        <x-button
            id="btnPauseTracking"
            color="primary"
            label="Pause Tracking"
            icon="square"
            x-data @click="$store.doStore.pauseTracking()"
        ></x-button>
    </div>
    <div>
        <x-button
            id="btnStopTracking"
            color="primary"
            label="Stop Tracking"
            icon="square"
            x-data @click="$store.doStore.stopTracking()"
        ></x-button>
    </div>
    <div>
        <x-button
            id="btnEndObject"
            color="primary"
            label=""
            icon="square"
            x-data @click="$store.doStore.endObject()"
        >
            <span class="label" x-data x-text="'End Object at frame #' + ($store.doStore.currentFrame || '')"></span>
        </x-button>
    </div>
</div>
{{--<div class="controls flex flex-row gap-3">--}}
{{--    <div>--}}
{{--        <button--}}
{{--            type="button"--}}
{{--            id="btnShowHideObjects"--}}
{{--            class="btn btn-option"--}}
{{--            x-data @click="$store.doStore.showHideObjects()"--}}
{{--        >--}}
{{--            <i class="material-icons-outlined wt-icon wt-icon-show-hide-objects-on"></i>--}}
{{--            <span class="label" x-data x-text="'Show/Hide Objects at frame #' + ($store.doStore.currentFrame || '')"></span>--}}
{{--        </button>--}}
{{--    </div>--}}
{{--</div>--}}

