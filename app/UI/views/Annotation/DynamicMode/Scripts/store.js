document.addEventListener('alpine:init', () => {
    window.doStore = Alpine.store('doStore', {
        dataState: '',
        timeCount: 0,
        frameCount: 1,
        timeDuration: 0,
        frameDuration: 0,
        currentVideoState: 'paused',
        currentFrame: 1,
        currentObject: null,
        currentObjectState: 'none',
        newObjectState: 'none',
        showHideBoxesState: 'hide',
        objects: [],
        init() {
            annotation.objects.init();
        },
        config() {
            let config = {
                idVideoDOMElement: annotation.video.idVideo,
                fps: annotation.video.fps,
            };
            annotation.objects.config(config);
            annotation.drawBox.config(config);
        },
        setObjects(objects) {
            this.objects = objects;
        },
        async updateObjectList() {
            this.dataState = 'loading';
            await annotation.api.loadObjects();
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
        selectObject(idObject) {
            if (idObject === null) {
                this.currentObject = null;
                this.newObjectState = 'none';
            } else {
                let object = annotation.objects.get(idObject);
                this.currentObject = object;
                //console.log(object);
                let time = annotation.video.timeFromFrame(object.object.startFrame);
                //console.log(time, object.object.startFrame);
                annotation.video.player.currentTime(time);
                annotation.objects.drawFrameObject(object.object.startFrame);
                this.newObjectState = 'showing';
                htmx.ajax("GET","/annotation/dynamicMode/formObject/" + object.object.idDynamicObject + "/" + idObject, "#formObject");
            }
            // annotationGridObject.selectRowByObject(idObject);
        },
        selectObjectByIdDynamicObject(idDynamicObject) {
            //console.log('getting', idDynamicObject);
            let object = annotation.objects.getByIdDynamicObject(idDynamicObject);
            //console.log('after', object);
            this.selectObject(object.idObject);
        },
        selectObjectFrame(idObject, frameNumber) {
            this.currentObject = annotation.objects.get(idObject);
            // annotationGridObject.selectRowByObject(idObject);
            let time = annotation.video.timeFromFrame(frameNumber);
            annotation.video.player.currentTime(time);
            annotation.objects.drawFrameObject(frameNumber);
            this.newObjectState = 'showing';
        },
        createObject() {
            if (this.currentVideoState === 'paused') {
                //console.log('create object');
                this.selectObject(null);
                this.newObjectState = 'creating';
                annotation.objects.creatingObject();
            }
        },
        async endObject() {
            if (this.currentVideoState === 'paused') {
                //console.log('end object');
                this.currentObject.object.endFrame = this.currentFrame;
                await annotation.objects.saveRawObject(this.currentObject);
                this.selectObject(null);
            }
        },
        async deleteObject(idDynamicObject) {
            if (this.currentVideoState === 'paused') {
                await annotation.api.deleteObject(idDynamicObject);
                this.updateObjectList();
                this.selectObject(null);
            }
        },

        startTracking() {
            console.log('start tracking');
            this.newObjectState = 'tracking';
            this.currentVideoState = 'playing';
            annotation.objects.tracking(true);

        },
        pauseTracking() {
            console.log('pause tracking');
            this.newObjectState = 'tracking';
            this.currentVideoState = 'paused';
        },
        stopTracking() {
            console.log('stop tracking');
            this.currentVideoState = 'paused';
            this.newObjectState = 'showing';
            // this.selectObject(null);
        },
        clear() {
            console.log('clear');
            this.newObjectState = 'none';
            this.selectObject(null);
            htmx.ajax("GET","/annotation/dynamicMode/formObject/0", "#formObject");
        },
        showHideObjects() {
            console.log('show/hide objects');
            if (this.showHideBoxesState === 'show') {
                this.showHideBoxesState = 'hide';
            } else {
                this.showHideBoxesState = 'show';
            }
            annotation.objects.drawFrameBoxes(this.currentFrame);
        },
    });

    Alpine.effect(() => {
        const timeCount = Alpine.store('doStore').timeCount;
        //console.log('timecount change', timeCount);
    });
    Alpine.effect(() => {
        const frameCount = Alpine.store('doStore').frameCount;
        //console.log('framecount change', frameCount);
    });
    Alpine.effect(() => {
        const currentVideoState = Alpine.store('doStore').currentVideoState;
        const newObjectStateTracking = (Alpine.store('doStore').newObjectState === 'tracking');
        if (currentVideoState === 'playing') {
            if (!newObjectStateTracking) {
                $('#btnCreateObject').addClass('disabled');
                $('#btnStartTracking').addClass('disabled');
                $('#btnPauseTracking').addClass('disabled');
                $('#btnEndObject').addClass('disabled');
                $('#btnShowHideObjects').addClass('disabled');
                $('#btnClear').addClass('disabled');
                let rate =  annotation.video.player.playbackRate();
                console.log("======== rate ", rate);
                if (rate > 0.9) {
                    //Alpine.store('doStore').newObjectState = 'none';
                    Alpine.store('doStore').selectObject(null);
                }
            }
        }
        if (currentVideoState === 'paused') {
            if (!newObjectStateTracking) {
                $('#btnCreateObject').removeClass('disabled');
                $('#btnShowHideObjects').removeClass('disabled');
                $('#btnClear').removeClass('disabled');
            }
        }
    });
    Alpine.effect(async () => {
        const newObjectState = Alpine.store('doStore').newObjectState;
        console.log("newobjectstate = " + newObjectState);
        if (newObjectState === 'creating') {
            $('#btnCreateObject').addClass('disabled');
            $('#btnStartTracking').addClass('disabled');
            $('#btnPauseTracking').addClass('disabled');
            // $('#btnStopTracking').addClass('disabled');
            $('#btnEndObject').addClass('disabled');
            $('#btnShowHideObjects').addClass('disabled');
            annotation.video.disablePlayPause();
            annotation.video.disableSkipFrame();
        }
        if (newObjectState === 'created') {
            await annotation.objects.createdObject();
            Alpine.store('doStore').newObjectState = 'tracking';
            Alpine.store('doStore').currentVideoState = 'paused';
            annotation.video.enableSkipFrame();
        }
        if (newObjectState === 'showing') {
            $('#btnCreateObject').addClass('disabled');
            $('#btnStartTracking').removeClass('disabled');
            $('#btnPauseTracking').addClass('disabled');
            // $('#btnStopTracking').addClass('disabled');
            $('#btnEndObject').addClass('disabled');
            $('#btnShowHideObjects').addClass('disabled');
            annotation.video.enablePlayPause();
            annotation.video.enableSkipFrame();
        }
        if (newObjectState === 'tracking') {
            let pausedTracking = Alpine.store('doStore').currentVideoState === 'paused';
            $('#btnCreateObject').addClass('disabled');
            if (pausedTracking) {
                $('#btnStartTracking').removeClass('disabled');
                // $('#btnStopTracking').removeClass('disabled');
                $('#btnPauseTracking').addClass('disabled');
                $('#btnEndObject').removeClass('disabled');
                $('#btnShowHideObjects').removeClass('disabled');
            } else {
                $('#btnStartTracking').addClass('disabled');
                // $('#btnStopTracking').removeClass('disabled');
                $('#btnPauseTracking').removeClass('disabled');
                $('#btnEndObject').addClass('disabled');
                $('#btnShowHideObjects').addClass('disabled');
            }
            annotation.video.disablePlayPause();
        }
        if (newObjectState === 'none') {
            annotation.objects.clearFrameObject();
            $('#btnCreateObject').removeClass('disabled');
            $('#btnStartTracking').addClass('disabled');
            $('#btnPauseTracking').addClass('disabled');
            // $('#btnStopTracking').addClass('disabled');
            $('#btnEndObject').addClass('disabled');
            $('#btnShowHideObjects').removeClass('disabled');
            annotation.video.enablePlayPause();
        }
    });
    Alpine.effect(async () => {
        const dataState = Alpine.store('doStore').dataState;
        if (dataState === 'loaded') {
            console.log('Data Loaded');
            console.log(annotation.objectList);
            window.annotation.objects.annotateObjects(annotation.objectList);
            Alpine.store('doStore').setObjects(annotation.objectList);
            Alpine.store('doStore').newObjectState = 'none';
            Alpine.store('doStore').currentVideoState = 'paused';
        }
    });
});
