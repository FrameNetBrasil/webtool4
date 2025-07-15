function formsComponent() {
    return {
        formsPane: null,
        currentFrame: 0,
        currentObject: null,
        isPlaying: false,

        init() {
            this.formsPane = document.getElementById("formsPane");
        },

        onVideoUpdateState(e) {
            this.currentFrame = e.detail.frame.current;
            this.isPlaying = e.detail.isPlaying;
        },

        onObjectSelected(e) {
            console.log(e.detail.dynamicObject);
            this.currentObject = e.detail.dynamicObject;
        },

        copyFrameFor(name) {
            console.log(name);
            const input = document.querySelector(`input[name="${name}"]`);
            input.value = this.currentFrame;
        }

    };
}
