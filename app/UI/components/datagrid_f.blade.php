{{--
    Alpine.js DataGrid Component Template
    
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
        'striped' => true
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
    <div x-show="isLoading" class="ui active loader"></div>
    
    <!-- Data Table -->
    <div x-show="!isLoading">
        <table :class="tableClasses">
            <!-- Table Header -->
            <thead x-show="showHeader && hasData">
                <tr>
                    <!-- Row Numbers Column -->
                    <th x-show="rownumbers" class="center aligned" style="width: 50px;">#</th>
                    
                    <!-- Data Columns -->
                    <template x-for="column in visibleColumns" :key="column.field">
                        <th 
                            :style="'width: ' + getColumnWidth(column)"
                            :class="column.align ? column.align + ' aligned' : ''"
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
                        <td x-show="rownumbers" class="center aligned" x-text="index + 1"></td>
                        
                        <!-- Data Cells -->
                        <template x-for="column in visibleColumns" :key="column.field">
                            <td 
                                :class="column.align ? column.align + ' aligned' : ''"
                                x-html="getCellValue(row, column)"
                            ></td>
                        </template>
                    </tr>
                </template>
                
                <!-- Empty State -->
                <tr x-show="!hasData">
                    <td 
                        :colspan="visibleColumns.length + (rownumbers ? 1 : 0)" 
                        class="center aligned"
                    >
                        <div class="ui message">
                            <span x-text="emptyMsg"></span>
                        </div>
                    </td>
                </tr>
            </tbody>
            
            <!-- Table Footer -->
            <tfoot x-show="showFooter && hasData">
                <tr>
                    <th x-show="rownumbers"></th>
                    <template x-for="column in visibleColumns" :key="column.field + '_footer'">
                        <th x-text="column.footerText || ''"></th>
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
    
    .datagrid-container .ui.table {
        margin: 0;
    }
    
    .datagrid-container .ui.table tr.selected {
        background-color: rgba(33, 133, 208, 0.1) !important;
    }
    
    .datagrid-container .ui.table tr.hover {
        background-color: rgba(0, 0, 0, 0.05) !important;
    }
    
    .datagrid-container .ui.table tr.selected:hover {
        background-color: rgba(33, 133, 208, 0.2) !important;
    }
    
    .datagrid-container .ui.table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05) !important;
    }
</style>