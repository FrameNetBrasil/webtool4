function formsComponent() {
    return {
        formsPane: null,
        currentFrame: 0,
        isPlaying: false,

        init() {
            this.formsPane = document.getElementById("formsPane");
        },

        onVideoUpdateState(e) {
            this.currentFrame = e.detail.frame.current;
            this.isPlaying = e.detail.isPlaying;
        },

        onCloseObjectPane() {
            htmx.ajax('GET', "/annotation/deixis/object/0", {target:'#formsPane', swap:'innerHTML'});
        },

        copyFrameFor(name) {
            console.log(name);
            const input = document.querySelector(`input[name="${name}"]`);
            input.value = this.currentFrame;
        }

    };
}
