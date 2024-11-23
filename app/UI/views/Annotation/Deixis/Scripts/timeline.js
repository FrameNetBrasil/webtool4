var outlineContainer = document.getElementById("outline-container");
var keyframeWithCustomImage = {
    val: 500
};
annotation.timeline = {
    generateModel: function() {
        let timelineModel = {
            rows: [
            ]
        };
        return timelineModel;
    }

};

const timelineModel = annotation.timeline.generateModel();
var timeline = new timelineModule.Timeline();
const defaultKeyframesRenderer = timeline._renderKeyframe.bind(timeline);

// Custom Image
// const image = new Image();
// image.src = "https://material-icons.github.io/material-icons-png/png/white/public/baseline-2x.png"; // replace with your image path
// image.onload = () => {
//     timeline.redraw();
// };

// Set custom keyframes renderer
timeline._renderKeyframe = (ctx, keyframeViewModel) => {
    if (keyframeViewModel.model === keyframeWithCustomImage) {
        ctx.drawImage(image, keyframeViewModel.size.x - 5, keyframeViewModel.size.y - 5, keyframeViewModel.size.width + 5, keyframeViewModel.size.height + 5);
    } else {
        // Use default renderer
        defaultKeyframesRenderer(ctx, keyframeViewModel);
    }
};

timeline.initialize({ id: "timeline", headerHeight: 45 }, timelineModel);

// Select all elements on key down
document.addEventListener("keydown", function(args) {
    if (args.which === 65 && timeline._controlKeyPressed(args)) {
        timeline.selectAllKeyframes();
        args.preventDefault();
    }
});

timeline.onTimeChanged(function(event) {

    showActivePositionInformation();
});

function showActivePositionInformation() {
    console.log("time changed", timeline);
    if (timeline) {
        var fromPx = timeline.scrollLeft;
        var toPx = timeline.scrollLeft + timeline.getClientWidth();
        var fromMs = timeline.pxToVal(fromPx - timeline._leftMargin());
        var toMs = timeline.pxToVal(toPx - timeline._leftMargin());
        var positionInPixels = timeline.valToPx(timeline.getTime()) + timeline._leftMargin();
        var message = "Timeline in ms: " + timeline.getTime() + "ms. Displayed from:" + fromMs.toFixed() + "ms to: " + toMs.toFixed() + "ms.";
        message += "<br>";
        message += "Timeline in px: " + positionInPixels + "px. Displayed from: " + fromPx + "px to: " + toPx + "px";
        console.log(message);
        var currentElement = document.getElementById("currentTime");
        if (currentElement) {
            currentElement.innerHTML = message;
        }
    }
}

timeline.onSelected(function(obj) {
    console.log("Selected Event: (" + obj.selected.length + "). changed selection :" + obj.changed.length, 2);
});

timeline.onDragStarted(function(obj) {
    console.log(obj, "dragstarted");
});

timeline.onDrag(function(obj) {
    console.log(obj, "drag");
});

timeline.onKeyframeChanged(function(obj) {
    console.log("keyframe: " + obj.val);
});

timeline.onDragFinished(function(obj) {
    console.log(obj, "dragfinished");
});

timeline.onContextMenu(function(obj) {
    if (obj.args) {
        obj.args.preventDefault();
    }
    console.log(obj, "addKeyframe");

    obj.elements.forEach(p => {
        if (p.type === "row" && p.row) {
            if (!p.row?.keyframes) {
                p.row.keyframes = [];
            }
            p.row?.keyframes?.push({ val: obj.point?.val || 0 });
        }
    });
    timeline.redraw();
});

timeline.onMouseDown(function(obj) {
    var type = obj.target ? obj.target.type : "";
    if (obj.pos) {
        console.log("mousedown:" + obj.val + ".  target:" + type + ". " + Math.floor(obj.pos.x) + "x" + Math.floor(obj.pos.y), 2);
    }
});

timeline.onDoubleClick(function(obj) {
    var type = obj.target ? obj.target.type : "";
    if (obj.pos) {
        console.log("doubleclick:" + obj.val + ".  target:" + type + ". " + Math.floor(obj.pos.x) + "x" + Math.floor(obj.pos.y), 2);
    }
});

// Synchronize component scroll renderer with HTML list of the nodes.
timeline.onScroll(function(obj) {
    var options = timeline.getOptions();
    if (options) {
        if (outlineContainer) {
            outlineContainer.style.minHeight = obj.scrollHeight + "px";
            const outlineElement = document.getElementById("outline-scroll-container");
            if (outlineElement) {
                outlineElement.scrollTop = obj.scrollTop;
            }
        }
    }
    showActivePositionInformation();
});

timeline.onScrollFinished(function(_) {
    // Stop move component screen to the timeline when user start manually scrolling.
    console.log("on scroll finished", 2);
});

generateHTMLOutlineListNodes(timelineModel.rows);

/**
 * Generate html for the left menu for each row.
 * */
function generateHTMLOutlineListNodes(rows) {
    var options = timeline.getOptions();
    var headerElement = document.getElementById("outline-header");
    if (!headerElement) {
        return;
    }
    headerElement.style.maxHeight = headerElement.style.minHeight = options.headerHeight + "px";
    // headerElement.style.backgroundColor = options.headerFillColor;
    if (!outlineContainer) {
        console.log("Error: Cannot find html element to output outline/tree view");
        return;
    }
    outlineContainer.innerHTML = "";

    rows.forEach(function(row, index) {
        var div = document.createElement("div");
        div.classList.add("outline-node");
        const h = (row.style ? row.style.height : 0) || (options.rowsStyle ? options.rowsStyle.height : 0);
        div.style.maxHeight = div.style.minHeight = h + "px";
        div.style.marginBottom = ((options.rowsStyle ? options.rowsStyle.marginBottom : 0) || 0) + "px";
        div.innerText = row.title || "Track " + index;
        div.id = div.innerText;
        var alreadyAddedWithSuchNameElement = document.getElementById(div.innerText);
        // Combine outlines with the same name:
        if (alreadyAddedWithSuchNameElement) {
            var increaseSize = Number.parseInt(alreadyAddedWithSuchNameElement.style.maxHeight) + h;
            alreadyAddedWithSuchNameElement.style.maxHeight = alreadyAddedWithSuchNameElement.style.minHeight = increaseSize + "px";

            return;
        }
        if (outlineContainer) {
            outlineContainer.appendChild(div);
        }

    });
}

// Handle events from html page
function selectMode() {
    if (timeline) {
        timeline.setInteractionMode("selection");
    }
}

function zoomMode() {
    if (timeline) {
        timeline.setInteractionMode("zoom");
    }
}

function noneMode() {
    if (timeline) {
        timeline.setInteractionMode("none");
    }
}

function removeKeyframe() {
    if (timeline) {
        // Add keyframe
        const currentModel = timeline.getModel();
        if (currentModel && currentModel.rows) {
            currentModel.rows.forEach((row) => {
                if (row.keyframes) {
                    row.keyframes = row.keyframes.filter((p) => !p.selected);
                }
            });
            timeline.setModel(currentModel);
        }
    }
}

function addKeyframe() {
    if (timeline) {
        // Add keyframe
        const currentModel = timeline.getModel();
        if (!currentModel) {
            return;
        }
        currentModel.rows.push({ keyframes: [{ val: timeline.getTime() }] });
        timeline.setModel(currentModel);

        // Generate outline list menu
        generateHTMLOutlineListNodes(currentModel.rows);
    }
}

function panMode(interactive) {
    if (timeline) {
        timeline.setInteractionMode(interactive ? "pan" : "nonInteractivePan");
    }
}

// Set scroll back to timeline when mouse scroll over the outline
function outlineMouseWheel(event) {
    if (timeline) {
        this.timeline._handleWheelEvent(event);
    }
}

var playing = false;
var playStep = 50;
// Automatic tracking should be turned off when user interaction happened.
var trackTimelineMovement = false;

function onPlayClick(event) {
    playing = true;
    trackTimelineMovement = true;
    if (timeline) {
        this.moveTimelineIntoTheBounds();
        // Don't allow to manipulate timeline during playing (optional).
        timeline.setOptions({ timelineDraggable: false });
    }
}

function onPauseClick(event) {
    playing = false;
    if (timeline) {
        timeline.setOptions({ timelineDraggable: true });
    }
}

function moveTimelineIntoTheBounds() {
    if (timeline) {
        if (timeline._startPosMouseArgs || timeline._scrollAreaClickOrDragStarted) {
            // User is manipulating items, don't move screen in this case.
            return;
        }
        const fromPx = timeline.scrollLeft;
        const toPx = timeline.scrollLeft + timeline.getClientWidth();

        let positionInPixels = timeline.valToPx(timeline.getTime()) + timeline._leftMargin();
        // Scroll to timeline position if timeline is out of the bounds:
        if (positionInPixels <= fromPx || positionInPixels >= toPx) {
            this.timeline.scrollLeft = positionInPixels;
        }
    }
}

function initPlayer() {
    setInterval(() => {
        if (playing) {
            if (timeline) {
                timeline.setTime(timeline.getTime() + playStep);
                moveTimelineIntoTheBounds();
            }
        }
    }, playStep);
}

// Note: this can be any other player: audio, video, svg and etc.
// In this case you have to synchronize events of the component and player.
// initPlayer();
// showActivePositionInformation();
// window.onresize = showActivePositionInformation;

// const existingModel = timeline.getModel();
// existingModel.rows[0].keyframes.append({ val: 20 });
// timeline.setModel(existingModel);
// console.log("time", timeline.getTime());
// let change  = timeline.setTime(120000);
// moveTimelineIntoTheBounds();
// console.log("change", change);
// console.log("time", timeline.getTime());
