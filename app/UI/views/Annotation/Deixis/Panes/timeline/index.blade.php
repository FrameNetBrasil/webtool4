<style>
    body {
        margin: 0;
        padding: 20px;
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
    }

    .timeline-container {
        border: 1px solid #ccc;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

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
        overflow: hidden;
    }

    .ruler-content {
        position: relative;
        height: 100%;
        display: flex;
        align-items: center;
        width: {{ $config['timelineWidth'] + $config['labelWidth'] }}px;
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

    .timeline-content {
        position: relative;
        overflow-x: auto;
        overflow-y: auto;
        max-height: 600px;
    }

    .timeline-inner {
        position: relative;
        min-width: 100%;
        width: {{ $config['timelineWidth'] + $config['labelWidth'] }}px;
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
        width: {{ $config['labelWidth'] }}px;
        background-color: #e9ecef;
        border-right: 1px solid #ddd;
        padding: 8px;
        font-size: 12px;
        font-weight: bold;
        color: #333;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    .layer-objects {
        position: relative;
        flex: 1;
        min-height: {{ $config['objectHeight'] }}px;
        width: {{ $config['timelineWidth'] }}px;
    }

    .timeline-object {
        position: absolute;
        height: {{ $config['objectHeight'] }}px;
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
        min-width: {{ $config['minObjectWidth'] }}px;
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

    .timeline-info {
        position: sticky;
        bottom: 0;
        background-color: #f8f9fa;
        border-top: 1px solid #ddd;
        padding: 8px;
        font-size: 12px;
        color: #666;
        z-index: 100;
    }

    .timeline-controls {
        margin-bottom: 10px;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
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
</style>
</head>
<body>
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

    <form hx-post="/timeline/highlight-frame"
          hx-target="#highlight-container"
          hx-swap="innerHTML"
          hx-trigger="submit">
        <label>Highlight frame:
            <input type="number" name="frame" placeholder="Enter frame number" value="1500">
        </label>
        <button type="submit">Highlight</button>
    </form>
</div>

<!-- Timeline Component -->
<div class="timeline-container">
    <!-- Ruler -->
    <div class="timeline-ruler">
        <div class="ruler-content" id="ruler-content">
            @for ($frame = $config['minFrame']; $frame <= $config['maxFrame']; $frame += 100)
                @php
                    $isMajor = $frame % 1000 === 0;
                    $left = $config['labelWidth'] + ($frame - $config['minFrame']) * $config['frameToPixel'];
                @endphp
                <div class="ruler-tick {{ $isMajor ? 'major' : '' }}"
                     style="left: {{ $left }}px;">
                    {{ number_format($frame) }}
                </div>
            @endfor
        </div>
    </div>

    <!-- Timeline Content -->
    <div class="timeline-content" id="timeline-content">
        <div class="timeline-inner">
            @foreach ($groupedLayers as $visualLayerIndex => $visualLayer)
                @php
                    $layerHeight = count($visualLayer['lines']) * $config['objectHeight'];
                @endphp
                <div class="layer" style="height: {{ $layerHeight }}px;">
                    <!-- Layer Label -->
                    <div class="layer-label"
                         style="height: {{ $layerHeight }}px;"
                         title="{{ $visualLayer['name'] }} ({{ count($visualLayer['lines']) }} line{{ count($visualLayer['lines']) > 1 ? 's' : '' }})">
                        {{ $visualLayer['name'] }}
                    </div>

                    <!-- Layer Objects -->
                    <div class="layer-objects" style="height: {{ $layerHeight }}px;">
                        @foreach ($visualLayer['lines'] as $lineIndex => $line)
                            @foreach ($line['objects'] as $objIndex => $object)
                                @php
                                    $startPos = ($object->startFrame - $config['minFrame']) * $config['frameToPixel'];
                                    $duration = $object->endFrame - $object->startFrame;
                                    $width = max($config['minObjectWidth'], $duration * $config['frameToPixel']);
                                    $bgColor = $object->bgColorGL ?? '#999999';
                                    $label = $object->gl ?? $object->luName ?? $object->name ?? "Object " . ($objIndex + 1);
                                    $top = $lineIndex * $config['objectHeight'];
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

    <!-- Timeline Info -->
    <div class="timeline-info" id="timeline-info">
        Timeline: {{ count($timelineData) }} layers,
        {{ array_sum(array_map(function($layer) { return count($layer['objects']); }, $timelineData)) }} objects,
        frames {{ number_format($config['minFrame']) }}-{{ number_format($config['maxFrame']) }}
    </div>
</div>

<!-- Dynamic Content Areas -->
<div id="highlight-container"></div>
<div id="object-click-info"></div>

<script>
    // Sync ruler scroll with timeline content
    document.getElementById('timeline-content').addEventListener('scroll', function() {
        const scrollLeft = this.scrollLeft;
        document.getElementById('ruler-content').style.transform = `translateX(-${scrollLeft}px)`;

        // Update frame range info
        const viewportWidth = this.clientWidth;
        const startFrame = Math.floor(scrollLeft / {{ $config['frameToPixel'] }}) + {{ $config['minFrame'] }};
        const endFrame = Math.floor((scrollLeft + viewportWidth) / {{ $config['frameToPixel'] }}) + {{ $config['minFrame'] }};

        document.getElementById('timeline-info').textContent = `Viewing frames: ${startFrame.toLocaleString()} - ${endFrame.toLocaleString()}`;
    });

    // Handle scroll to frame response
    document.body.addEventListener('htmx:afterRequest', function(event) {
        if (event.detail.xhr.responseURL.includes('scroll-to-frame')) {
            const response = JSON.parse(event.detail.xhr.responseText);
            if (response.scrollPosition !== undefined) {
                document.getElementById('timeline-content').scrollTo({
                    left: response.scrollPosition,
                    behavior: 'smooth'
                });
            }
        }
    });

    // Global functions for external access
    window.timelineScrollToFrame = function(frameNumber) {
        htmx.ajax('POST', '/timeline/scroll-to-frame', {
            values: { frame: frameNumber },
            target: '#timeline-info'
        });
    };

    window.timelineHighlightFrame = function(frameNumber) {
        htmx.ajax('POST', '/timeline/highlight-frame', {
            values: { frame: frameNumber },
            target: '#highlight-container'
        });
    };
</script>
