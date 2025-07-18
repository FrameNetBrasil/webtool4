annotation.video = {
    idVideoJs: "videoContainer",
    idVideo: "videoContainer_html5_api",
    fps: 25, // frames por segundo
    timeInterval: 1 / 25, // intervalo entre frames - 0.04s = 40ms
    originalDimensions: {
        width: 852,
        height: 480
    },
    player: null,
    frameFromTime(timeSeconds) {
        //let frame= parseInt((parseInt(timeSeconds * 1000) * 25) / 1000) + 1;
        let frame = Math.floor(parseFloat(timeSeconds.toFixed(3)) * annotation.video.fps) + 1;
        return frame;
    },
    timeFromFrame(frameNumber) {
        return Math.floor(((frameNumber - 1) * annotation.video.timeInterval) * 1000) / 1000;
    },
    framesRange: {
        first: 1,
        last: 1
    },
    playingRange: null,
    gotoFrame(frameNumber) {
        let time = annotation.video.timeFromFrame(frameNumber) + 2e-2;
        //console.log("gotoFrame", frameNumber, time);
        annotation.video.player.currentTime(time);
    },
    gotoTime(time) {
        let frame = annotation.video.frameFromTime(time);
        annotation.video.gotoFrame(frame);
    },
    enablePlayPause() {
        $btn = document.querySelector(".vjs-play-control");
        if ($btn) {
            $btn.disabled = false;
            $btn.style.color = "white";
            $btn.style.cursor = "pointer";
        }
    },
    disablePlayPause() {
        $btn = document.querySelector(".vjs-play-control");
        if ($btn) {
            $btn.disabled = true;
            $btn.style.color = "grey";
            $btn.style.cursor = "default";
        }
    },
    enableSkipFrame() {
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
    },
    disableSkipFrame() {
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
    },
    playByRange(startTime, endTime, offset) {
        let playRange = {
            startFrame: annotation.video.frameFromTime(startTime - offset),
            endFrame: annotation.video.frameFromTime(endTime + offset)
        };
        annotation.video.playRange(playRange);
    },
    playByFrameRange(startFrame, endFrame, offset) {
        let playRange = {
            startFrame: startFrame,
            endFrame: endFrame
        };
        annotation.video.playRange(playRange);
    },
    playRange(range) {
        annotation.video.playingRange = range;
        annotation.video.gotoFrame(range.startFrame);
        annotation.video.player.play();
    }
};

$(function() {
    console.log(annotation.video);
    annotation.video.player = videojs(annotation.video.idVideoJs, {
        height: annotation.video.height,
        width: annotation.video.width,
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
                pictureInPictureToggle: false
            },
            mediaLoader: true,
            loadingSpinner: true
        },
        userActions: {
            doubleClick: false
        }
    });
    let player = annotation.video.player;
    player.crossOrigin("anonymous");

    player.player_.handleTechClick_ = function(event) {
        console.log("video clicking");
        let state = Alpine.store("doStore").currentVideoState;
        if (state === "paused") {
            player.play();
        }
        if (state === "playing") {
            player.pause();
        }
    };

    //<span class="vjs-icon-placeholder" aria-hidden="true"></span>
    //<span class="vjs-control-text" aria-live="polite">Play</span>

    // button frame forward
    // let btnForward = player.controlBar.addChild("button", {}, 0);
    // let btnForwardDom = btnForward.el();
    // btnForwardDom.innerHTML = "<span class=\"vjs-icon-placeholder\" id=\"btnForward\" aria-hidden=\"true\" title=\"Next frame\"><i class=\"video-material\">skip_next</i></span>";
    // btnForwardDom.onclick = function() {
    //     console.log("click forward");
    //     let state = Alpine.store("doStore").currentVideoState;
    //     if (state === "paused") {
    //         let currentTime = player.currentTime();
    //         let newTime = currentTime + annotation.video.timeInterval;
    //         //console.log('newTime', newTime);
    //         player.currentTime(newTime);
    //     }
    // };
    // // button frame backward
    // let btnBackward = player.controlBar.addChild("button", {}, 0);
    // let btnBackwardDom = btnBackward.el();
    // btnBackwardDom.innerHTML = "<span class=\"vjs-icon-placeholder\"  id=\"btnBackward\" aria-hidden=\"true\" title=\"Previous frame\"><i class=\"video-material\">skip_previous</i></span>";
    // btnBackwardDom.onclick = function() {
    //     console.log("click backward");
    //     let state = Alpine.store("doStore").currentVideoState;
    //     if (state === "paused") {
    //         let currentTime = player.currentTime();
    //         if (Alpine.store("doStore").frameCount > 1) {
    //             let newTime = currentTime - annotation.video.timeInterval;
    //             //console.log('newTime', newTime);
    //             player.currentTime(newTime);
    //         }
    //     }
    // };

    player.ready(function() {
        // Alpine.store('doStore').config();
        player.on("durationchange", () => {
            let duration = player.duration();
            // Alpine.store('doStore').timeDuration = parseInt(duration);
            let lastFrame = annotation.video.frameFromTime(duration);
            // Alpine.store('doStore').frameDuration = lastFrame;
            annotation.video.framesRange.last = lastFrame;
            document.dispatchEvent(new CustomEvent('update-duration', {
                detail: {
                    lastFrame,
                    duration
                }
            }));

            // Alpine.store('doStore').loadLayerList();
        });

        player.on("timeupdate", () => {
            let currentTime = player.currentTime();
            let currentFrame = annotation.video.frameFromTime(currentTime);
            // Alpine.store('doStore').timeCount = Math.floor(currentTime * 1000) /1000;
            // Alpine.store('doStore').updateCurrentFrame(currentFrame);
            //annotation.timeline.setTime(Math.trunc(currentTime * 1000));
            // if (Alpine.store('doStore').newObjectState === 'editing') {
            //     Alpine.store('doStore').uiEditingObject();
            // }
            if (annotation.video.playingRange) {
                if (currentFrame > annotation.video.playingRange.endFrame) {
                    annotation.video.player.pause();
                    annotation.video.playingRange = null;
                }
            }
        });

        player.on("play", () => {
            // let state = Alpine.store('doStore').currentVideoState;
            if (state === "paused") {
                // Alpine.store('doStore').currentVideoState = 'playing';
                annotation.timeline.onPlayClick();
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
            }
        });

        player.on("pause", () => {
            //player.currentTime(Alpine.store('doStore').timeCount);
            let currentTime = player.currentTime();
            // Alpine.store('doStore').currentVideoState = 'paused';
            annotation.timeline.onPauseClick();
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
        });
    });


});
