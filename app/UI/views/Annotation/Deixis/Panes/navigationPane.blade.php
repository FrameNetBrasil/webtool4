<div x-data="navigationComponent()" class="control-bar flex-container between">
    <div style="width:128px;text-align:left;">
        <div class="ui label">
            <span x-text="frame.current"></span> [<span
                x-text="time.current"></span>]
        </div>
    </div>
    <div id="videoNavigation" class="ui small basic icon buttons">
        <button
            class="ui button nav"
            @click="gotoFrame(0)"
        ><i class="fast backward icon"></i>
        </button>
        <button
            class="ui button nav"
            @click="gotoFrame(frame.current - 250)"
        ><i class="backward icon"></i>
        </button>
        <button
            class="ui button nav"
            @click="gotoFrame(frame.current - 1)"
        ><i class="step backward icon"></i>
        </button>
        <button
            class="ui button toggle"
            @click="toggle()"
        ><i :class="isPlaying ? 'pause icon' : 'play icon'"></i>
        </button>
        <button
            class="ui button nav"
            @click="gotoFrame(frame.current + 1)"
        ><i class="step forward icon"></i>
        </button>
        <button
            class="ui button nav"
            @click="gotoFrame(frame.current + 250)"
        ><i class="forward icon"></i>
        </button>
        <button
            class="ui button nav"
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

