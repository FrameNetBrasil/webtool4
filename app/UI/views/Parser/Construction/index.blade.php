<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser'],['','Constructions']]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="ui container overflow-y-auto">
                <div class="page-content">
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-title">BNF Constructions</div>
                            <div class="page-subtitle">Grammar: {{ $grammar->name }} ({{ $grammar->language }})</div>
                        </div>
                        <div class="page-header-actions">
                            <a href="/parser/construction/create?grammar={{ $grammar->idGrammarGraph }}" class="ui primary button">
                                <i class="plus icon"></i>
                                New Construction
                            </a>
                            <a href="/parser/construction/import?grammar={{ $grammar->idGrammarGraph }}" class="ui button">
                                <i class="upload icon"></i>
                                Import
                            </a>
                            <a href="/parser/construction/export?grammar={{ $grammar->idGrammarGraph }}" class="ui button">
                                <i class="download icon"></i>
                                Export
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

                    @if(session('error'))
                        <div class="ui error message">
                            <i class="close icon"></i>
                            <div class="header">Error</div>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="ui statistics">
                        <div class="statistic">
                            <div class="value">{{ $stats['total'] }}</div>
                            <div class="label">Total</div>
                        </div>
                        <div class="statistic">
                            <div class="value">{{ $stats['enabled'] }}</div>
                            <div class="label">Enabled</div>
                        </div>
                        <div class="statistic">
                            <div class="value">{{ $stats['total'] - $stats['enabled'] }}</div>
                            <div class="label">Disabled</div>
                        </div>
                    </div>

                    <div class="ui divider"></div>

                    @if(count($constructions) === 0)
                        <div class="ui placeholder segment">
                            <div class="ui icon header">
                                <i class="file outline icon"></i>
                                No constructions found
                            </div>
                            <div class="inline">
                                <a href="/parser/construction/create?grammar={{ $grammar->idGrammarGraph }}" class="ui primary button">
                                    Create First Construction
                                </a>
                            </div>
                        </div>
                    @else
                        <table class="ui celled striped table">
                            <thead>
                                <tr>
                                    <th width="30px">#</th>
                                    <th>Name</th>
                                    <th>Pattern</th>
                                    <th>Semantic Type</th>
                                    <th width="80px">Priority</th>
                                    <th width="100px">Status</th>
                                    <th width="200px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($constructions as $construction)
                                    <tr class="{{ $construction->enabled ? '' : 'disabled' }}">
                                        <td>{{ $construction->idConstruction }}</td>
                                        <td>
                                            <a href="/parser/construction/{{ $construction->idConstruction }}">
                                                {{ $construction->name }}
                                            </a>
                                        </td>
                                        <td><code class="pattern-code">{{ \Illuminate\Support\Str::limit($construction->pattern, 80) }}</code></td>
                                        <td><span class="ui small label">{{ $construction->semanticType }}</span></td>
                                        <td class="center aligned">{{ $construction->priority }}</td>
                                        <td class="center aligned">
                                            <div class="ui fitted toggle checkbox"
                                                 data-construction-id="{{ $construction->idConstruction }}"
                                                 data-enabled="{{ $construction->enabled ? 1 : 0 }}">
                                                <input type="checkbox" {{ $construction->enabled ? 'checked' : '' }}>
                                                <label></label>
                                            </div>
                                        </td>
                                        <td class="center aligned">
                                            <div class="ui small basic icon buttons">
                                                <a href="/parser/construction/{{ $construction->idConstruction }}"
                                                   class="ui button" title="View">
                                                    <i class="eye icon"></i>
                                                </a>
                                                <a href="/parser/construction/{{ $construction->idConstruction }}/edit"
                                                   class="ui button" title="Edit">
                                                    <i class="edit icon"></i>
                                                </a>
                                                <a href="/parser/construction/{{ $construction->idConstruction }}/graph"
                                                   class="ui button" title="View Graph">
                                                    <i class="project diagram icon"></i>
                                                </a>
                                                <button class="ui button" title="Delete"
                                                        onclick="deleteConstruction({{ $construction->idConstruction }}, '{{ $construction->name }}')">
                                                    <i class="trash icon"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <style>
        .pattern-code {
            font-family: 'Courier New', monospace;
            background: #f4f4f4;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 0.9em;
        }
        tr.disabled {
            opacity: 0.5;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize Fomantic checkbox with HTMX toggle
            $('.ui.checkbox').checkbox({
                onChecked: function() {
                    const constructionId = $(this).closest('.checkbox').data('construction-id');
                    toggleConstruction(constructionId, true);
                },
                onUnchecked: function() {
                    const constructionId = $(this).closest('.checkbox').data('construction-id');
                    toggleConstruction(constructionId, false);
                }
            });

            // Close messages on click
            $('.message .close').on('click', function() {
                $(this).closest('.message').transition('fade');
            });
        });

        function toggleConstruction(id, enabled) {
            $.post(`/parser/construction/${id}/toggle`, {
                _token: '{{ csrf_token() }}'
            })
            .done(function(response) {
                if (response.success) {
                    const row = $(`.ui.checkbox[data-construction-id="${id}"]`).closest('tr');
                    if (enabled) {
                        row.removeClass('disabled');
                    } else {
                        row.addClass('disabled');
                    }
                }
            })
            .fail(function(xhr) {
                alert('Failed to toggle construction: ' + (xhr.responseJSON?.error || 'Unknown error'));
                location.reload();
            });
        }

        function deleteConstruction(id, name) {
            if (!confirm(`Are you sure you want to delete construction "${name}"?`)) {
                return;
            }

            $.ajax({
                url: `/parser/construction/${id}/delete`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Failed to delete construction: ' + (xhr.responseJSON?.error || 'Unknown error'));
                }
            });
        }
    </script>
</x-layout::index>
