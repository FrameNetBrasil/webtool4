@php
    // Sample data for testing the Bulma DataGrid component
    $sampleData = [
        [
            'id' => 1,
            'name' => 'Frame Analysis',
            'type' => 'Frame',
            'status' => 'Active',
            'created' => '2024-01-15',
            'actions' => '<button class="button is-small is-primary">Edit</button>'
        ],
        [
            'id' => 2,
            'name' => 'Lexical Unit Study',
            'type' => 'LU',
            'status' => 'Draft',
            'created' => '2024-01-20',
            'actions' => '<button class="button is-small is-primary">Edit</button>'
        ],
        [
            'id' => 3,
            'name' => 'Construction Report',
            'type' => 'Construction',
            'status' => 'Completed',
            'created' => '2024-01-25',
            'actions' => '<button class="button is-small is-primary">Edit</button>'
        ],
        [
            'id' => 4,
            'name' => 'Semantic Analysis',
            'type' => 'SemanticType',
            'status' => 'Active',
            'created' => '2024-02-01',
            'actions' => '<button class="button is-small is-primary">Edit</button>'
        ]
    ];

    $columns = [
        [
            'field' => 'id',
            'title' => 'ID',
            'width' => '60px',
            'align' => 'center'
        ],
        [
            'field' => 'name',
            'title' => 'Report Name',
            'width' => '300px'
        ],
        [
            'field' => 'type',
            'title' => 'Type',
            'width' => '120px',
            'align' => 'center'
        ],
        [
            'field' => 'status',
            'title' => 'Status',
            'width' => '100px',
            'align' => 'center'
        ],
        [
            'field' => 'created',
            'title' => 'Created',
            'width' => '120px',
            'align' => 'center'
        ],
        [
            'field' => 'actions',
            'title' => 'Actions',
            'width' => '120px',
            'align' => 'center'
        ]
    ];

    $config = [
        'rownumbers' => true,
        'striped' => true,
        'hoverable' => true,
        'border' => true,
        'size' => 'is-fullwidth'
    ];
@endphp

<x-layout::index-bulma>
    <div class="app-layout">
        @include('layouts.header-bulma')
        @include("layouts.sidebar-bulma")
        <main class="app-main">
            <div class="container is-fluid">
                <div class="content">
                    <nav class="breadcrumb" aria-label="breadcrumbs">
                        <ul>
                            <li><a href="/">Home</a></li>
                            <li><a href="/frame">Frame</a></li>
                            <li><a href="/frame/report">Report</a></li>
                            <li class="is-active"><a>Bulma DataGrid Test</a></li>
                        </ul>
                    </nav>

                    <div class="block">
                        <h1 class="title is-3">Bulma DataGrid Component Test</h1>
                        <p class="subtitle">Testing the new Alpine.js + Bulma DataGrid implementation</p>
                    </div>

                    <div class="block">
                        <div class="card">
                            <div class="card-header">
                                <p class="card-header-title">Sample Reports DataGrid</p>
                                <button class="card-header-icon">
                                    <span class="icon">
                                        <i class="material-symbols-outlined">refresh</i>
                                    </span>
                                </button>
                            </div>
                            <div class="card-content">
                                <x-ui::datagrid-bulma 
                                    :data="$sampleData" 
                                    :columns="$columns" 
                                    :config="$config"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="columns">
                            <div class="column is-half">
                                <div class="card">
                                    <div class="card-header">
                                        <p class="card-header-title">Empty DataGrid Test</p>
                                    </div>
                                    <div class="card-content">
                                        <x-ui::datagrid-bulma 
                                            :data="[]" 
                                            :columns="$columns" 
                                            :config="['emptyMsg' => 'No data available']"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="column is-half">
                                <div class="card">
                                    <div class="card-header">
                                        <p class="card-header-title">Compact DataGrid</p>
                                    </div>
                                    <div class="card-content">
                                        <x-ui::datagrid-bulma 
                                            :data="array_slice($sampleData, 0, 2)" 
                                            :columns="array_slice($columns, 0, 4)" 
                                            :config="['rownumbers' => false, 'striped' => false, 'size' => 'is-narrow']"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="notification is-info is-light">
                            <span class="icon">
                                <i class="material-symbols-outlined">info</i>
                            </span>
                            <strong>DataGrid Features:</strong>
                            <ul class="mt-2">
                                <li>✅ Alpine.js powered interactivity</li>
                                <li>✅ Bulma styling with hover and selection states</li>
                                <li>✅ Row numbers and column alignment</li>
                                <li>✅ Empty state handling with custom messages</li>
                                <li>✅ Configurable sizing and styling options</li>
                                <li>✅ Click handlers and selection support</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Test click handler
        document.addEventListener('alpine:init', () => {
            // Add global dataGrid click handler for testing
            window.testRowClick = function(index, row, event) {
                console.log('Row clicked:', { index, row, event });
                
                // Show notification
                const notification = document.createElement('div');
                notification.className = 'notification is-success is-light';
                notification.innerHTML = `
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <strong>Row Clicked:</strong> ${row.name} (ID: ${row.id})
                `;
                
                const container = document.querySelector('.app-main') || document.body;
                container.insertBefore(notification, container.firstChild);
                
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 3000);
            };
        });
    </script>
</x-layout::index-bulma>