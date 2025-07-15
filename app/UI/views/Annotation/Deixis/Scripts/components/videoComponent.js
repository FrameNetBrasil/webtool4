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
        player: null,
        frame: {
            current: 1,
            last: 0
        },
        time: {
            current: ""
        },
        isPlaying: false,

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
                        playToggle: false,
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

            document.addEventListener("video-seek-frame", (e) => {
                this.seekToFrame(e.detail.frameNumber);
            });

            document.addEventListener("video-toggle-play", (e) => {
                this.togglePlay();
            });

            player.crossOrigin("anonymous");

            player.player_.handleTechClick_ = () => {
                this.togglePlay();
                // console.log("video clicking");
                //document.dispatchEvent(new CustomEvent("action-toggle"));
                //let state = Alpine.store("doStore").currentVideoState;
                // if (video.state === "paused") {
                //     document.dispatchEvent(new CustomEvent("action-play"));
                // }
                // if (video.state === "playing") {
                //     document.dispatchEvent(new CustomEvent("action-pause"));
                // }
            };

            player.ready(() => {
                // Alpine.store('doStore').config();
                player.on("durationchange", () => {
                    let duration = player.duration();
                    let lastFrame = this.frameFromTime(duration);
                    // Alpine.store('doStore').timeDuration = parseInt(duration);
                    //let lastFrame = video.frameFromTime(duration);
                    // Alpine.store('doStore').frameDuration = lastFrame;
                    //video.framesRange.last = lastFrame;
                    document.dispatchEvent(new CustomEvent("video-update-duration", {
                        detail: {
                            duration,
                            lastFrame
                        }
                    }));
                });

                player.on("timeupdate", () => {
                    this.time.current = player.currentTime();
                    this.frame.current = this.frameFromTime(this.time.current);
                    // console.log("timeupdate", currentFrame,currentTime);
                    // Alpine.store('doStore').timeCount = Math.floor(currentTime * 1000) /1000;
                    // Alpine.store('doStore').updateCurrentFrame(currentFrame);
                    //annotation.timeline.setTime(Math.trunc(currentTime * 1000));
                    // if (Alpine.store('doStore').newObjectState === 'editing') {
                    //     Alpine.store('doStore').uiEditingObject();
                    // }
                    this.broadcastState();
                    // document.dispatchEvent(new CustomEvent("update-current-time", {
                    //     detail: {
                    //         currentTime
                    //     }
                    // }));
                    // if (video.playingRange) {
                    //     if (currentFrame > video.playingRange.endFrame) {
                    //         video.player.pause();
                    //         video.playingRange = null;
                    //     }
                    // }
                });

                player.on("play", () => {
                    this.isPlaying = true;
                    this.broadcastState();
                    // let state = Alpine.store('doStore').currentVideoState;
                    // if (video.state === "paused") {
                        // Alpine.store('doStore').currentVideoState = 'playing';
                        //annotation.timeline.onPlayClick();
                        //video.disableSkipFrame();
//                        video.disableVideoNavigationButtons();
//                         document.dispatchEvent(new CustomEvent("action-play"));
//                         video.state = "playing";
//                     }
                });

                player.on("pause", () => {
                    this.isPlaying = false;
                    this.broadcastState();
                    //player.currentTime(Alpine.store('doStore').timeCount);
                    //let currentTime = player.currentTime();
                    // Alpine.store('doStore').currentVideoState = 'paused';
                    //annotation.timeline.onPauseClick();
//                    video.enableSkipFrame();
//                     video.enableVideoNavigationButtons();
//                     document.dispatchEvent(new CustomEvent("action-pause"));
//                     video.state = "paused";

                });


            });


        },

        broadcastState() {
            // Send current state to controls
            // console.log("broadcastState", this.frame.current, this.time.current, this.isPlaying);
            document.dispatchEvent(new CustomEvent('video-update-state', {
                detail: {
                    frame: this.frame,
                    time: this.time,
                    isPlaying: this.isPlaying
                }
            }));
        },

        seekToFrame(frame) {
            this.frame.current = frame;
            this.player.currentTime(this.timeFromFrame(frame));
            // this.$refs.video.currentTime = this.frameToTime(frame);
        },

        togglePlay() {
            if (this.isPlaying) {
                this.player.pause();
            } else {
                this.player.play();
            }
        },


        // enablePlayPause() {
        //     let $btn = document.querySelector(".vjs-play-control");
        //     if ($btn) {
        //         $btn.disabled = false;
        //         $btn.style.color = "white";
        //         $btn.style.cursor = "pointer";
        //     }
        // },
        // disablePlayPause() {
        //     let $btn = document.querySelector(".vjs-play-control");
        //     if ($btn) {
        //         $btn.disabled = true;
        //         $btn.style.color = "grey";
        //         $btn.style.cursor = "default";
        //     }
        // },
        frameFromTime(timeSeconds) {
            return Math.floor(parseFloat(timeSeconds.toFixed(3)) * this.fps) + 1;
        },
        timeFromFrame(frameNumber) {
            return Math.floor(((frameNumber - 1) * this.timeInterval) * 1000) / 1000;
        },
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
