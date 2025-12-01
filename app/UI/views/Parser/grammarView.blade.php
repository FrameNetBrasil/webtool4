<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser'],['/parser/grammar','Grammars']]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="page-content">
                <div class="page-header">
                    <div class="page-header-content">
                        <div class="page-title">{{ $grammar->name }}</div>
                        <div class="page-subtitle">Language: {{ $grammar->language }}</div>
                    </div>
                </div>

                @if(count($errors) > 0)
                <div class="ui warning message">
                    <div class="header">Grammar Validation Warnings</div>
                    <ul class="list">
                        @foreach($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($grammar->description)
                <div class="ui segment">
                    <p>{{ $grammar->description }}</p>
                </div>
                @endif

                <div class="ui statistics">
                    <div class="statistic">
                        <div class="value">{{ count($grammar->nodes ?? []) }}</div>
                        <div class="label">Nodes</div>
                    </div>
                    <div class="statistic">
                        <div class="value">{{ count($grammar->edges ?? []) }}</div>
                        <div class="label">Edges</div>
                    </div>
                    <div class="statistic">
                        <div class="value">{{ count($grammar->mwes ?? []) }}</div>
                        <div class="label">MWEs</div>
                    </div>
                </div>

                <div class="ui divider"></div>

                <h3 class="ui header">Grammar Nodes</h3>
                <table class="ui celled table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Label</th>
                            <th>Type</th>
                            <th>Threshold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grammar->nodes as $node)
                        <tr>
                            <td>{{ $node->idGrammarNode }}</td>
                            <td><strong>{{ $node->label }}</strong></td>
                            <td>
                                <span class="ui label" style="background-color: {{ config('parser.visualization.nodeColors.' . $node->type, '#999') }}; color: white;">
                                    {{ $node->type }}
                                </span>
                            </td>
                            <td>{{ $node->threshold }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3 class="ui header">Multi-Word Expressions</h3>
                <table class="ui celled table">
                    <thead>
                        <tr>
                            <th>Phrase</th>
                            <th>Components</th>
                            <th>Semantic Type</th>
                            <th>Length</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grammar->mwes as $mwe)
                        <tr>
                            <td><strong>{{ $mwe->phrase }}</strong></td>
                            <td>{{ is_string($mwe->components) ? $mwe->components : json_encode($mwe->components) }}</td>
                            <td>
                                <span class="ui mini label">{{ $mwe->semanticType }}</span>
                            </td>
                            <td>{{ $mwe->length }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="ui divider"></div>

                <a href="/parser" class="ui button">
                    <i class="arrow left icon"></i>
                    Back to Parser
                </a>
            </div>
        </main>

        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
