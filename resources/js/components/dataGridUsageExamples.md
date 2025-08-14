# DataGrid Component Usage Examples

## 1. Basic Usage with UI Component

```blade
@php
$columns = [
    ['field' => 'id', 'title' => 'ID', 'width' => '80px'],
    ['field' => 'name', 'title' => 'Name', 'width' => '200px'],
    ['field' => 'email', 'title' => 'Email', 'width' => '250px'],
];

$config = [
    'showHeader' => true,
    'singleSelect' => true,
    'striped' => true,
    'onRowClick' => '(index, row) => { console.log("Row clicked:", row); }'
];
@endphp

<x-ui::datagrid
    :data="$users"
    :columns="$columns"
    :config="$config"
/>
```

## 2. Inline Alpine.js Usage

```blade
<div 
    x-data="dataGrid({
        data: @js($users),
        columns: @js($columns),
        showHeader: true,
        singleSelect: true,
        onRowClick: (index, row) => {
            console.log('Selected:', row);
            // HTMX integration
            htmx.ajax('GET', `/user/${row.id}`, '#userDetails');
        }
    })" 
    x-init="init()"
>
    <!-- Datagrid template content -->
</div>
```

## 3. Column Configuration Options

```javascript
const columns = [
    {
        field: 'id',
        title: 'ID',
        width: '80px',
        align: 'center',
        hidden: false
    },
    {
        field: 'name',
        title: 'Full Name',
        width: '200px',
        align: 'left',
        formatter: (value, row, column) => {
            return `<strong>${value}</strong>`;
        }
    },
    {
        field: 'status',
        title: 'Status',
        width: '100px',
        align: 'center',
        formatter: (value) => {
            const color = value === 'active' ? 'green' : 'red';
            return `<span class="ui ${color} label">${value}</span>`;
        }
    }
];
```

## 4. Event Handling

```javascript
{
    data: userData,
    columns: columns,
    onRowClick: (index, row, event) => {
        console.log('Row clicked:', { index, row });
    },
    onRowSelect: (index, row) => {
        console.log('Row selected:', { index, row });
    },
    onRowUnselect: (index, row) => {
        console.log('Row unselected:', { index, row });
    }
}
```

## 5. Configuration Options

```javascript
{
    // Data and columns
    data: [],
    columns: [],
    
    // Display options
    showHeader: true,        // Show table header
    rownumbers: false,       // Show row numbers
    showFooter: false,       // Show table footer
    border: true,           // Show table borders
    striped: true,          // Striped rows
    fit: false,             // Fit container width
    
    // Selection
    singleSelect: true,     // Single or multi-selection
    
    // Messages
    emptyMsg: 'No records', // Empty state message
    
    // Events
    onRowClick: null,       // Row click callback
    onRowSelect: null,      // Row select callback
    onRowUnselect: null     // Row unselect callback
}
```

## 6. HTMX Integration

```blade
<div 
    x-data="dataGrid({
        data: @js($documents),
        columns: [
            {field: 'title', title: 'Title', width: '60%'},
            {field: 'date', title: 'Date', width: '40%'}
        ],
        onRowClick: (index, row) => {
            // Load document details via HTMX
            htmx.ajax('GET', `/document/${row.id}`, '#documentContent');
        }
    })"
    x-init="init()"
>
    <!-- Table template here -->
</div>

<div id="documentContent">
    <!-- Document details will be loaded here -->
</div>
```

## 7. Programmatic Access

```javascript
// Get DataGrid instance
const grid = Alpine.$data(document.getElementById('my-grid'));

// Get selected row
const selected = grid.getSelectedRow();

// Get all selected rows (multi-select mode)
const allSelected = grid.getSelectedRows();

// Reload data
grid.reload(newDataArray);

// Clear selection
grid.clearSelection();

// Select specific row
grid.selectRow(2, grid.data[2]);
```

## Migration from jQuery EasyUI

### Before (jQuery EasyUI):
```javascript
$("#myGrid").datagrid({
    data: userData,
    columns: [[
        {field: 'id', hidden: true},
        {field: 'name', width: '50%'},
        {field: 'email', width: '50%'}
    ]],
    singleSelect: true,
    onClickRow: (index, row) => {
        console.log('Selected:', row);
    }
});
```

### After (Alpine.js):
```blade
<div 
    x-data="dataGrid({
        data: @js($userData),
        columns: [
            {field: 'id', hidden: true},
            {field: 'name', width: '50%'},
            {field: 'email', width: '50%'}
        ],
        singleSelect: true,
        onRowClick: (index, row) => {
            console.log('Selected:', row);
        }
    })"
    x-init="init()"
>
    <!-- Template content -->
</div>
```