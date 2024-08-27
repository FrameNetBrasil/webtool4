document.addEventListener('alpine:init', () => {
    window.doStore = Alpine.store('doStore', {
        dataState: '',
        timeCount: 0,
        frameCount: 1,
        timeDuration: 0,
        frameDuration: 0,
        currentVideoState: 'paused',
        currentFrame: 1,
        init() {
        },
        config() {
            let config = {
                idVideoDOMElement: annotation.video.idVideo,
                fps: annotation.video.fps,
            };
        },
        updateCurrentFrame(frameNumber) {
            //console.log('updateCurrentFrame',this.currentVideoState,this.newObjectState);
            this.frameCount = this.currentFrame = frameNumber;
            if ((this.currentVideoState === 'paused') ||
                (this.newObjectState === 'tracking') ||
                (this.newObjectState === 'showing')
            ) {
                annotation.objects.drawFrameObject(frameNumber);
            }
        },
    });

    Alpine.effect(() => {
        const timeCount = Alpine.store('doStore').timeCount;
    });
    Alpine.effect(() => {
        const frameCount = Alpine.store('doStore').frameCount;
    });
    Alpine.effect(() => {
        const currentVideoState = Alpine.store('doStore').currentVideoState;
        if (currentVideoState === 'playing') {
        }
        if (currentVideoState === 'paused') {
        }
    });
    Alpine.effect(async () => {
        const dataState = Alpine.store('doStore').dataState;
        if (dataState === 'loaded') {
            console.log('Data Loaded');
            Alpine.store('doStore').currentVideoState = 'paused';
        }
    });
});
