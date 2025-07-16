let drawBoxObject = {
    canvas: null,//document.getElementById("canvas"),
    ctx: null,//document.getElementById("canvas").getContext("2d"),
    color: '#000',//vatic.getColor(0),
    prevStartX: 0,
    prevStartY: 0,
    prevWidth: 0,
    prevHeight: 0,
    box: {
        x: 0,
        y: 0,
        width: 0,
        height: 0
    },
    config(config) {
        this.canvas = document.getElementById("canvas");
        this.ctx = document.getElementById("canvas").getContext("2d");
        this.color = vatic.getColor(0);
        console.log("drawBoxObject config");
        let video = document.getElementById(config.idVideoDOMElement);
        const rect = video.getBoundingClientRect();
        let $canvas = document.querySelector("#canvas");
        this.offsetX = rect.x;
        this.offsetY = rect.y;
        $canvas.width = config.videoDimensions.width;
        $canvas.height = config.videoDimensions.height;
        $canvas.style.position = "absolute";
        $canvas.style.top = "0px";
        $canvas.style.left = "0px";
        $canvas.style.backgroundColor = "transparent";
        $canvas.style.zIndex = 1;
    },
    init() {
        this.isDown = false;
    },
    handleMouseDown(e) {
        e.preventDefault();
        e.stopPropagation();

        // save the starting x/y of the rectangle
        this.startX = parseInt(e.clientX - this.offsetX);
        this.startY = parseInt(e.clientY - this.offsetY);

        // set a flag indicating the drag has begun
        this.isDown = true;
    },
    handleMouseUp(e) {
        e.preventDefault();
        e.stopPropagation();
        this.isDown = false;

        if ((this.prevWidth !== 0) && (this.prevHeight !== 0)) {
            // the drag is over, clear the dragging flag
            console.log("up", this.prevStartX, this.prevStartY, this.prevWidth, this.prevHeight);

            // clear the canvas
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        }
    },
    handleMouseOut(e) {
        e.preventDefault();
        e.stopPropagation();

        // the drag is over, clear the dragging flag
        this.isDown = false;
    },
    handleMouseMove(e) {
        e.preventDefault();
        e.stopPropagation();

        // if we're not dragging, just return
        if (!this.isDown) {
            return;
        }

        // get the current mouse position
        this.mouseX = parseInt(e.clientX - this.offsetX);
        this.mouseY = parseInt(e.clientY - this.offsetY);

        // Put your mousemove stuff here

        // calculate the rectangle width/height based
        // on starting vs current mouse position
        var width = this.mouseX - this.startX;
        var height = this.mouseY - this.startY;

        // clear the canvas
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // draw a new rect from the start position
        // to the current mouse position
        this.ctx.strokeStyle = this.color.bg;
        this.ctx.strokeRect(this.startX, this.startY, width, height);

        this.prevStartX = this.startX;
        this.prevStartY = this.startY;

        this.prevWidth = width;
        this.prevHeight = height;

        this.box = {
            x: this.startX,
            y: this.startY,
            width: width,
            height: height
        };
    }
};
