<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser']]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="page-content" id="parserApp">
                <div class="page-header">
                    <div class="page-header-content">
                        <div class="page-title">Graph-Based Predictive Parser</div>
                        <div class="page-subtitle">Multi-word expression processing with activation-based mechanisms</div>
                    </div>
                </div>

                <div class="parser-controls">
                    <form class="ui form" id="parserForm">
                        <div class="fields">
                            <div class="fourteen wide field">
                                <label for="sentence">Sentence</label>
                                <input
                                    type="text"
                                    id="sentence"
                                    name="sentence"
                                    placeholder="Enter a sentence to parse (e.g., 'Tomei café da manhã cedo')"
                                    required
                                />
                            </div>
                        </div>

                        <div class="fields">
                            <div class="eight wide field">
                                <label for="idGrammarGraph">Grammar</label>
                                <select id="idGrammarGraph" name="idGrammarGraph" class="ui dropdown" required>
                                    @foreach($grammars as $grammar)
                                        <option value="{{ $grammar->idGrammarGraph }}">
                                            {{ $grammar->name }} ({{ $grammar->language }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="six wide field">
                                <label for="queueStrategy">Queue Strategy</label>
                                <select id="queueStrategy" name="queueStrategy" class="ui dropdown">
                                    <option value="fifo">FIFO (First In, First Out)</option>
                                    <option value="lifo">LIFO (Last In, First Out)</option>
                                </select>
                            </div>
                        </div>

                        <x-button
                            id="btnParse"
                            label="Parse Sentence"
                            color="primary"
                            hx-post="/parser/parse"
                            hx-target="#parseResults"
                            hx-indicator="#parseLoader"
                        ></x-button>

                        <div id="parseLoader" class="ui active inline loader htmx-indicator"></div>
                    </form>
                </div>

                <div class="mt-6">
                    <div id="parseResults" class="parser-results"></div>
                </div>

                @if(count($recentParses) > 0)
                <div class="mt-8">
                    <div class="ui divider"></div>
                    <h3 class="ui header">Recent Parses</h3>
                    <table class="ui celled table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sentence</th>
                                <th>Grammar</th>
                                <th>Status</th>
                                <th>Nodes</th>
                                <th>Edges</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentParses as $parse)
                            <tr>
                                <td>{{ $parse->idParserGraph }}</td>
                                <td>{{ $parse->sentence }}</td>
                                <td>{{ $parse->grammarName }}</td>
                                <td>
                                    <span class="ui label {{ $parse->status === 'complete' ? 'green' : ($parse->status === 'failed' ? 'red' : 'yellow') }}">
                                        {{ ucfirst($parse->status) }}
                                    </span>
                                </td>
                                <td>{{ $parse->nodeCount }}</td>
                                <td>{{ $parse->linkCount }}</td>
                                <td>
                                    <a href="/parser/result/{{ $parse->idParserGraph }}" class="ui mini button">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </main>

        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>

<script>
    // Initialize Fomantic-UI dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        $('.ui.dropdown').dropdown();
    });

    // HTMX event handlers
    document.body.addEventListener('htmx:afterSwap', function(evt) {
        if (evt.detail.target.id === 'parseResults') {
            // Scroll to results
            evt.detail.target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    });

    document.body.addEventListener('htmx:responseError', function(evt) {
        if (evt.detail.target.id === 'parseResults') {
            evt.detail.target.innerHTML = '<div class="ui negative message"><p>Parse error. Please try again.</p></div>';
        }
    });
</script>
