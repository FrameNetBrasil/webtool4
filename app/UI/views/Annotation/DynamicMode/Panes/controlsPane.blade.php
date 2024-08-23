<div class="controls flex flex-row gap-3 justify-content-between mr-5">
    <div
        class="flex align-items-center"
    >
        <div class="mr-1 font-bold">
            Object
        </div>
        <div>
            <div class="">
                <button
                    id="btnCreateObject"
                    class="ui button primary"
                    x-data @click="$store.doStore.createObject()"
                >
                    <i class="plus square outline icon"></i>
                    Create
                </button>
                <button
                    id="btnEndObject"
                    class="ui button primary"
                    x-data @click="$store.doStore.endObject()"
                >
                    <i class="window close outline icon"></i>
                    <span x-data x-text="'End at frame #' + ($store.doStore.currentFrame || '')"></span>
                </button>
                <button
                    id="btnShowHideObjects"
                    class="ui toggle button secondary"
                    x-data @click="$store.doStore.showHideObjects()"
                >
                    Show/Hide
                </button>
            </div>
        </div>
    </div>
    <div
        class="flex align-items-center"
    >
        <div class="mr-1 font-bold">
            Tracking
        </div>
        <div>
            <div class="">
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
{{--                <button--}}
{{--                    id="btnStopTracking"--}}
{{--                    class="ui button primary"--}}
{{--                    x-data @click="$store.doStore.stopTracking()"--}}
{{--                >--}}
{{--                    <i class="stop icon"></i>--}}
{{--                    Stop--}}
{{--                </button>--}}
            </div>
        </div>
    </div>
    <div>
        <button
            id="btnClear"
            class="ui button secondary"
            x-data @click="$store.doStore.clear()"
        >
            <i class="redo icon"></i>
            Clear
        </button>
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

