<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser'],['/parser/construction','Constructions'],['','Edit']]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="ui container overflow-y-auto">
                <div class="page-content">
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">Edit Construction</div>
                            <div class="page-subtitle">{{ $construction->name }}</div>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="ui error message">
                            <i class="close icon"></i>
                            <div class="header">Error</div>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form class="ui form" method="POST" action="/parser/construction/{{ $construction->idConstruction }}/update">
                        @csrf

                        <div class="field required">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="e.g., number_thousand"
                                   value="{{ old('name', $construction->name) }}" required maxlength="100">
                            <small>Unique identifier for this construction</small>
                        </div>

                        <div class="field required">
                            <label>BNF Pattern</label>
                            <textarea name="pattern" rows="4" placeholder="e.g., [{NUM}] mil [, ] [{NUM}] [e {NUM}]"
                                      required>{{ old('pattern', $construction->pattern) }}</textarea>
                            <small>
                                Syntax: {POS}, {POS:constraint}, {*}, [optional], (A | B), A+, A*
                            </small>
                        </div>

                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" rows="2" placeholder="Optional description">{{ old('description', $construction->description) }}</textarea>
                        </div>

                        <div class="two fields">
                            <div class="field required">
                                <label>Semantic Type</label>
                                <select name="semanticType" class="ui dropdown" required>
                                    @foreach($semanticTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('semanticType', $construction->semanticType) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label>Priority</label>
                                <input type="number" name="priority" value="{{ old('priority', $construction->priority) }}"
                                       min="-128" max="127" step="1">
                                <small>Higher priority constructions match first</small>
                            </div>
                        </div>

                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="enabled" value="1"
                                       {{ old('enabled', $construction->enabled) ? 'checked' : '' }}>
                                <label>Enabled</label>
                            </div>
                        </div>

                        <div class="ui divider"></div>

                        <div class="ui buttons">
                            <button type="submit" class="ui primary button">
                                <i class="save icon"></i>
                                Save Changes
                            </button>
                            <a href="/parser/construction/{{ $construction->idConstruction }}" class="ui button">
                                Cancel
                            </a>
                        </div>
                    </form>
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
