function objectComponent(object, token) {
    return {
        object: null,
        idDynamicObject: null,
        _token: "",
        bbox: null,
        boxesContainer: null,
        currentFrame: 0,
        tracker: null,

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

            document.addEventListener("bbox-drawn", this.bboxDrawn.bind(this));
            document.addEventListener("video-update-state", (e) => {
                this.currentFrame = e.detail.frame.current;
                console.log("%% update object current frame to " + this.currentFrame);
                this.annotateObject(object);
            });

        },

        annotateObject(object) {
            this.object = new DynamicObject(object);
            //let annotatedObject = new DeixisObject(object);
            this.object.dom = this.newBboxElement();
            //annotation.layerList[indexLayer].objects[indexObj] = annotatedObject;
            //annotation.objects.add(annotatedObject);
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
            this.object.drawBoxInFrame(this.currentFrame, "editing");
        },

        createBBox() {
            this.clearFrameObject();
            drawBoxObject.enableDrawing();
            console.log("Drawing mode activated!");
        },

        stopBBox() {
            drawBoxObject.disableDrawing();
            console.log("Drawing mode deactivated!");
        },

        async bboxDrawn(e) {
            console.log("bbox drawn", e);

            this.bbox = e.detail.bbox;
            console.log(this.object);
            //let object = new DynamicObject();
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
            console.log(this.idDynamicObject, this.currentFrame, bbox);
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
            this.object.drawBoxInFrame(this.currentFrame, "editing");
            manager.notify("success", "New bbox created.");

            // let bbox = new BoundingBox(currentFrame, tempObject.bbox.x, tempObject.bbox.y, tempObject.bbox.width, tempObject.bbox.height, true, null);
            // console.log(annotatedObject);
            // annotatedObject.addBBox(bbox);
            // annotation.objects.interactify(
            //     annotatedObject,
            //     async (x, y, width, height, idBoundingBox) => {
            //
            //         let currentFrame = Alpine.store("doStore").currentFrame;
            //         let bbox = new BoundingBox(currentFrame, x, y, width, height, true);
            //         annotatedObject.updateBBox(bbox);
            //         //console.log("update annotated object bbox", bbox);
            //         //let bbox = annotatedObject.getBoundingBoxAt(currentFrame);
            //         annotation.api.updateBBox({
            //             idBoundingBox: bbox.idBoundingBox,
            //             bbox: bbox
            //         });
            //     }
            // );
            // //console.log("##### creating newBBox");
            // //let data = await annotation.objects.createBBox(annotatedObject);
            // let paramsBBox = {
            //     idDynamicObject: annotatedObject.idDynamicObject,
            //     frameNumber: currentFrame,
            //     bbox: bbox
            // };
            // console.log("##### creating new BBox");
            // await annotation.api.createBBox(paramsBBox, async (idBoundingBox) => {
            //     console.log(idBoundingBox);
            //     console.log("new BoundingBox", idBoundingBox);
            //     bbox.idBoundingBox = idBoundingBox;
            // });
            // //await Alpine.store("doStore").loadLayerList();
            // console.log("##### New bbox created.");
            // Alpine.store("doStore").newObjectState = "tracking";
            // annotation.objects.tracker.getFrameImage(currentFrame);
            // annotatedObject.drawBoxInFrame(currentFrame, "editing");
            // Alpine.store("doStore").uiEnableTracking();
            // manager.notify("success", "New bbox created.");


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



    };
}
