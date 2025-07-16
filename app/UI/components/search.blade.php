{{-- Custom Search Component --}}
@props([
    'name' => 'search',
    'placeholder' => 'Search...',
    'searchUrl' => '/api/search',
    'searchFields' => ['q'], // Array of search field names
    'displayField' => 'name', // Field to display in readonly input
    'valueField' => 'id', // Field to store in hidden input
    'value' => '',
    'displayValue' => '',
    'modalTitle' => 'Search',
    'required' => false,
    'onChange' => null // Function name as string
])

<div x-data="searchComponent({
    name: '{{ $name }}',
    searchUrl: '{{ $searchUrl }}',
    displayField: '{{ $displayField }}',
    valueField: '{{ $valueField }}',
    initialValue: '{{ $value }}',
    initialDisplayValue: '{{ $displayValue }}',
    onChange: '{{ $onChange }}'
})" class="search-component">

    {{-- Hidden field to store the selected value --}}
    <input type="hidden"
           name="{{ $name }}"
           x-model="selectedValue"
        {{ $required ? 'required' : '' }}>

    {{-- Display field (readonly) --}}
    <div class="ui fluid left icon input">
        <input type="text"
               x-model="displayValue"
               placeholder="{{ $placeholder }}"
               readonly
               @click="openModal()"
               style="cursor: pointer;">
        <i class="search icon" style="cursor: pointer;" @click="openModal()"></i>
    </div>

    {{-- Modal Background --}}
    <div x-show="isModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="ui dimmer modals page active"
         @click="closeModal()"
         style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1001; background-color: rgba(0, 0, 0, 0.85);">
    </div>

    {{-- Modal Window --}}
    <div x-show="isModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="ui modal active"
         style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1002; height: 50vh; width: 600px; max-width: 90vw; display: flex; flex-direction: column; background: white; border-radius: 0.28571429rem; box-shadow: 0 0 0 1px rgba(34,36,38,.15), 0 1px 3px 0 rgba(34,36,38,.15);"
         @click.stop>
        <div class="header" style="flex-shrink: 0; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(34,36,38,.15);">
            <i class="search icon"></i>
            {{ $modalTitle }}
        </div>

        <div class="content" style="flex: 1; overflow-y: auto; padding: 1rem;">
            {{-- Search Form --}}
            <form class="ui form" @submit.prevent="performSearch()">
                @foreach($searchFields as $field)
                    <div class="field">
                        {{--                        <label>{{ ucfirst($field) }}</label>--}}
                        <div class="ui left icon input">
                            <input type="search"
                                   x-model="searchParams.{{ $field }}"
                                   placeholder="Enter {{ $field }}..."
                                   @input.debounce.300ms="performSearch()">
                            <i class="search icon"></i>
                        </div>

                    </div>
                @endforeach
            </form>

            {{-- Results/Loading Container with fixed height --}}
            <div style="position: relative; height: 300px; margin-top: 1rem; overflow-y: auto;">
                {{-- Loading State --}}
                <div x-show="isLoading" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                    <div class="ui active centered inline loader"></div>
                </div>

                {{-- Results Container --}}
                <div x-show="!isLoading && searchResults.length > 0" class="ui relaxed divided list" style="height: 100%;">
                    <template x-for="result in searchResults" :key="result.{{ $valueField }}">
                        <div class="item" style="cursor: pointer; padding: 10px;"
                             @click="selectResult(result)"
                             @mouseenter="$el.style.backgroundColor = '#f8f9fa'"
                             @mouseleave="$el.style.backgroundColor = 'transparent'">
                            <div class="content">
                                <div class="header" x-text="result.{{ $displayField }}"></div>
                                <div class="description" x-text="result.description || ''"></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- No Results Message --}}
                <div x-show="!isLoading && searchPerformed && searchResults.length === 0"
                     style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80%;">
                    <div class="ui message">
                        <div class="header">No results found</div>
                        <p>Try adjusting your search criteria.</p>
                    </div>
                </div>
            </div>

            {{-- Error Message --}}
            <div x-show="errorMessage" class="ui error message">
                <div class="header">Error</div>
                <p x-text="errorMessage"></p>
            </div>
        </div>

        <div class="actions" style="flex-shrink: 0; padding: 1rem 1.5rem; border-top: 1px solid rgba(34,36,38,.15); text-align: right;">
            <button type="button" class="ui button" @click="closeModal()">Cancel</button>
            <button type="button" class="ui red button" @click="clearSelection()">Clear</button>
        </div>
    </div>
</div>

<script>
    function searchComponent(config) {
        return {
            // Configuration
            name: config.name,
            searchUrl: config.searchUrl,
            displayField: config.displayField,
            valueField: config.valueField,
            onChange: config.onChange,

            // State
            isModalOpen: false,
            isLoading: false,
            searchPerformed: false,
            selectedValue: config.initialValue || '',
            displayValue: config.initialDisplayValue || '',
            searchResults: [],
            errorMessage: '',

            // Search parameters (dynamic based on searchFields)
            searchParams: {},

            init() {
                // Initialize search parameters
                @foreach($searchFields as $field)
                    this.searchParams['{{ $field }}'] = '';
                @endforeach

                // Ensure modal starts closed
                this.isModalOpen = false;

                // Close modal on ESC key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.isModalOpen) {
                        this.closeModal();
                    }
                });
            },

            openModal() {
                this.isModalOpen = true;
                this.errorMessage = '';
                document.body.classList.add('modal-open');

                // Focus first search field
                this.$nextTick(() => {
                    const firstInput = document.querySelector('.ui.modal.active input[type="search"]');
                    if (firstInput) firstInput.focus();
                });
            },

            closeModal() {
                this.isModalOpen = false;
                document.body.classList.remove('modal-open');
                this.resetSearch();
            },

            resetSearch() {
                // Clear search parameters
                Object.keys(this.searchParams).forEach(key => {
                    this.searchParams[key] = '';
                });
                this.searchResults = [];
                this.searchPerformed = false;
                this.errorMessage = '';
            },

            async performSearch() {
                // Check if any search parameter has value
                const hasSearchTerms = Object.values(this.searchParams).some(value => value.trim() !== '');

                if (!hasSearchTerms) {
                    this.searchResults = [];
                    this.searchPerformed = false;
                    return;
                }

                this.isLoading = true;
                this.errorMessage = '';

                try {
                    // Build URL with search parameters
                    const url = new URL(this.searchUrl, window.location.origin);
                    Object.entries(this.searchParams).forEach(([key, value]) => {
                        if (value.trim() !== '') {
                            url.searchParams.append(key, value);
                        }
                    });

                    const response = await fetch(url);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    this.searchResults = data.results || data || [];
                    this.searchPerformed = true;

                } catch (error) {
                    console.error('Search error:', error);
                    this.errorMessage = 'An error occurred while searching. Please try again.';
                    this.searchResults = [];
                } finally {
                    this.isLoading = false;
                }
            },

            selectResult(result) {
                this.selectedValue = result[this.valueField];
                this.displayValue = result[this.displayField];
                this.closeModal();

                // Trigger change event for form validation and custom handlers
                this.$nextTick(() => {
                    const hiddenInput = document.querySelector(`input[name="${this.name}"]`);
                    if (hiddenInput) {
                        // Dispatch standard change event
                        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

                        // Dispatch custom event with kebab-case name for Alpine.js
                        hiddenInput.dispatchEvent(new CustomEvent('search-component-change', {
                            bubbles: true,
                            detail: {
                                value: this.selectedValue,
                                displayValue: this.displayValue,
                                selectedItem: result,
                                componentName: this.name
                            }
                        }));
                    }

                    // Dispatch event on the component container for Alpine.js
                    this.$el.dispatchEvent(new CustomEvent('search-component-change', {
                        bubbles: true,
                        detail: {
                            value: this.selectedValue,
                            displayValue: this.displayValue,
                            selectedItem: result,
                            componentName: this.name
                        }
                    }));

                    // Call custom onChange function if provided
                    if (this.onChange && typeof window[this.onChange] === 'function') {
                        window[this.onChange]({
                            detail: {
                                value: this.selectedValue,
                                displayValue: this.displayValue,
                                selectedItem: result,
                                componentName: this.name
                            }
                        });
                    }
                });
            },

            clearSelection() {
                this.selectedValue = '';
                this.displayValue = '';

                // Trigger change event when clearing selection
                this.$nextTick(() => {
                    const hiddenInput = document.querySelector(`input[name="${this.name}"]`);
                    if (hiddenInput) {
                        // Dispatch standard change event
                        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

                        // Dispatch custom event with kebab-case name for Alpine.js
                        hiddenInput.dispatchEvent(new CustomEvent('search-component-change', {
                            bubbles: true,
                            detail: {
                                value: '',
                                displayValue: '',
                                selectedItem: null,
                                componentName: this.name,
                                action: 'clear'
                            }
                        }));
                    }

                    // Dispatch event on the component container for Alpine.js
                    this.$el.dispatchEvent(new CustomEvent('search-component-change', {
                        bubbles: true,
                        detail: {
                            value: '',
                            displayValue: '',
                            selectedItem: null,
                            componentName: this.name,
                            action: 'clear'
                        }
                    }));
                });
                // Don't close modal - just clear the selection
            }
        }
    }
</script>

<style>
    .search-component .ui.input input[readonly] {
        background-color: #ffffff !important;
        opacity: 1 !important;
    }

    .search-component .ui.input input[readonly]:focus {
        border-color: #85b7d9 !important;
    }

    body.modal-open {
        overflow: hidden;
    }

    .search-component .list .item:hover {
        background-color: #f8f9fa !important;
    }
</style>
