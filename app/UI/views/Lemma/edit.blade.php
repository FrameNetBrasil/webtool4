<x-layout.index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/structure','Structure'],['/lemma','Lemmas'],['', 'Lemma #' . $lemma->idLexicon]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="ui container h-full d-flex flex-col">
                <div class="page-header-object">
                    <div class="page-object">
                        <div class="page-object-name">
                            <span>{{$lemma->fullName}}</span>
                        </div>
                        <div class="page-object-data">
                            <div class="ui label wt-tag-id">
                                #{{$lemma->idLemma}}
                            </div>
                            <button
                                class="ui danger button"
                                x-data
                                @click.prevent="messenger.confirmDelete(`Removing Lemma '{{$lemma->fullName}}'.`, '/lemma/{{$lemma->idLexicon}}')"
                            >Delete</button>
                        </div>
                    </div>
                    <dic class="page-subtitle">
                        Lemma
                    </dic>
                </div>
                <div class="page-content">
                    <form>
                        <input type="hidden" name="idLexiconGroup" value="2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="idLexicon" value="{{$lemma->idLexicon}}">
                        <div class="ui fluid card form-card">
                            <div class="content">
                                <div class="header">
                                    Edit Lemma
                                </div>
                                <div class="description">

                                </div>
                            </div>
                            <div class="content">
                                <div class="ui form">
                                    <div class="two fields">
                                        <div class="field">
                                            <x-ui::text-field
                                                label="Name"
                                                id="name"
                                                :value="$lemma->name"
                                            ></x-ui::text-field>
                                        </div>
                                        <div class="field">
                                            <x-combobox::ud-pos
                                                id="idUDPOS"
                                                label="POS"
                                                :value="$lemma->idUDPOS"
                                            ></x-combobox::ud-pos>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="extra content">
                                <div class="ui buttons">
                                    <button
                                        class="ui button primary"
                                        hx-put="/lemma/{{$lemma->idLemma}}"
                                    >Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="ui warning message">
                        <div class="header">
                            Warning!
                        </div>
                        If lemma is a MWE, each expression can be another lemma or a word. Choose wisely.
                    </div>
                    <form>
                        <input type="hidden" name="idLemmaBase" value="{{$lemma->idLexicon}}">

                        <div class="ui fluid card form-card">
                            <div class="content">
                                <div class="header">
                                    Add Expression
                                </div>
                                <div class="description">

                                </div>
                            </div>
                            <div class="content">
                                <div class="ui form">
                                    <div class="fields">
                                        <div class="field w-8rem">
                                            <x-combobox::options
                                                label="Type"
                                                id="idLexiconGroup"
                                                :options="[1 => 'word', 2 => 'lemma']"
                                                value=""
                                            ></x-combobox::options>
                                        </div>
                                        <div class="field">
                                            <x-ui::text-field
                                                label="Form"
                                                id="form"
                                                value=""
                                            ></x-ui::text-field>
                                        </div>
                                        <div class="field">
                                            <x-combobox::ud-pos
                                                id="idUDPOSExpression"
                                                label="UDPOS"
                                                :value="$lemma->idUDPOS"
                                            ></x-combobox::ud-pos>
                                        </div>
                                    </div>
                                    <div class="fields">
                                        <div class="field">
                                            <x-ui::text-field
                                                label="Position"
                                                id="position"
                                                :value="1"
                                            ></x-ui::text-field>
                                        </div>
                                        <div class="field">
                                            <x-ui::checkbox
                                                id="headWord"
                                                name="head"
                                                label="Is Head?"
                                                :active="true"
                                            ></x-ui::checkbox>
                                        </div>
                                        <div class="field">
                                            <x-ui::checkbox
                                                id="breakBefore"
                                                label="Break before?"
                                                :active="false"
                                            ></x-ui::checkbox>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="extra content">
                                <div class="ui buttons">
                                    <button
                                        class="ui button primary"
                                        hx-post="/lemma/{{$lemma->idLemma}}/expression"
                                    >Add Expression</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <h3 class="ui header">Expressions</h3>
                    @include("Lemma.expressions")

                    @if(!empty($pattern))
                    <h3 class="ui header">Dependency Pattern</h3>
                    <div class="ui fluid card">
                        <div class="content">
                            <div class="header">
                                <i class="sitemap icon"></i>
                                Syntactic Structure
                            </div>
                            <div class="description">
                                This pattern represents the dependency tree structure for this lemma.
                            </div>
                        </div>
                        <div class="content">
                            <div id="pattern-tree-container"
                                 data-lemma-id="{{$lemma->idLemma}}"
                                 style="min-height: 400px; border: 1px solid #e0e0e0; border-radius: 4px; padding: 20px;">
                                <!-- Tree visualization will be rendered here by JavaScript -->
                                <div class="ui active centered inline loader"></div>
                            </div>
                        </div>
                    </div>

                    <script src="/scripts/treex/js-treex-view.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const container = document.getElementById('pattern-tree-container');
                            if (!container) return;

                            const lemmaId = container.dataset.lemmaId;

                            // Set timeout to prevent infinite blocking
                            const controller = new AbortController();
                            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout

                            // Fetch pattern data from server (already converted to treex format)
                            fetch(`/lemma/${lemmaId}/pattern`, {
                                signal: controller.signal
                            })
                                .then(response => {
                                    clearTimeout(timeoutId);
                                    if (!response.ok) {
                                        throw new Error('Failed to load pattern data: ' + response.status);
                                    }
                                    return response.json();
                                })
                                .then(treexData => {
                                    console.log('Treex data received:', treexData);

                                    // Clear loader
                                    container.innerHTML = '';

                                    // Render tree
                                    if (typeof jQuery !== 'undefined' && jQuery.fn.treexView) {
                                        jQuery('#pattern-tree-container').treexView(treexData);
                                    } else {
                                        console.error('treexView plugin not loaded');
                                        container.innerHTML = '<div class="ui warning message"><p>Tree visualization library not loaded.</p></div>';
                                    }
                                })
                                .catch(error => {
                                    clearTimeout(timeoutId);
                                    console.error('Error loading pattern:', error);
                                    if (error.name === 'AbortError') {
                                        container.innerHTML = '<div class="ui warning message"><p>Request timeout: Pattern loading took too long. Please refresh the page.</p></div>';
                                    } else {
                                        container.innerHTML = '<div class="ui negative message"><p>Error loading dependency pattern: ' + error.message + '</p></div>';
                                    }
                                });
                        });
                    </script>
                    @else
                    <div class="ui warning message">
                        <div class="header">
                            <i class="info circle icon"></i>
                            No Pattern Available
                        </div>
                        <p>This lemma does not have a dependency pattern stored yet. Patterns are automatically generated for multi-word expressions (MWEs) with stored expressions.</p>
                    </div>
                    @endif
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout.index>
