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
            this.annotateObject(object);
            this.idDynamicObject = object.idDynamicObject;
            this.currentFrame = object.startFrame;
            this._token = token;
            this.boxesContainer = document.querySelector("#boxesContainer");
            this.tracker = new ObjectTrackerObject();
            this.tracker.config({
                canvas: drawBoxObject.canvas,
                ctx: drawBoxObject.ctx,
                video: drawBoxObject.video
            });
        },

        async onVideoUpdateState(e) {
            this.currentFrame = e.detail.frame.current;
            this.drawFrameBBox();
            await this.tracking();
        },

        async onBboxDrawn(e) {
            this.bbox = e.detail.bbox;
            // this.object = new DynamicObject();
            console.log("bboxDrawn",this.object);
            // this.object.setDom(this.newBboxElement());
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
            bbox.idBoundingBox = await ky.post("/annotation/dynamicMode/createBBox", {
                json: {
                    _token: this._token,
                    idDynamicObject: this.idDynamicObject,
                    frameNumber: this.currentFrame,
                    bbox//     bbox: bbox
                }
            }).json();
            console.log("bbox created: ", bbox.idBoundingBox);
            this.tracker.getFrameImage(this.currentFrame);
            this.object.drawBoxInFrame(this.currentFrame, "editing");
            this.canCreateBBox = false;
            messenger.notify("success", "New bbox created.");
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
            console.log("Object annotated");
        },

        createBBox() {
            this.clearFrameObject();
            drawBoxObject.enableDrawing();
            console.log("Drawing mode activated!");
        },

        async toggleTracking() {
            console.log("toogle tracking", this.isTracking ? "tracking" : "stopped");
            if (this.isTracking) {
                this.stopTracking();
            } else {
                if (this.object.getBoundingBoxAt(this.currentFrame)) {
                    await this.startTracking();
                } else {
                    messenger.notify("error", "No bbox found at this frame.");
                }
            }
        },

        async startTracking() {
            document.dispatchEvent(new CustomEvent("tracking-start"));
            this.isTracking = true;
            await this.tracking();
        },

        async tracking() {
            await new Promise(r => setTimeout(r, 800));
            const nextFrame = this.currentFrame + 1;
            // console.log("tracking....", nextFrame,this.object.startFrame, this.object.endFrame);
            if ((this.isTracking) && (nextFrame >= this.object.startFrame) && (nextFrame <= this.object.endFrame)){
                // console.log("goto Frame ", nextFrame);
                this.gotoFrame(nextFrame);
            } else {
                this.stopTracking();
            }
        },

        stopTracking() {
            //console.log("stop tracking");
            this.isTracking = false;
            document.dispatchEvent(new CustomEvent("tracking-stop"));
            this.object.drawBoxInFrame(this.currentFrame, "editing");
        },


        newBboxElement: () => {
            let dom = document.createElement("div");
            dom.className = "bbox";
            this.boxesContainer.appendChild(dom);
            return dom;
        },

        interactify: (object, onChange) => {
            let dom = object.dom;
            console.log(dom);
            let bbox = $(dom);
            console.log(bbox);

                let handle = document.createElement("div");
                handle.className = "objectId";
                bbox.append(handle);
                handle.innerHTML = object.idObject;

            let position = { x: bbox.position().left, y: bbox.position().top };

            interact(dom)
                .resizable({
                    // resize from all edges and corners
                    edges: { left: true, right: true, bottom: true, top: true },

                    listeners: {
                        move (event) {
                            var target = event.target;
                            var x = (parseFloat(target.getAttribute('data-x')) || 0);
                            var y = (parseFloat(target.getAttribute('data-y')) || 0);

                            // update the element's style
                            target.style.width = event.rect.width + 'px';
                            target.style.height = event.rect.height + 'px';

                            // translate when resizing from top or left edges
                            x += event.deltaRect.left;
                            y += event.deltaRect.top;

                            target.style.transform = 'translate(' + x + 'px,' + y + 'px)';

                            target.setAttribute('data-x', x);
                            target.setAttribute('data-y', y);
                            //target.textContent = Math.round(event.rect.width) + '\u00D7' + Math.round(event.rect.height);
                            console.log("data-x",parseFloat(target.getAttribute('data-x')));
                            console.log("data-y",parseFloat(target.getAttribute('data-y')));
                            $target = $(target);
                            const position = $target.position();
                            console.log(position.left,position.top,Math.round($target.outerWidth()),$target.outerHeight());
                            onChange(position.left, position.top, Math.round(bbox.outerWidth()), Math.round(bbox.outerHeight()));
                        }
                    },
                    modifiers: [
                        // keep the edges inside the parent
                        interact.modifiers.restrictEdges({
                            outer: '#boxesContainer'
                        }),

                        // minimum size
                        interact.modifiers.restrictSize({
                            min: { width: 20, height: 20 }
                        })
                    ],

                    inertia: true
                })
                .draggable({
                    listeners: {
                        start(event) {
                            console.log('Drag started');
                            event.target.classList.add('dragging');
                        },

                        move(event) {
                            // Update position
                            position.x += event.dx;
                            position.y += event.dy;

                            // Apply the transformation
                            event.target.style.transform = `translate(${position.x}px, ${position.y}px)`;

                        },

                        end(event) {
                            console.log('Drag ended');
                            var target = event.target;
                            // keep the dragged position in the data-x/data-y attributes
                            var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                            var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                            // translate the element
                            target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';

                            // update the posiion attributes
                            target.setAttribute('data-x', x);
                            target.setAttribute('data-y', y);
                            event.target.classList.remove('dragging');
                            $target = $(target);
                            const position = $target.position();
                            console.log(position.left,position.top,Math.round($target.outerWidth()),$target.outerHeight());
                            onChange(position.left, position.top, Math.round(bbox.outerWidth()), Math.round(bbox.outerHeight()));
                        }
                    },
                    inertia: true,
                    modifiers: [
                        interact.modifiers.restrictRect({
                            restriction: 'parent',
                            endOnly: true
                        })
                    ]
                });

            // /*
            //     registra os listeners para interação com a boundingbox (dom) associada com o objeto
            //  */
            // let dom = object.dom;
            // console.log(dom);
            // let bbox = $(dom);
            // console.log(bbox);
            // let createHandleDiv = (className, content = null) => {
            //     //console.log('className = ' + className + '  content = ' + content);
            //     let handle = document.createElement("div");
            //     handle.className = className;
            //     bbox.append(handle);
            //     if (content !== null) {
            //         handle.innerHTML = content;
            //     }
            //     return handle;
            // };
            // let x = createHandleDiv("handle center-drag");
            // let i = createHandleDiv("objectId", object.idObject);
            // bbox.resizable({
            //     handles: "n, e, s, w",
            //      // containment: "#canvas",
            //     start:(e,ui) => {
            //     //    console.log("start", ui);
            //     },
            //     resize: (e,ui) => {
            //         console.log(ui);
            //         const $container = $("#canvas");
            //         const containerWidth = $container.outerWidth();
            //         const containerHeight = $container.outerHeight();
            //         console.log("w",ui.originalElement.outerWidth());
            //         console.log("h",ui.originalElement.outerHeight());
            //         console.log("container",containerWidth,containerHeight);
            //
            //         let width = bbox.outerWidth();
            //         let height = bbox.outerHeight();
            //         let { top, left } = ui.position;
            //         console.log("bbox",top, left, width, height);
            //
            //         // Right boundary check
            //         // if (left + width > containerWidth) {
            //         //     //width = containerWidth - left;
            //         // }
            //         // if (left > (left + width - 20)) {
            //         //
            //         // }
            //
            //         // Bottom boundary check
            //         // if (top + height > containerHeight) {
            //         //     //height = containerHeight - top;
            //         // }
            //
            //         //Left boundary check (when resizing from left edge)
            //         if (left < 0) {
            //         //    width += left; // Compensate width
            //             left = 0;
            //         }
            //
            //         //Top boundary check (when resizing from top edge)
            //         if (top < 0) {
            //         //    height += top; // Compensate height
            //             top = 0;
            //         }
            //
            //         // Minimum size constraints
            //         // width = Math.max(width, 20);  // min width
            //         // height = Math.max(height, 20); // min height
            //         // console.log("after",top, left, width, height);
            //
            //         // Apply the corrected values
            //         ui.size.width = width;
            //         ui.size.height = height;
            //         ui.position.top = top;
            //         ui.position.left = left;
            //     },
            //     stop: (e, ui) => {
            //         let position = bbox.position();
            //         console.log("stopd resize position", position);
            //         console.log("resize width", ui.size.width);
            //          console.log("resize height", ui.size.height);
            //         //onChange(Math.round(position.left), Math.round(position.top), Math.round(bbox.outerWidth()), Math.round(bbox.outerHeight()));
            //         let { top, left } = ui.position;
            //         onChange(left, top, ui.size.width, ui.size.height);
            //     }
            // });
            // i.addEventListener("click", function() {
            //     let idObject = parseInt(this.innerHTML);
            //     //Alpine.store("doStore").selectObject(idObject);
            // });
            // bbox.draggable({
            //     handle: $(x),
            //     containment: "#canvas",
            //     scroll: false,
            //     drag: (e) => {
            //         // const position = bbox.position();
            //         // const width = bbox.outerWidth();
            //         // const height = bbox.outerHeight();
            //         // console.log("drag position", position);
            //         // console.log("drag width", bbox.outerWidth());
            //         // console.log("drag height", bbox.outerHeight());
            //         //
            //         // if (position.left < 0) {
            //         //     bbox.left = 0;
            //         // }
            //         // if (position.top < 0) {
            //         //     e.target.top = 0;
            //         // }
            //         // if (position.left + width > $("#canvas").width()) {
            //         //     bbox.left = $("#canvas").width() - width;
            //         // }
            //         // if (position.top + height > $("#canvas").height()) {
            //         //     bbox.top = $("#canvas").height() - height;
            //         // }
            //
            //         // var d = e.data;
            //         // console.log("e", e);
            //         // if (d.left < 0) {
            //         //     d.left = 0;
            //         // }
            //         // if (d.top < 0) {
            //         //     d.top = 0;
            //         // }
            //         // if (d.left + $(d.target).outerWidth() > $("#canvas").width()) {
            //         //     d.left = $("#canvas").width() - $(d.target).outerWidth();
            //         // }
            //         // if (d.top + $(d.target).outerHeight() > $("#canvas").height()) {
            //         //     d.top = $("#canvas").height() - $(d.target).outerHeight();
            //         // }
            //     },
            //     stop: (e) => {
            //         let position = bbox.position();
            //         console.log("stopdrag position", position);
            //         onChange(Math.round(position.left), Math.round(position.top), Math.round(bbox.outerWidth()), Math.round(bbox.outerHeight()));
            //     }
            // });
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
        },

        async drawFrameBBox() {
            if (this.object) {
                this.clearFrameObject();
                if (this.isTracking) {
                    // se está tracking, a box:
                    // - ou já existe (foi criada antes)
                    // - ou precisa ser criada
                    let bbox = this.object.getBoundingBoxAt(this.currentFrame);
                    if (bbox === null) {
                        await this.tracker.setBBoxForObject(this.object, this.currentFrame);
                        let bbox = this.object.getBoundingBoxAt(this.currentFrame);
                        // console.log("creating bbox at frame", this.currentFrame);
                        bbox.idBoundingBox = await ky.post("/annotation/dynamicMode/createBBox", {
                            json: {
                                _token: this._token,
                                idDynamicObject: this.idDynamicObject,
                                frameNumber: this.currentFrame,
                                bbox
                            }
                        }).json();
                    }
                }
                // let x = this.object.getBoundingBoxAt(this.currentFrame);
                // console.log("drawFrameBBox", this.currentFrame,x ? 'ok' : 'nine');
                this.object.drawBoxInFrame(this.currentFrame, this.isTracking ? "tracking" : "editing");
            }
        }
    };
}
