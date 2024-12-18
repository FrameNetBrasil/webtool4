@php
    $id = uniqid("luDynamicTree");
@endphp
<div class="grid w-full h-full">
    <div class="col-4">
        <div
            class="h-full"
        >
            <div class="relative h-full overflow-auto">
                <div id="luDynamicTreeWrapper" class="ui striped small compact table absolute top-0 left-0 bottom-0 right-0">
                    @fragment('search')
                        <ul id="{{$id}}">
                        </ul>
                        <script>
                            $(function() {
                                $("#{{$id}}").datagrid({
                                    data: {!! Js::from($objects) !!},
                                    fit: true,
                                    showHeader: false,
                                    rownumbers: false,
                                    showFooter: false,
                                    border: false,
                                    singleSelect:true,
                                    emptyMsg:"No records",
                                    columns: [[
                                        {
                                            field: "idDocument",
                                            hidden: true
                                        },
                                        {
                                            field: "documentName",
                                            width: "80%",
                                        },
                                        {
                                            field: "idDynamicObject",
                                            width: "20%",
                                        }
                                    ]],
                                    onClickRow: (index,row) => {
                                        htmx.ajax("GET", `/report/lu/dynamic/object/${row.idDynamicObject}`, "#objectImageAreaScript");
                                    }
                                });
                            });
                        </script>
                    @endfragment
                </div>
            </div>
        </div>
    </div>
    <div class="col-8">
        <div
            id="objectImageArea"
            class="h-full"
        >
            <script type="text/javascript">
                // document.addEventListener('alpine:init', () => {
                window.doStore = Alpine.store('doStore', {
                    idVideoJs: 'videoContainer',
                    idVideo: 'videoContainer_html5_api',
                    fps: 25, // frames por segundo
                    timeInterval: 1 / 25, // intervalo entre frames - 0.04s = 40ms
                    originalDimensions: {
                        width: 852,
                        height: 480
                    },
                    timeCount: 0,
                    currentTime : 0,
                    currentFrame: 0,
                    timeByFrame: 0,
                    frameCount: 1,
                    timeFormated: (timeSeconds) => {
                        let minute = Math.trunc(timeSeconds / 60);
                        let seconds = Math.trunc(timeSeconds - (minute * 60));
                        return minute + ':' + seconds;
                    },
                    timeFromFrame: (frame) => {
                        return (frame - 1) * 0.04;
                    },
                    setCurrentFrame(frame) {
                        this.currentFrame = frame;
                        this.currentTime = (frame - 1) * 0.04;
                    }
                })
                let dom = null;
                // });
                $(function () {

                    let player = videojs("videoContainer", {
                        height: parseInt(Alpine.store('doStore').originalDimensions.height),
                        width: parseInt(Alpine.store('doStore').originalDimensions.width),
                        controls: true,
                        autoplay: false,
                        preload: "auto",
                        playbackRates: [0.2, 0.5, 0.8, 1, 2],
                        bigPlayButton: false,
                        inactivityTimeout: 0,
                        children: {
                            controlBar: {
                                playToggle: true,
                                volumePanel: false,
                                remainingTimeDisplay: false,
                                fullscreenToggle: false,
                                pictureInPictureToggle: false,
                            },
                            mediaLoader: true,
                            loadingSpinner: true,
                        },
                        userActions: {
                            doubleClick: false
                        }
                    });
                    player.crossOrigin('anonymous')

                    // button frame forward
                    let btnForward = player.controlBar.addChild('button', {}, 0);
                    let btnForwardDom = btnForward.el();
                    btnForwardDom.innerHTML = '<span class="vjs-icon-placeholder" id="btnForward" aria-hidden="true" title="Next frame"><i class="video-material">skip_next</i></span>';
                    btnForwardDom.onclick = function () {
                        console.log('click forward');
                        let state = Alpine.store('doStore').currentVideoState;
                        if (state === 'paused') {
                            let currentTime = player.currentTime();
                            let newTime = currentTime + annotation.video.timeInterval;
                            //console.log('newTime', newTime);
                            player.currentTime(newTime);
                        }
                    };
                    // button frame backward
                    let btnBackward = player.controlBar.addChild('button', {}, 0);
                    let btnBackwardDom = btnBackward.el();
                    btnBackwardDom.innerHTML = '<span class="vjs-icon-placeholder"  id="btnBackward" aria-hidden="true" title="Previous frame"><i class="video-material">skip_previous</i></span>';
                    btnBackwardDom.onclick = function () {
                        console.log('click backward');
                        let state = Alpine.store('doStore').currentVideoState;
                        if (state === 'paused') {
                            let currentTime = player.currentTime();
                            if (Alpine.store('doStore').frameCount > 1) {
                                let newTime = currentTime - annotation.video.timeInterval;
                                //console.log('newTime', newTime);
                                player.currentTime(newTime);
                            }
                        }
                    };

                    player.ready(function () {
                        // Alpine.store('doStore').config();
                        player.on('durationchange', () => {
                        })
                        player.on('timeupdate', () => {
                            let currentTime = player.currentTime();
                            Alpine.store('doStore').timeCount = Math.floor(currentTime * 1000) /1000;
                        })
                    });


                })
            </script>
            <div
                style="position:relative; width:415px;height:245px"
            >
                <video-js
                    id="videoContainer"
                    class="video-js"
{{--                    src="https://dynamic.frame.net.br/afa00f72fb6fe767d051f2dff2633ee3e67eecdd.mp4"--}}
                    {{--        src="https://dynamic.frame.net.br/{{$video->sha1Name}}.mp4"--}}
                >
                </video-js>
                <div id="boxesContainer">
                </div>
                <div x-data class="info flex flex-row justify-content-between">
                    <div style="width:200px; text-align:left">
                        <div class="ui label">
                            <span x-text="$store.doStore.currentFrame"></span> [<span x-text="$store.doStore.currentTime"></span>s]
                        </div>
                    </div>
                </div>

            </div>

            <div id="objectImageAreaScript">

            </div>
        </div>
    </div>
</div>

<script>

</script>

