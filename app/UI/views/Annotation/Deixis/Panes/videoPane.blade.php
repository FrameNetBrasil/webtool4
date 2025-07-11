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
    <div class="control-bar flex-container between">
        <div style="width:128px;text-align:left;">
            <div class="ui label">
                <span x-text="frame.current"></span> [<span
                    x-text="time.current"></span>]
            </div>
        </div>
            <div id="videoNavigation" class="ui small basic icon buttons">
                <button
                    class="ui button"
                    @click="gotoFrame(0)"
                ><i class="fast backward icon"></i>
                </button>
                <button
                    class="ui button"
                    @click="gotoFrame(frame.current - 250)"
                ><i class="backward icon"></i>
                </button>
                <button
                    class="ui button"
                    @click="gotoFrame(frame.current - 1)"
                ><i class="step backward icon"></i>
                </button>
                <button
                    class="ui button"
                    @click="gotoFrame(frame.current + 1)"
                ><i class="step forward icon"></i>
                </button>
                <button
                    class="ui button"
                    @click="gotoFrame(frame.current + 250)"
                ><i class="forward icon"></i>
                </button>
                <button
                    class="ui button"
                    @click="gotoFrame(frame.last)"
                ><i class="fast forward icon"></i>
                </button>
            </div>
        <div style="width:128px;text-align:right;">
            <div class="ui label">
                <span x-text="frame.last"></span> [<span
                    x-text="time.duration"></span>]
            </div>
        </div>
    </div>
</div>
