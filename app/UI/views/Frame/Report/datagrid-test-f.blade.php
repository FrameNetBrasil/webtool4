{{--
    Simple DataGrid Test Page
    Use this to test the datagrid component functionality
--}}

@php
    // Sample test data
    $testData = [
        ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'active'],
        ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'inactive'],
        ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'status' => 'active'],
        ['id' => 4, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'status' => 'active'],
    ];
    
    // Column configuration
    $columns = [
        [
            'field' => 'id',
            'title' => 'ID',
            'width' => '80px',
            'align' => 'center'
        ],
        [
            'field' => 'name',
            'title' => 'Full Name',
            'width' => '200px',
            'align' => 'left'
        ],
        [
            'field' => 'email', 
            'title' => 'Email Address',
            'width' => '250px',
            'align' => 'left'
        ],
        [
            'field' => 'status',
            'title' => 'Status',
            'width' => '100px',
            'align' => 'center'
        ]
    ];
    
    // Configuration options
    $config = [
        'showHeader' => true,
        'rownumbers' => true,
        'singleSelect' => true,
        'striped' => true,
        'border' => true,
        'emptyMsg' => 'No users found'
    ];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataGrid Test</title>
    @vite(['resources/css/app.less', 'resources/js/app.js'])
</head>
<body>
    <div class="ui container" style="margin-top: 2rem;">
        <h1 class="ui header">DataGrid Component Test</h1>
        
        <div class="ui segment">
            <h3>Basic DataGrid</h3>
            <div style="height: 300px;">
                <x-ui::datagrid
                    :data="$testData"
                    :columns="$columns" 
                    :config="$config"
                />
            </div>
        </div>
        
        <div class="ui segment">
            <h3>DataGrid with Row Click Handler</h3>
            <div style="height: 300px;">
                <x-ui::datagrid
                    :data="$testData"
                    :columns="$columns"
                    :config="array_merge($config, [
                        'showHeader' => false,
                        'onRowClick' => 'alert(`Clicked row ${index}: ${row.name} (${row.email})`);'
                    ])"
                />
            </div>
        </div>
        
        <div class="ui segment">
            <h3>Empty DataGrid</h3>
            <div style="height: 200px;">
                <x-ui::datagrid
                    :data="[]"
                    :columns="$columns"
                    :config="array_merge($config, [
                        'emptyMsg' => 'No data available to display'
                    ])"
                />
            </div>
        </div>
    </div>
</body>
</html>