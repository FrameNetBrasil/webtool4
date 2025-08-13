# 🚀 Bulma Migration Guide - FNBr Webtool 4.0

## Overview

This guide provides comprehensive instructions for migrating from Fomantic-UI to Bulma CSS framework in the FNBr Webtool 4.0 project. The migration maintains backward compatibility while introducing modern, accessible, and performant alternatives.

## 📋 Migration Status

### ✅ Completed Components
- **Layout System**: Header, sidebar, main layout templates
- **DataGrid Component**: Full Alpine.js powered data tables
- **Interactive Components**: Dropdown, modal, accordion, tabs, notifications
- **Accessibility**: WCAG 2.1 AA compliance with ARIA labels and keyboard navigation
- **Performance**: Code splitting, lazy loading, optimized builds

### 🔄 Parallel System
The project now runs both Fomantic-UI and Bulma CSS frameworks in parallel, allowing gradual migration:
- **Fomantic-UI**: `app-MRUqxSqW.css` (701.86 kB)
- **Bulma**: `app-CiMA3RkI.css` (1,634.50 kB)

## 🏗️ Architecture

### Component Structure
```
app/UI/
├── components/
│   ├── datagrid.blade.php              # Original Fomantic-UI
│   └── datagrid-bulma.blade.php        # New Bulma version
├── layouts/
│   ├── header.blade.php                # Original Fomantic-UI
│   ├── header-bulma.blade.php          # New Bulma version
│   ├── sidebar.blade.php               # Original Fomantic-UI
│   ├── sidebar-bulma.blade.php         # New Bulma version
│   ├── index.blade.php                 # Original layout
│   └── index-bulma.blade.php           # New Bulma layout
└── views/
    └── Frame/Report/
        ├── report.blade.php            # Original page
        └── report-bulma.blade.php      # Migrated page
```

### JavaScript Architecture
```
resources/js/
├── components/
│   ├── dataGridComponent.js            # Original component
│   ├── dataGridBulmaComponent.js       # Bulma version
│   └── bulmaComponents.js              # All Bulma components
└── app.js                              # Main entry point
```

### SASS Architecture
```
resources/sass/
├── abstracts/
│   └── _variables.scss                 # Bulma customizations
├── layouts/
│   └── _app-layout.scss                # Layout styles
├── utilities/
│   ├── _responsive.scss                # Mobile-first responsive
│   └── _accessibility.scss             # WCAG 2.1 compliance
└── app.scss                            # Main entry point
```

## 🔧 Usage Instructions

### 1. Using Bulma Layout Templates

Replace existing layout references with Bulma versions:

```blade
<!-- OLD: Fomantic-UI Layout -->
<x-layout::index>
    <div class="app-layout">
        @include('layouts.header')
        @include('layouts.sidebar')
        <!-- content -->
    </div>
</x-layout::index>

<!-- NEW: Bulma Layout -->
<x-layout::index-bulma>
    <div class="app-layout">
        @include('layouts.header-bulma')
        @include('layouts.sidebar-bulma')
        <!-- content -->
    </div>
</x-layout::index-bulma>
```

### 2. Using Bulma DataGrid Component

```blade
<!-- OLD: Fomantic-UI DataGrid -->
<x-ui::datagrid 
    :data="$data" 
    :columns="$columns" 
    :config="$config"
/>

<!-- NEW: Bulma DataGrid -->
<x-ui::datagrid-bulma 
    :data="$data" 
    :columns="$columns" 
    :config="[
        'rownumbers' => true,
        'striped' => true,
        'hoverable' => true,
        'size' => 'is-fullwidth'
    ]"
/>
```

### 3. Interactive Components

All Alpine.js components are automatically available:

```blade
<!-- Dropdown -->
<div x-data="dropdown">
    <button @click="toggle">Toggle</button>
    <div x-show="isOpen" x-transition>Content</div>
</div>

<!-- Modal -->
<div x-data="modal" @open-modal.window="if ($event.detail.id === 'my-modal') open()">
    <!-- Modal content -->
</div>

<!-- Accordion -->
<div x-data="accordion">
    <button @click="toggle">Expand/Collapse</button>
    <div x-show="isOpen" x-transition>Content</div>
</div>
```

## 📚 Component Reference

### DataGrid Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `rownumbers` | boolean | false | Show row numbers |
| `striped` | boolean | true | Alternate row colors |
| `hoverable` | boolean | true | Enable hover effects |
| `border` | boolean | true | Show table borders |
| `size` | string | 'is-fullwidth' | Table size class |
| `singleSelect` | boolean | true | Single row selection |
| `emptyMsg` | string | 'No records' | Empty state message |

### Column Definition

```javascript
const columns = [
    {
        field: 'name',           // Data field name
        title: 'Display Name',   // Column header
        width: '200px',          // Column width
        align: 'center',         // left, center, right
        formatter: function(value, row, column) {
            return `<strong>${value}</strong>`;
        }
    }
];
```

## 🎨 CSS Class Migration

### Common Class Conversions

| Fomantic-UI | Bulma | Notes |
|-------------|-------|-------|
| `ui button` | `button` | Basic button |
| `ui primary button` | `button is-primary` | Primary button |
| `ui table` | `table` | Basic table |
| `ui striped table` | `table is-striped` | Striped table |
| `ui dropdown` | `dropdown` | Dropdown component |
| `ui modal` | `modal` | Modal component |
| `ui header` | `title` | Page titles |
| `ui message` | `notification` | Messages/notifications |

### Layout Classes

| Fomantic-UI | Bulma | Notes |
|-------------|-------|-------|
| `ui container` | `container` | Content container |
| `ui grid` | `columns` | Grid system |
| `column` | `column` | Grid columns |
| `centered` | `has-text-centered` | Text alignment |
| `right aligned` | `has-text-right` | Text alignment |

## 🚀 Performance Optimizations

### Code Splitting
The optimized build creates separate chunks:
- **Main app**: 96.66 kB (72% reduction)
- **Vendor libraries**: 44.06 kB
- **Chart.js**: 205.17 kB (lazy loaded)

### Lazy Loading
Large libraries like Chart.js are loaded only when needed:

```javascript
// Automatic lazy loading
window.Chart = async () => {
    const { default: ChartJS } = await Chart();
    return ChartJS;
};
```

### Build Commands
```bash
# Development build
npm run dev

# Production build (optimized)
npm run build:prod

# Bundle analysis
npm run build:analyze
```

## ♿ Accessibility Features

### WCAG 2.1 AA Compliance
- **Keyboard Navigation**: Full support for keyboard users
- **Screen Readers**: Proper ARIA labels and semantic HTML
- **High Contrast**: Support for high contrast mode
- **Focus Management**: Enhanced focus indicators
- **Skip Links**: Jump to main content

### Example Implementation
```blade
<main class="app-main" role="main" aria-label="Frame report content">
    <!-- Skip to main content link -->
    <a href="#main-content" class="skip-link sr-only-focusable">Skip to main content</a>
    
    <div id="main-content">
        <section aria-labelledby="stats-heading">
            <h2 id="stats-heading" class="sr-only">Frame Statistics</h2>
            <!-- Content -->
        </section>
    </div>
</main>
```

## 📱 Responsive Design

### Mobile-First Approach
All components are built mobile-first with progressive enhancement:

```scss
// Mobile first
.component {
    padding: 1rem;
}

// Tablet and up
@media (min-width: 768px) {
    .component {
        padding: 2rem;
    }
}

// Desktop and up  
@media (min-width: 1024px) {
    .component {
        padding: 3rem;
    }
}
```

### Responsive Utilities
- `is-mobile`: Mobile-specific styles
- `is-tablet`: Tablet-specific styles
- `is-desktop`: Desktop-specific styles
- `is-fullwidth-mobile`: Full width on mobile

## 🔄 Migration Strategy

### Phase 1: Foundation ✅
- Bulma installation and configuration
- SASS setup and variable system
- Alpine.js component library

### Phase 2: Layout Migration ✅
- Header, sidebar, and main layout templates
- Application structure conversion

### Phase 3: Component Integration ✅
- DataGrid component migration
- Interactive components testing
- Example page conversion

### Phase 4: Production Optimization ✅
- Build system optimization
- Performance improvements
- Bundle analysis and code splitting

### Phase 5: Gradual Migration (Next)
- Page-by-page migration strategy
- A/B testing framework
- User feedback collection

## 🧪 Testing

### Manual Testing Checklist
- [ ] Layout renders correctly on all screen sizes
- [ ] Interactive components function properly
- [ ] Keyboard navigation works
- [ ] Screen reader compatibility
- [ ] Performance metrics within targets

### Automated Testing (Future)
- Component unit tests
- Accessibility testing
- Visual regression testing
- Performance monitoring

## 🐛 Troubleshooting

### Common Issues

**1. Styles Not Loading**
```bash
# Clear build cache
npm run build:prod
```

**2. Alpine.js Components Not Working**
Ensure components are registered:
```javascript
document.addEventListener('alpine:init', () => {
    Alpine.data('componentName', componentFunction);
});
```

**3. SASS Compilation Errors**
Check import order and syntax:
```scss
// Variables must be imported first
@import "abstracts/variables";
@import "bulma/bulma";
```

## 📞 Support

For migration assistance or questions:
1. Check the component examples in `/app/UI/views/Frame/Report/`
2. Review the SASS architecture in `/resources/sass/`
3. Test interactive components in the test pages

## 🔮 Future Enhancements

### Planned Features
- [ ] Theme customization system
- [ ] Advanced component library
- [ ] Automated migration tools
- [ ] Performance monitoring dashboard
- [ ] Component documentation site

### Long-term Goals
- Complete Fomantic-UI deprecation
- Modern design system implementation
- Enhanced accessibility features
- Progressive web app capabilities