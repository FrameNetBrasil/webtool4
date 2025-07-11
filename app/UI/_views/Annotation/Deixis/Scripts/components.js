function videoComponent() {
    return {
        lastFrame: 0,
        duration: "",
        currentFrame: 0,
        currentTime: "",

        init() {
            // Listen for external events
            document.addEventListener('update-duration', (e) => {
                console.log(e.detail);
                this.lastFrame = e.detail.lastFrame;
                this.duration = window.annotation.timeFormated(e.detail.duration);
            });
        },
        // Your methods
        updateState(value) {
            this.dataState = value;
        },

        changeObjectState(state) {
            this.newObjectState = state;
        }

        // Add all your other data and methods here
    };
}
