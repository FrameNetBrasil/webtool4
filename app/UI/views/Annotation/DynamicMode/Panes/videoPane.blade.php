<script type="text/javascript">
    $(function () {
        annotation.video.player = videojs(annotation.video.idVideoJs, {
            height: annotation.video.originalDimensions.height,
            width: annotation.video.originalDimensions.width,
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
        let player = annotation.video.player;
        player.crossOrigin('anonymous')

        player.player_.handleTechClick_ = function (event) {
            console.log('video clicking')
            let state = Alpine.store('doStore').currentVideoState;
            if (state === 'paused') {
                player.play();
            }
            if (state === 'playing') {
                player.pause();
            }
        };

        //<span class="vjs-icon-placeholder" aria-hidden="true"></span>
        //<span class="vjs-control-text" aria-live="polite">Play</span>

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
            Alpine.store('doStore').config();
            player.on('durationchange', () => {
                let duration = player.duration();
                Alpine.store('doStore').timeDuration = parseInt(duration);
                let lastFrame = annotation.video.frameFromTime(duration);
                Alpine.store('doStore').frameDuration = lastFrame;
                annotation.video.framesRange.last = lastFrame;
                Alpine.store('doStore').updateObjectList();
            })
            player.on('timeupdate', () => {
                let currentTime = player.currentTime();
                let currentFrame = annotation.video.frameFromTime(currentTime);
                //console.log('time update', currentTime);
                Alpine.store('doStore').timeCount = parseInt(currentTime);
                Alpine.store('doStore').updateCurrentFrame(currentFrame);
                if (annotation.video.playingRange) {
                    if (currentFrame > annotation.video.playingRange.endFrame) {
                        annotation.video.player.pause();
                        annotation.video.playingRange = null;
                    }
                }
            })
            player.on('play', () => {
                let state = Alpine.store('doStore').currentVideoState;
                if (state === 'paused') {
                    Alpine.store('doStore').currentVideoState = 'playing';
                }
                $btn = document.querySelector("#btnBackward");
                if ($btn) {
                    $btn.style.color = "grey";
                    $btn.style.cursor = "default";
                }
                $btn = document.querySelector("#btnForward");
                if ($btn) {
                    $btn.style.color = "grey";
                    $btn.style.cursor = "default";
                }
            })
            player.on('pause', () => {
                Alpine.store('doStore').currentVideoState = 'paused';
                $btn = document.querySelector("#btnBackward");
                if ($btn) {
                    $btn.style.color = "white";
                    $btn.style.cursor = "pointer";
                }
                $btn = document.querySelector("#btnForward");
                if ($btn) {
                    $btn.style.color = "white";
                    $btn.style.cursor = "pointer";
                }
            })
        });


    })
</script>
<div style="position:relative; width:852px;height:480px">
    <video-js
        id="videoContainer"
        class="video-js"
{{--        src="http://dynamic.frame.net.br/afa00f72fb6fe767d051f2dff2633ee3e67eecdd.mp4"--}}
        src="http://dynamic.frame.net.br/{{$video->sha1Name}}.mp4"
    >
    </video-js>
    <canvas id="canvas" width=0 height=0></canvas>
    <div id="boxesContainer">
    </div>
    <div x-data class="info flex flex-row justify-content-between">
        <div style="width:120px; text-align:left">
            <span x-text="$store.doStore.frameCount"></span> [<span x-text="$store.doStore.timeCount"></span>s]
        </div>
        <div>
            Video: <span x-text="$store.doStore.currentVideoState"></span>
        </div>
        <div>
            Object: #<span x-text="$store.doStore.currentObject?.idObject || 'none'"></span>
        </div>
        <div>
            Status: <span x-text="$store.doStore.newObjectState"></span>
        </div>
        <div style="width:120px; text-align:right">
            <span x-text="$store.doStore.frameDuration"></span> [<span x-text="$store.doStore.timeDuration"></span>s]
        </div>
    </div>

</div>
