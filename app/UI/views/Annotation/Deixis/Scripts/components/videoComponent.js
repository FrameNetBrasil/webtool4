function videoComponent() {
    return {
        idVideoJs: "videoContainer",
        idVideo: "videoContainer_html5_api",
        fps: 25, // frames por segundo
        timeInterval: 1 / 25, // interval between frames - 0.04s = 40ms
        dimensions: {
            width: 852,
            height: 480
        },
        frame: {
            current: 1,
            last: 0
        },
        time: {
            current: "",
            duration: ""
        },
        framesRange: {
            first: 1,
            last: 1
        },
        playingRange: null,
        player: null,
        state: "paused",

        init() {
            console.log("videoComponent init");
            this.player = videojs(this.idVideoJs, {
                height: this.dimensions.height,
                width: this.dimensions.width,
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
            let player = this.player;
            let video = this;

            player.crossOrigin("anonymous");

            player.player_.handleTechClick_ = function() {
                console.log("video clicking");
                //let state = Alpine.store("doStore").currentVideoState;
                if (video.state === "paused") {
                    player.play();
                }
                if (video.state === "playing") {
                    player.pause();
                }
            };

            player.ready(function() {
                // Alpine.store('doStore').config();
                player.on("durationchange", () => {
                    let duration = player.duration();
                    // Alpine.store('doStore').timeDuration = parseInt(duration);
                    let lastFrame = video.frameFromTime(duration);
                    // Alpine.store('doStore').frameDuration = lastFrame;
                    video.framesRange.last = lastFrame;
                    document.dispatchEvent(new CustomEvent("update-duration", {
                        detail: {
                            lastFrame,
                            duration
                        }
                    }));
                });

                player.on("timeupdate", () => {
                    let currentTime = player.currentTime();
                    let currentFrame = video.frameFromTime(currentTime);
                    console.log("timeupdate", currentFrame,currentTime);
                    // Alpine.store('doStore').timeCount = Math.floor(currentTime * 1000) /1000;
                    // Alpine.store('doStore').updateCurrentFrame(currentFrame);
                    //annotation.timeline.setTime(Math.trunc(currentTime * 1000));
                    // if (Alpine.store('doStore').newObjectState === 'editing') {
                    //     Alpine.store('doStore').uiEditingObject();
                    // }
                    document.dispatchEvent(new CustomEvent("update-current-frame", {
                        detail: {
                            currentFrame
                        }
                    }));
                    if (video.playingRange) {
                        if (currentFrame > video.playingRange.endFrame) {
                            video.player.pause();
                            video.playingRange = null;
                        }
                    }
                });

                player.on("play", () => {
                    // let state = Alpine.store('doStore').currentVideoState;
                    if (video.state === "paused") {
                        // Alpine.store('doStore').currentVideoState = 'playing';
                        //annotation.timeline.onPlayClick();
                        //video.disableSkipFrame();
                        video.disableVideoNavigationButtons();
                    }
                });

                player.on("pause", () => {
                    //player.currentTime(Alpine.store('doStore').timeCount);
                    //let currentTime = player.currentTime();
                    // Alpine.store('doStore').currentVideoState = 'paused';
                    //annotation.timeline.onPauseClick();
//                    video.enableSkipFrame();
                    video.enableVideoNavigationButtons();

                });
            });

            this.time.current = this.timeFormated(0);

            document.addEventListener("update-duration", (e) => {
                console.log(e.detail);
                this.frame.last = e.detail.lastFrame;
                this.time.duration = this.timeFormated(e.detail.duration);
            });


        },
        // Your methods
        updateState(value) {
            this.dataState = value;
        },

        changeObjectState(state) {
            this.newObjectState = state;
        },

        timeFormated: (timeSeconds) => {
            let minute = Math.trunc(timeSeconds / 60);
            let seconds = Math.trunc(timeSeconds - (minute * 60));
            return minute + ":" + seconds;
        },

        frameFromTime(timeSeconds) {
            return Math.floor(parseFloat(timeSeconds.toFixed(3)) * this.fps) + 1;
        },
        timeFromFrame(frameNumber) {
            return Math.floor(((frameNumber - 1) * this.timeInterval) * 1000) / 1000;
        },
        gotoFrame(frameNumber) {
            if (frameNumber < 1) {
                frameNumber = 1;
            }
            if (frameNumber > this.frame.last) {
                frameNumber = this.frame.last;
            }
            console.log("gotoFrame", frameNumber);
            this.frame.current = frameNumber;
            this.time.current = this.timeFromFrame(frameNumber);// + 2e-2;
            this.player.currentTime(this.time.current);
        },
        gotoTime(time) {
            let frame = this.frameFromTime(time);
            this.gotoFrame(frame);
        },
        enablePlayPause() {
            let $btn = document.querySelector(".vjs-play-control");
            if ($btn) {
                $btn.disabled = false;
                $btn.style.color = "white";
                $btn.style.cursor = "pointer";
            }
        },
        disablePlayPause() {
            let $btn = document.querySelector(".vjs-play-control");
            if ($btn) {
                $btn.disabled = true;
                $btn.style.color = "grey";
                $btn.style.cursor = "default";
            }
        },

        toggleVideoNavigationButtons(disabled = true) {
            const buttons = document.querySelectorAll("#videoNavigation button");
            buttons.forEach(button => {
                button.disabled = disabled;

                // Optional: Add visual feedback by toggling a CSS class
                if (disabled) {
                    button.classList.add("disabled");
                } else {
                    button.classList.remove("disabled");
                }
            });
        },

        disableVideoNavigationButtons() {
            this.toggleVideoNavigationButtons(true);
        },

        enableVideoNavigationButtons() {
            this.toggleVideoNavigationButtons(false);
        },

        // enableSkipFrame() {
        //     let $navigation = document.querySelector("#videoNavigation");
        //     if ($navigation) {
        //         $navigation.classList.remove("disable");
        //     }
        //
        //     // $btn = document.querySelector("#btnBackward");
        //     // if ($btn) {
        //     //     $btn.style.color = "white";
        //     //     $btn.style.cursor = "pointer";
        //     // }
        //     // $btn = document.querySelector("#btnForward");
        //     // if ($btn) {
        //     //     $btn.style.color = "white";
        //     //     $btn.style.cursor = "pointer";
        //     // }
        // },
        // disableSkipFrame() {
        //     let $navigation = document.querySelector("#videoNavigation");
        //     if ($navigation) {
        //         $navigation.classList.add("disable");
        //     }
        //     // $btn = document.querySelector("#btnBackward");
        //     // if ($btn) {
        //     //     $btn.style.color = "grey";
        //     //     $btn.style.cursor = "default";
        //     // }
        //     // $btn = document.querySelector("#btnForward");
        //     // if ($btn) {
        //     //     $btn.style.color = "grey";
        //     //     $btn.style.cursor = "default";
        //     // }
        // },
        playByRange(startTime, endTime, offset) {
            let playRange = {
                startFrame: this.frameFromTime(startTime - offset),
                endFrame: this.frameFromTime(endTime + offset)
            };
            this.playRange(playRange);
        },
        playByFrameRange(startFrame, endFrame) {
            let playRange = {
                startFrame: startFrame,
                endFrame: endFrame
            };
            this.playRange(playRange);
        },
        playRange(range) {
            this.playingRange = range;
            this.gotoFrame(range.startFrame);
            this.player.play();
        }

    };
}
