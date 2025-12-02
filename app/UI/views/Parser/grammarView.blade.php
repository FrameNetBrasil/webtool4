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

                <div class="grammar-actions">
                    <div class="ui form">
                        <div class="inline fields">
                            <div class="field">
                                <label>Filter by word:</label>
                                <input
                                    type="text"
                                    id="grammarFilter"
                                    placeholder="Enter word to filter nodes..."
                                    style="width: 300px;"
                                    value="{{ request()->get('filter', '') }}"
                                />
                            </div>
                            <div class="field">
                                <button
                                    class="ui primary button"
                                    hx-get="/parser/grammar/{{ $grammar->idGrammarGraph }}/visualization"
                                    hx-target="#grammarVisualization"
                                    hx-swap="innerHTML"
                                    hx-include="#grammarFilter"
                                    hx-vals='js:{"filter": document.getElementById("grammarFilter").value}'
                                >
                                    <i class="project diagram icon"></i>
                                    Show Graph Visualization
                                </button>
                            </div>
                            <div class="field">
                                <button
                                    class="ui button"
                                    onclick="document.getElementById('grammarFilter').value = ''; document.getElementById('grammarVisualization').innerHTML = ''; document.getElementById('filteredTables').innerHTML = '';"
                                >
                                    <i class="times icon"></i>
                                    Clear
                                </button>
                            </div>
                            <div class="field">
                                <button
                                    class="ui button"
                                    hx-get="/parser/grammar/{{ $grammar->idGrammarGraph }}/tables"
                                    hx-target="#filteredTables"
                                    hx-swap="innerHTML"
                                    hx-include="#grammarFilter"
                                    hx-vals='js:{"filter": document.getElementById("grammarFilter").value}'
                                >
                                    <i class="list icon"></i>
                                    Show Tables
                                </button>
                            </div>
                            <div class="field">
                                <a href="/parser" class="ui button">
                                    <i class="arrow left icon"></i>
                                    Back to Parser
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <div id="grammarVisualization"></div>
                </div>

                <div class="mt-6">
                    <div id="filteredTables"></div>
                </div>

                @if(count($errors) > 0)
                <div class="ui divider"></div>
                <div class="ui warning message">
                    <div class="header">Grammar Validation Warnings</div>
                    <ul class="list">
                        @foreach($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </main>

        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>

<style>
    .grammar-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .mt-6 {
        margin-top: 1.5rem;
    }
</style>
