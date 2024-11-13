<div class="relative h-full">
    <div class=" absolute top-0 left-0 bottom-0 right-0 h-full">
        <div class="tl-container">
            {{--    <main>--}}
            {{--        <aside></aside>--}}
            {{--        <div class="content">--}}
            {{--            <div id="currentTime"></div>--}}
            {{--            <div class="logs">--}}
            {{--                <div class="output" id="output1"></div>--}}
            {{--                <div class="output" id="output2"></div>--}}
            {{--            </div>--}}
            {{--        </div>--}}
            {{--    </main>--}}
            <div class="toolbar">
                <button class="button icon material" title="Timeline selection mode" onclick="selectMode()">
                    tab_unselected
                </button>
                <button class="button icon material" title="Timeline pan mode with the keyframe selection."
                        onclick="panMode(true)">pan_tool_alt
                </button>
                <button class="button icon material" title="Timeline pan mode non interactive" onclick="panMode(false)">
                    pan_tool
                </button>
                <button class="button icon material" title="Timeline zoom mode. Also ctrl + scroll can be used."
                        onclick="zoomMode()">search
                </button>
                <button class="icon material" title="Only view mode." onclick="noneMode()">visibility</button>
                <div style="width: 1px; background: gray; height: 100%"></div>
                <button class="button icon material"
                        title="Use external player to play\stop the timeline. For the demo simple setInterval is used."
                        onclick="onPlayClick()">
                    play_arrow
                </button>
                <button class="button icon material"
                        title="Use external player to play\stop the timeline. For the demo simple setInterval is used."
                        onclick="onPauseClick()">
                    pause
                </button>
                <div style="flex: 1"></div>
                <button class="flex-left icon material" title="Remove selected keyframe" onclick="removeKeyframe()">
                    close
                </button>
                <button class="flex-left icon material" title="Add new track with the keyframe" onclick="addKeyframe()">
                    add
                </button>
                <div class="links">
                </div>
            </div>
            <div class="footer">
                <div class="outline">
                    <div class="outline-header" id="outline-header"></div>
                    <div class="outline-scroll-container" id="outline-scroll-container"
                         onwheel="outlineMouseWheel(arguments[0])">
                        <div class="outline-items" id="outline-container"></div>
                    </div>
                </div>
                <div id="timeline">

                </div>
            </div>
        </div>
    </div>
</div>

</div>
<script type="text/javascript">
    @include("Annotation.Deixis.Scripts.timeline")
</script>

{{--<div class="relative h-full">--}}
{{--    <div class=" absolute top-0 left-0 bottom-0 right-0 h-full">--}}
{{--        <div class="south h-full">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

