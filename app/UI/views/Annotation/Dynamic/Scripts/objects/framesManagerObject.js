class FramesManager {
    constructor() {
        this.frames = {};
        this.onReset = [];
        this.totalFrames = 0;
        this.frameNumber = -1;
        this.interval = 0;
    }

    setConfig(config) {
        this.config = config;
        this.video = this.config.video;
        this.canvas = this.config.canvas;
        this.ctx = this.config.ctx;
        this.config.imageMimeType = "image/png";
    }


    addFrame(frameNumber, frameImage) {
        this.frames[frameNumber] = frameImage;
    }

    async getFrameImage(frameNumber) {
        let frameImage = this.frames[frameNumber];
        if (typeof frameImage === "undefined") {
            let frameImage = await this.getFrameFromVideo();
            this.addFrame(frameNumber, frameImage);
            return frameImage;
        }
        return frameImage;
    }

    async getFrameFromVideo() {
        // considera que o frame de video está sendo exibido no canvas
        this.video.crossOrigin = "*";
        this.ctx.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
        return this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
    }

}

