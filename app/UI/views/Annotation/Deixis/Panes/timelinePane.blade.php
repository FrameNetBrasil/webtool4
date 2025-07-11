<style>

    /* Main timeline wrapper - responsive container */
    .timeline-wrapper {
        width: 100%;
        max-width: 100%;
        min-width: 400px;
        overflow: hidden;
    }

    .timeline-container {
        width: 100%;
        border: 1px solid #ccc;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Controls at top */
    .timeline-controls {
        flex-shrink: 0;
        padding: 10px;
        background-color: #fff;
        border-bottom: 1px solid #ddd;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .timeline-controls input {
        padding: 4px 8px;
        border: 1px solid #ccc;
        border-radius: 2px;
        width: 80px;
    }

    .timeline-controls button {
        padding: 4px 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 2px;
        cursor: pointer;
    }

    .timeline-controls button:hover {
        background-color: #0056b3;
    }

    /* Ruler at top - split into label area + ruler content */
    .timeline-ruler {
        flex-shrink: 0;
        height: 30px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
        display: flex;
    }

    .ruler-label-area {
        width: 150px;
        background-color: #e9ecef;
        border-right: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 11px;
        color: #666;
        flex-shrink: 0;
    }

    .ruler-content-area {
        flex: 1;
        overflow: hidden;
        position: relative;
    }

    .ruler-content {
        position: relative;
        height: 30px;
        width: 22500px; /* Timeline width */
    }

    .ruler-tick {
        position: absolute;
        height: 20px;
        border-left: 1px solid #999;
        font-size: 10px;
        padding-left: 2px;
        display: flex;
        align-items: center;
        top: 5px;
    }

    .ruler-tick.major {
        border-left: 2px solid #333;
        font-weight: bold;
    }

    /* Main content area - split into labels + timeline */
    .timeline-main {
        flex: 1;
        display: flex;
        /*overflow: hidden;*/
        min-height: 0;
    }

    /* Labels column - fixed width, scrolls vertically */
    .labels-column {
        width: 150px;
        background-color: #e9ecef;
        border-right: 1px solid #ddd;
        overflow-y: auto;
        flex-shrink: 0;
        /* Hide scrollbar cross-browser */
        scrollbar-width: none;        /* Firefox */
        -ms-overflow-style: none;     /* IE/Edge */
    }

    .labels-column::-webkit-scrollbar {
        display: none;                /* Chrome/Safari/WebKit */
    }

    .label-item {
        padding: 8px;
        border-bottom: 1px solid #ddd;
        font-size: 12px;
        font-weight: bold;
        color: #333;
        min-height: 24px;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    /* Timeline content area - scrollable */
    .timeline-content {
        flex: 1;
        overflow-x: scroll; /* Horizontal scrollbar appears here */
        overflow-y: auto; /* Vertical scrollbar appears here */
        position: relative;
    }

    .timeline-inner {
        width: {{ $timeline['config']['timelineWidth'] }}px;
        position: relative;
        /*padding-bottom: 10px; !* Small space for horizontal scrollbar *!*/
    }

    .layer-row {
        position: relative;
        border-bottom: 1px solid #eee;
        min-height: 24px; /* Minimum layer height */
    }

    /* Timeline objects */
    .timeline-object {
        position: absolute;
        height: 24px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 2px;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 0 4px;
        font-size: 10px;
        color: white;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        box-sizing: border-box;
        min-width: 16px;
        transition: opacity 0.2s, transform 0.2s;
    }

    .timeline-object:hover {
        opacity: 0.8;
        transform: scale(1.02);
        z-index: 5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .timeline-object.highlighted {
        outline: 2px solid #ff0000;
        outline-offset: 1px;
    }

    /* Info bar at bottom */
    .timeline-info {
        flex-shrink: 0;
        background-color: #f8f9fa;
        /*border-top: 1px solid #ddd;*/
        padding: 8px;
        font-size: 12px;
        color: #666;
    }
</style>

<div class="timeline-wrapper">
    <div class="timeline-container">
        <!-- Controls -->
        <div class="timeline-controls">
            <label>Go to frame:
                <input type="number" id="frame-input" value="5000" min="0" max="22000">
            </label>
            <button onclick="scrollToFrame()">Go</button>
            <button onclick="scrollToStart()">Start</button>
            <button onclick="scrollToEnd()">End</button>
            <span id="current-frame">Frame: 0</span>
        </div>

        <!-- Ruler -->
        <div class="timeline-ruler">
            <div class="ruler-label-area">
                Frames
            </div>
            <div class="ruler-content-area">
                <div class="ruler-content" id="ruler-content">
                    <!-- Ruler ticks will be generated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <div class="timeline-main">
            <!-- Labels column -->
            <div class="labels-column" id="labels-column">
                @foreach ($groupedLayers as $visualLayer)
                    @php $layerHeight = count($visualLayer['lines']) * $timeline['config']['objectHeight']; @endphp
                    <div class="label-item" style="height: {{ $layerHeight }}px;">
                        {{ $visualLayer['name'] }}
                    </div>
                @endforeach
            </div>
            <!-- Timeline content -->
            <div class="timeline-content" id="timeline-content" style="">
                <div class="timeline-inner" style="width: {{ $timeline['config']['timelineWidth'] }}px;">
                    @foreach ($groupedLayers as $visualLayerIndex => $visualLayer)
                        @php
                            $layerHeight = count($visualLayer['lines']) * $timeline['config']['objectHeight'];
                        @endphp
                        <div class="layer-row" style="height: {{ $layerHeight }}px;">
                            <!-- Layer Objects -->
                            <div class="layer-objects" style="height: {{ $layerHeight }}px;">
                                @foreach ($visualLayer['lines'] as $lineIndex => $line)
                                    @foreach ($line['objects'] as $objIndex => $object)
                                        @php
                                            $startPos = ($object->startFrame - $timeline['config']['minFrame']) * $timeline['config']['frameToPixel'];
                                            $duration = $object->endFrame - $object->startFrame;
                                            $width = max($timeline['config']['minObjectWidth'], $duration * $timeline['config']['frameToPixel']);
                                            $bgColor = $object->bgColorGL ?? '#999999';
                                            $label = $object->gl ?? $object->luName ?? $object->name ?? "Object " . ($objIndex + 1);
                                            $top = $lineIndex * $timeline['config']['objectHeight'];
                                            $tooltip = $label . "\nFrames: " . $object->startFrame . "-" . $object->endFrame . "\nDuration: " . $duration . " frames";
                                        @endphp
                                        <div class="timeline-object"
                                             style="left: {{ $startPos }}px; top: {{ $top }}px; width: {{ $width }}px; background-color: {{ $bgColor }};"
                                             title="{{ $tooltip }}"
                                             data-layer-index="{{ $line['originalIndex'] }}"
                                             data-object-index="{{ $objIndex }}"
                                             data-line-index="{{ $lineIndex }}"
                                             data-start-frame="{{ $object->startFrame }}"
                                             data-end-frame="{{ $object->endFrame }}"
                                             hx-post="/timeline/object-click"
                                             hx-vals='{"layerIndex": "{{ $line['originalIndex'] }}", "objectIndex": "{{ $objIndex }}", "lineIndex": "{{ $lineIndex }}"}'
                                             hx-target="#object-click-info"
                                             hx-swap="innerHTML">
                                            {{ $label }}
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Info bar -->
        <div class="timeline-info" id="timeline-info">
            Timeline ready - scroll to navigate
        </div>
    </div>
</div>

<!-- Dynamic Content Areas -->
<div id="highlight-container"></div>
<div id="object-click-info"></div>

<script>

    // Configuration
    const config = {
        minFrame: {{$timeline['config']['minFrame']}},
        maxFrame: {{$timeline['config']['maxFrame']}},
        frameToPixel: {{$timeline['config']['maxFrame']}},
        labelWidth: {{$timeline['config']['maxFrame']}}
    };

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Timeline config:', config); // Debug config
        generateRuler();
        setupScrollSync();

        const timelineContent = document.getElementById('timeline-content');
        const timelineInfo = document.getElementById('timeline-info');
        const rulerContent = document.getElementById('ruler-content');
        const labelsColumn = document.getElementById('labels-column');

        if (timelineContent && timelineInfo) {
            timelineContent.addEventListener('scroll', function() {
                const scrollLeft = this.scrollLeft;
                const scrollTop = this.scrollTop;
                const viewportWidth = this.clientWidth;

                // Frame calculation (1px = 1 frame, starting from 0)
                const frameStart = Math.floor(scrollLeft);
                const frameEnd = Math.floor(scrollLeft + viewportWidth);

                // Update frame info
                timelineInfo.textContent = `Viewing frames: ${frameStart.toLocaleString()} - ${frameEnd.toLocaleString()}`;

                // Sync ruler
                if (rulerContent) {
                    rulerContent.style.transform = `translateX(-${scrollLeft}px)`;
                }

                // Sync labels
                if (labelsColumn) {
                    labelsColumn.scrollTop = scrollTop;
                }
            });

            // Labels scroll back to timeline
            if (labelsColumn) {
                labelsColumn.addEventListener('scroll', function() {
                    timelineContent.scrollTop = this.scrollTop;
                });
            }
        }
    });
    // Generate ruler ticks
    function generateRuler() {
        const rulerContent = document.getElementById('ruler-content');
        rulerContent.innerHTML = '';

        // Major ticks every 1000 frames
        // for (let frame = config.minFrame; frame <= config.maxFrame; frame += 1000) {
        //     const tick = document.createElement('div');
        //     tick.className = 'ruler-tick major';
        //     tick.style.left = frame + 'px';
        //     tick.textContent = frame.toLocaleString();
        //     rulerContent.appendChild(tick);
        // }
        //
        // Minor ticks every 100 frames
        for (let frame = 100; frame <= config.maxFrame; frame += 100) {
            const tick = document.createElement('div');
            if (frame % 1000 === 0) {
                tick.className = 'ruler-tick major';
            } else {
                tick.className = 'ruler-tick';
            }
            tick.style.left = frame + 'px';
            tick.textContent = frame.toLocaleString();
            rulerContent.appendChild(tick);
        }
    }

    // Setup scroll synchronization
    function setupScrollSync() {
        const timelineContent = document.getElementById('timeline-content');
        const labelsColumn = document.getElementById('labels-column');
        const rulerContent = document.getElementById('ruler-content');
        const timelineInfo = document.getElementById('timeline-info');
        const currentFrame = document.getElementById('current-frame');

        console.log('Setting up scroll sync with elements:', {
            timelineContent: !!timelineContent,
            labelsColumn: !!labelsColumn,
            rulerContent: !!rulerContent,
            timelineInfo: !!timelineInfo,
            currentFrame: !!currentFrame
        });

        if (!timelineContent || !timelineInfo) {
            console.error('Critical elements not found for scroll sync!');
            return;
        }

        // Function to update frame info
        function updateFrameInfo(scrollLeft, viewportWidth) {
            const frameStart = Math.floor(scrollLeft / config.frameToPixel) + config.minFrame;
            const frameEnd = Math.floor((scrollLeft + viewportWidth) / config.frameToPixel) + config.minFrame;

            console.log('Updating frame info:', {
                scrollLeft,
                viewportWidth,
                frameStart,
                frameEnd
            });

            if (timelineInfo) {
                timelineInfo.textContent = `Viewing frames: ${frameStart.toLocaleString()} - ${frameEnd.toLocaleString()}`;
            }

            if (currentFrame) {
                currentFrame.textContent = `Frame: ${frameStart.toLocaleString()}`;
            }
        }

        // Main timeline scroll event
        timelineContent.addEventListener('scroll', function(event) {
            console.log('Scroll event fired!', { scrollLeft: this.scrollLeft, scrollTop: this.scrollTop });

            const scrollLeft = this.scrollLeft;
            const scrollTop = this.scrollTop;
            const viewportWidth = this.clientWidth;

            // Sync ruler horizontally
            if (rulerContent) {
                rulerContent.style.transform = `translateX(-${scrollLeft}px)`;
            }

            // Sync labels vertically
            if (labelsColumn) {
                labelsColumn.scrollTop = scrollTop;
            }

            // Update frame info
            updateFrameInfo(scrollLeft, viewportWidth);
        });

        // Labels scroll back to timeline
        if (labelsColumn) {
            labelsColumn.addEventListener('scroll', function() {
                timelineContent.scrollTop = this.scrollTop;
            });
        }

        // Initial update
        const initialScrollLeft = timelineContent.scrollLeft;
        const initialViewportWidth = timelineContent.clientWidth;
        updateFrameInfo(initialScrollLeft, initialViewportWidth);

        console.log('Scroll sync setup complete');
    }

    // Navigation functions
    function scrollToFrame() {
        const frameNumber = parseInt(document.getElementById('frame-input').value) || 0;
        const timelineContent = document.getElementById('timeline-content');

        const framePosition = (frameNumber - config.minFrame) * config.frameToPixel;
        const viewportWidth = timelineContent.clientWidth;
        const centerOffset = viewportWidth / 2;

        let scrollPosition = framePosition - centerOffset;
        scrollPosition = Math.max(0, scrollPosition);

        const maxScroll = timelineContent.scrollWidth - timelineContent.clientWidth;
        scrollPosition = Math.min(scrollPosition, maxScroll);

        timelineContent.scrollTo({
            left: scrollPosition,
            behavior: 'smooth'
        });
    }

    function scrollToStart() {
        document.getElementById('timeline-content').scrollTo({
            left: 0,
            behavior: 'smooth'
        });
    }

    function scrollToEnd() {
        const timelineContent = document.getElementById('timeline-content');
        timelineContent.scrollTo({
            left: timelineContent.scrollWidth - timelineContent.clientWidth,
            behavior: 'smooth'
        });
    }

    // Object click handler
    function objectClick(element) {
        const rect = element.getBoundingClientRect();
        const timelineContent = document.getElementById('timeline-content');
        const timelineRect = timelineContent.getBoundingClientRect();

        const relativeLeft = rect.left - timelineRect.left + timelineContent.scrollLeft;
        const startFrame = Math.round(relativeLeft / config.frameToPixel) + config.minFrame;
        const width = rect.width;
        const duration = Math.round(width / config.frameToPixel);
        const endFrame = startFrame + duration;

        console.log('Object clicked:', {
            element: element.textContent,
            startFrame: startFrame,
            endFrame: endFrame,
            duration: duration
        });

        document.getElementById('timeline-info').textContent =
            `Clicked: ${element.textContent} (${startFrame}-${endFrame})`;
    }

    // Global functions for external access
    window.timelineScrollToFrame = scrollToFrame;
    window.timelineGoToStart = scrollToStart;
    window.timelineGoToEnd = scrollToEnd;

    // Debug function to test scroll events
    window.testTimelineScroll = function() {
        const timelineContent = document.getElementById('timeline-content');
        const timelineInfo = document.getElementById('timeline-info');

        console.log('=== TIMELINE SCROLL TEST ===');
        console.log('Timeline element:', timelineContent);
        console.log('Info element:', timelineInfo);
        console.log('Current scroll left:', timelineContent.scrollLeft);
        console.log('Client width:', timelineContent.clientWidth);

        // Test scroll
        console.log('Setting scroll to 1000...');
        timelineContent.scrollLeft = 1000;

        // Check if scroll event fired
        setTimeout(() => {
            console.log('After scroll - scroll left:', timelineContent.scrollLeft);
            console.log('Info text:', timelineInfo.textContent);
        }, 100);

        return 'Test complete - check console for results';
    };

    // Manual fix function
    window.fixFrameInfo = function() {
        const timelineContent = document.getElementById('timeline-content');
        const timelineInfo = document.getElementById('timeline-info');

        timelineContent.addEventListener('scroll', function() {
            const scrollLeft = this.scrollLeft;
            const viewportWidth = this.clientWidth;

            // Simple calculation: 1px = 1 frame, starting from frame 0
            const frameStart = Math.floor(scrollLeft);
            const frameEnd = Math.floor(scrollLeft + viewportWidth);

            console.log('Manual calculation:', { scrollLeft, frameStart, frameEnd });

            timelineInfo.textContent = `Viewing frames: ${frameStart.toLocaleString()} - ${frameEnd.toLocaleString()}`;
        });

        // Trigger initial update
        const scrollLeft = timelineContent.scrollLeft;
        const viewportWidth = timelineContent.clientWidth;
        const frameStart = Math.floor(scrollLeft);
        const frameEnd = Math.floor(scrollLeft + viewportWidth);
        timelineInfo.textContent = `Viewing frames: ${frameStart.toLocaleString()} - ${frameEnd.toLocaleString()}`;

        return 'Manual fix applied';
    };


    // // Initialize timeline
    // document.addEventListener("DOMContentLoaded", function() {
    //     generateRuler();
    //     setupScrollSync();
    // });
    //
    // // Generate ruler ticks
    // function generateRuler() {
    //     const rulerContent = document.getElementById("ruler-content");
    //     rulerContent.innerHTML = "";
    //
    //     // Major ticks every 1000 frames
    //     // for (let frame = config.minFrame; frame <= config.maxFrame; frame += 1000) {
    //     //     const tick = document.createElement("div");
    //     //     tick.className = "ruler-tick major";
    //     //     tick.style.left = frame + "px";
    //     //     tick.textContent = frame.toLocaleString();
    //     //     rulerContent.appendChild(tick);
    //     // }
    //
    //     // Minor ticks every 500 frames
    //     for (let frame = 100; frame <= config.maxFrame; frame += 100) {
    //         const tick = document.createElement("div");
    //         if (frame % 1000 === 0) {
    //             tick.className = "ruler-tick major";
    //         } else {
    //             tick.className = "ruler-tick";
    //         }
    //         tick.style.left = frame + "px";
    //         tick.textContent = frame.toLocaleString();
    //         rulerContent.appendChild(tick);
    //     }
    // }
    //
    // // Setup scroll synchronization
    // function setupScrollSync() {
    //     const timelineContent = document.getElementById("timeline-content");
    //     const labelsColumn = document.getElementById("labels-column");
    //     const rulerContent = document.getElementById("ruler-content");
    //     const timelineInfo = document.getElementById("timeline-info");
    //     const currentFrame = document.getElementById("current-frame");
    //
    //     // Main timeline scroll
    //     timelineContent.addEventListener("scroll", function() {
    //         const scrollLeft = this.scrollLeft;
    //         const scrollTop = this.scrollTop;
    //
    //         // Sync ruler horizontally
    //         rulerContent.style.transform = `translateX(-${scrollLeft}px)`;
    //
    //         // Sync labels vertically
    //         labelsColumn.scrollTop = scrollTop;
    //
    //         // Update frame info
    //         const frameStart = Math.floor(scrollLeft / config.frameToPixel) + config.minFrame;
    //         const frameEnd = Math.floor((scrollLeft + this.clientWidth) / config.frameToPixel) + config.minFrame;
    //
    //         timelineInfo.textContent = `Viewing frames: ${frameStart.toLocaleString()} - ${frameEnd.toLocaleString()}`;
    //         currentFrame.textContent = `Frame: ${frameStart.toLocaleString()}`;
    //     });
    //
    //     // Labels scroll back to timeline
    //     labelsColumn.addEventListener("scroll", function() {
    //         timelineContent.scrollTop = this.scrollTop;
    //     });
    // }
    //
    // // Navigation functions
    // function scrollToFrame() {
    //     const frameNumber = parseInt(document.getElementById("frame-input").value) || 0;
    //     const timelineContent = document.getElementById("timeline-content");
    //
    //     const framePosition = (frameNumber - config.minFrame) * config.frameToPixel;
    //     const viewportWidth = timelineContent.clientWidth;
    //     const centerOffset = viewportWidth / 2;
    //
    //     let scrollPosition = framePosition - centerOffset;
    //     scrollPosition = Math.max(0, scrollPosition);
    //
    //     const maxScroll = timelineContent.scrollWidth - timelineContent.clientWidth;
    //     scrollPosition = Math.min(scrollPosition, maxScroll);
    //
    //     timelineContent.scrollTo({
    //         left: scrollPosition,
    //         behavior: "smooth"
    //     });
    // }
    //
    // function scrollToStart() {
    //     document.getElementById("timeline-content").scrollTo({
    //         left: 0,
    //         behavior: "smooth"
    //     });
    // }
    //
    // function scrollToEnd() {
    //     const timelineContent = document.getElementById("timeline-content");
    //     timelineContent.scrollTo({
    //         left: timelineContent.scrollWidth - timelineContent.clientWidth,
    //         behavior: "smooth"
    //     });
    // }
    //
    // // Object click handler
    // function objectClick(element) {
    //     const rect = element.getBoundingClientRect();
    //     const timelineContent = document.getElementById("timeline-content");
    //     const timelineRect = timelineContent.getBoundingClientRect();
    //
    //     const relativeLeft = rect.left - timelineRect.left + timelineContent.scrollLeft;
    //     const startFrame = Math.round(relativeLeft / config.frameToPixel) + config.minFrame;
    //     const width = rect.width;
    //     const duration = Math.round(width / config.frameToPixel);
    //     const endFrame = startFrame + duration;
    //
    //     console.log("Object clicked:", {
    //         element: element.textContent,
    //         startFrame: startFrame,
    //         endFrame: endFrame,
    //         duration: duration
    //     });
    //
    //     document.getElementById("timeline-info").textContent =
    //         `Clicked: ${element.textContent} (${startFrame}-${endFrame})`;
    // }
    //
    // // Global functions for external access
    // window.timelineScrollToFrame = scrollToFrame;
    // window.timelineGoToStart = scrollToStart;
    // window.timelineGoToEnd = scrollToEnd;
</script>
