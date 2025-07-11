<div
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
    <div x-data="videoComponent()" class="control-bar flex-container between">
        <div style="width:128px;text-align:left;">
            <div class="ui label">
                <span x-text="currentFrame"></span> [<span
                    x-text="currentTime"></span>]
            </div>
        </div>
            <div class="ui small basic icon buttons">
                <button
                    class="ui button"
                    @click="$dispatch('update-current-frame', {frame:0})"
                ><i class="fast backward icon"></i>
                </button>
                <button
                    class="ui button"
                ><i class="backward icon"></i>
                </button>
                <button
                    class="ui button"
                ><i class="step backward icon"></i>
                </button>
                <button
                    class="ui button"
                ><i class="step forward icon"></i>
                </button>
                <button
                    class="ui button"
                ><i class="forward icon"></i>
                </button>
                <button
                    class="ui button"
                    @click="$dispatch('update-current-frame', {frame: lastFrame})"
                ><i class="fast forward icon"></i>
                </button>
            </div>
        <div style="width:128px;text-align:right;">
            <div class="ui label">
                <span x-text="lastFrame"></span> [<span
                    x-text="duration"></span>]
            </div>
        </div>
    </div>
    {{--    <div x-data class="info flex flex-row justify-content-between">--}}
    {{--        <div style="width:100px;text-align:left;">--}}
    {{--            <div class="ui label">--}}
    {{--            <span x-text="$store.doStore.frameCount"></span> [<span x-text="$store.doStore.timeFormated($store.doStore.timeCount)"></span>]--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div>--}}
    {{--            <div class="flex">--}}
    {{--                <div--}}
    {{--                    title="Register startFrame"--}}
    {{--                >--}}
    {{--                    <button--}}
    {{--                        class="compact ui button text-base"--}}
    {{--                        @click.stop="$store.doStore.newStartFrame = $store.doStore.currentStartFrame = $store.doStore.currentFrame"--}}
    {{--                    ><x-icon.start></x-icon.start>--}}
    {{--                    </button>--}}
    {{--                </div>--}}
    {{--                <div--}}
    {{--                    title="Register endFrame"--}}
    {{--                >--}}
    {{--                    <button--}}
    {{--                        class="compact ui button text-base"--}}
    {{--                        @click.stop="$store.doStore.newEndFrame = $store.doStore.currentEndFrame = $store.doStore.currentFrame"--}}
    {{--                    ><x-icon.end></x-icon.end>--}}
    {{--                    </button>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div>--}}
    {{--            <div class="ui label">--}}
    {{--                Video <div class="detail"><span x-text="$store.doStore.currentVideoState"></span></div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div>--}}
    {{--            <div class="ui label">--}}
    {{--                Object <div class="detail">#<span x-text="$store.doStore.currentObject?.idObject || 'none'"></span></div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div>--}}
    {{--            <div class="ui label">--}}
    {{--                Status <div class="detail"><span x-text="$store.doStore.newObjectState"></span></div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div style="width:100px; text-align:right">--}}
    {{--            <div class="ui label">--}}
    {{--            <span x-text="$store.doStore.frameDuration"></span> [<span x-text="$store.doStore.timeFormated($store.doStore.timeDuration)"></span>]--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

</div>
