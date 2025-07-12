function navigationComponent() {
    return {
        fps: 25,
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

        init() {
            this.time.current = this.timeFormated(0);

            document.addEventListener("action-play", (e) => {
                this.disableVideoNavigationButtons();
            });

            document.addEventListener("action-pause", (e) => {
                this.enableVideoNavigationButtons();
            });

            document.addEventListener("update-duration", (e) => {
                console.log(e.detail);
                this.time.duration = this.timeFormated(e.detail.duration);
                let lastFrame = this.frameFromTime(e.detail.duration);
                console.log("lastFrame", lastFrame);
                this.framesRange.last = lastFrame;
                this.frame.last = lastFrame;
            });

            document.addEventListener("update-current-time", (e) => {
                this.frame.current = this.frameFromTime(e.detail.currentTime);
                this.time.current = this.timeFormated(this.frame.current);
            });

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



    };
}
