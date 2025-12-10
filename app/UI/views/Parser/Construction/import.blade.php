<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser'],['/parser/construction','Constructions'],['','Import']]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="ui container overflow-y-auto">
                <div class="page-content">
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">Import Constructions</div>
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

                    <div class="ui info message">
                        <div class="header">Import Format</div>
                        <p>
                            Upload a JSON file containing an array of constructions. Each construction should have:
                            <code>name</code>, <code>pattern</code>, <code>description</code>, <code>semanticType</code>,
                            <code>priority</code>, and <code>enabled</code> fields.
                        </p>
                    </div>

                    <form class="ui form" method="POST" action="/parser/construction/import" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="idGrammarGraph" value="{{ $grammar->idGrammarGraph }}">

                        <div class="field required">
                            <label>JSON File</label>
                            <input type="file" name="file" accept=".json" required>
                        </div>

                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="overwrite" value="1">
                                <label>Overwrite existing constructions with same name</label>
                            </div>
                        </div>

                        <div class="ui divider"></div>

                        <div class="ui buttons">
                            <button type="submit" class="ui primary button">
                                <i class="upload icon"></i>
                                Import Constructions
                            </button>
                            <a href="/parser/construction?grammar={{ $grammar->idGrammarGraph }}" class="ui button">
                                Cancel
                            </a>
                        </div>
                    </form>

                    <div class="ui divider"></div>

                    <div class="ui segment">
                        <h4 class="ui header">Example JSON Format</h4>
                        <pre style="background: #f8f8f8; padding: 15px; overflow-x: auto;">[
  {
    "name": "number_thousand",
    "pattern": "[{NUM}] mil [, ] [{NUM}] [e {NUM}]",
    "description": "Portuguese thousands pattern",
    "semanticType": "Head",
    "priority": 10,
    "enabled": true
  },
  {
    "name": "date_month_day",
    "pattern": "{NUM} de {NOUN}",
    "description": "Date format: day of month",
    "semanticType": "Temporal",
    "priority": 5,
    "enabled": true
  }
]</pre>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            $('.ui.checkbox').checkbox();

            $('.message .close').on('click', function() {
                $(this).closest('.message').transition('fade');
            });
        });
    </script>
</x-layout::index>
