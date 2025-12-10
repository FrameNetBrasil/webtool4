<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser'],['/parser/construction','Constructions'],['','New']]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="ui container overflow-y-auto">
                <div class="page-content">
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">Create New Construction</div>
                            <div class="page-subtitle">Grammar: {{ $grammar->name }}</div>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="ui error message">
                            <i class="close icon"></i>
                            <div class="header">Error</div>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form class="ui form" method="POST" action="/parser/construction/create">
                        @csrf
                        <input type="hidden" name="idGrammarGraph" value="{{ $grammar->idGrammarGraph }}">

                        <div class="field required">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="e.g., number_thousand"
                                   value="{{ old('name') }}" required maxlength="100">
                            <small>Unique identifier for this construction</small>
                        </div>

                        <div class="field required">
                            <label>BNF Pattern</label>
                            <textarea name="pattern" rows="4" placeholder="e.g., [{NUM}] mil [, ] [{NUM}] [e {NUM}]"
                                      required>{{ old('pattern') }}</textarea>
                            <small>
                                Syntax: {POS}, {POS:constraint}, {*}, [optional], (A | B), A+, A*
                            </small>
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" rows="2" placeholder="Optional description">{{ old('description') }}</textarea>
                        </div>

                        <div class="two fields">
                            <div class="field required">
                                <label>Semantic Type</label>
                                <select name="semanticType" class="ui dropdown" required>
                                    @foreach($semanticTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('semanticType') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label>Priority</label>
                                <input type="number" name="priority" value="{{ old('priority', 0) }}"
                                       min="-128" max="127" step="1">
                                <small>Higher priority constructions match first</small>
                            </div>
                        </div>

                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="enabled" value="1"
                                       {{ old('enabled', true) ? 'checked' : '' }}>
                                <label>Enabled</label>
                            </div>
                        </div>

                        <div class="ui divider"></div>

                        <div class="ui buttons">
                            <button type="submit" class="ui primary button">
                                <i class="save icon"></i>
                                Create Construction
                            </button>
                            <a href="/parser/construction?grammar={{ $grammar->idGrammarGraph }}" class="ui button">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <div class="ui divider"></div>

                    <div class="ui segment">
                        <h4 class="ui header">Pattern Syntax Reference</h4>
                        <table class="ui very basic table">
                            <tbody>
                                <tr>
                                    <td><code>{POS}</code></td>
                                    <td>Match any word with specified UDPOS tag (e.g., {NUM}, {NOUN})</td>
                                </tr>
                                <tr>
                                    <td><code>{POS:constraint}</code></td>
                                    <td>Match POS with additional constraint (e.g., {NUM:Gender=Masc})</td>
                                </tr>
                                <tr>
                                    <td><code>{*}</code></td>
                                    <td>Match any single token (wildcard)</td>
                                </tr>
                                <tr>
                                    <td><code>word</code></td>
                                    <td>Match literal word (exact text match)</td>
                                </tr>
                                <tr>
                                    <td><code>[element]</code></td>
                                    <td>Optional element (0 or 1 occurrence)</td>
                                </tr>
                                <tr>
                                    <td><code>(A | B | C)</code></td>
                                    <td>Alternative elements (choose one)</td>
                                </tr>
                                <tr>
                                    <td><code>A+</code></td>
                                    <td>One or more repetitions</td>
                                </tr>
                                <tr>
                                    <td><code>A*</code></td>
                                    <td>Zero or more repetitions</td>
                                </tr>
                                <tr>
                                    <td><code>(A B C)</code></td>
                                    <td>Sequence grouping</td>
                                </tr>
                            </tbody>
                        </table>

                        <h5 class="ui header">Examples</h5>
                        <ul>
                            <li><code>[{NUM}] mil [, ] [{NUM}] [e {NUM}]</code> - Portuguese thousands (e.g., "mil", "dois mil e quinze")</li>
                            <li><code>{NUM} de {NOUN}</code> - Quantified noun phrase (e.g., "cinco de março")</li>
                            <li><code>(o | a | os | as) {NOUN}</code> - Definite article + noun</li>
                            <li><code>{ADJ}+ {NOUN}</code> - One or more adjectives + noun</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            $('.ui.dropdown').dropdown();
            $('.ui.checkbox').checkbox();

            $('.message .close').on('click', function() {
                $(this).closest('.message').transition('fade');
            });
        });
    </script>
</x-layout::index>
