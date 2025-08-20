{{--
    Tabs Component - Fomantic UI Tab Component with AlpineJS Utilities and HTMX Content Loading

    Uses Fomantic UI for tab behavior and styling, AlpineJS for utility functions,
    and HTMX for dynamic content loading.

    Parameters:
    - $tabs: Array of tab definitions with keys: id, label, icon, url, loadingText (optional)
    - $defaultTab: ID of the default active tab (default: first tab)
    - $context: Context ID for multiple tab sets on same page (default: 'main')
    - $baseClass: Additional CSS classes for the tab container (default: '')
    - $sectionToggle: Show section toggle button (default: false)
    - $sectionTitle: Title for the section (default: '')

    Tab definition example:
    [
        ['id' => 'textual', 'label' => 'Textual', 'icon' => 'file text', 'url' => '/api/textual'],
        ['id' => 'static', 'label' => 'Static', 'icon' => 'image', 'url' => '/api/static'],
        ['id' => 'dynamic', 'label' => 'Dynamic', 'icon' => 'video', 'url' => '/api/dynamic']
    ]
--}}

@php
    $tabs = $tabs ?? [];
    $defaultTab = $defaultTab ?? ($tabs[0]['id'] ?? '');
    $context = $context ?? 'main';
    $baseClass = $baseClass ?? '';
    $sectionToggle = $sectionToggle ?? false;
    $sectionTitle = $sectionTitle ?? '';
    $countTabs = [
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'none'
    ];
@endphp

<div class="tabs-component {{ $baseClass }}"
     x-data="tabsComponent()"
     x-init="init()"
     data-context="{{ $context }}"
     data-default-tab="{{ $defaultTab }}"
     data-tabs="{{ json_encode($tabs) }}"
>
    @if($sectionToggle && $sectionTitle)
        <div class="section-header">
            <h1 class="ui header section-title" id="{{ $context }}-tabs">
                <a href="#{{ $context }}-tabs">{{ $sectionTitle }}</a>
            </h1>
            <button class="ui button basic icon section-toggle"
                    onclick="toggleSection('{{ $context }}-tabs-content')"
                    aria-expanded="true">
                <i class="chevron up icon"></i>
            </button>
        </div>
    @endif

    <div class="section-content" id="{{ $context }}-tabs-content">
        {{-- Tab Navigation Menu --}}
        <div class="ui {{ $countTabs[count($tabs)] }} item stackable tabs menu">
            @foreach($tabs as $index => $tab)
                <a class="item {{ $tab['id'] === $defaultTab ? 'active' : '' }}"
                   data-tab="{{ $tab['id'] }}">
                    @if(isset($tab['icon']))
                        <x-dynamic-component :component="'icon::' . $tab['icon']" />
{{--                        <i class="{{ $tab['icon'] }} icon"></i>--}}
                    @endif
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Tab Content Areas --}}
        @foreach($tabs as $tab)
            <div class="ui tab {{ $tab['id'] === $defaultTab ? 'active' : '' }}"
                 data-tab="{{ $tab['id'] }}">

                <div class="tab-loading-indicator" style="display: none;">
                    <div class="ui active centered inline loader"></div>
                    <p>{{ $tab['loadingText'] ?? 'Loading ' . strtolower($tab['label']) . ' content...' }}</p>
                </div>

                <div class="tab-content" id="{{ $tab['id'] }}-content"
                     hx-get="{{ $tab['url'] }}"
                     hx-trigger="load-{{ $tab['id'] }} from:body"
                     hx-swap="innerHTML"
                     @htmx:before-request="$dispatch('tab-loading-start', '{{ $tab['id'] }}')"
                     @htmx:after-request="$dispatch('tab-loading-end', '{{ $tab['id'] }}')"
                     @htmx:response-error="$dispatch('tab-loading-error', '{{ $tab['id'] }}')">
                    {{-- Content will be loaded here via HTMX --}}
                </div>

                <div class="tab-error" style="display: none;">
                    <div class="ui error message">
                        <div class="header">Failed to load content</div>
                        <p>Unable to load {{ strtolower($tab['label']) }} content. Please try again.</p>
                        <button class="ui button"
                                @click="retryLoadContent('{{ $tab['id'] }}')">
                            <i class="refresh icon"></i>
                            Retry
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- HTMX Loading Elements (hidden, used for dynamic loading) --}}
@foreach($tabs as $tab)
    <div id="htmx-loader-{{ $tab['id'] }}" style="display: none;"
         hx-get="{{ $tab['url'] }}"
         hx-target="#{{ $tab['id'] }}-content"
         hx-swap="innerHTML"
         hx-trigger="load-{{ $tab['id'] }} from:body"
         @htmx:before-request="$dispatch('tab-loading-start', '{{ $tab['id'] }}')"
         @htmx:after-request="$dispatch('tab-loading-end', '{{ $tab['id'] }}')"
         @htmx:response-error="$dispatch('tab-loading-error', '{{ $tab['id'] }}')">
    </div>
@endforeach
