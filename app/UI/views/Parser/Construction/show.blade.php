<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser'],['/parser/construction','Constructions'],['','View']]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="ui container overflow-y-auto">
                <div class="page-content">
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">{{ $construction->name }}</div>
                            <div class="page-subtitle">
                                Grammar: {{ $grammar->name }} |
                                <span class="ui small label">{{ $construction->semanticType }}</span>
                                @if($construction->enabled)
                                    <span class="ui green label">Enabled</span>
                                @else
                                    <span class="ui grey label">Disabled</span>
                                @endif
                            </div>
                        </div>
                        <div class="page-header-actions">
                            <a href="/parser/construction/{{ $construction->idConstruction }}/edit" class="ui primary button">
                                <i class="edit icon"></i>
                                Edit
                            </a>
                            <a href="/parser/construction/{{ $construction->idConstruction }}/graph" class="ui button">
                                <i class="project diagram icon"></i>
                                View Graph
                            </a>
                            <a href="/parser/construction?grammar={{ $construction->idGrammarGraph }}" class="ui button">
                                <i class="list icon"></i>
                                Back to List
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="ui success message">
                            <i class="close icon"></i>
                            <div class="header">Success</div>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="ui segments">
                        <div class="ui segment">
                            <h4 class="ui header">Pattern</h4>
                            <div class="ui segment" style="background: #f8f8f8; font-family: 'Courier New', monospace; font-size: 1.1em;">
                                {{ $construction->pattern }}
                            </div>
                        </div>

                        @if($construction->description)
                            <div class="ui segment">
                                <h4 class="ui header">Description</h4>
                                <p>{{ $construction->description }}</p>
                            </div>
                        @endif

                        <div class="ui segment">
                            <h4 class="ui header">Properties</h4>
                            <table class="ui very basic table">
                                <tbody>
                                    <tr>
                                        <td width="200px"><strong>Semantic Type</strong></td>
                                        <td><span class="ui label">{{ $construction->semanticType }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Priority</strong></td>
                                        <td>{{ $construction->priority }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            @if($construction->enabled)
                                                <span class="ui green label">Enabled</span>
                                            @else
                                                <span class="ui grey label">Disabled</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created</strong></td>
                                        <td>{{ $construction->created_at ? $construction->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated</strong></td>
                                        <td>{{ $construction->updated_at ? $construction->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="ui segment">
                            <h4 class="ui header">Compiled Graph Structure</h4>
                            <div class="ui segment" style="background: #f8f8f8;">
                                <pre style="max-height: 400px; overflow-y: auto;">{{ json_encode($compiledGraph, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                            <p>
                                <strong>Nodes:</strong> {{ count($compiledGraph['nodes'] ?? []) }} |
                                <strong>Edges:</strong> {{ count($compiledGraph['edges'] ?? []) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            $('.message .close').on('click', function() {
                $(this).closest('.message').transition('fade');
            });
        });
    </script>
</x-layout::index>
