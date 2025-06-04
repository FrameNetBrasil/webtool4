# Page-Specific LESS Files

Specialized styles for different page types in your CRUD application.

---

## 1. CRUD Pages (`src/styles/pages/crud.less`)

```less
/*******************************
    CRUD Pages Styling
*******************************/

.crud-page {
  /* Page container */
  .page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #E5E7EB;
    
    .page-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: #1F2937;
      margin: 0;
    }
    
    .page-subtitle {
      color: #6B7280;
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }
    
    .page-actions {
      display: flex;
      gap: 0.75rem;
    }
  }
  
  /* Filters and search */
  .filters-bar {
    background: #F9FAFB;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border: 1px solid #E5E7EB;
    
    .filters-row {
      display: flex;
      align-items: center;
      gap: 1rem;
      flex-wrap: wrap;
    }
    
    .filter-group {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      
      label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        white-space: nowrap;
      }
    }
    
    .search-input {
      min-width: 250px;
      flex: 1;
    }
    
    .filter-tags {
      display: flex;
      gap: 0.5rem;
      margin-top: 0.75rem;
      flex-wrap: wrap;
    }
  }
  
  /* Data display modes */
  .view-controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    
    .view-toggle {
      display: flex;
      border: 1px solid #E5E7EB;
      border-radius: 6px;
      overflow: hidden;
      
      .toggle-option {
        padding: 0.5rem 1rem;
        background: #FFFFFF;
        border: none;
        color: #6B7280;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.15s ease;
        
        &:hover {
          background: #F9FAFB;
          color: #374151;
        }
        
        &.active {
          background: #5E6AD2;
          color: #FFFFFF;
        }
        
        &:not(:last-child) {
          border-right: 1px solid #E5E7EB;
        }
      }
    }
    
    .results-info {
      color: #6B7280;
      font-size: 0.875rem;
    }
  }
  
  /* Enhanced table styling */
  .data-table {
    .ui.table {
      border-radius: 8px;
      border: 1px solid #E5E7EB;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      
      thead th {
        background: #F9FAFB;
        font-weight: 600;
        color: #374151;
        text-transform: none;
        letter-spacing: 0;
        position: relative;
        
        /* Sortable columns */
        &.sortable {
          cursor: pointer;
          user-select: none;
          
          &:hover {
            background: #F3F4F6;
          }
          
          &::after {
            content: '';
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 4px solid #9CA3AF;
            opacity: 0;
            transition: opacity 0.15s ease;
          }
          
          &:hover::after {
            opacity: 0.5;
          }
          
          &.sorted-asc::after {
            opacity: 1;
            border-bottom: 4px solid #5E6AD2;
          }
          
          &.sorted-desc::after {
            opacity: 1;
            border-bottom: none;
            border-top: 4px solid #5E6AD2;
          }
        }
      }
      
      tbody tr {
        &:hover {
          background: #F9FAFB;
        }
        
        &.selected {
          background: #EEF2FF;
          
          &:hover {
            background: #E0E7FF;
          }
        }
        
        &.highlighted {
          background: #FEF3C7;
          
          &:hover {
            background: #FDE68A;
          }
        }
      }
      
      .action-cell {
        width: 120px;
        text-align: right;
        
        .action-buttons {
          display: flex;
          gap: 0.25rem;
          justify-content: flex-end;
          opacity: 0;
          transition: opacity 0.15s ease;
        }
      }
      
      tbody tr:hover .action-cell .action-buttons {
        opacity: 1;
      }
      
      .status-cell {
        width: 100px;
      }
      
      .relation-cell {
        .relation-link {
          color: #5E6AD2;
          text-decoration: none;
          
          &:hover {
            text-decoration: underline;
          }
        }
      }
    }
  }
  
  /* Form containers */
  .form-container {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 2rem;
    
    .form-header {
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #F3F4F6;
      
      .form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
      }
      
      .form-description {
        color: #6B7280;
        font-size: 0.875rem;
      }
    }
    
    .form-section {
      margin-bottom: 2rem;
      
      .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 1rem;
      }
      
      .section-description {
        color: #6B7280;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
      }
    }
    
    .form-actions {
      display: flex;
      gap: 0.75rem;
      justify-content: flex-end;
      padding-top: 1.5rem;
      border-top: 1px solid #F3F4F6;
    }
    
    /* Field groups */
    .field-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      
      &.single-column {
        grid-template-columns: 1fr;
      }
      
      &.three-column {
        grid-template-columns: 1fr 1fr 1fr;
      }
    }
  }
  
  /* Relationship management */
  .relationship-manager {
    .relationship-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1rem;
      
      .relationship-title {
        font-weight: 600;
        color: #1F2937;
      }
      
      .relationship-count {
        color: #6B7280;
        font-size: 0.875rem;
      }
    }
    
    .relationship-list {
      border: 1px solid #E5E7EB;
      border-radius: 6px;
      max-height: 300px;
      overflow-y: auto;
      
      .relationship-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #F3F4F6;
        
        &:last-child {
          border-bottom: none;
        }
        
        &:hover {
          background: #F9FAFB;
        }
        
        .item-info {
          flex: 1;
          
          .item-title {
            font-weight: 500;
            color: #1F2937;
          }
          
          .item-subtitle {
            color: #6B7280;
            font-size: 0.875rem;
          }
        }
        
        .item-actions {
          display: flex;
          gap: 0.25rem;
        }
      }
    }
    
    .add-relationship {
      margin-top: 1rem;
      
      .search-and-add {
        display: flex;
        gap: 0.5rem;
        
        .search-input {
          flex: 1;
        }
      }
    }
  }
}

/* Bulk operations */
.bulk-operations {
  position: fixed;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  background: #1F2937;
  color: #FFFFFF;
  padding: 1rem 1.5rem;
  border-radius: 8px;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
  z-index: 100;
  transition: all 0.3s ease;
  
  &.hidden {
    opacity: 0;
    transform: translateX(-50%) translateY(100%);
    pointer-events: none;
  }
  
  .selection-info {
    font-size: 0.875rem;
    
    .count {
      font-weight: 600;
    }
  }
  
  .bulk-actions {
    display: flex;
    gap: 0.5rem;
    
    .ui.button {
      background: rgba(255, 255, 255, 0.1);
      color: #FFFFFF;
      border: 1px solid rgba(255, 255, 255, 0.2);
      
      &:hover {
        background: rgba(255, 255, 255, 0.2);
      }
      
      &.primary {
        background: #5E6AD2;
        border-color: #5E6AD2;
      }
      
      &.negative {
        background: #EF4444;
        border-color: #EF4444;
      }
    }
  }
  
  .close-bulk {
    background: none;
    border: none;
    color: #9CA3AF;
    cursor: pointer;
    padding: 0.25rem;
    
    &:hover {
      color: #FFFFFF;
    }
  }
}

---

## 2. Dashboard Pages (`src/styles/pages/dashboard.less`)

```less
/*******************************
    Dashboard Pages Styling
*******************************/

.dashboard-page {
  /* Dashboard header */
  .dashboard-header {
    margin-bottom: 2rem;
    
    .header-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
      
      .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 0.5rem 0;
      }
      
      .dashboard-subtitle {
        color: #6B7280;
        font-size: 1rem;
      }
      
      .dashboard-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
      }
    }
    
    .dashboard-nav {
      margin-top: 1.5rem;
      
      .nav-tabs {
        display: flex;
        gap: 0;
        border-bottom: 1px solid #E5E7EB;
        
        .nav-tab {
          padding: 0.75rem 1.5rem;
          background: none;
          border: none;
          color: #6B7280;
          font-size: 0.875rem;
          font-weight: 500;
          cursor: pointer;
          border-bottom: 2px solid transparent;
          transition: all 0.15s ease;
          
          &:hover {
            color: #374151;
            background: #F9FAFB;
          }
          
          &.active {
            color: #5E6AD2;
            border-bottom-color: #5E6AD2;
          }
        }
      }
    }
  }
  
  /* Stats overview */
  .stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
    
    .stat-card {
      background: #FFFFFF;
      border: 1px solid #E5E7EB;
      border-radius: 8px;
      padding: 1.5rem;
      transition: all 0.15s ease;
      
      &:hover {
        border-color: #C7D2FE;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      }
      
      .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        
        .stat-title {
          color: #6B7280;
          font-size: 0.875rem;
          font-weight: 500;
          text-transform: uppercase;
          letter-spacing: 0.05em;
        }
        
        .stat-icon {
          width: 2rem;
          height: 2rem;
          background: #F3F4F6;
          border-radius: 6px;
          display: flex;
          align-items: center;
          justify-content: center;
          color: #6B7280;
        }
      }
      
      .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
      }
      
      .stat-change {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.875rem;
        
        &.positive {
          color: #059669;
        }
        
        &.negative {
          color: #DC2626;
        }
        
        &.neutral {
          color: #6B7280;
        }
        
        .change-icon {
          width: 1rem;
          height: 1rem;
        }
      }
      
      /* Colored stat cards */
      &.primary {
        background: linear-gradient(135deg, #5E6AD2 0%, #4C51BF 100%);
        color: #FFFFFF;
        border: none;
        
        .stat-title,
        .stat-change {
          color: rgba(255, 255, 255, 0.8);
        }
        
        .stat-value {
          color: #FFFFFF;
        }
        
        .stat-icon {
          background: rgba(255, 255, 255, 0.2);
          color: #FFFFFF;
        }
      }
    }
  }
  
  /* Chart containers */
  .chart-container {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    
    .chart-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
      
      .chart-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1F2937;
      }
      
      .chart-controls {
        display: flex;
        gap: 0.5rem;
        
        .time-selector {
          display: flex;
          border: 1px solid #E5E7EB;
          border-radius: 6px;
          overflow: hidden;
          
          .time-option {
            padding: 0.5rem 0.75rem;
            background: #FFFFFF;
            border: none;
            color: #6B7280;
            font-size: 0.875rem;
            cursor: pointer;
            
            &:hover {
              background: #F9FAFB;
            }
            
            &.active {
              background: #5E6AD2;
              color: #FFFFFF;
            }
            
            &:not(:last-child) {
              border-right: 1px solid #E5E7EB;
            }
          }
        }
      }
    }
    
    .chart-content {
      height: 300px;
      position: relative;
      
      /* Placeholder for actual charts */
      .chart-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #9CA3AF;
        font-size: 0.875rem;
        border: 2px dashed #E5E7EB;
        border-radius: 6px;
      }
    }
  }
  
  /* Activity feed */
  .activity-feed {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    
    .feed-header {
      padding: 1.5rem 1.5rem 0;
      
      .feed-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 1rem;
      }
    }
    
    .feed-list {
      max-height: 400px;
      overflow-y: auto;
      
      .feed-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #F3F4F6;
        
        &:last-child {
          border-bottom: none;
        }
        
        .item-avatar {
          width: 2.5rem;
          height: 2.5rem;
          border-radius: 50%;
          background: #F3F4F6;
          display: flex;
          align-items: center;
          justify-content: center;
          flex-shrink: 0;
          
          &.action-create {
            background: #D1FAE5;
            color: #059669;
          }
          
          &.action-update {
            background: #DBEAFE;
            color: #2563EB;
          }
          
          &.action-delete {
            background: #FEE2E2;
            color: #DC2626;
          }
        }
        
        .item-content {
          flex: 1;
          
          .item-description {
            color: #1F2937;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
          }
          
          .item-meta {
            color: #6B7280;
            font-size: 0.75rem;
            
            .item-time {
              margin-right: 1rem;
            }
          }
        }
      }
    }
  }
  
  /* Recent items */
  .recent-items {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    
    .items-header {
      padding: 1.5rem;
      border-bottom: 1px solid #F3F4F6;
      
      display: flex;
      align-items: center;
      justify-content: space-between;
      
      .items-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1F2937;
      }
      
      .view-all-link {
        color: #5E6AD2;
        font-size: 0.875rem;
        text-decoration: none;
        
        &:hover {
          text-decoration: underline;
        }
      }
    }
    
    .items-list {
      .recent-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #F3F4F6;
        transition: background 0.15s ease;
        
        &:last-child {
          border-bottom: none;
        }
        
        &:hover {
          background: #F9FAFB;
        }
        
        .item-icon {
          width: 2rem;
          height: 2rem;
          border-radius: 4px;
          background: #F3F4F6;
          display: flex;
          align-items: center;
          justify-content: center;
          color: #6B7280;
          flex-shrink: 0;
        }
        
        .item-info {
          flex: 1;
          
          .item-title {
            font-weight: 500;
            color: #1F2937;
            margin-bottom: 0.25rem;
          }
          
          .item-subtitle {
            color: #6B7280;
            font-size: 0.875rem;
          }
        }
        
        .item-time {
          color: #9CA3AF;
          font-size: 0.75rem;
          flex-shrink: 0;
        }
      }
    }
  }
}

---

## 3. Annotation Interface (`src/styles/pages/annotation.less`)

```less
/*******************************
    Annotation Interface Styling
*******************************/

.annotation-page {
  height: 100vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  
  /* Annotation toolbar */
  .annotation-toolbar {
    display: flex;
    align-items: center;
    background: #1F2937;
    color: #FFFFFF;
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid #374151;
    flex-shrink: 0;
    
    .toolbar-section {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      
      &:not(:last-child) {
        margin-right: 1.5rem;
        padding-right: 1.5rem;
        border-right: 1px solid #4B5563;
      }
      
      .section-label {
        color: #9CA3AF;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-right: 0.5rem;
      }
    }
    
    .tool-group {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      
      .tool-button {
        padding: 0.5rem;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 4px;
        color: #9CA3AF;
        cursor: pointer;
        transition: all 0.15s ease;
        
        &:hover {
          background: rgba(255, 255, 255, 0.1);
          color: #F9FAFB;
        }
        
        &.active {
          background: #5E6AD2;
          border-color: #5E6AD2;
          color: #FFFFFF;
        }
        
        &.disabled {
          opacity: 0.5;
          cursor: not-allowed;
          
          &:hover {
            background: transparent;
            color: #9CA3AF;
          }
        }
      }
    }
    
    .zoom-controls {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      
      .zoom-level {
        min-width: 4rem;
        text-align: center;
        font-size: 0.875rem;
        color: #D1D5DB;
      }
    }
    
    .playback-controls {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      
      .play-button {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: #5E6AD2;
        border: none;
        color: #FFFFFF;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        
        &:hover {
          background: #4C51BF;
        }
      }
      
      .time-display {
        font-family: monospace;
        font-size: 0.875rem;
        color: #D1D5DB;
        min-width: 8rem;
        text-align: center;
      }
    }
    
    .annotation-info {
      margin-left: auto;
      
      .info-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: 1rem;
        
        .info-label {
          color: #9CA3AF;
          font-size: 0.875rem;
        }
        
        .info-value {
          color: #F9FAFB;
          font-weight: 500;
          font-size: 0.875rem;
        }
      }
    }
  }
  
  /* Main annotation area */
  .annotation-workspace {
    flex: 1;
    display: flex;
    overflow: hidden;
    
    /* Canvas area */
    .canvas-container {
      flex: 1;
      position: relative;
      background: #000000;
      overflow: hidden;
      
      .media-canvas {
        width: 100%;
        height: 100%;
        object-fit: contain;
        cursor: crosshair;
        
        &.panning {
          cursor: grab;
        }
        
        &.pan-active {
          cursor: grabbing;
        }
      }
      
      /* Overlay for annotations */
      .annotation-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        
        .bounding-box {
          position: absolute;
          border: 2px solid #3B82F6;
          background: rgba(59, 130, 246, 0.1);
          pointer-events: all;
          cursor: move;
          
          &.selected {
            border-color: #EF4444;
            background: rgba(239, 68, 68, 0.1);
          }
          
          &.hover {
            border-color: #10B981;
            background: rgba(16, 185, 129, 0.1);
          }
          
          .resize-handle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #FFFFFF;
            border: 1px solid #3B82F6;
            
            &.nw { top: -4px; left: -4px; cursor: nw-resize; }
            &.ne { top: -4px; right: -4px; cursor: ne-resize; }
            &.sw { bottom: -4px; left: -4px; cursor: sw-resize; }
            &.se { bottom: -4px; right: -4px; cursor: se-resize; }
            &.n { top: -4px; left: 50%; margin-left: -4px; cursor: n-resize; }
            &.s { bottom: -4px; left: 50%; margin-left: -4px; cursor: s-resize; }
            &.w { top: 50%; left: -4px; margin-top: -4px; cursor: w-resize; }
            &.e { top: 50%; right: -4px; margin-top: -4px; cursor: e-resize; }
          }
          
          .box-label {
            position: absolute;
            top: -1.5rem;
            left: 0;
            background: rgba(0, 0, 0, 0.8);
            color: #FFFFFF;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.75rem;
            white-space: nowrap;
          }
        }
      }
      
      /* Canvas controls */
      .canvas-controls {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        
        .control-group {
          background: rgba(255, 255, 255, 0.9);
          border: 1px solid #E5E7EB;
          border-radius: 6px;
          padding: 0.5rem;
          backdrop-filter: blur(4px);
          
          .control-button {
            display: block;
            width: 2.5rem;
            height: 2.5rem;
            border: none;
            background: transparent;
            color: #4B5563;
            cursor: pointer;
            border-radius: 4px;
            
            &:hover {
              background: #F3F4F6;
              color: #1F2937;
            }
            
            &:not(:last-child) {
              margin-bottom: 0.25rem;
            }
          }
        }
      }
    }
    
    /* Properties panel */
    .properties-panel {
      width: 320px;
      background: #F9FAFB;
      border-left: 1px solid #E5E7EB;
      display: flex;
      flex-direction: column;
      
      .panel-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #E5E7EB;
        background: #FFFFFF;
        
        .panel-title {
          font-weight: 600;
          color: #1F2937;
          margin: 0;
        }
      }
      
      .panel-content {
        flex: 1;
        overflow-y: auto;
        
        .property-section {
          padding: 1.5rem;
          border-bottom: 1px solid #E5E7EB;
          
          .section-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
          }
          
          .property-field {
            margin-bottom: 1rem;
            
            label {
              display: block;
              font-size: 0.875rem;
              font-weight: 500;
              color: #374151;
              margin-bottom: 0.5rem;
            }
            
            .ui.input,
            .ui.dropdown {
              width: 100%;
            }
          }
          
          .color-picker {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            
            .color-option {
              width: 2rem;
              height: 2rem;
              border-radius: 4px;
              border: 2px solid transparent;
              cursor: pointer;
              
              &.selected {
                border-color: #1F2937;
              }
            }
          }
        }
      }
    }
  }
  
  /* Timeline for video annotation */
  .annotation-timeline {
    height: 120px;
    background: #F9FAFB;
    border-top: 1px solid #E5E7EB;
    display: flex;
    flex-direction: column;
    
    .timeline-header {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #E5E7EB;
      background: #FFFFFF;
      
      display: flex;
      align-items: center;
      justify-content: space-between;
      
      .timeline-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
      }
      
      .timeline-controls {
        display: flex;
        gap: 0.5rem;
        
        .timeline-zoom {
          display: flex;
          gap: 0.25rem;
          
          button {
            padding: 0.25rem 0.5rem;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            
            &:hover {
              background: #F9FAFB;
            }
          }
        }
      }
    }
    
    .timeline-track {
      flex: 1;
      position: relative;
      overflow-x: auto;
      overflow-y: hidden;
      
      .track-ruler {
        height: 20px;
        background: #FFFFFF;
        border-bottom: 1px solid #E5E7EB;
        position: relative;
        
        .time-marker {
          position: absolute;
          top: 0;
          height: 100%;
          border-left: 1px solid #9CA3AF;
          
          .time-label {
            position: absolute;
            top: 2px;
            left: 4px;
            font-size: 0.75rem;
            color: #6B7280;
          }
        }
      }
      
      .track-content {
        flex: 1;
        position: relative;
        
        .annotation-segment {
          position: absolute;
          height: 30px;
          background: #5E6AD2;
          border-radius: 4px;
          color: #FFFFFF;
          font-size: 0.75rem;
          padding: 0.25rem 0.5rem;
          cursor: pointer;
          
          &.selected {
            background: #EF4444;
          }
          
          &:hover {
            opacity: 0.8;
          }
          
          .segment-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
          }
        }
        
        .playhead {
          position: absolute;
          top: 0;
          width: 2px;
          height: 100%;
          background: #EF4444;
          z-index: 10;
          pointer-events: none;
          
          &::before {
            content: '';
            position: absolute;
            top: -6px;
            left: -6px;
            width: 14px;
            height: 14px;
            background: #EF4444;
            border-radius: 50%;
          }
        }
      }
    }
  }
}

/* Annotation modes */
.annotation-page {
  &.mode-select .media-canvas {
    cursor: default;
  }
  
  &.mode-draw .media-canvas {
    cursor: crosshair;
  }
  
  &.mode-pan .media-canvas {
    cursor: grab;
  }
  
  &.mode-zoom .media-canvas {
    cursor: zoom-in;
  }
}

---

## 4. Utility Classes (`src/styles/utilities/spacing.less`)

```less
/*******************************
    Spacing Utilities
*******************************/

/* Margin utilities */
.m-0 { margin: 0 !important; }
.m-1 { margin: 0.25rem !important; }
.m-2 { margin: 0.5rem !important; }
.m-3 { margin: 0.75rem !important; }
.m-4 { margin: 1rem !important; }
.m-5 { margin: 1.25rem !important; }
.m-6 { margin: 1.5rem !important; }
.m-8 { margin: 2rem !important; }
.m-10 { margin: 2.5rem !important; }
.m-12 { margin: 3rem !important; }

/* Margin directional */
.mt-0 { margin-top: 0 !important; }
.mt-1 { margin-top: 0.25rem !important; }
.mt-2 { margin-top: 0.5rem !important; }
.mt-3 { margin-top: 0.75rem !important; }
.mt-4 { margin-top: 1rem !important; }
.mt-6 { margin-top: 1.5rem !important; }
.mt-8 { margin-top: 2rem !important; }

.mb-0 { margin-bottom: 0 !important; }
.mb-1 { margin-bottom: 0.25rem !important; }
.mb-2 { margin-bottom: 0.5rem !important; }
.mb-3 { margin-bottom: 0.75rem !important; }
.mb-4 { margin-bottom: 1rem !important; }
.mb-6 { margin-bottom: 1.5rem !important; }
.mb-8 { margin-bottom: 2rem !important; }

.ml-0 { margin-left: 0 !important; }
.ml-1 { margin-left: 0.25rem !important; }
.ml-2 { margin-left: 0.5rem !important; }
.ml-3 { margin-left: 0.75rem !important; }
.ml-4 { margin-left: 1rem !important; }
.ml-auto { margin-left: auto !important; }

.mr-0 { margin-right: 0 !important; }
.mr-1 { margin-right: 0.25rem !important; }
.mr-2 { margin-right: 0.5rem !important; }
.mr-3 { margin-right: 0.75rem !important; }
.mr-4 { margin-right: 1rem !important; }
.mr-auto { margin-right: auto !important; }

/* Padding utilities */
.p-0 { padding: 0 !important; }
.p-1 { padding: 0.25rem !important; }
.p-2 { padding: 0.5rem !important; }
.p-3 { padding: 0.75rem !important; }
.p-4 { padding: 1rem !important; }
.p-5 { padding: 1.25rem !important; }
.p-6 { padding: 1.5rem !important; }
.p-8 { padding: 2rem !important; }

/* Padding directional */
.pt-0 { padding-top: 0 !important; }
.pt-1 { padding-top: 0.25rem !important; }
.pt-2 { padding-top: 0.5rem !important; }
.pt-3 { padding-top: 0.75rem !important; }
.pt-4 { padding-top: 1rem !important; }
.pt-6 { padding-top: 1.5rem !important; }

.pb-0 { padding-bottom: 0 !important; }
.pb-1 { padding-bottom: 0.25rem !important; }
.pb-2 { padding-bottom: 0.5rem !important; }
.pb-3 { padding-bottom: 0.75rem !important; }
.pb-4 { padding-bottom: 1rem !important; }
.pb-6 { padding-bottom: 1.5rem !important; }

.px-0 { padding-left: 0 !important; padding-right: 0 !important; }
.px-1 { padding-left: 0.25rem !important; padding-right: 0.25rem !important; }
.px-2 { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
.px-3 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
.px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
.px-6 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }

.py-0 { padding-top: 0 !important; padding-bottom: 0 !important; }
.py-1 { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
.py-2 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
.py-3 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
.py-4 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
.py-6 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }

---

## 5. Helper Classes (`src/styles/utilities/helpers.less`)

```less
/*******************************
    Helper Utilities
*******************************/

/* Display utilities */
.d-none { display: none !important; }
.d-block { display: block !important; }
.d-inline { display: inline !important; }
.d-inline-block { display: inline-block !important; }
.d-flex { display: flex !important; }
.d-grid { display: grid !important; }

/* Flexbox utilities */
.flex-row { flex-direction: row !important; }
.flex-col { flex-direction: column !important; }
.flex-wrap { flex-wrap: wrap !important; }
.flex-nowrap { flex-wrap: nowrap !important; }

.justify-start { justify-content: flex-start !important; }
.justify-center { justify-content: center !important; }
.justify-end { justify-content: flex-end !important; }
.justify-between { justify-content: space-between !important; }
.justify-around { justify-content: space-around !important; }

.items-start { align-items: flex-start !important; }
.items-center { align-items: center !important; }
.items-end { align-items: flex-end !important; }
.items-stretch { align-items: stretch !important; }

.flex-1 { flex: 1 !important; }
.flex-auto { flex: auto !important; }
.flex-none { flex: none !important; }

/* Gap utilities */
.gap-0 { gap: 0 !important; }
.gap-1 { gap: 0.25rem !important; }
.gap-2 { gap: 0.5rem !important; }
.gap-3 { gap: 0.75rem !important; }
.gap-4 { gap: 1rem !important; }
.gap-6 { gap: 1.5rem !important; }

/* Text utilities */
.text-left { text-align: left !important; }
.text-center { text-align: center !important; }
.text-right { text-align: right !important; }
.text-justify { text-align: justify !important; }

.text-xs { font-size: 0.75rem !important; }
.text-sm { font-size: 0.875rem !important; }
.text-base { font-size: 1rem !important; }
.text-lg { font-size: 1.125rem !important; }
.text-xl { font-size: 1.25rem !important; }
.text-2xl { font-size: 1.5rem !important; }

.font-light { font-weight: 300 !important; }
.font-normal { font-weight: 400 !important; }
.font-medium { font-weight: 500 !important; }
.font-semibold { font-weight: 600 !important; }
.font-bold { font-weight: 700 !important; }

.text-gray-500 { color: #6B7280 !important; }
.text-gray-600 { color: #4B5563 !important; }
.text-gray-700 { color: #374151 !important; }
.text-gray-900 { color: #1F2937 !important; }
.text-primary { color: #5E6AD2 !important; }
.text-success { color: #059669 !important; }
.text-warning { color: #D97706 !important; }
.text-danger { color: #DC2626 !important; }

/* Background utilities */
.bg-white { background-color: #FFFFFF !important; }
.bg-gray-50 { background-color: #F9FAFB !important; }
.bg-gray-100 { background-color: #F3F4F6 !important; }
.bg-gray-200 { background-color: #E5E7EB !important; }
.bg-primary { background-color: #5E6AD2 !important; }
.bg-success { background-color: #059669 !important; }
.bg-warning { background-color: #D97706 !important; }
.bg-danger { background-color: #DC2626 !important; }

/* Border utilities */
.border { border: 1px solid #E5E7EB !important; }
.border-t { border-top: 1px solid #E5E7EB !important; }
.border-b { border-bottom: 1px solid #E5E7EB !important; }
.border-l { border-left: 1px solid #E5E7EB !important; }
.border-r { border-right: 1px solid #E5E7EB !important; }
.border-none { border: none !important; }

.border-gray-200 { border-color: #E5E7EB !important; }
.border-gray-300 { border-color: #D1D5DB !important; }
.border-primary { border-color: #5E6AD2 !important; }

.rounded { border-radius: 0.25rem !important; }
.rounded-md { border-radius: 0.375rem !important; }
.rounded-lg { border-radius: 0.5rem !important; }
.rounded-xl { border-radius: 0.75rem !important; }
.rounded-full { border-radius: 9999px !important; }
.rounded-none { border-radius: 0 !important; }

/* Shadow utilities */
.shadow-none { box-shadow: none !important; }
.shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important; }
.shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important; }
.shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important; }
.shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; }

/* Position utilities */
.relative { position: relative !important; }
.absolute { position: absolute !important; }
.fixed { position: fixed !important; }
.sticky { position: sticky !important; }

.top-0 { top: 0 !important; }
.right-0 { right: 0 !important; }
.bottom-0 { bottom: 0 !important; }
.left-0 { left: 0 !important; }

/* Width and height utilities */
.w-auto { width: auto !important; }
.w-full { width: 100% !important; }
.w-1\/2 { width: 50% !important; }
.w-1\/3 { width: 33.333333% !important; }
.w-2\/3 { width: 66.666667% !important; }
.w-1\/4 { width: 25% !important; }
.w-3\/4 { width: 75% !important; }

.h-auto { height: auto !important; }
.h-full { height: 100% !important; }
.h-screen { height: 100vh !important; }

.min-h-0 { min-height: 0 !important; }
.min-h-full { min-height: 100% !important; }
.min-h-screen { min-height: 100vh !important; }

.max-w-xs { max-width: 20rem !important; }
.max-w-sm { max-width: 24rem !important; }
.max-w-md { max-width: 28rem !important; }
.max-w-lg { max-width: 32rem !important; }
.max-w-xl { max-width: 36rem !important; }
.max-w-2xl { max-width: 42rem !important; }
.max-w-full { max-width: 100% !important; }
.max-w-none { max-width: none !important; }

/* Overflow utilities */
.overflow-auto { overflow: auto !important; }
.overflow-hidden { overflow: hidden !important; }
.overflow-visible { overflow: visible !important; }
.overflow-scroll { overflow: scroll !important; }

.overflow-x-auto { overflow-x: auto !important; }
.overflow-x-hidden { overflow-x: hidden !important; }
.overflow-y-auto { overflow-y: auto !important; }
.overflow-y-hidden { overflow-y: hidden !important; }

/* Z-index utilities */
.z-0 { z-index: 0 !important; }
.z-10 { z-index: 10 !important; }
.z-20 { z-index: 20 !important; }
.z-30 { z-index: 30 !important; }
.z-40 { z-index: 40 !important; }
.z-50 { z-index: 50 !important; }

/* Opacity utilities */
.opacity-0 { opacity: 0 !important; }
.opacity-25 { opacity: 0.25 !important; }
.opacity-50 { opacity: 0.5 !important; }
.opacity-75 { opacity: 0.75 !important; }
.opacity-100 { opacity: 1 !important; }

/* Cursor utilities */
.cursor-auto { cursor: auto !important; }
.cursor-default { cursor: default !important; }
.cursor-pointer { cursor: pointer !important; }
.cursor-wait { cursor: wait !important; }
.cursor-text { cursor: text !important; }
.cursor-move { cursor: move !important; }
.cursor-not-allowed { cursor: not-allowed !important; }

/* User select utilities */
.select-none { user-select: none !important; }
.select-text { user-select: text !important; }
.select-all { user-select: all !important; }
.select-auto { user-select: auto !important; }

/* Pointer events */
.pointer-events-none { pointer-events: none !important; }
.pointer-events-auto { pointer-events: auto !important; }

/* Transition utilities */
.transition-none { transition: none !important; }
.transition-all { transition: all 0.15s ease !important; }
.transition-colors { transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease !important; }
.transition-opacity { transition: opacity 0.15s ease !important; }
.transition-transform { transition: transform 0.15s ease !important; }

/* Transform utilities */
.transform { transform: translateX(var(--tw-translate-x, 0)) translateY(var(--tw-translate-y, 0)) rotate(var(--tw-rotate, 0)) skewX(var(--tw-skew-x, 0)) skewY(var(--tw-skew-y, 0)) scaleX(var(--tw-scale-x, 1)) scaleY(var(--tw-scale-y, 1)) !important; }
.scale-95 { --tw-scale-x: 0.95; --tw-scale-y: 0.95; }
.scale-100 { --tw-scale-x: 1; --tw-scale-y: 1; }
.scale-105 { --tw-scale-x: 1.05; --tw-scale-y: 1.05; }

/* Visibility utilities */
.visible { visibility: visible !important; }
.invisible { visibility: hidden !important; }

/* Screen reader utilities */
.sr-only {
  position: absolute !important;
  width: 1px !important;
  height: 1px !important;
  padding: 0 !important;
  margin: -1px !important;
  overflow: hidden !important;
  clip: rect(0, 0, 0, 0) !important;
  white-space: nowrap !important;
  border: 0 !important;
}

/* Responsive helpers */
@media (max-width: 767px) {
  .mobile-hidden { display: none !important; }
  .mobile-only { display: block !important; }
}

@media (min-width: 768px) {
  .mobile-only { display: none !important; }
  .desktop-only { display: block !important; }
}

@media (max-width: 1023px) {
  .desktop-only { display: none !important; }
}
```

---

## How to Use These Files

### 1. Import Order in your main app.less:
```less
/* Import Fomantic UI first */
@import '../fomantic-ui/semantic.less';

/* Then your application styles */
@import 'layout/grid.less';
@import 'layout/components.less';
@import 'layout/responsive.less';
@import 'pages/crud.less';
@import 'pages/dashboard.less';
@import 'pages/annotation.less';
@import 'utilities/spacing.less';
@import 'utilities/helpers.less';
```

### 2. HTML Structure Examples:

#### CRUD Page Layout:
```html
<div class="app-layout">
  <header class="app-header">
    <div class="header-left">
      <h1 class="logo">MyApp</h1>
      <nav class="breadcrumb-nav">
        <span>Users</span> / <span>List</span>
      </nav>
    </div>
    <div class="header-center">
      <div class="ui input search-bar">
        <input type="text" placeholder="Search...">
      </div>
    </div>
    <div class="header-right">
      <button class="ui button">Settings</button>
    </div>
  </header>
  
  <nav class="app-sidebar">
    <div class="nav-section">
      <div class="section-title">Main</div>
      <a href="/dashboard" class="nav-item">
        <i class="nav-icon dashboard icon"></i>
        Dashboard
      </a>
      <a href="/users" class="nav-item active">
        <i class="nav-icon users icon"></i>
        Users
        <span class="nav-badge">142</span>
      </a>
    </div>
  </nav>
  
  <main class="app-main">
    <div class="content-container wide">
      <div class="crud-page">
        <div class="page-header">
          <div>
            <h1 class="page-title">Users</h1>
            <p class="page-subtitle">Manage user accounts and permissions</p>
          </div>
          <div class="page-actions">
            <button class="ui primary button">New User</button>
          </div>
        </div>
        
        <div class="filters-bar">
          <!-- Filter controls -->
        </div>
        
        <div class="table-container">
          <table class="ui celled striped selectable table">
            <!-- Table content -->
          </table>
        </div>
      </div>
    </div>
  </main>
  
  <aside class="app-tools">
    <div class="tools-section">
      <h3 class="section-title">Quick Actions</h3>
      <!-- Tool content -->
    </div>
  </aside>
</div>
```

#### Dashboard Layout:
```html
<div class="app-layout no-tools">
  <main class="app-main">
    <div class="content-container">
      <div class="dashboard-page">
        <div class="stats-overview">
          <div class="stat-card primary">
            <div class="stat-header">
              <span class="stat-title">Total Users</span>
              <i class="stat-icon users icon"></i>
            </div>
            <div class="stat-value">1,247</div>
            <div class="stat-change positive">
              <i class="change-icon arrow up icon"></i>
              +12% from last month
            </div>
          </div>
          <!-- More stat cards -->
        </div>
        
        <div class="dashboard-grid">
          <div class="chart-container">
            <!-- Chart content -->
          </div>
          <div class="activity-feed">
            <!-- Activity content -->
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
```

### 3. Build Process Integration:

Add to your build tool (Vite, Webpack, etc.):

```javascript
// vite.config.js
export default {
  css: {
    preprocessorOptions: {
      less: {
        paths: ['src/styles', 'src/fomantic-ui'],
        javascriptEnabled: true
      }
    }
  }
}
```

This complete structure gives you:
- ✅ **Responsive design** built-in
- ✅ **Component-based styling** for maintainability  
- ✅ **Page-specific optimizations** for CRUD, dashboard, and annotation
- ✅ **Utility classes** for rapid development
- ✅ **Consistent design system** integrated with Fomantic UI
- ✅ **Professional, modern aesthetics** avoiding desktop-app feel

You can now start implementing your CRUD application with this solid foundation!
