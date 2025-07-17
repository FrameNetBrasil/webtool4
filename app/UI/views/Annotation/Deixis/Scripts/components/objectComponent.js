function objectComponent(object, token) {
    return {
        object: null,
        idDynamicObject: null,
        _token: "",
        bbox: null,
        boxesContainer: null,
        currentFrame: 0,
        tracker: null,
        isTracking: false,
        canCreateBBox: false,
        hasBBoxInCurrentFrame: false,

        init() {
            console.log("Object component init");
            this.idDynamicObject = object.idDynamicObject;
            this._token = token;
            this.boxesContainer = document.querySelector("#boxesContainer");
            this.tracker = new ObjectTrackerObject();
            this.tracker.config({
                canvas: drawBoxObject.canvas,
                ctx: drawBoxObject.ctx,
                video: drawBoxObject.video
            });
        },

        onVideoUpdateState(e) {
            if (this.currentFrame === 0) {
                this.annotateObject(object);
            }
            this.currentFrame = e.detail.frame.current;
            console.log("onVideoUpdateState", this.currentFrame);
            console.log("onVideoUpdateState",this.isTracking ? "tracking" : "stopped");

            if (this.isTracking) {
                this.tracking();
            }
            this.hasBBoxInCurrentFrame = this.object.hasBBoxInFrame(this.currentFrame);
            if (this.hasBBoxInCurrentFrame) {
                this.object.drawBoxInFrame(this.currentFrame, this.isTracking ? "tracking" : "editing");
            }
        },

        annotateObject(object) {
            console.log("annotateObject");
            this.object = new DynamicObject(object);
            this.object.dom = this.newBboxElement();
            this.interactify(
                this.object,
                async (x, y, width, height) => {
                    let bbox = new BoundingBox(this.currentFrame, x, y, width, height, true);
                    this.object.updateBBox(bbox);
                    await ky.post("/annotation/dynamicMode/updateBBox", {
                        json: {
                            _token: this._token,
                            idBoundingBox: bbox.idBoundingBox,
                            bbox
                        }
                    }).json();
                }
            );
            let bboxes = object.bboxes;
            for (let j = 0; j < bboxes.length; j++) {
                let bbox = object.bboxes[j];
                let frameNumber = parseInt(bbox.frameNumber);
                let isGroundThrough = true;// parseInt(topLeft.find('l').text()) == 1;
                let x = parseInt(bbox.x);
                let y = parseInt(bbox.y);
                let w = parseInt(bbox.width);
                let h = parseInt(bbox.height);
                let newBBox = new BoundingBox(frameNumber, x, y, w, h, isGroundThrough, parseInt(bbox.idBoundingBox));
                newBBox.blocked = (parseInt(bbox.blocked) === 1);
                this.object.addBBox(newBBox);
            }
            this.canCreateBBox = !this.object.hasBBox();
        },

        createBBox() {
            this.clearFrameObject();
            drawBoxObject.enableDrawing();
            console.log("Drawing mode activated!");
        },

        async toggleTracking() {
            console.log("toogle tracking",this.isTracking ? "tracking" : "stopped");
            if (this.isTracking) {
                this.stopTracking();
            } else {
                await this.startTracking();
            }
            // this.isPlayingTracking = !this.isPlayingTracking;
        },

        async startTracking() {
            document.dispatchEvent(new CustomEvent("tracking-start"));
            await this.tracking();
        },

        async tracking() {
            const createDelay = (ms) => new Promise(resolve => setTimeout(resolve, ms));
            const nextFrame = this.currentFrame + 1;
            console.log("tracking....", nextFrame);
            await createDelay(800);
            this.gotoFrame(nextFrame);
        },

        stopTracking() {
            console.log("stop tracking");
            this.isTracking = false;
            document.dispatchEvent(new CustomEvent("tracking-stop"));
            this.object.drawBoxInFrame(this.currentFrame, "editing");
        },

        async bboxDrawn(e) {
            this.bbox = e.detail.bbox;
            console.log(this.object);
            this.object.setDom(this.newBboxElement());
            let bbox = new BoundingBox(this.currentFrame, this.bbox.x, this.bbox.y, this.bbox.width, this.bbox.height, true, null);
            this.object.addBBox(bbox);
            this.interactify(
                this.object,
                async (x, y, width, height, idBoundingBox) => {
                    let bbox = new BoundingBox(this.currentFrame, x, y, width, height, true);
                    this.object.updateBBox(bbox);
                    await ky.post("/annotation/dynamicMode/updateBBox", {
                        json: {
                            _token: this._token,
                            idBoundingBox: bbox.idBoundingBox,
                            bbox
                        }
                    }).json();
                }
            );
            // console.log(this.idDynamicObject, this.currentFrame, bbox);
            drawBoxObject.disableDrawing();
            await ky.post("/annotation/dynamicMode/createBBox", {
                json: {
                    _token: this._token,
                    idDynamicObject: this.idDynamicObject,
                    frameNumber: this.currentFrame,
                    bbox//     bbox: bbox
                }
            }).json();
            this.tracker.getFrameImage(this.currentFrame);
            this.object.drawBoxInFrame(this.currentFrame, "tracking");
            manager.notify("success", "New bbox created.");
        },

        newBboxElement: () => {
            let dom = document.createElement("div");
            dom.className = "bbox";
            this.boxesContainer.appendChild(dom);
            return dom;
        },

        interactify: (object, onChange) => {
            /*
                registra os listeners para interação com a boundingbox (dom) associada com o objeto
             */
            let dom = object.dom;
            let bbox = $(dom);
            let createHandleDiv = (className, content = null) => {
                //console.log('className = ' + className + '  content = ' + content);
                let handle = document.createElement("div");
                handle.className = className;
                bbox.append(handle);
                if (content !== null) {
                    handle.innerHTML = content;
                }
                return handle;
            };
            let x = createHandleDiv("handle center-drag");
            let i = createHandleDiv("objectId", object.idObject);
            bbox.resizable({
                handles: "n, e, s, w",
                onStopResize: (e) => {
                    let position = bbox.position();
                    onChange(Math.round(position.left), Math.round(position.top), Math.round(bbox.width()), Math.round(bbox.height()));
                }
            });
            i.addEventListener("click", function() {
                let idObject = parseInt(this.innerHTML);
                //Alpine.store("doStore").selectObject(idObject);
            });
            bbox.draggable({
                handle: $(x),
                onDrag: (e) => {
                    var d = e.data;
                    if (d.left < 0) {
                        d.left = 0;
                    }
                    if (d.top < 0) {
                        d.top = 0;
                    }
                    if (d.left + $(d.target).outerWidth() > $("#canvas").width()) {
                        d.left = $("#canvas").width() - $(d.target).outerWidth();
                    }
                    if (d.top + $(d.target).outerHeight() > $("#canvas").height()) {
                        d.top = $("#canvas").height() - $(d.target).outerHeight();
                    }
                },
                onStopDrag: (e) => {
                    let position = bbox.position();
                    // console.log("stopdrag position", position);
                    onChange(Math.round(position.left), Math.round(position.top), Math.round(bbox.width()), Math.round(bbox.height()));
                }
            });
            bbox.css("display", "none");
        },

        clearFrameObject: function() {
            $(".bbox").css("display", "none");
        },

        gotoFrame(frameNumber) {
            document.dispatchEvent(new CustomEvent("video-seek-frame", {
                detail: {
                    frameNumber
                }
            }));
        }


    };
}
