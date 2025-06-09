# Comprehensive Spacing System Documentation

A complete guide to maintaining consistent spacing throughout your FrameNet application using Fomantic-UI variables and your custom design system.

---

## Table of Contents

1. [Spacing Philosophy](#spacing-philosophy)
2. [Fomantic-UI Base Variables](#fomantic-ui-base-variables)
3. [Design System Spacing Scale](#design-system-spacing-scale)
4. [Component Spacing Patterns](#component-spacing-patterns)
5. [Layout Spacing Rules](#layout-spacing-rules)
6. [Utility Classes](#utility-classes)
7. [Responsive Spacing](#responsive-spacing)
8. [Best Practices](#best-practices)
9. [Common Spacing Scenarios](#common-spacing-scenarios)

---

## Spacing Philosophy

### Core Principles
1. **Consistent Scale** - Use a mathematical progression for spacing values
2. **Semantic Meaning** - Each spacing level has a specific purpose
3. **Visual Rhythm** - Create harmonious relationships between elements
4. **Responsive Adaptation** - Spacing scales appropriately across devices
5. **Component Coherence** - Related elements share spacing patterns

### The 8px Grid System
Your design system is built on an **8px base unit** that ensures:
- Perfect pixel alignment across all devices
- Consistent visual rhythm
- Easy mental calculation
- Compatibility with common screen densities

---

## Fomantic-UI Base Variables

### Essential Spacing Variables from `site.variables`

```less
// Base Unit (Foundation of all spacing)
@emSize: 14px;                    // Base font size
@fontSize: 14px;                  // Default font size

// Fundamental Spacing Units
@mini: 0.25rem;                   // 4px  - Micro spacing
@tiny: 0.5rem;                    // 8px  - Tiny spacing
@small: 0.75rem;                  // 12px - Small spacing
@medium: 1rem;                    // 16px - Medium spacing (base)
@large: 1.25rem;                  // 20px - Large spacing
@big: 1.5rem;                     // 24px - Big spacing
@huge: 2rem;                      // 32px - Huge spacing
@massive: 2.5rem;                 // 40px - Massive spacing

// Relative Spacing (Essential for Responsive Design)
@relativeMini: 0.25em;            // Relative to font size
@relativeTiny: 0.5em;
@relativeSmall: 0.75em;
@relativeMedium: 1em;             // Equals current font size
@relativeLarge: 1.25em;
@relativeBig: 1.5em;
@relativeHuge: 2em;
@relativeMassive: 2.5em;

// Page and Container Spacing
@pageHorizontalPadding: 1rem;     // Default page padding
@pageVerticalPadding: 1rem;       // Default page padding
@columnPadding: 1rem;             // Grid column padding

// Component-Specific Spacing
@buttonHorizontalPadding: 1.5em;  // Button internal padding
@buttonVerticalPadding: 0.78571429em;
@inputHorizontalPadding: 1em;     // Input field padding
@inputVerticalPadding: 0.67857143em;

// Border and Shadow Spacing
@borderWidth: 1px;                // Standard border width
@shadowDistance: 0.25rem;         // Default shadow offset
```

### Typography Spacing Variables

```less
// Line Heights (Critical for Vertical Rhythm)
@lineHeight: 1.4285em;            // Base line height
@headerLineHeight: 1.28571429em;  // Header line height
@paragraphSpacing: 1em;           // Paragraph margins

// Header Spacing
@headerMargin: calc(2rem - 0.14285714em) 0 1rem; // H1-H6 default margins
@subHeaderMargin: 0 0 0.5rem 0;   // Subheader margins

// List Spacing
@listItemVerticalPadding: 0.3em;  // List item padding
@listItemHorizontalPadding: 0;
@listChildIndentation: 1.5em;     // Nested list indentation
```

### Layout Spacing Variables

```less
// Container Spacing
@containerOffset: 2em;            // Container side margins
@containerMargin: 0 auto;         // Container centering
@gridGutterWidth: 1rem;           // Grid gutter spacing

// Card Spacing
@cardPadding: 1em;                // Card internal padding
@cardMargin: 1rem 0;              // Card external margins
@cardGroupMargin: 0.875em -0.125em; // Card group margins

// Menu Spacing
@menuItemVerticalPadding: 0.92857143em; // Menu item padding
@menuItemHorizontalPadding: 1.14285714em;

// Modal and Popup Spacing
@modalPadding: 1.5rem;            // Modal content padding
@modalMargin: 0 0 1rem;           // Modal section margins
```

---

## Design System Spacing Scale

### Primary Spacing Scale (Based on 8px Grid)

```less
// Design System Spacing Variables
:root {
  // Base spacing scale (8px increments)
  --spacing-0: 0;                 // 0px   - No spacing
  --spacing-1: 0.25rem;           // 4px   - Micro (borders, fine details)
  --spacing-2: 0.5rem;            // 8px   - Tiny (tight spacing)
  --spacing-3: 0.75rem;           // 12px  - Small (compact layouts)
  --spacing-4: 1rem;              // 16px  - Base (standard spacing)
  --spacing-5: 1.25rem;           // 20px  - Medium-large
  --spacing-6: 1.5rem;            // 24px  - Large (section spacing)
  --spacing-8: 2rem;              // 32px  - XL (major sections)
  --spacing-10: 2.5rem;           // 40px  - XXL (page sections)
  --spacing-12: 3rem;             // 48px  - Huge (major layout)
  --spacing-16: 4rem;             // 64px  - Massive (hero sections)
  --spacing-20: 5rem;             // 80px  - Ultra (rare usage)
  --spacing-24: 6rem;             // 96px  - Maximum (special cases)
  
  // Semantic spacing aliases
  --spacing-xs: var(--spacing-1); // Extra small
  --spacing-sm: var(--spacing-2); // Small
  --spacing-md: var(--spacing-4); // Medium (default)
  --spacing-lg: var(--spacing-6); // Large
  --spacing-xl: var(--spacing-8); // Extra large
  --spacing-xxl: var(--spacing-12); // Extra extra large
}
```

### Semantic Spacing Categories

```less
// Layout Spacing (Between major sections)
:root {
  --layout-gap: var(--spacing-6);      // 24px - Grid gaps
  --layout-margin: var(--spacing-8);   // 32px - Section margins
  --layout-padding: var(--spacing-8);  // 32px - Container padding
}

// Component Spacing (Within components)
:root {
  --component-gap: var(--spacing-4);       // 16px - Component internal gaps
  --component-margin: var(--spacing-4);    // 16px - Component margins
  --component-padding: var(--spacing-4);   // 16px - Component padding
}

// Content Spacing (Text and media)
:root {
  --content-gap: var(--spacing-3);      // 12px - Content gaps
  --content-margin: var(--spacing-3);   // 12px - Content margins
  --text-margin: var(--spacing-2);      // 8px  - Text element margins
}

// Interactive Spacing (Buttons, inputs, clickable areas)
:root {
  --interactive-padding-x: var(--spacing-4); // 16px - Horizontal padding
  --interactive-padding-y: var(--spacing-2); // 8px  - Vertical padding
  --interactive-gap: var(--spacing-2);       // 8px  - Between interactive elements
}
```

---

## Component Spacing Patterns

### Card Component Spacing

```less
// Card spacing following design system
.ui.card {
  margin: var(--spacing-4) 0;           // 16px vertical margin
  
  .content {
    padding: var(--spacing-4);          // 16px internal padding
    
    .header {
      margin-bottom: var(--spacing-2);  // 8px below header
    }
    
    .description {
      margin: var(--spacing-3) 0;       // 12px vertical margins
      line-height: 1.5;                 // Maintain vertical rhythm
    }
    
    .meta {
      margin-top: var(--spacing-2);     // 8px above meta
      margin-bottom: var(--spacing-1);  // 4px below meta
    }
  }
  
  .extra.content {
    padding: var(--spacing-3) var(--spacing-4); // 12px/16px padding
    border-top: 1px solid var(--border-color);
  }
}

// Card grid spacing
.card-grid {
  gap: var(--spacing-4);               // 16px between cards
  margin: var(--spacing-6) 0;          // 24px above/below grid
  
  &.dense {
    gap: var(--spacing-3);             // 12px for dense layout
  }
  
  &.wide {
    gap: var(--spacing-6);             // 24px for spacious layout
  }
}
```

### Header Component Spacing

```less
.app-header {
  padding: 0 var(--spacing-6);         // 24px horizontal padding
  height: 60px;                        // Fixed height for consistency
  
  .header-left,
  .header-center,
  .header-right {
    gap: var(--spacing-4);             // 16px between header sections
  }
  
  .logo {
    margin-right: var(--spacing-6);    // 24px after logo
  }
  
  .breadcrumb-nav {
    gap: var(--spacing-2);             // 8px between breadcrumb items
  }
  
  .search-bar {
    min-width: 300px;
    margin: 0 var(--spacing-4);        // 16px horizontal margins
  }
}
```

### Sidebar Component Spacing

```less
.app-sidebar {
  padding: var(--spacing-4) 0;         // 16px top/bottom padding
  
  .nav-section {
    margin-bottom: var(--spacing-6);   // 24px between sections
    
    .section-title {
      padding: 0 var(--spacing-4);     // 16px horizontal padding
      margin-bottom: var(--spacing-3); // 12px below title
    }
  }
  
  .nav-item {
    padding: var(--spacing-2) var(--spacing-4); // 8px/16px padding
    margin-bottom: var(--spacing-1);   // 4px between items
    
    .nav-icon {
      margin-right: var(--spacing-3);  // 12px after icon
    }
    
    .nav-badge {
      margin-left: auto;               // Push badge to right
    }
  }
  
  .sidebar-footer {
    margin-top: auto;
    padding: var(--spacing-4);         // 16px all around
    border-top: 1px solid var(--border-color);
  }
}
```

### Form Component Spacing

```less
.form-grid {
  gap: var(--spacing-4);               // 16px between form sections
  
  .field {
    margin-bottom: var(--spacing-4);   // 16px between fields
    
    label {
      margin-bottom: var(--spacing-2); // 8px below label
      display: block;
    }
    
    input,
    textarea,
    select {
      padding: var(--spacing-2) var(--spacing-3); // 8px/12px padding
      margin-bottom: var(--spacing-1); // 4px below input
    }
    
    .help-text {
      margin-top: var(--spacing-1);    // 4px above help text
      font-size: 0.875rem;
    }
  }
  
  .form-actions {
    margin-top: var(--spacing-8);      // 32px above actions
    padding-top: var(--spacing-4);     // 16px top padding
    border-top: 1px solid var(--border-color);
    
    .ui.button {
      margin-right: var(--spacing-3);  // 12px between buttons
      
      &:last-child {
        margin-right: 0;
      }
    }
  }
}
```

### Table Component Spacing

```less
.table-container {
  margin: var(--spacing-6) 0;          // 24px vertical margins
  
  .ui.table {
    th {
      padding: var(--spacing-3) var(--spacing-4); // 12px/16px padding
    }
    
    td {
      padding: var(--spacing-2) var(--spacing-4); // 8px/16px padding
      
      &:first-child {
        padding-left: var(--spacing-4); // 16px left padding
      }
      
      &:last-child {
        padding-right: var(--spacing-4); // 16px right padding
      }
    }
  }
  
  .pagination {
    margin-top: var(--spacing-4);      // 16px above pagination
    padding: var(--spacing-3) 0;       // 12px vertical padding
  }
}
```

---

## Layout Spacing Rules

### Container Spacing

```less
// Content containers follow consistent patterns
.content-container {
  padding: var(--spacing-8);           // 32px default padding
  margin: 0 auto;
  
  &.narrow {
    padding: var(--spacing-6);         // 24px for narrow containers
  }
  
  &.wide {
    padding: var(--spacing-4) var(--spacing-8); // 16px/32px padding
  }
  
  &.compact {
    padding: var(--spacing-4);         // 16px for compact layout
  }
}

// Page-level spacing
.page-header {
  padding: var(--spacing-8);           // 32px all around
  border-bottom: 1px solid var(--border-color);
  
  .page-title {
    margin-bottom: var(--spacing-2);   // 8px below title
  }
  
  .page-subtitle {
    margin-bottom: var(--spacing-4);   // 16px below subtitle
  }
}

.page-content {
  padding: var(--spacing-8);           // 32px all around
}
```

### Grid Spacing

```less
// Consistent grid patterns
.dashboard-grid {
  gap: var(--spacing-6);               // 24px between dashboard items
  margin: var(--spacing-8) 0;          // 32px vertical margins
}

.stats-grid {
  gap: var(--spacing-4);               // 16px between stats
  margin: var(--spacing-6) 0;          // 24px vertical margins
}

.crud-grid {
  gap: var(--spacing-8);               // 32px between CRUD sections
  
  &.with-sidebar {
    gap: var(--spacing-6);             // 24px between sidebar and main
  }
}
```

### Action Bar Spacing

```less
.action-bar {
  padding: var(--spacing-4) var(--spacing-8); // 16px/32px padding
  border-bottom: 1px solid var(--border-color);
  
  .action-bar-left,
  .action-bar-center,
  .action-bar-right {
    gap: var(--spacing-4);             // 16px between action sections
  }
  
  .quick-filters {
    gap: var(--spacing-2);             // 8px between filter items
  }
  
  .filter-tag {
    margin-right: var(--spacing-2);    // 8px between tags
    padding: var(--spacing-1) var(--spacing-3); // 4px/12px internal
  }
}
```

---

## Utility Classes

### Margin Utilities

```less
// Margin utility classes following the spacing scale
.m-0 { margin: 0 !important; }
.m-1 { margin: var(--spacing-1) !important; }     // 4px
.m-2 { margin: var(--spacing-2) !important; }     // 8px
.m-3 { margin: var(--spacing-3) !important; }     // 12px
.m-4 { margin: var(--spacing-4) !important; }     // 16px
.m-6 { margin: var(--spacing-6) !important; }     // 24px
.m-8 { margin: var(--spacing-8) !important; }     // 32px

// Directional margins
.mt-0 { margin-top: 0 !important; }
.mt-1 { margin-top: var(--spacing-1) !important; }
.mt-2 { margin-top: var(--spacing-2) !important; }
.mt-3 { margin-top: var(--spacing-3) !important; }
.mt-4 { margin-top: var(--spacing-4) !important; }
.mt-6 { margin-top: var(--spacing-6) !important; }
.mt-8 { margin-top: var(--spacing-8) !important; }

.mb-0 { margin-bottom: 0 !important; }
.mb-1 { margin-bottom: var(--spacing-1) !important; }
.mb-2 { margin-bottom: var(--spacing-2) !important; }
.mb-3 { margin-bottom: var(--spacing-3) !important; }
.mb-4 { margin-bottom: var(--spacing-4) !important; }
.mb-6 { margin-bottom: var(--spacing-6) !important; }
.mb-8 { margin-bottom: var(--spacing-8) !important; }

.ml-0 { margin-left: 0 !important; }
.ml-1 { margin-left: var(--spacing-1) !important; }
.ml-2 { margin-left: var(--spacing-2) !important; }
.ml-3 { margin-left: var(--spacing-3) !important; }
.ml-4 { margin-left: var(--spacing-4) !important; }
.ml-auto { margin-left: auto !important; }

.mr-0 { margin-right: 0 !important; }
.mr-1 { margin-right: var(--spacing-1) !important; }
.mr-2 { margin-right: var(--spacing-2) !important; }
.mr-3 { margin-right: var(--spacing-3) !important; }
.mr-4 { margin-right: var(--spacing-4) !important; }
.mr-auto { margin-right: auto !important; }

// Horizontal and vertical margins
.mx-0 { margin-left: 0 !important; margin-right: 0 !important; }
.mx-1 { margin-left: var(--spacing-1) !important; margin-right: var(--spacing-1) !important; }
.mx-2 { margin-left: var(--spacing-2) !important; margin-right: var(--spacing-2) !important; }
.mx-3 { margin-left: var(--spacing-3) !important; margin-right: var(--spacing-3) !important; }
.mx-4 { margin-left: var(--spacing-4) !important; margin-right: var(--spacing-4) !important; }
.mx-auto { margin-left: auto !important; margin-right: auto !important; }

.my-0 { margin-top: 0 !important; margin-bottom: 0 !important; }
.my-1 { margin-top: var(--spacing-1) !important; margin-bottom: var(--spacing-1) !important; }
.my-2 { margin-top: var(--spacing-2) !important; margin-bottom: var(--spacing-2) !important; }
.my-3 { margin-top: var(--spacing-3) !important; margin-bottom: var(--spacing-3) !important; }
.my-4 { margin-top: var(--spacing-4) !important; margin-bottom: var(--spacing-4) !important; }
.my-6 { margin-top: var(--spacing-6) !important; margin-bottom: var(--spacing-6) !important; }
.my-8 { margin-top: var(--spacing-8) !important; margin-bottom: var(--spacing-8) !important; }
```

### Padding Utilities

```less
// Padding utility classes
.p-0 { padding: 0 !important; }
.p-1 { padding: var(--spacing-1) !important; }     // 4px
.p-2 { padding: var(--spacing-2) !important; }     // 8px
.p-3 { padding: var(--spacing-3) !important; }     // 12px
.p-4 { padding: var(--spacing-4) !important; }     // 16px
.p-6 { padding: var(--spacing-6) !important; }     // 24px
.p-8 { padding: var(--spacing-8) !important; }     // 32px

// Directional padding
.pt-0 { padding-top: 0 !important; }
.pt-1 { padding-top: var(--spacing-1) !important; }
.pt-2 { padding-top: var(--spacing-2) !important; }
.pt-3 { padding-top: var(--spacing-3) !important; }
.pt-4 { padding-top: var(--spacing-4) !important; }
.pt-6 { padding-top: var(--spacing-6) !important; }
.pt-8 { padding-top: var(--spacing-8) !important; }

.pb-0 { padding-bottom: 0 !important; }
.pb-1 { padding-bottom: var(--spacing-1) !important; }
.pb-2 { padding-bottom: var(--spacing-2) !important; }
.pb-3 { padding-bottom: var(--spacing-3) !important; }
.pb-4 { padding-bottom: var(--spacing-4) !important; }
.pb-6 { padding-bottom: var(--spacing-6) !important; }
.pb-8 { padding-bottom: var(--spacing-8) !important; }

.pl-0 { padding-left: 0 !important; }
.pl-1 { padding-left: var(--spacing-1) !important; }
.pl-2 { padding-left: var(--spacing-2) !important; }
.pl-3 { padding-left: var(--spacing-3) !important; }
.pl-4 { padding-left: var(--spacing-4) !important; }

.pr-0 { padding-right: 0 !important; }
.pr-1 { padding-right: var(--spacing-1) !important; }
.pr-2 { padding-right: var(--spacing-2) !important; }
.pr-3 { padding-right: var(--spacing-3) !important; }
.pr-4 { padding-right: var(--spacing-4) !important; }

// Horizontal and vertical padding
.px-0 { padding-left: 0 !important; padding-right: 0 !important; }
.px-1 { padding-left: var(--spacing-1) !important; padding-right: var(--spacing-1) !important; }
.px-2 { padding-left: var(--spacing-2) !important; padding-right: var(--spacing-2) !important; }
.px-3 { padding-left: var(--spacing-3) !important; padding-right: var(--spacing-3) !important; }
.px-4 { padding-left: var(--spacing-4) !important; padding-right: var(--spacing-4) !important; }
.px-6 { padding-left: var(--spacing-6) !important; padding-right: var(--spacing-6) !important; }
.px-8 { padding-left: var(--spacing-8) !important; padding-right: var(--spacing-8) !important; }

.py-0 { padding-top: 0 !important; padding-bottom: 0 !important; }
.py-1 { padding-top: var(--spacing-1) !important; padding-bottom: var(--spacing-1) !important; }
.py-2 { padding-top: var(--spacing-2) !important; padding-bottom: var(--spacing-2) !important; }
.py-3 { padding-top: var(--spacing-3) !important; padding-bottom: var(--spacing-3) !important; }
.py-4 { padding-top: var(--spacing-4) !important; padding-bottom: var(--spacing-4) !important; }
.py-6 { padding-top: var(--spacing-6) !important; padding-bottom: var(--spacing-6) !important; }
.py-8 { padding-top: var(--spacing-8) !important; padding-bottom: var(--spacing-8) !important; }
```

### Gap Utilities (for Grid and Flexbox)

```less
// Gap utilities for CSS Grid and Flexbox
.gap-0 { gap: 0 !important; }
.gap-1 { gap: var(--spacing-1) !important; }       // 4px
.gap-2 { gap: var(--spacing-2) !important; }       // 8px
.gap-3 { gap: var(--spacing-3) !important; }       // 12px
.gap-4 { gap: var(--spacing-4) !important; }       // 16px
.gap-6 { gap: var(--spacing-6) !important; }       // 24px
.gap-8 { gap: var(--spacing-8) !important; }       // 32px

// Row and column gaps
.gap-x-0 { column-gap: 0 !important; }
.gap-x-1 { column-gap: var(--spacing-1) !important; }
.gap-x-2 { column-gap: var(--spacing-2) !important; }
.gap-x-3 { column-gap: var(--spacing-3) !important; }
.gap-x-4 { column-gap: var(--spacing-4) !important; }
.gap-x-6 { column-gap: var(--spacing-6) !important; }
.gap-x-8 { column-gap: var(--spacing-8) !important; }

.gap-y-0 { row-gap: 0 !important; }
.gap-y-1 { row-gap: var(--spacing-1) !important; }
.gap-y-2 { row-gap: var(--spacing-2) !important; }
.gap-y-3 { row-gap: var(--spacing-3) !important; }
.gap-y-4 { row-gap: var(--spacing-4) !important; }
.gap-y-6 { row-gap: var(--spacing-6) !important; }
.gap-y-8 { row-gap: var(--spacing-8) !important; }
```

---

## Responsive Spacing

### Mobile-First Approach

```less
// Base spacing (mobile)
.content-container {
  padding: var(--spacing-4);           // 16px on mobile
}

.card-grid {
  gap: var(--spacing-3);               // 12px on mobile
}

.page-header {
  padding: var(--spacing-4);           // 16px on mobile
}

// Tablet spacing adjustments
@media (min-width: 768px) {
  .content-container {
    padding: var(--spacing-6);         // 24px on tablet
  }
  
  .card-grid {
    gap: var(--spacing-4);             // 16px on tablet
  }
  
  .page-header {
    padding: var(--spacing-6);         // 24px on tablet
  }
}

// Desktop spacing adjustments
@media (min-width: 1024px) {
  .content-container {
    padding: var(--spacing-8);         // 32px on desktop
  }
  
  .card-grid {
    gap: var(--spacing-6);             // 24px on desktop
  }
  
  .page-header {
    padding: var(--spacing-8);         // 32px on desktop
  }
}

// Large desktop spacing
@media (min-width: 1200px) {
  .content-container {
    padding: var(--spacing-10);        // 40px on large screens
  }
  
  .card-grid {
    gap: var(--spacing-8);             // 32px on large screens
  }
}
```

### Responsive Utility Classes

```less
// Mobile-specific spacing
@media (max-width: 767px) {
  .m-mobile-0 { margin: 0 !important; }
  .m-mobile-2 { margin: var(--spacing-2) !important; }
  .m-mobile-4 { margin: var(--spacing-4) !important; }
  
  .p-mobile-0 { padding: 0 !important; }
  .p-mobile-2 { padding: var(--spacing-2) !important; }
  .p-mobile-4 { padding: var(--spacing-4) !important; }
  
  .gap-mobile-2 { gap: var(--spacing-2) !important; }
  .gap-mobile-3 { gap: var(--spacing-3) !important; }
}

// Tablet-specific spacing
@media (min-width: 768px) and (max-width: 1023px) {
  .m-tablet-4 { margin: var(--spacing-4) !important; }
  .m-tablet-6 { margin: var(--spacing-6) !important; }
  
  .p-tablet-4 { padding: var(--spacing-4) !important; }
  .p-tablet-6 { padding: var(--spacing-6) !important; }
  
  .gap-tablet-4 { gap: var(--spacing-4) !important; }
  .gap-tablet-6 { gap: var(--spacing-6) !important; }
}

// Desktop-specific spacing
@media (min-width: 1024px) {
  .m-desktop-6 { margin: var(--spacing-6) !important; }
  .m-desktop-8 { margin: var(--spacing-8) !important; }
  
  .p-desktop-6 { padding: var(--spacing-6) !important; }
  .p-desktop-8 { padding: var(--spacing-8) !important; }
  
  .gap-desktop-8 { gap: var(--spacing-8) !important; }
}
```

---

## Best Practices

### 1. **Use Semantic Spacing Variables**

```less
// ✅ Good - Semantic and maintainable
.card-content {
  padding: var(--component-padding);
  margin-bottom: var(--component-margin);
}

// ❌ Avoid - Hard to maintain
.card-content {
  padding: 16px;
  margin-bottom: 16px;
}
```

### 2. **Maintain Vertical Rhythm**

```less
// ✅ Good - Consistent vertical rhythm
.article {
  h1 {
    margin: var(--spacing-8) 0 var(--spacing-4) 0; // 32px top, 16px bottom
    line-height: 1.2;
  }
  
  h2 {
    margin: var(--spacing-6) 0 var(--spacing-3) 0; // 24px top, 12px bottom
    line-height: 1.3;
  }
  
  p {
    margin-bottom: var(--spacing-4);               // 16px bottom
    line-height: 1.5;
  }
  
  ul, ol {
    margin: var(--spacing-4) 0;                    // 16px top/bottom
    padding-left: var(--spacing-6);                // 24px left indent
  }
}
```

### 3. **Progressive Spacing Enhancement**

```less
// ✅ Good - Progressive enhancement
.feature-section {
  // Mobile first
  padding: var(--spacing-4);                       // 16px
  margin: var(--spacing-6) 0;                      // 24px
  
  @media (min-width: 768px) {
    padding: var(--spacing-6);                     // 24px
    margin: var(--spacing-8) 0;                    // 32px
  }
  
  @media (min-width: 1024px) {
    padding: var(--spacing-8);                     // 32px
    margin: var(--spacing-12) 0;                   // 48px
  }
}
```

### 4. **Component-Specific Spacing Rules**

```less
// ✅ Good - Consistent component spacing
.button-group {
  display: flex;
  gap: var(--spacing-2);                          // 8px between buttons
  
  .ui.button {
    padding: var(--spacing-2) var(--spacing-4);   // 8px/16px internal
    margin: 0;                                     // No external margins
  }
  
  &.spaced {
    gap: var(--spacing-4);                        // 16px for spaced variant
  }
}

.form-section {
  margin-bottom: var(--spacing-8);                // 32px between sections
  
  .field-group {
    margin-bottom: var(--spacing-6);              // 24px between field groups
  }
  
  .field {
    margin-bottom: var(--spacing-4);              // 16px between fields
  }
}
```

### 5. **Avoid Margin Collapsing Issues**

```less
// ✅ Good - Use padding or flex gaps to avoid margin collapsing
.content-stack {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-4);                          // 16px - no collapsing
}

.content-section {
  padding-bottom: var(--spacing-6);               // 24px - no collapsing
  
  &:not(:last-child) {
    border-bottom: 1px solid var(--border-color);
  }
}

// ❌ Avoid - Margin collapsing can be unpredictable
.content-item {
  margin-bottom: var(--spacing-4);
}
```

---

## Common Spacing Scenarios

### Dashboard Layout

```html
<div class="page-content">
  <div class="content-container wide">
    <!-- Stats overview -->
    <div class="stats-grid mb-8">
      <div class="ui card summary-card">...</div>
    </div>
    
    <!-- Dashboard widgets -->
    <div class="dashboard-grid">
      <div class="ui card">...</div>
    </div>
  </div>
</div>
```

```less
// Dashboard spacing
.stats-grid {
  gap: var(--spacing-4);                          // 16px between stat cards
  margin-bottom: var(--spacing-8);                // 32px below stats
}

.dashboard-grid {
  gap: var(--spacing-6);                          // 24px between widgets
}

.summary-card {
  padding: var(--spacing-6);                      // 24px internal padding
}
```

### CRUD Interface

```html
<div class="page-content">
  <div class="content-container wide">
    <!-- Filters -->
    <div class="filters-bar mb-6">
      <div class="filter-group">
        <label>Category:</label>
        <select class="ui dropdown">...</select>
      </div>
    </div>
    
    <!-- Data table -->
    <div class="table-container">
      <table class="ui table">...</table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination mt-4">...</div>
  </div>
</div>
```

```less
// CRUD spacing
.filters-bar {
  padding: var(--spacing-4);                      // 16px internal padding
  margin-bottom: var(--spacing-6);                // 24px below filters
  background: var(--gray-50);
  border-radius: 8px;
  
  .filter-group {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);                        // 12px between label and input
    
    &:not(:last-child) {
      margin-right: var(--spacing-6);             // 24px between filter groups
    }
  }
}

.table-container {
  .ui.table {
    th {
      padding: var(--spacing-3) var(--spacing-4); // 12px/16px
    }
    
    td {
      padding: var(--spacing-2) var(--spacing-4); // 8px/16px
    }
  }
}

.pagination {
  padding: var(--spacing-4) 0;                    // 16px top/bottom
  text-align: center;
}
```

### Form Layout

```html
<div class="page-content">
  <div class="content-container narrow">
    <form class="ui form">
      <div class="form-section">
        <h3 class="section-title">Basic Information</h3>
        <div class="form-grid two-column">
          <div class="field">
            <label>First Name</label>
            <input type="text">
          </div>
          <div class="field">
            <label>Last Name</label>
            <input type="text">
          </div>
        </div>
      </div>
      
      <div class="form-actions">
        <button class="ui button">Cancel</button>
        <button class="ui button primary">Save</button>
      </div>
    </form>
  </div>
</div>
```

```less
// Form spacing
.form-section {
  margin-bottom: var(--spacing-8);                // 32px between sections
  
  .section-title {
    margin-bottom: var(--spacing-6);              // 24px below section title
    padding-bottom: var(--spacing-2);             // 8px padding + border
    border-bottom: 1px solid var(--border-color);
  }
}

.form-grid {
  gap: var(--spacing-4);                          // 16px between form columns
  
  .field {
    margin-bottom: var(--spacing-4);              // 16px between fields
    
    label {
      margin-bottom: var(--spacing-2);            // 8px below label
    }
    
    input, textarea, select {
      padding: var(--spacing-2) var(--spacing-3); // 8px/12px internal
    }
  }
}

.form-actions {
  margin-top: var(--spacing-8);                   // 32px above actions
  padding-top: var(--spacing-6);                  // 24px top padding
  border-top: 1px solid var(--border-color);
  display: flex;
  gap: var(--spacing-3);                          // 12px between buttons
  justify-content: flex-end;
}
```

### Card Grid Layout

```html
<div class="page-content">
  <div class="content-container wide">
    <div class="card-grid dense">
      <div class="ui card data-card">
        <div class="content">
          <div class="header">User Name</div>
          <div class="meta">Administrator</div>
          <div class="description">User description...</div>
        </div>
        <div class="extra content">
          <div class="ui buttons">
            <button class="ui button">View</button>
            <button class="ui button primary">Edit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

```less
// Card grid spacing
.card-grid {
  gap: var(--spacing-4);                          // 16px between cards
  margin: var(--spacing-6) 0;                     // 24px top/bottom
  
  &.dense {
    gap: var(--spacing-3);                        // 12px for dense layout
  }
  
  .ui.card {
    margin: 0;                                     // Reset Fomantic margins
    
    .content {
      padding: var(--spacing-4);                  // 16px internal padding
      
      .header {
        margin-bottom: var(--spacing-2);          // 8px below header
      }
      
      .meta {
        margin-bottom: var(--spacing-3);          // 12px below meta
      }
      
      .description {
        line-height: 1.5;                         // Maintain rhythm
      }
    }
    
    .extra.content {
      padding: var(--spacing-3) var(--spacing-4); // 12px/16px padding
      
      .ui.buttons {
        gap: var(--spacing-2);                    // 8px between buttons
      }
    }
  }
}
```

### Modal Dialog

```html
<div class="ui modal">
  <div class="header">
    <h3>Edit User</h3>
  </div>
  <div class="content">
    <form class="ui form">
      <!-- Form content -->
    </form>
  </div>
  <div class="actions">
    <button class="ui button">Cancel</button>
    <button class="ui button primary">Save</button>
  </div>
</div>
```

```less
// Modal spacing
.ui.modal {
  .header {
    padding: var(--spacing-6) var(--spacing-8);   // 24px/32px padding
    border-bottom: 1px solid var(--border-color);
    
    h3 {
      margin: 0;                                   // Reset header margin
    }
  }
  
  .content {
    padding: var(--spacing-8);                    // 32px all around
    max-height: 60vh;                             // Limit height
    overflow-y: auto;                             // Allow scrolling
  }
  
  .actions {
    padding: var(--spacing-6) var(--spacing-8);   // 24px/32px padding
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: var(--spacing-3);                        // 12px between buttons
    justify-content: flex-end;
  }
}
```

---

## Quick Reference Guide

### Spacing Scale Quick Reference

| Variable | Value | Usage |
|----------|-------|-------|
| `--spacing-1` | 4px | Borders, fine details, micro adjustments |
| `--spacing-2` | 8px | Tight spacing, small gaps, text margins |
| `--spacing-3` | 12px | Compact layouts, small component gaps |
| `--spacing-4` | 16px | **Default spacing** - most common use |
| `--spacing-6` | 24px | Section spacing, large component gaps |
| `--spacing-8` | 32px | Major sections, container padding |
| `--spacing-12` | 48px | Page sections, major layout divisions |
| `--spacing-16` | 64px | Hero sections, major page divisions |

### Component Spacing Patterns

| Component | Padding | Margin | Gap |
|-----------|---------|--------|-----|
| Cards | `--spacing-4` | `--spacing-4` | `--spacing-4` |
| Buttons | `--spacing-2` `--spacing-4` | `0` | `--spacing-2` |
| Form Fields | `--spacing-2` `--spacing-3` | `--spacing-4` | `--spacing-4` |
| Headers | `--spacing-6` `--spacing-8` | `--spacing-8` | `--spacing-4` |
| Tables | `--spacing-2` `--spacing-4` | `--spacing-6` | N/A |
| Modals | `--spacing-8` | N/A | `--spacing-3` |

### When to Use Each Spacing Level

**Micro (1-2):** Borders, fine details, tight relationships
**Small (3):** Compact layouts, related items
**Medium (4):** Default spacing for most components
**Large (6):** Section divisions, important relationships
**XL (8):** Major sections, page-level spacing
**XXL (12+):** Page divisions, hero sections

---

## Implementation Checklist

### ✅ Setup Checklist
- [ ] Define CSS custom properties for spacing scale
- [ ] Create semantic spacing aliases
- [ ] Set up utility classes for margins, padding, and gaps
- [ ] Configure responsive spacing breakpoints
- [ ] Establish component-specific spacing patterns

### ✅ Usage Checklist
- [ ] Use variables instead of hardcoded values
- [ ] Maintain consistent vertical rhythm
- [ ] Apply progressive spacing enhancement
- [ ] Avoid margin collapsing issues
- [ ] Test spacing across all device sizes

### ✅ Quality Assurance
- [ ] Visual spacing consistency across components
- [ ] Proper spacing hierarchy (micro to macro)
- [ ] Responsive behavior on all devices
- [ ] Accessibility compliance (minimum touch targets)
- [ ] Performance optimization (minimal CSS output)

This spacing system ensures visual consistency, maintainability, and scalability across your entire FrameNet application while working seamlessly with Fomantic-UI's component system.6 { gap: var(--spacing-6) !important; }
  .gap-desktop-