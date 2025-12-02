<div class="graph-visualization-container">
    <h4 class="ui header">
        Grammar Graph Visualization
        @if(!empty($filter))
            <span class="ui label">
                <i class="filter icon"></i>
                Filtered by: "{{ $filter }}"
            </span>
        @endif
    </h4>

    <div class="ui segment">
        <div id="grammarGraphCanvas" class="graph-canvas"></div>
    </div>

    <div class="ui statistics small">
        <div class="statistic">
            <div class="value">{{ $stats['totalNodes'] }}</div>
            <div class="label">
                @if(!empty($filter))
                    Filtered Nodes
                @else
                    Total Nodes
                @endif
            </div>
        </div>
        <div class="statistic">
            <div class="value">{{ $stats['totalEdges'] }}</div>
            <div class="label">
                @if(!empty($filter))
                    Filtered Edges
                @else
                    Total Edges
                @endif
            </div>
        </div>
        <div class="statistic">
            <div class="value">{{ number_format($stats['avgDegree'], 2) }}</div>
            <div class="label">Avg Degree</div>
        </div>
        @if(!empty($filter))
        <div class="statistic">
            <div class="value">{{ $stats['unfilteredTotalNodes'] }}</div>
            <div class="label">Total in Grammar</div>
        </div>
        @endif
    </div>

    @if(isset($stats['nodesByType']) && count($stats['nodesByType']) > 0)
    <div class="ui segment">
        <h5 class="ui header">Node Distribution by Type</h5>
        <div class="ui labels">
            @foreach($stats['nodesByType'] as $type => $count)
                <span class="ui label" style="background-color: {{ config('parser.visualization.nodeColors.' . $type, '#999') }}; color: white;">
                    {{ $type }}: {{ $count }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="ui segment">
        <h5 class="ui header">Legend</h5>
        <div class="ui list">
            <div class="item">
                <strong>Node Types:</strong>
                <span class="ui mini label" style="background-color: {{ config('parser.visualization.nodeColors.E') }}; color: white;">E</span> Entity
                <span class="ui mini label" style="background-color: {{ config('parser.visualization.nodeColors.V') }}; color: white;">V</span> Eventive
                <span class="ui mini label" style="background-color: {{ config('parser.visualization.nodeColors.A') }}; color: white;">A</span> Attribute
                <span class="ui mini label" style="background-color: {{ config('parser.visualization.nodeColors.F') }}; color: white;">F</span> Function
            </div>
            <div class="item">
                <strong>Edge Types:</strong>
                <span class="ui mini label" style="background-color: {{ config('parser.visualization.edgeColors.prediction') }}; color: white;">Prediction</span>
                <span class="ui mini label" style="background-color: {{ config('parser.visualization.edgeColors.activate') }}; color: white;">Activate</span>
                <span class="ui mini label" style="background-color: {{ config('parser.visualization.edgeColors.sequential') }}; color: white;">Sequential</span>
            </div>
        </div>
    </div>
</div>

<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    (function() {
        const data = {!! $d3Data !!};

        // Get full container width
        const container = document.getElementById('grammarGraphCanvas');
        const width = container.clientWidth || 1400;
        const height = 800;

        // Clear previous graph
        d3.select('#grammarGraphCanvas').selectAll('*').remove();

        // Create SVG with explicit ID for svgPanZoom
        const svg = d3.select('#grammarGraphCanvas')
            .append('svg')
            .attr('id', 'grammarGraphSvg')
            .attr('width', '100%')
            .attr('height', height)
            .attr('viewBox', [0, 0, width, height])
            .attr('preserveAspectRatio', 'xMidYMid meet')
            .attr('style', 'width: 100%; height: auto;');

        // Create a group for all content (required for pan/zoom)
        const g = svg.append('g');

        // Create force simulation
        const simulation = d3.forceSimulation(data.nodes)
            .force('link', d3.forceLink(data.links).id(d => d.id).distance(150))
            .force('charge', d3.forceManyBody().strength(-500))
            .force('center', d3.forceCenter(width / 2, height / 2))
            .force('collision', d3.forceCollide().radius(d => d.size + 10));

        // Create arrow markers for directed edges
        svg.append('defs').selectAll('marker')
            .data(['end'])
            .enter().append('marker')
            .attr('id', 'arrowhead')
            .attr('viewBox', '0 -5 10 10')
            .attr('refX', 25)
            .attr('refY', 0)
            .attr('markerWidth', 8)
            .attr('markerHeight', 8)
            .attr('orient', 'auto')
            .append('path')
            .attr('d', 'M0,-5L10,0L0,5')
            .attr('fill', '#999');

        // Create links (append to g group)
        const link = g.append('g')
            .attr('class', 'links')
            .selectAll('line')
            .data(data.links)
            .enter().append('line')
            .attr('stroke', d => d.color)
            .attr('stroke-width', d => d.width)
            .attr('marker-end', 'url(#arrowhead)');

        // Create edge labels (append to g group)
        const edgeLabels = g.append('g')
            .attr('class', 'edge-labels')
            .selectAll('text')
            .data(data.links)
            .enter().append('text')
            .text(d => d.weight ? `${d.type} (${d.weight})` : d.type)
            .attr('font-size', 10)
            .attr('fill', '#666')
            .attr('text-anchor', 'middle');

        // Create nodes (append to g group)
        const node = g.append('g')
            .attr('class', 'nodes')
            .selectAll('circle')
            .data(data.nodes)
            .enter().append('circle')
            .attr('r', d => d.size)
            .attr('fill', d => d.color)
            .attr('stroke', '#fff')
            .attr('stroke-width', 3)
            .call(d3.drag()
                .on('start', dragstarted)
                .on('drag', dragged)
                .on('end', dragended));

        // Add node labels (append to g group)
        const labels = g.append('g')
            .attr('class', 'labels')
            .selectAll('text')
            .data(data.nodes)
            .enter().append('text')
            .text(d => d.label)
            .attr('font-size', 14)
            .attr('font-weight', 'bold')
            .attr('dx', 20)
            .attr('dy', 4);

        // Add tooltips
        node.append('title')
            .text(d => `${d.label}\nType: ${d.type}\nThreshold: ${d.threshold}`);

        // Update positions on simulation tick
        simulation.on('tick', () => {
            link
                .attr('x1', d => d.source.x)
                .attr('y1', d => d.source.y)
                .attr('x2', d => d.target.x)
                .attr('y2', d => d.target.y);

            edgeLabels
                .attr('x', d => (d.source.x + d.target.x) / 2)
                .attr('y', d => (d.source.y + d.target.y) / 2);

            node
                .attr('cx', d => d.x)
                .attr('cy', d => d.y);

            labels
                .attr('x', d => d.x)
                .attr('y', d => d.y);
        });

        // Drag functions
        function dragstarted(event, d) {
            if (!event.active) simulation.alphaTarget(0.3).restart();
            d.fx = d.x;
            d.fy = d.y;
        }

        function dragged(event, d) {
            d.fx = event.x;
            d.fy = event.y;
        }

        function dragended(event, d) {
            if (!event.active) simulation.alphaTarget(0);
            d.fx = null;
            d.fy = null;
        }

        // Initialize pan and zoom after simulation stabilizes
        simulation.on('end', () => {
            if (typeof window.svgPanZoom !== 'undefined') {
                const svgElement = document.getElementById('grammarGraphSvg');
                if (svgElement) {
                    const panZoomInstance = window.svgPanZoom(svgElement, {
                        zoomEnabled: true,
                        controlIconsEnabled: true,
                        fit: true,
                        center: true,
                        minZoom: 0.1,
                        maxZoom: 10,
                        zoomScaleSensitivity: 0.3,
                        dblClickZoomEnabled: false
                    });
                }
            }
        });
    })();
</script>

<style>
    .graph-canvas {
        min-height: 600px;
        width: 100%;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .graph-visualization-container {
        margin-top: 2rem;
        width: 100%;
    }

    .links line {
        stroke-opacity: 0.8;
    }

    .nodes circle {
        cursor: pointer;
    }

    .labels text {
        pointer-events: none;
        font-family: sans-serif;
    }

    .edge-labels text {
        pointer-events: none;
        font-family: sans-serif;
    }
</style>
