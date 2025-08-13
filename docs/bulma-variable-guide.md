# Bulma Variable System Guide
**FNBr Webtool 4.0 - Bulma v1.0.4 Integration**

## Overview

This guide explains how Sass variables map to CSS custom properties in Bulma v1.0.4 and how to customize the design system effectively in our webtool.

## Quick Mapping Reference

### Basic Pattern
```
Sass Variable          →  CSS Custom Property
$family-primary        →  --bulma-family-primary
$size-normal          →  --bulma-size-normal
$primary              →  --bulma-primary
$text                 →  --bulma-text
```

### Our Current Configuration
Located in `resources/sass/abstracts/_variables.scss`:

```scss
@use "bulma/sass" as bulma with (
  // Typography
  $family-primary: ('Noto Sans', 'Lato', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif),
  $family-sans-serif: ('Noto Sans', 'Lato', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif),
  $family-monospace: ('Noto Sans Mono', 'JetBrains Mono', 'Fira Code', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', monospace),
  
  // Colors
  $primary: #2563eb  // blue-600 from our palette
);
```

This generates:
```css
:root {
  --bulma-family-primary: 'Noto Sans', 'Lato', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
  --bulma-family-sans-serif: 'Noto Sans', 'Lato', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
  --bulma-family-monospace: 'Noto Sans Mono', 'JetBrains Mono', 'Fira Code', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', monospace;
  --bulma-primary: #2563eb;
}
```

## Common Customizations

### Typography Sizes

#### Our Harmonic Size System Integration
```scss
// Our harmonic progression: 9.7px → 11.7px → 14px → 16.8px → 20.2px
@use "bulma/sass" as bulma with (
  $size-7: 11.7px,     // small     → --bulma-size-7, --bulma-size-small
  $size-6: 14px,       // normal    → --bulma-size-6, --bulma-size-normal  
  $size-5: 16.8px,     // medium    → --bulma-size-5, --bulma-size-medium
  $size-4: 20.2px,     // large     → --bulma-size-4, --bulma-size-large
);
```

#### Standard Bulma Size Variables
```scss
$size-1: 3rem;      // --bulma-size-1 (48px)
$size-2: 2.5rem;    // --bulma-size-2 (40px)
$size-3: 2rem;      // --bulma-size-3 (32px)
$size-4: 1.5rem;    // --bulma-size-4 (24px)
$size-5: 1.25rem;   // --bulma-size-5 (20px)
$size-6: 1rem;      // --bulma-size-6 (16px) - normal/base
$size-7: 0.875rem;  // --bulma-size-7 (14px)
```

### Color System

#### Status Colors
```scss
@use "bulma/sass" as bulma with (
  $primary: #2563eb,    // --bulma-primary
  $success: #22c55e,    // --bulma-success  
  $warning: #f59e0b,    // --bulma-warning
  $danger: #dc2626,     // --bulma-danger
  $info: #4f46e5,       // --bulma-info
);
```

#### Text Colors
```scss
@use "bulma/sass" as bulma with (
  $text: #374151,           // --bulma-text (gray-700)
  $text-strong: #111827,    // --bulma-text-strong (gray-900)
  $text-weak: #6b7280,      // --bulma-text-weak (gray-500)
);
```

### Layout & Spacing

#### Container & Breakpoints
```scss
@use "bulma/sass" as bulma with (
  $container-max-width: 1400px,  // --bulma-container-max-width
  $tablet: 768px,                // --bulma-tablet
  $desktop: 1024px,              // --bulma-desktop
  $widescreen: 1216px,           // --bulma-widescreen
  $fullhd: 1408px,               // --bulma-fullhd
);
```

## Browser Usage Examples

### Finding CSS Variables in DevTools
In your browser's DevTools, you'll see Bulma using variables like:
```css
.title {
  font-family: var(--bulma-family-primary);
  font-size: var(--bulma-size-3);
  color: var(--bulma-text-strong);
}

.button.is-primary {
  background-color: var(--bulma-primary);
  color: var(--bulma-primary-invert);
}
```

### Common CSS Variable Lookups
| What you see in DevTools | Sass variable to customize |
|---------------------------|----------------------------|
| `var(--bulma-family-primary)` | `$family-primary` |
| `var(--bulma-size-normal)` | `$size-6` |
| `var(--bulma-primary)` | `$primary` |
| `var(--bulma-text)` | `$text` |
| `var(--bulma-radius)` | `$radius` |

## Customization Methods

### Method 1: Compile-time Configuration (Recommended)
Configure variables during Sass compilation in `_variables.scss`:

```scss
@use "bulma/sass" as bulma with (
  $family-primary: ('Custom Font', sans-serif),
  $size-6: 18px,  // Changes the base font size
  $primary: #custom-color
);
```

**Pros:** Better performance, smaller CSS, build-time optimization
**Cons:** Requires rebuild to change

### Method 2: Runtime CSS Override
Override CSS custom properties in your CSS:

```css
:root {
  --bulma-family-primary: 'Dynamic Font', sans-serif;
  --bulma-primary: #runtime-color;
}

/* For component-specific overrides */
.my-component {
  --bulma-size-normal: 16px;
}
```

**Pros:** Dynamic changes, theme switching capability
**Cons:** Larger CSS bundle, runtime computation

## Webtool-Specific Variables

### FrameNet Entity Colors
Our custom entity color system is preserved alongside Bulma:

```scss
// These remain as custom variables (not Bulma variables)
$frame-color: $red-700;        // #b91c1c
$lu-color: $blue-700;          // #1d4ed8  
$construction-color: $green-700; // #15803d
$concept-color: $purple-700;    // #7c3aed
```

### Layout Dimensions
```scss
// Custom layout variables for the webtool
$sidebar-width: 280px;
$tools-panel-width: 320px;
$header-height: 64px;
$timeline-height: 180px;
```

## Troubleshooting

### Issue: Font changes not applying
**Problem:** Custom fonts defined but browser still shows defaults
**Solution:** Check that you're using `@use ... with()` syntax, not separate variable definitions

```scss
// ❌ Wrong - separate definition
$family-primary: 'Custom Font', sans-serif;
@use "bulma/sass";

// ✅ Correct - configuration during import
@use "bulma/sass" as bulma with (
  $family-primary: ('Custom Font', sans-serif)
);
```

### Issue: "Variable is not defined" errors
**Problem:** Bulma variables not available in component files
**Solution:** Ensure you're importing variables correctly:

```scss
// In component files
@use "../../abstracts/variables" as *;

.my-component {
  font-size: var(--bulma-size-normal); // ✅ Use CSS custom property
  // or
  font-size: $size-normal; // ✅ Use Sass variable if forwarded
}
```

### Issue: Size changes not visible
**Problem:** Font size looks the same after changing `$size-*` variables
**Solution:** Remember Bulma's size mapping:
- `$size-6` = normal text size (--bulma-size-normal)
- `$size-5` = medium size (--bulma-size-medium)
- Change the specific size tier you want to affect

## Quick Reference Cheat Sheet

### Most Common Variables

| Purpose | Sass Variable | CSS Custom Property | Default |
|---------|---------------|-------------------|---------|
| Base font | `$family-primary` | `--bulma-family-primary` | Inter, system fonts |
| Normal text size | `$size-6` | `--bulma-size-normal` | 1rem (16px) |
| Primary color | `$primary` | `--bulma-primary` | #00d1b2 |
| Text color | `$text` | `--bulma-text` | #4a4a4a |
| Background | `$background` | `--bulma-background` | #fafafa |
| Border radius | `$radius` | `--bulma-radius` | 4px |

### Font Sizes in Order
```
$size-1 (3rem)     - Huge headings
$size-2 (2.5rem)   - Large headings  
$size-3 (2rem)     - Medium headings
$size-4 (1.5rem)   - Small headings
$size-5 (1.25rem)  - Large text
$size-6 (1rem)     - Normal text ← Base size
$size-7 (0.875rem) - Small text
```

### Our Harmonic Sizes
```
$size-mini: 8.1px     - Ultra small
$size-tiny: 9.7px     - Very small  
$size-small: 11.7px   - Small
$size-normal: 14px    - Base ← Our custom base
$size-medium: 16.8px  - Medium
$size-large: 20.2px   - Large
$size-huge: 24.2px    - Very large
```

## File Locations

- **Main configuration:** `resources/sass/abstracts/_variables.scss`
- **App entry point:** `resources/sass/app.scss`
- **This guide:** `docs/bulma-variable-guide.md`
- **Build command:** `npm run build` or `npm run dev`

---

**Last updated:** Based on Bulma v1.0.4 and Webtool 4.0 migration (2024)