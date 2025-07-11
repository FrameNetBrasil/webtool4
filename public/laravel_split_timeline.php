<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clean Timeline Interface</title>
    
    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@2.0.2"></script>
    
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            height: 600px; /* Fixed height for demo */
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
            overflow: hidden;
        }

        /* Labels column - fixed width, scrolls vertically */
        .labels-column {
            width: 150px;
            background-color: #e9ecef;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            flex-shrink: 0;
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
            overflow-x: auto; /* Horizontal scrollbar appears here */
            overflow-y: auto; /* Vertical scrollbar appears here */
            position: relative;
        }

        .timeline-inner {
            width: 22500px; /* Fixed timeline width */
            position: relative;
        }

        .layer-row {
            position: relative;
            border-bottom: 1px solid #eee;
            min-height: 48px; /* Minimum layer height */
        }

        /* Timeline objects */
        .timeline-object {
            position: absolute;
            height: 24px;
            border: 1px solid rgba(0,0,0,0.2);
            border-radius: 2px;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 0 4px;
            font-size: 10px;
            color: white;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.5);
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .timeline-object.highlighted {
            outline: 2px solid #ff0000;
            outline-offset: 1px;
        }

        /* Info bar at bottom */
        .timeline-info {
            flex-shrink: 0;
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
            padding: 8px;
            font-size: 12px;
            color: #666;
        }

        /* Demo container styles */
        .demo-container {
            max-width: 1200px;
            margin: 0 auto;
            border: 2px dashed #007bff;
            padding: 20px;
            margin-bottom: 20px;
        }

        .demo-container h3 {
            margin: 0 0 15px 0;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="demo-container">
        <h3>Clean Timeline with Split Layout</h3>
        <p>Labels column + Timeline content with scrollbar only under timeline objects</p>
        
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
                        <div class="label-item" style="height: 48px;">Audio_LU</div>
                        <div class="label-item" style="height: 48px;">Camera_angle</div>
                        <div class="label-item" style="height: 48px;">Audio_LU</div>
                        <div class="label-item" style="height: 48px;">Deitic_center</div>
                        <div class="label-item" style="height: 48px;">Cut</div>
                        <div class="label-item" style="height: 48px;">Deitic_type</div>
                    </div>

                    <!-- Timeline content - gets the scrollbar -->
                    <div class="timeline-content" id="timeline-content">
                        <div class="timeline-inner">
                            <!-- Layer 1: Audio_LU -->
                            <div class="layer-row" style="height: 48px;">
                                <div class="timeline-object" 
                                     style="left: 1425px; top: 0px; width: 330px; background-color: #FF0000;"
                                     onclick="objectClick(this)">
                                    sino.noun
                                </div>
                                <div class="timeline-object" 
                                     style="left: 9866px; top: 24px; width: 234px; background-color: #0000FF;"
                                     onclick="objectClick(this)">
                                    bater.verb
                                </div>
                            </div>

                            <!-- Layer 2: Camera_angle -->
                            <div class="layer-row" style="height: 48px;">
                                <div class="timeline-object" 
                                     style="left: 625px; top: 12px; width: 545px; background-color: #00008B;"
                                     onclick="objectClick(this)">
                                    Horizontal-angle
                                </div>
                            </div>

                            <!-- Layer 3: Audio_LU (repeated layer) -->
                            <div class="layer-row" style="height: 48px;">
                                <div class="timeline-object" 
                                     style="left: 18390px; top: 0px; width: 365px; background-color: #008000;"
                                     onclick="objectClick(this)">
                                    arma de fogo.noun
                                </div>
                            </div>

                            <!-- Layer 4: Deitic_center -->
                            <div class="layer-row" style="height: 48px;">
                                <div class="timeline-object" 
                                     style="left: 500px; top: 0px; width: 125px; background-color: #FFA500;"
                                     onclick="objectClick(this)">
                                    Personagem
                                </div>
                                <div class="timeline-object" 
                                     style="left: 1170px; top: 24px; width: 255px; background-color: #FF1493;"
                                     onclick="objectClick(this)">
                                    Leitor
                                </div>
                                <div class="timeline-object" 
                                     style="left: 10395px; top: 0px; width: 1545px; background-color: #9400D3;"
                                     onclick="objectClick(this)">
                                    Personagem
                                </div>
                            </div>

                            <!-- Layer 5: Cut -->
                            <div class="layer-row" style="height: 48px;">
                                <div class="timeline-object" 
                                     style="left: 3770px; top: 12px; width: 535px; background-color: #8B0000;"
                                     onclick="objectClick(this)">
                                    Corte-seco
                                </div>
                                <div class="timeline-object" 
                                     style="left: 12356px; top: 12px; width: 285px; background-color: #8B0000;"
                                     onclick="objectClick(this)">
                                    Corte-seco
                                </div>
                            </div>

                            <!-- Layer 6: Deitic_type -->
                            <div class="layer-row" style="height: 48px;">
                                <div class="timeline-object" 
                                     style="left: 500px; top: 0px; width: 125px; background-color: #008B8B;"
                                     onclick="objectClick(this)">
                                    Pessoa
                                </div>
                                <div class="timeline-object" 
                                     style="left: 2474px; top: 24px; width: 737px; background-color: #4B0082;"
                                     onclick="objectClick(this)">
                                    Lugar
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info bar -->
                <div class="timeline-info" id="timeline-info">
                    Timeline ready - scroll to navigate
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const config = {
            minFrame: 0,
            maxFrame: 22000,
            frameToPixel: 1,
            labelWidth: 150
        };

        // Initialize timeline
        document.addEventListener('DOMContentLoaded', function() {
            generateRuler();
            setupScrollSync();
        });

        // Generate ruler ticks
        function generateRuler() {
            const rulerContent = document.getElementById('ruler-content');
            rulerContent.innerHTML = '';

            // Major ticks every 1000 frames
            for (let frame = config.minFrame; frame <= config.maxFrame; frame += 1000) {
                const tick = document.createElement('div');
                tick.className = 'ruler-tick major';
                tick.style.left = frame + 'px';
                tick.textContent = frame.toLocaleString();
                rulerContent.appendChild(tick);
            }

            // Minor ticks every 500 frames
            for (let frame = 500; frame <= config.maxFrame; frame += 1000) {
                const tick = document.createElement('div');
                tick.className = 'ruler-tick';
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

            // Main timeline scroll
            timelineContent.addEventListener('scroll', function() {
                const scrollLeft = this.scrollLeft;
                const scrollTop = this.scrollTop;

                // Sync ruler horizontally
                rulerContent.style.transform = `translateX(-${scrollLeft}px)`;

                // Sync labels vertically
                labelsColumn.scrollTop = scrollTop;

                // Update frame info
                const frameStart = Math.floor(scrollLeft / config.frameToPixel) + config.minFrame;
                const frameEnd = Math.floor((scrollLeft + this.clientWidth) / config.frameToPixel) + config.minFrame;
                
                timelineInfo.textContent = `Viewing frames: ${frameStart.toLocaleString()} - ${frameEnd.toLocaleString()}`;
                currentFrame.textContent = `Frame: ${frameStart.toLocaleString()}`;
            });

            // Labels scroll back to timeline
            labelsColumn.addEventListener('scroll', function() {
                timelineContent.scrollTop = this.scrollTop;
            });
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
    </script>
</body>
</html>

<!-- 
🎯 CLEAN STRUCTURE EXPLANATION:

1. LAYOUT:
   - timeline-wrapper: Responsive container
   - timeline-container: Fixed structure with flex column
   - timeline-controls: Controls at top
   - timeline-ruler: Split into label area + ruler content
   - timeline-main: Split into labels column + timeline content
   - timeline-info: Info at bottom

2. SCROLLING:
   - Horizontal scrollbar: ONLY in timeline-content
   - Vertical scrollbar: ONLY in timeline-content and labels-column (synced)
   - Ruler: Transforms with horizontal scroll

3. SYNCHRONIZATION:
   - Timeline horizontal scroll → Ruler transforms
   - Timeline vertical scroll → Labels scroll
   - Labels vertical scroll → Timeline scroll

4. FOR LARAVEL BLADE:
   Replace the demo content with your @foreach loops
   Keep the same structure and IDs
   JavaScript works as-is

5. RESPONSIVE:
   - Container adapts to parent width
   - Timeline content scrolls horizontally
   - Scrollbar appears only under timeline objects
-->
