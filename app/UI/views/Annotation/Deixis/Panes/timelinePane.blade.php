<style>
    /* Container responsiveness - adapts to parent */
    .timeline-wrapper {
        width: 100%; /* Takes full width of parent container */
        max-width: 100%; /* Never exceeds parent */
        min-width: 300px; /* Minimum usable width */
        overflow: hidden; /* Clean container boundaries */
    }

    .timeline-container {
        /*width: 100%; !* Fill the wrapper *!*/
        border: 1px solid #ccc;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: auto;
    }

    /* Fixed timeline internals - no scaling */
    .timeline-ruler {
        position: sticky;
        top: 0;
        z-index: 100;
        height: 30px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        font-size: 12px;
        color: #666;
        overflow: hidden; /* Hide ruler scrollbar */
        width: 100%;
    }

    /* Ruler label area - matches layer labels */
    .ruler-label-area {
        position: sticky;
        left: 0;
        z-index: 11; /* Above layer labels */
        width: 150px;
        background-color: white;
        border-right: 1px solid #ddd;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 11px;
        /*color: #666;*/
    }

    .ruler-content {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        width: {{ $timeline['config']['timelineWidth'] + $timeline['config']['labelWidth'] }}px; /* Fixed width */
    }

    .ruler-tick {
        position: absolute;
        height: 20px;
        border-left: 1px solid #999;
        font-size: 10px;
        padding-left: 2px;
        display: flex;
        align-items: center;
    }

    .ruler-tick.major {
        border-left: 2px solid #333;
        font-weight: bold;
    }

    /*.timeline-content {*/
    /*    position: relative;*/
    /*    overflow-x: auto; !* Horizontal scroll when content exceeds container *!*/
    /*    overflow-y: auto; !* Vertical scroll for many layers *!*/
    /*    max-height: 600px;*/
    /*    width: 100%; !* Responsive container width *!*/
    /*    display: flex; !* Split into two columns *!*/
    /*}*/

    .timeline-content {
        flex: 1;
        width: 100%;
        min-height: 0;
        display: flex;
    }

    .timeline-inner {
        position: relative;
        width: {{ $timeline['config']['timelineWidth'] + $timeline['config']['labelWidth'] }}px; /* Fixed content width */
        min-width: 100%;
    }

    .timeline-labels-column {
        width: 150px; /* Fixed labels column */
        flex-shrink: 0;
        overflow-y: auto;
        background-color: #e9ecef;
        border-right: 1px solid #ddd;
    }

    .timeline-scrollable-area {
        flex: 1;
        overflow-x: auto; /* Horizontal scroll - ONLY here */
        overflow-y: auto; /* Vertical scroll */
        min-height: 0;
    }

    .timeline-inner {
        width: {{ $timeline['config']['timelineWidth'] }}px;
        min-height: 100%;
        position: relative;
    }

    /* Layer label items */
    .timeline-label-item {
        width: {{ $timeline['config']['labelWidth'] }}px;
        background-color: #e9ecef;
        border-bottom: 1px solid #eee;
        padding: 8px;
        font-size: 12px;
        font-weight: bold;
        color: #333;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    .layer {
        position: relative;
        border-bottom: 1px solid #eee;
        display: flex;
    }

    .layer-label {
        position: sticky;
        left: 0;
        z-index: 10;
        width: {{ $timeline['config']['labelWidth'] }}px; /* Fixed label width */
        background-color: #e9ecef;
        border-right: 1px solid #ddd;
        padding: 8px;
        font-size: 12px;
        font-weight: bold;
        color: #333;
        display: flex;
        align-items: center;
        box-sizing: border-box;
        flex-shrink: 0; /* Don't shrink labels */
    }

    .layer-objects {
        position: relative;
        flex: 1;
        min-height: {{ $timeline['config']['objectHeight'] }}px;
        width: {{ $timeline['config']['timelineWidth'] }}px; /* Fixed timeline width */
    }

    .timeline-object {
        position: absolute;
        height: {{ $timeline['config']['objectHeight'] }}px;
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
        min-width: {{ $timeline['config']['minObjectWidth'] }}px;
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

    .timeline-info {
        position: sticky;
        bottom: 0;
        background-color: #f8f9fa;
        border-top: 1px solid #ddd;
        padding: 8px;
        font-size: 12px;
        color: #666;
        z-index: 100;
        width: 100%;
    }

    .timeline-controls {
        margin-bottom: 10px;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
        box-sizing: border-box;
    }

    .timeline-controls input {
        margin-right: 10px;
        padding: 4px 8px;
        border: 1px solid #ccc;
        border-radius: 2px;
    }

    .timeline-controls button {
        padding: 4px 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 2px;
        cursor: pointer;
        margin-right: 5px;
    }

    .timeline-controls button:hover {
        background-color: #0056b3;
    }

    .object-info {
        background-color: #e8f4fd;
        border: 1px solid #bee5eb;
        border-radius: 4px;
        padding: 8px;
        margin-top: 5px;
    }

    /* Demo: Different container examples */
    .demo-50 {
        width: 50%;
    }

    .demo-75 {
        width: 75%;
    }

    .demo-full {
        width: 100%;
    }

    .demo-fixed {
        width: 600px;
    }

    .demo-container {
        margin-bottom: 20px;
        padding: 10px;
        border: 2px dashed #007bff;
        background-color: #f8f9fa;
    }

    .demo-container h3 {
        margin: 0 0 10px 0;
        color: #007bff;
    }

    /* Mobile responsive adjustments for very small screens */
    @media (max-width: 480px) {
        .timeline-wrapper {
            min-width: 250px;
        }

        .layer-label {
            font-size: 10px;
            padding: 4px;
        }

        .timeline-object {
            font-size: 9px;
        }
    }
</style>

<div class="timeline-wrapper">
    <!-- Timeline Controls -->
    <div class="timeline-controls">
        <form hx-post="/time/scroll-to-frame"
              hx-target="#timeline-info"
              hx-swap="innerHTML"
              hx-trigger="submit">
            <label>Go to frame:
                <input type="number" name="frame" placeholder="Enter frame number" value="1000">
            </label>
            <button type="submit">Go</button>
        </form>

        {{--        <form hx-post="/timeline/highlight-frame"--}}
        {{--              hx-target="#highlight-container"--}}
        {{--              hx-swap="innerHTML"--}}
        {{--              hx-trigger="submit">--}}
        {{--            <label>Highlight frame:--}}
        {{--                <input type="number" name="frame" placeholder="Enter frame number" value="1500">--}}
        {{--            </label>--}}
        {{--            <button type="submit">Highlight</button>--}}
        {{--        </form>--}}
    </div>

    <!-- Timeline Component -->
    <div class="timeline-container">
        <!-- Ruler -->
        <div class="timeline-ruler">
            <div class="ruler-label-area">
                Layers
            </div>
            <div class="ruler-content" id="ruler-content">
                @for ($frame = $timeline['config']['minFrame']; $frame <= $timeline['config']['maxFrame']; $frame += 100)
                    @php
                        $isMajor = $frame % 1000 === 0;
                        //$left = $timeline['config']['labelWidth'] + ($frame - $timeline['config']['minFrame']) * $timeline['config']['frameToPixel'];
                    $left = ($frame - $timeline['config']['minFrame']) * $timeline['config']['frameToPixel'];
                    @endphp
                    <div class="ruler-tick {{ $isMajor ? 'major' : '' }}"
                         style="left: {{ $left }}px;">
                        {{ number_format($frame) }}
                    </div>
                @endfor
            </div>
        </div>

        <!-- Timeline Content - Split layout -->
        <div class="timeline-scrollable">
            <!-- Labels Column -->
            <div class="timeline-labels-column" id="timeline-labels">
                @foreach ($groupedLayers as $visualLayer)
                    @php $layerHeight = count($visualLayer['lines']) * $timeline['config']['objectHeight']; @endphp
                    <div class="timeline-label-item" style="height: {{ $layerHeight }}px;">
                        {{ $visualLayer['name'] }}
                    </div>
                @endforeach
            </div>

            <!-- Scrollable Timeline Area - Gets the scrollbar -->
            <div class="timeline-scrollable-area" id="timeline-scrollable">
                <div class="timeline-inner">
                    @foreach ($groupedLayers as $visualLayer)
                        <div class="layer-objects-only"
                             style="height: {{ count($visualLayer['lines']) * $timeline['config']['objectHeight'] }}px;">
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
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Timeline Content -->
        {{--        <div class="timeline-content" id="timeline-content">--}}
        {{--            <div class="timeline-inner">--}}
        {{--                @foreach ($groupedLayers as $visualLayerIndex => $visualLayer)--}}
        {{--                    @php--}}
        {{--                        $layerHeight = count($visualLayer['lines']) * $timeline['config']['objectHeight'];--}}
        {{--                    @endphp--}}
        {{--                    <div class="layer" style="height: {{ $layerHeight }}px;">--}}
        {{--                        <!-- Layer Label -->--}}
        {{--                        <div class="layer-label"--}}
        {{--                             style="height: {{ $layerHeight }}px;"--}}
        {{--                             title="{{ $visualLayer['name'] }} ({{ count($visualLayer['lines']) }} line{{ count($visualLayer['lines']) > 1 ? 's' : '' }})">--}}
        {{--                            {{ $visualLayer['name'] }}--}}
        {{--                        </div>--}}

        {{--                        <!-- Layer Objects -->--}}
        {{--                        <div class="layer-objects" style="height: {{ $layerHeight }}px;">--}}
        {{--                            @foreach ($visualLayer['lines'] as $lineIndex => $line)--}}
        {{--                                @foreach ($line['objects'] as $objIndex => $object)--}}
        {{--                                    @php--}}
        {{--                                        $startPos = ($object->startFrame - $timeline['config']['minFrame']) * $timeline['config']['frameToPixel'];--}}
        {{--                                        $duration = $object->endFrame - $object->startFrame;--}}
        {{--                                        $width = max($timeline['config']['minObjectWidth'], $duration * $timeline['config']['frameToPixel']);--}}
        {{--                                        $bgColor = $object->bgColorGL ?? '#999999';--}}
        {{--                                        $label = $object->gl ?? $object->luName ?? $object->name ?? "Object " . ($objIndex + 1);--}}
        {{--                                        $top = $lineIndex * $timeline['config']['objectHeight'];--}}
        {{--                                        $tooltip = $label . "\nFrames: " . $object->startFrame . "-" . $object->endFrame . "\nDuration: " . $duration . " frames";--}}
        {{--                                    @endphp--}}
        {{--                                    <div class="timeline-object"--}}
        {{--                                         style="left: {{ $startPos }}px; top: {{ $top }}px; width: {{ $width }}px; background-color: {{ $bgColor }};"--}}
        {{--                                         title="{{ $tooltip }}"--}}
        {{--                                         data-layer-index="{{ $line['originalIndex'] }}"--}}
        {{--                                         data-object-index="{{ $objIndex }}"--}}
        {{--                                         data-line-index="{{ $lineIndex }}"--}}
        {{--                                         data-start-frame="{{ $object->startFrame }}"--}}
        {{--                                         data-end-frame="{{ $object->endFrame }}"--}}
        {{--                                         hx-post="/timeline/object-click"--}}
        {{--                                         hx-vals='{"layerIndex": "{{ $line['originalIndex'] }}", "objectIndex": "{{ $objIndex }}", "lineIndex": "{{ $lineIndex }}"}'--}}
        {{--                                         hx-target="#object-click-info"--}}
        {{--                                         hx-swap="innerHTML">--}}
        {{--                                        {{ $label }}--}}
        {{--                                    </div>--}}
        {{--                                @endforeach--}}
        {{--                            @endforeach--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                @endforeach--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <!-- Timeline Info -->
        <div class="timeline-info" id="timeline-info">
            Timeline: {{ count($timeline['data']) }} layers,
            {{ array_sum(array_map(function($layer) { return count($layer['objects']); }, $timeline['data'])) }}
            objects,
            frames {{ number_format($timeline['config']['minFrame']) }}
            -{{ number_format($timeline['config']['maxFrame']) }}
        </div>
    </div>
</div>

<!-- Dynamic Content Areas -->
<div id="highlight-container"></div>
<div id="object-click-info"></div>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        // Get elements by their exact IDs
        const timelineScrollable = document.getElementById("timeline-scrollable");
        const timelineLabels = document.getElementById("timeline-labels");
        const rulerContent = document.getElementById("ruler-content");
        const timelineInfo = document.getElementById("timeline-info");

        // 1. Timeline scroll → affects ruler + labels
        timelineScrollable.addEventListener("scroll", function() {
            const scrollLeft = this.scrollLeft;
            const scrollTop = this.scrollTop;

            // Sync ruler horizontally
            rulerContent.style.transform = `translateX(-${scrollLeft}px)`;

            // Sync labels vertically
            timelineLabels.scrollTop = scrollTop;

            // Update frame info
            const frameStart = Math.floor(scrollLeft / {{ $timeline['config']['frameToPixel'] }}) + {{ $timeline['config']['minFrame'] }};
            const frameEnd = Math.floor((scrollLeft + this.clientWidth) / {{ $timeline['config']['frameToPixel'] }}) + {{ $timeline['config']['minFrame'] }};
            timelineInfo.textContent = `Viewing frames: ${frameStart.toLocaleString()} - ${frameEnd.toLocaleString()}`;
        });

        // 2. Labels scroll → affects timeline
        timelineLabels.addEventListener("scroll", function() {
            timelineScrollable.scrollTop = this.scrollTop;
        });
    });

    {{--// Sync ruler scroll with timeline content--}}
    {{--document.getElementById("timeline-content").addEventListener("scroll", function() {--}}
    {{--    const scrollLeft = this.scrollLeft;--}}
    {{--    document.getElementById("ruler-content").style.transform = `translateX(-${scrollLeft}px)`;--}}

    {{--    // Update frame range info--}}
    {{--    const viewportWidth = this.clientWidth;--}}
    {{--    const startFrame = Math.floor(scrollLeft / {{ $timeline['config']['frameToPixel'] }}) + {{ $timeline['config']['minFrame'] }};--}}
    {{--    const endFrame = Math.floor((scrollLeft + viewportWidth) / {{ $timeline['config']['frameToPixel'] }}) + {{ $timeline['config']['minFrame'] }};--}}

    {{--    document.getElementById("timeline-info").textContent = `Viewing frames: ${startFrame.toLocaleString()} - ${endFrame.toLocaleString()}`;--}}
    {{--});--}}

    {{--// Handle scroll to frame response--}}
    {{--document.body.addEventListener("htmx:afterRequest", function(event) {--}}
    {{--    if (event.detail.xhr.responseURL.includes("scroll-to-frame")) {--}}
    {{--        const response = JSON.parse(event.detail.xhr.responseText);--}}
    {{--        if (response.scrollPosition !== undefined) {--}}
    {{--            document.getElementById("timeline-content").scrollTo({--}}
    {{--                left: response.scrollPosition,--}}
    {{--                behavior: "smooth"--}}
    {{--            });--}}
    {{--        }--}}
    {{--    }--}}
    {{--});--}}

    {{--// Global functions for external access--}}
    {{--window.timelineScrollToFrame = function(frameNumber) {--}}
    {{--    htmx.ajax("POST", "/timeline/scroll-to-frame", {--}}
    {{--        values: { frame: frameNumber },--}}
    {{--        target: "#timeline-info"--}}
    {{--    });--}}
    {{--};--}}

    {{--window.timelineHighlightFrame = function(frameNumber) {--}}
    {{--    htmx.ajax("POST", "/timeline/highlight-frame", {--}}
    {{--        values: { frame: frameNumber },--}}
    {{--        target: "#highlight-container"--}}
    {{--    });--}}
    {{--};--}}
</script>
