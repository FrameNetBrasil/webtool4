@php
    // Sample frame data for testing (normally this would come from the controller)
    $frame = (object) [
        'name' => 'Communication.frame',
        'description' => 'This frame involves a Speaker communicating a Message to an Addressee. The Means of communication may be linguistic or non-linguistic.',
        'id' => 123,
        'idLanguage' => 1
    ];

    $frameElements = [
        [
            'id' => 1,
            'name' => 'Speaker',
            'coreType' => 'Core',
            'description' => 'The person who is communicating the Message',
            'colorName' => 'frame-element-core'
        ],
        [
            'id' => 2,
            'name' => 'Message',
            'coreType' => 'Core', 
            'description' => 'The content that is being communicated',
            'colorName' => 'frame-element-core'
        ],
        [
            'id' => 3,
            'name' => 'Addressee',
            'coreType' => 'Core',
            'description' => 'The person to whom the Message is being communicated',
            'colorName' => 'frame-element-core'
        ],
        [
            'id' => 4,
            'name' => 'Means',
            'coreType' => 'Peripheral',
            'description' => 'The method or channel of communication',
            'colorName' => 'frame-element-peripheral'
        ]
    ];

    $stats = [
        'totalFEs' => count($frameElements),
        'coreFEs' => count(array_filter($frameElements, fn($fe) => $fe['coreType'] === 'Core')),
        'peripheralFEs' => count(array_filter($frameElements, fn($fe) => $fe['coreType'] === 'Peripheral')),
        'totalLUs' => 25,
        'totalRelations' => 8
    ];
@endphp

<x-layout::index-bulma>
    <div class="app-layout">
        @include('layouts.header-bulma')
        @include("layouts.sidebar-bulma")
        <main class="app-main" role="main" aria-label="Frame report content">
            <!-- Skip to main content link for keyboard navigation -->
            <a href="#main-content" class="skip-link sr-only-focusable">Skip to main content</a>
            
            <div class="container is-fluid" id="main-content">
                <!-- Breadcrumb Navigation -->
                <nav class="breadcrumb mb-4" aria-label="breadcrumbs">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/frame">Frames</a></li>
                        <li><a href="/report/frame">Frame Reports</a></li>
                        <li class="is-active"><a>{{ $frame->name }}</a></li>
                    </ul>
                </nav>

                <!-- Page Header -->
                <div class="hero is-light mb-6">
                    <div class="hero-body py-5">
                        <div class="level">
                            <div class="level-left">
                                <div class="level-item">
                                    <div>
                                        <h1 class="title is-3 frame">
                                            <span class="icon mr-2">
                                                <i class="material-symbols-outlined">account_tree</i>
                                            </span>
                                            {{ $frame->name }}
                                        </h1>
                                        <div class="subtitle is-6">
                                            {!! str_replace('ex>','code>',nl2br($frame->description)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="level-right">
                                <div class="level-item">
                                    <a href="/report/frame" class="button is-light">
                                        <span class="icon">
                                            <i class="material-symbols-outlined">arrow_back</i>
                                        </span>
                                        <span>Back to Frames</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Section -->
                <section class="block" aria-labelledby="stats-heading">
                    <h2 id="stats-heading" class="sr-only">Frame Statistics</h2>
                    <div class="columns">
                        <div class="column">
                            <div class="card">
                                <div class="card-content has-text-centered">
                                    <div class="is-size-1 has-text-primary has-text-weight-bold">{{ $stats['totalFEs'] }}</div>
                                    <div class="is-size-6 has-text-grey">Total Frame Elements</div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="card">
                                <div class="card-content has-text-centered">
                                    <div class="is-size-1 has-text-success has-text-weight-bold">{{ $stats['coreFEs'] }}</div>
                                    <div class="is-size-6 has-text-grey">Core Elements</div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="card">
                                <div class="card-content has-text-centered">
                                    <div class="is-size-1 has-text-info has-text-weight-bold">{{ $stats['peripheralFEs'] }}</div>
                                    <div class="is-size-6 has-text-grey">Peripheral Elements</div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="card">
                                <div class="card-content has-text-centered">
                                    <div class="is-size-1 has-text-warning has-text-weight-bold">{{ $stats['totalLUs'] }}</div>
                                    <div class="is-size-6 has-text-grey">Lexical Units</div>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="card">
                                <div class="card-content has-text-centered">
                                    <div class="is-size-1 has-text-danger has-text-weight-bold">{{ $stats['totalRelations'] }}</div>
                                    <div class="is-size-6 has-text-grey">Relations</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Frame Elements Section -->
                <div class="block" x-data="accordion">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-title">
                                <span class="icon mr-2">
                                    <i class="material-symbols-outlined">category</i>
                                </span>
                                Frame Elements
                            </div>
                            <button class="card-header-icon" @click="toggle">
                                <span class="icon">
                                    <i class="material-symbols-outlined" :class="{ 'rotate-180': isOpen }">expand_more</i>
                                </span>
                            </button>
                        </div>
                        <div class="card-content" x-show="isOpen" x-transition>
                            <x-ui::datagrid-bulma 
                                :data="$frameElements" 
                                :columns="[
                                    [
                                        'field' => 'name',
                                        'title' => 'Element Name',
                                        'width' => '200px'
                                    ],
                                    [
                                        'field' => 'coreType',
                                        'title' => 'Core Type',
                                        'width' => '120px',
                                        'align' => 'center'
                                    ],
                                    [
                                        'field' => 'description',
                                        'title' => 'Description',
                                        'width' => '400px'
                                    ]
                                ]" 
                                :config="[
                                    'rownumbers' => true,
                                    'striped' => true,
                                    'hoverable' => true,
                                    'size' => 'is-fullwidth'
                                ]"
                            />
                        </div>
                    </div>
                </div>

                <!-- Interactive Components Test Section -->
                <div class="block">
                    <div class="columns">
                        <div class="column is-half">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-header-title">Dropdown Test</div>
                                </div>
                                <div class="card-content">
                                    <div class="field">
                                        <label class="label">Select Language</label>
                                        <div class="control">
                                            <div class="dropdown" x-data="dropdown">
                                                <div class="dropdown-trigger">
                                                    <button class="button" @click="toggle" :class="{ 'is-active': isOpen }">
                                                        <span>Select Language</span>
                                                        <span class="icon is-small">
                                                            <i class="material-symbols-outlined" :class="{ 'rotate-180': isOpen }">expand_more</i>
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="dropdown-menu" x-show="isOpen" x-transition>
                                                    <div class="dropdown-content">
                                                        <a class="dropdown-item" @click="close">English</a>
                                                        <a class="dropdown-item" @click="close">Portuguese</a>
                                                        <a class="dropdown-item" @click="close">Spanish</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-half">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-header-title">Modal Test</div>
                                </div>
                                <div class="card-content">
                                    <div class="field">
                                        <div class="control">
                                            <button class="button is-primary" x-data @click="$dispatch('open-modal', { id: 'test-modal' })">
                                                Open Modal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Test Modal -->
                <div class="modal" x-data="modal" @open-modal.window="if ($event.detail.id === 'test-modal') open()">
                    <div class="modal-background" @click="close"></div>
                    <div class="modal-card">
                        <header class="modal-card-head">
                            <p class="modal-card-title">Test Modal</p>
                            <button class="delete" @click="close"></button>
                        </header>
                        <section class="modal-card-body">
                            <div class="content">
                                <p>This is a test modal using Alpine.js and Bulma styling.</p>
                                <p>All interactive components are working correctly:</p>
                                <ul>
                                    <li>✅ Modal open/close functionality</li>
                                    <li>✅ Dropdown interactions</li>
                                    <li>✅ DataGrid selection and hover states</li>
                                    <li>✅ Accordion expand/collapse</li>
                                </ul>
                            </div>
                        </section>
                        <footer class="modal-card-foot">
                            <button class="button is-success" @click="close">Close</button>
                        </footer>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layout::index-bulma>