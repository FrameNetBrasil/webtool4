# FNBr Webtool Design System Implementation Guide
**Building a Hybrid Bulma + Custom Variables Design System**

## Overview

This guide explains how we implement our design system using a hybrid approach that combines Bulma CSS framework with custom variables specific to the FrameNet Brasil annotation system. This approach gives us the power of Bulma's components while maintaining full control over our specialized annotation interface.

## Architecture Overview

### The Hybrid Approach

```
┌─────────────────────────────────────────────────────────────┐
│                FNBr Webtool Design System                  │
├─────────────────────────────────────────────────────────────┤
│ LAYER 1: Bulma Foundation                                   │
│ • Typography system (--bulma-family-*, --bulma-size-*)     │
│ • Basic colors (--bulma-primary, --bulma-text)             │
│ • Layout primitives (container, columns, flex)             │
│ • Component base (buttons, forms, modals)                  │
├─────────────────────────────────────────────────────────────┤
│ LAYER 2: Custom Variables (Our Domain-Specific System)     │
│ • FrameNet entity colors ($frame-color, $lu-color)         │
│ • Annotation layer system ($layer-fe, $layer-target)       │
│ • Harmonic size system ($size-tiny: 9.7px)                 │
│ • Layout dimensions ($sidebar-width, $timeline-height)     │
│ • Semantic color palette ($blue-50 to $blue-950)           │
├─────────────────────────────────────────────────────────────┤
│ LAYER 3: Component Implementations                         │
│ • Uses both Bulma and custom variables                     │
│ • Annotation-specific styling                              │
│ • Specialized interaction patterns                         │
└─────────────────────────────────────────────────────────────┘
```

## File Structure

```
resources/sass/
├── abstracts/
│   └── _variables.scss          # THE MASTER FILE
│       ├── Bulma configuration (@use with)
│       ├── Custom color palette
│       ├── FrameNet entity system  
│       ├── Harmonic size system
│       └── Layout variables
├── app.scss                     # Entry point (imports variables)
├── components/
│   ├── app/_sidebar.scss        # Uses custom variables
│   └── ...
└── pages/
    └── annotation/
        ├── _annotation.scss     # Domain-specific styling
        └── ...
```

## Implementation Strategy

### 1. Foundation Configuration (Bulma Layer)

In `resources/sass/abstracts/_variables.scss`, we configure Bulma's foundation:

```scss
// Configure Bulma with our design system values
@use "bulma/sass" as bulma with (
  // Typography - Our Noto Sans system
  $family-primary: ('Noto Sans', 'Lato', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif),
  $family-monospace: ('Noto Sans Mono', 'JetBrains Mono', 'Fira Code', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', monospace),
  
  // Core color
  $primary: #2563eb  // Our blue-600
);

// Forward Bulma utilities so components can access them
@forward "bulma/sass/utilities";
@forward "bulma/sass/utilities/derived-variables";
```

**Result:** This gives us access to Bulma variables throughout our system:
- `--bulma-family-primary` (CSS) and `$family-primary` (Sass)
- `--bulma-primary` (CSS) and `$primary` (Sass)
- All Bulma component styling with our customizations

### 2. Custom Variable Layer (Domain-Specific)

After Bulma configuration, we define our specialized variables:

```scss
// -----------------------------------------------------------------------------
// Color Palette (Our Semantic System)
// -----------------------------------------------------------------------------

// Full color palette for systematic design
$blue-50: #eff6ff;
$blue-100: #dbeafe;
// ... through to
$blue-950: #172554;

// Grays, reds, greens, etc. - complete semantic palette

// -----------------------------------------------------------------------------
// FrameNet Entity Color System (Domain-Specific)
// -----------------------------------------------------------------------------

$frame-color: $red-700;        // Frame annotations
$frame-bg: $red-50;
$frame-border: $red-200;

$lu-color: $blue-700;          // Lexical Unit annotations  
$lu-bg: $blue-50;
$lu-border: $blue-200;

$construction-color: $green-700; // Construction annotations
$construction-bg: $green-50;
$construction-border: $green-200;

// Relation types
$relation-inherits: $blue-600;
$relation-uses: $green-600;
$relation-subframe: $orange-600;

// -----------------------------------------------------------------------------
// Harmonic Size System (Our Typography Scale)
// -----------------------------------------------------------------------------

$size-mini: 8.1px;       // Ultra-small text
$size-tiny: 9.7px;       // Very small text
$size-small: 11.7px;     // Small text
$size-normal: 14px;      // Base text size
$size-medium: 16.8px;    // Medium text
$size-large: 20.2px;     // Large text
$size-huge: 24.2px;      // Very large text

// -----------------------------------------------------------------------------
// Layout System (Application-Specific)
// -----------------------------------------------------------------------------

$sidebar-width: 280px;
$tools-panel-width: 320px;
$header-height: 64px;
$timeline-height: 180px;
$toolbar-height: 48px;
```

### 3. Component Usage (Both Systems)

In component files like `_annotation.scss` and `_sidebar.scss`:

```scss
@use "../../abstracts/variables" as *;

.annotation-toolbar {
    // Using Bulma foundation
    background: var(--bulma-scheme-main);  // or $scheme-main
    color: var(--bulma-text);              // or $text
    
    // Using our custom variables
    padding: $size-small $size-large;      // Our harmonic sizes
    border-bottom: 1px solid $primary;     // Our custom primary
    
    .frame-element {
        color: $frame-color;                // FrameNet-specific
        background: $frame-bg;
        border: 1px solid $frame-border;
    }
}

.app-sidebar {
    // Layout using our custom system
    width: $sidebar-width;
    padding: $size-tiny 0 0 $size-tiny;
    
    // Using Bulma utilities
    background: var(--bulma-background);
    border-right: 1px solid var(--bulma-border);
}
```

## Practical Examples

### Adding New Entity Type Colors

**Scenario:** You need to add colors for a new annotation type "Metaphor"

**Steps:**
1. Add to the entity color system in `_variables.scss`:
```scss
// FrameNet Entity Color System
$metaphor-color: $purple-700;
$metaphor-bg: $purple-50;
$metaphor-border: $purple-200;
```

2. Use in components:
```scss
.metaphor-annotation {
    color: $metaphor-color;
    background: $metaphor-bg;
    border: 1px solid $metaphor-border;
}
```

### Adjusting Typography Scale

**Scenario:** You want to make the base text size larger

**Steps:**
1. Modify the harmonic size in `_variables.scss`:
```scss
$size-normal: 16px;      // Was 14px
$size-medium: 19.2px;    // Adjust proportionally
$size-large: 23.04px;    // Maintain harmonic ratio
```

2. Components automatically inherit the change:
```scss
.annotation-text {
    font-size: $size-normal;  // Now 16px instead of 14px
}
```

### Custom Layout Dimensions

**Scenario:** You need a wider sidebar for more content

**Steps:**
1. Update layout variable:
```scss
$sidebar-width: 320px;  // Was 280px
```

2. Components using this variable update automatically:
```scss
.app-sidebar {
    width: $sidebar-width;  // Now 320px
}
```

## Design System Benefits

### 1. Consistency Through Variables
```scss
// All frame-related elements use the same colors
.frame-title { color: $frame-color; }
.frame-badge { background: $frame-bg; }
.frame-border { border-color: $frame-border; }
```

### 2. Maintainable Typography
```scss
// All sizes follow the harmonic progression
.small-text { font-size: $size-small; }    // 11.7px
.normal-text { font-size: $size-normal; }  // 14px
.large-text { font-size: $size-large; }    // 20.2px
```

### 3. Systematic Color Usage
```scss
// Semantic color system
.success-message { color: $green-600; }
.warning-message { color: $yellow-600; }
.error-message { color: $red-600; }
```

## Variable Naming Conventions

### Entity-Specific Pattern
```scss
$[entity]-color: [color];      // Text color
$[entity]-bg: [color];         // Background
$[entity]-border: [color];     // Border color

// Examples:
$frame-color: $red-700;
$lu-color: $blue-700;
$construction-color: $green-700;
```

### Size System Pattern
```scss
$size-[descriptor]: [value];

// Examples:
$size-tiny: 9.7px;
$size-normal: 14px;
$size-huge: 24.2px;
```

### Layout Dimension Pattern
```scss
$[component]-[property]: [value];

// Examples:
$sidebar-width: 280px;
$header-height: 64px;
$timeline-height: 180px;
```

## Best Practices

### 1. Use Semantic Color References
```scss
// ✅ Good - semantic reference
.error-text {
    color: $red-600;  // Clear intent
}

// ❌ Avoid - direct hex values
.error-text {
    color: #dc2626;  // Hard to maintain
}
```

### 2. Leverage Both Bulma and Custom Variables
```scss
// ✅ Good - hybrid approach
.annotation-panel {
    background: var(--bulma-background);  // Bulma foundation
    padding: $size-medium;                // Our harmonic system
    border: 1px solid $frame-border;      // Our entity system
}
```

### 3. Maintain Variable Import Consistency
```scss
// ✅ Always import variables in component files
@use "../../abstracts/variables" as *;

.my-component {
    font-size: $size-normal;  // Variables available
}
```

### 4. Use CSS Custom Properties for Dynamic Values
```scss
// For theme switching or dynamic changes
:root {
    --annotation-highlight: #{$frame-color};
}

.annotation-highlight {
    background: var(--annotation-highlight);
}
```

## Testing Your Design System Changes

### 1. Build and Verify
```bash
npm run build
# Check that CSS compiles without errors
```

### 2. Check Generated CSS
Look for your variables in the compiled CSS:
```css
/* Should see your custom values */
:root {
    --bulma-family-primary: 'Noto Sans', 'Lato', ...;
    --bulma-primary: #2563eb;
}

/* And your custom variables used in components */
.frame-element {
    color: #b91c1c;  /* $frame-color value */
}
```

### 3. Browser Testing
Use DevTools to verify:
- Font families are applied correctly
- Colors match your design system
- Sizes follow your harmonic progression

## Evolution and Maintenance

### Adding New Variables
1. **Follow naming conventions**
2. **Add to appropriate section in `_variables.scss`**
3. **Document the purpose and usage**
4. **Test across components**

### Deprecating Variables
1. **Mark as deprecated with comments**
2. **Provide migration path**
3. **Update documentation**
4. **Remove after transition period**

### Color System Expansion
When adding new color families:
```scss
// Add complete 50-950 scale for consistency
$purple-50: #faf5ff;
$purple-100: #f3e8ff;
// ... complete scale
$purple-950: #3b0764;

// Then create semantic entity colors
$semantic-type-color: $purple-700;
$semantic-type-bg: $purple-50;
$semantic-type-border: $purple-200;
```

---

## Summary

Our hybrid design system provides:
- **Bulma foundation** for components and typography
- **Custom variables** for domain-specific needs
- **Systematic approach** to colors, sizes, and layout
- **Maintainable architecture** that scales with the application

This approach lets us leverage Bulma's power while maintaining full control over the specialized annotation interface that makes FNBr Webtool unique.

**Key Files to Remember:**
- `resources/sass/abstracts/_variables.scss` - The master configuration
- `docs/bulma-variable-guide.md` - Bulma-specific reference
- `docs/design-system-implementation.md` - This implementation guide