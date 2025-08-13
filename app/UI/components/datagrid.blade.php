{{--
    Alpine.js DataGrid Component Template - Bulma Version
    
    Variables expected:
    - $id: Unique identifier for the grid (optional)
    - $data: Array of data rows
    - $columns: Array of column definitions  
    - $config: Additional configuration options (optional)
--}}

@php
    // Set default values
    $id = $id ?? uniqid('datagrid_');
    $data = $data ?? [];
    $columns = $columns ?? [];
    $config = $config ?? [];
    
    // Merge default config with provided config
    $defaultConfig = [
        'showHeader' => true,
        'rownumbers' => false,
        'showFooter' => false,
        'border' => true,
        'singleSelect' => true,
        'emptyMsg' => 'No records',
        'fit' => false,
        'striped' => true,
        'hoverable' => true,
        'size' => 'is-fullwidth' // is-narrow, is-fullwidth
    ];
    
    $gridConfig = array_merge($defaultConfig, $config);
@endphp

<div 
    id="{{ $id }}"
    x-data="dataGrid({
        data: @js($data),
        columns: @js($columns),
        ...@js($gridConfig)
    })" 
    x-init="init()"
    class="datagrid-container"
>
    <!-- Loading State -->
    <div x-show="isLoading" class="has-text-centered py-6">
        <button class="button is-loading is-large is-ghost"></button>
        <p class="mt-3 has-text-grey">Loading data...</p>
    </div>
    
    <!-- Data Table -->
    <div x-show="!isLoading" class="table-container">
        <table :class="tableClasses">
            <!-- Table Header -->
            <thead x-show="showHeader && hasData">
                <tr>
                    <!-- Row Numbers Column -->
                    <th x-show="rownumbers" class="has-text-centered" style="width: 50px;">#</th>
                    
                    <!-- Data Columns -->
                    <template x-for="column in visibleColumns" :key="column.field">
                        <th 
                            :style="'width: ' + getColumnWidth(column)"
                            :class="getColumnClasses(column)"
                        >
                            <span x-text="column.title || column.field"></span>
                        </th>
                    </template>
                </tr>
            </thead>
            
            <!-- Table Body -->
            <tbody>
                <!-- Data Rows -->
                <template x-for="(row, index) in data" :key="index">
                    <tr 
                        :class="getRowClasses(index)"
                        @click="handleRowClick(index, row, $event)"
                        @mouseenter="handleRowHover(index)"
                        @mouseleave="handleRowLeave()"
                        style="cursor: pointer;"
                    >
                        <!-- Row Number -->
                        <td x-show="rownumbers" class="has-text-centered" x-text="index + 1"></td>
                        
                        <!-- Data Cells -->
                        <template x-for="column in visibleColumns" :key="column.field">
                            <td 
                                :class="getColumnClasses(column)"
                                x-html="getCellValue(row, column)"
                            ></td>
                        </template>
                    </tr>
                </template>
                
                <!-- Empty State -->
                <tr x-show="!hasData">
                    <td 
                        :colspan="visibleColumns.length + (rownumbers ? 1 : 0)" 
                        class="has-text-centered py-6"
                    >
                        <div class="notification is-light">
                            <span class="icon has-text-grey">
                                <i class="material-symbols-outlined">inbox</i>
                            </span>
                            <p class="mt-2" x-text="emptyMsg"></p>
                        </div>
                    </td>
                </tr>
            </tbody>
            
            <!-- Table Footer -->
            <tfoot x-show="showFooter && hasData">
                <tr>
                    <th x-show="rownumbers"></th>
                    <template x-for="column in visibleColumns" :key="column.field + '_footer'">
                        <th :class="getColumnClasses(column)" x-text="column.footerText || ''"></th>
                    </template>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<style>
    .datagrid-container {
        position: relative;
    }
    
    .datagrid-container .table {
        margin-bottom: 0;
    }
    
    .datagrid-container .table tr.is-selected {
        background-color: hsl(var(--bulma-primary-h), var(--bulma-primary-s), 95%) !important;
        color: hsl(var(--bulma-primary-h), var(--bulma-primary-s), 25%);
    }
    
    .datagrid-container .table tr.is-hovered {
        background-color: hsl(0, 0%, 98%) !important;
    }
    
    .datagrid-container .table tr.is-selected.is-hovered {
        background-color: hsl(var(--bulma-primary-h), var(--bulma-primary-s), 92%) !important;
    }
    
    .datagrid-container .table.is-hoverable tbody tr:hover {
        background-color: hsl(0, 0%, 98%);
    }
    
    .datagrid-container .table.is-hoverable tbody tr.is-selected:hover {
        background-color: hsl(var(--bulma-primary-h), var(--bulma-primary-s), 92%);
    }
    
    .datagrid-container .notification {
        border: none;
        box-shadow: none;
    }
</style>