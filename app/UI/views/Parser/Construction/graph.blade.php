<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser'],['/parser/construction','Constructions'],['','Graph']]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="ui container overflow-y-auto">
                <div class="page-content">
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">{{ $construction->name }} - Graph Visualization</div>
                            <div class="page-subtitle">Compiled pattern structure</div>
                        </div>
                        <div class="page-header-actions">
                            <a href="/parser/construction/{{ $construction->idConstruction }}" class="ui button">
                                <i class="arrow left icon"></i>
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="ui segments">
                        <div class="ui segment">
                            <h4 class="ui header">Pattern</h4>
                            <div class="ui segment" style="background: #f8f8f8; font-family: 'Courier New', monospace; font-size: 1.1em;">
                                {{ $construction->pattern }}
                            </div>
                        </div>

                        <div class="ui segment">
                            <h4 class="ui header">Graph Structure</h4>
                            <p>
                                <strong>Nodes:</strong> {{ count($compiledGraph['nodes'] ?? []) }} |
                                <strong>Edges:</strong> {{ count($compiledGraph['edges'] ?? []) }}
                            </p>
                        </div>

                        <div class="ui segment">
                            <h4 class="ui header">DOT Representation</h4>
                            <div class="ui segment" style="background: #f8f8f8;">
                                <pre style="font-family: 'Courier New', monospace;">{{ $dot }}</pre>
                            </div>
                            <small>
                                You can visualize this DOT graph using online tools like
                                <a href="https://dreampuf.github.io/GraphvizOnline/" target="_blank">Graphviz Online</a>
                            </small>
                        </div>

                        <div class="ui segment">
                            <h4 class="ui header">Nodes</h4>
                            <table class="ui celled table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($compiledGraph['nodes'] ?? [] as $nodeId => $node)
                                        <tr>
                                            <td><code>{{ $nodeId }}</code></td>
                                            <td><span class="ui small label">{{ $node['type'] ?? 'N/A' }}</span></td>
                                            <td>
                                                @if(isset($node['value']))
                                                    <strong>Value:</strong> {{ $node['value'] }}
                                                @elseif(isset($node['pos']))
                                                    <strong>POS:</strong> {{ $node['pos'] }}
                                                    @if(isset($node['constraint']))
                                                        <br><strong>Constraint:</strong> {{ $node['constraint'] }}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="ui segment">
                            <h4 class="ui header">Edges</h4>
                            <table class="ui celled table">
                                <thead>
                                    <tr>
                                        <th>From Node</th>
                                        <th>To Node</th>
                                        <th>Label</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($compiledGraph['edges'] ?? [] as $edge)
                                        <tr>
                                            <td>{{ $edge['from'] }}</td>
                                            <td>{{ $edge['to'] }}</td>
                                            <td>{{ $edge['label'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layout::index>
