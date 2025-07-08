# Comprehensive Typography System Documentation

A complete guide to maintaining consistent typography throughout your FrameNet application using Fomantic-UI variables and your custom design system.

---

## Table of Contents

1. [Typography Philosophy](#typography-philosophy)
2. [Fomantic-UI Base Variables](#fomantic-ui-base-variables)
3. [Design System Typography Scale](#design-system-typography-scale)
4. [Component Typography Patterns](#component-typography-patterns)
5. [Semantic Typography Rules](#semantic-typography-rules)
6. [Utility Classes](#utility-classes)
7. [Responsive Typography](#responsive-typography)
8. [Best Practices](#best-practices)
9. [Common Typography Scenarios](#common-typography-scenarios)

---

## Typography Philosophy

### Core Principles
1. **Hierarchical Scale** - Clear distinction between heading levels and content
2. **Consistent Rhythm** - Harmonious line heights and spacing relationships
3. **Semantic Meaning** - Typography conveys information hierarchy
4. **Accessibility First** - Readable contrast and scalable text sizes
5. **Brand Consistency** - Typography reflects your application's personality

### The Modular Scale System
Your typography system is built on a **1.25 ratio modular scale** that ensures:
- Mathematical harmony between font sizes
- Consistent visual hierarchy
- Optimal reading experience
- Scalable design system
- Clear information architecture

---

## Fomantic-UI Base Variables

### Essential Typography Variables from `site.variables`

```less
// Base Typography Foundation
@emSize: 14px;                          // Base em calculation unit
@fontSize: 14px;                        // Root font size
@fontName: 'Lato', 'Helvetica Neue', Arial, Helvetica, sans-serif;
@fontSmoothing: antialiased;            // Font rendering optimization

// Font Size Scale (Fomantic's built-in scale)
@mini: 0.78571429rem;                   // 11px - Very small text
@tiny: 0.85714286rem;                   // 12px - Small labels
@small: 0.92857143rem;                  // 13px - Secondary text
@medium: 1rem;                          // 14px - Base size
@large: 1.14285714rem;                  // 16px - Emphasized text
@big: 1.28571429rem;                    // 18px - Large text
@huge: 1.42857143rem;                   // 20px - Extra large
@massive: 1.71428571rem;                // 24px - Display text

// Line Height Scale
@lineHeight: 1.4285em;                  // Base line height (20px at 14px)
@headerLineHeight: 1.28571429em;        // Header line height
@headerLineHeightOffset: 0.14285714em;  // Header spacing adjustment

// Font Weights
@normal: normal;                        // 400
@bold: bold;                           // 700

// Header Typography
@h1: 2rem;                             // 28px
@h2: 1.71428571rem;                    // 24px
@h3: 1.28571429rem;                    // 18px
@h4: 1.07142857rem;                    // 15px
@h5: 1rem;                             // 14px
@h6: 0.85714286rem;                    // 12px

// Header Margins and Spacing
@headerMargin: calc(2rem - 0.14285714em) 0 1rem; // Dynamic header margins
@subHeaderMargin: 0 0 0.5rem 0;        // Subheader spacing
@headerLineHeightOffset: 0.14285714em; // Optical adjustment

// Text Colors
@textColor: rgba(0, 0, 0, 0.87);       // Primary text color
@mutedTextColor: rgba(0, 0, 0, 0.6);   // Secondary text color
@lightTextColor: rgba(0, 0, 0, 0.4);   // Tertiary text color
@selectedTextColor: rgba(0, 0, 0, 0.95); // High emphasis text
@disabledTextColor: rgba(0, 0, 0, 0.3); // Disabled text

// Link Typography
@linkColor: #4183c4;                   // Link color
@linkUnderline: none;                  // Link decoration
@linkHoverColor: darken(@linkColor, 5%); // Hover state
@linkHoverUnderline: underline;        // Hover decoration

// Paragraph Typography
@paragraphMargin: 0 0 1em;             // Paragraph spacing
@paragraphLineHeight: 1.4285em;        // Paragraph line height

// Code Typography
@codeBackground: #f3f4f5;              // Code background
@codeBorder: 1px solid rgba(34, 36, 38, 0.15); // Code border
@codeColor: rgba(0, 0, 0, 0.87);       // Code text color
@codeFontSize: 0.92857143em;           // Code font size
@codeFontFamily: Monaco, Menlo, Ubuntu Mono, Consolas, source-code-pro, monospace;
```

### Component Typography Variables

```less
// Button Typography
@buttonFontSize: 1em;                  // Button text size
@buttonFontWeight: @bold;              // Button text weight
@buttonTextTransform: none;            // Button text casing

// Input Typography
@inputFontSize: 1em;                   // Input text size
@inputFontFamily: @fontName;           // Input font family
@inputFontWeight: normal;              // Input font weight

// Label Typography
@labelFontSize: 0.85714286em;          // Label text size
@labelFontWeight: @bold;               // Label font weight
@labelTextTransform: none;             // Label text casing

// Menu Typography
@menuFontSize: 1rem;                   // Menu item text size
@menuFontWeight: normal;               // Menu font weight
@menuTextTransform: none;              // Menu text casing

// Card Typography
@cardHeaderFontSize: 1.28571429em;     // Card header size
@cardHeaderFontWeight: @bold;          // Card header weight
@cardMetaFontSize: 1em;                // Card meta size
@cardDescriptionFontSize: 1em;         // Card description size

// Table Typography
@tableHeaderFontWeight: @bold;         // Table header weight
@tableCellFontSize: 1em;               // Table cell size
@tableHeaderBackground: #f9fafb;       // Table header background

// Modal Typography
@modalHeaderFontSize: 1.42857143rem;   // Modal header size
@modalHeaderFontWeight: @bold;         // Modal header weight
@modalContentFontSize: 1rem;           // Modal content size
```

---

## Design System Typography Scale

### Primary Typography Scale (Modular Scale 1.25)

```less
// Design System Typography Variables
:root {
  // Base font settings
  --font-family-base: 'Inter', 'Segoe UI', 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
  --font-family-heading: 'Inter', 'Segoe UI', 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
  --font-family-mono: 'JetBrains Mono', 'Fira Code', 'Monaco', 'Consolas', monospace;
  
  // Font weight scale
  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  --font-weight-extrabold: 800;
  
  // Primary font size scale (1.25 ratio modular scale)
  --font-size-xs: 0.75rem;             // 12px - Very small text
  --font-size-sm: 0.875rem;            // 14px - Small text
  --font-size-base: 1rem;              // 16px - Body text (default)
  --font-size-lg: 1.125rem;            // 18px - Large body text
  --font-size-xl: 1.25rem;             // 20px - Emphasis text
  --font-size-2xl: 1.5rem;             // 24px - Large headings
  --font-size-3xl: 1.875rem;           // 30px - Display headings
  --font-size-4xl: 2.25rem;            // 36px - Hero headings
  --font-size-5xl: 3rem;               // 48px - Major headings
  --font-size-6xl: 3.75rem;            // 60px - Display text
  --font-size-7xl: 4.5rem;             // 72px - Hero display
  
  // Line height scale (optimized for readability)
  --line-height-none: 1;               // Tight line height
  --line-height-tight: 1.25;           // Headlines
  --line-height-snug: 1.375;           // Large text
  --line-height-normal: 1.5;           // Body text
  --line-height-relaxed: 1.625;        // Comfortable reading
  --line-height-loose: 2;              // Spacious text
  
  // Letter spacing scale
  --letter-spacing-tighter: -0.05em;   // Very tight
  --letter-spacing-tight: -0.025em;    // Tight
  --letter-spacing-normal: 0;          // Normal
  --letter-spacing-wide: 0.025em;      // Wide
  --letter-spacing-wider: 0.05em;      // Wider
  --letter-spacing-widest: 0.1em;      // Widest
  
  // Text colors
  --text-primary: #1a1a1a;             // Primary text (90% black)
  --text-secondary: #666666;           // Secondary text (60% black)
  --text-tertiary: #999999;            // Tertiary text (40% black)
  --text-disabled: #cccccc;            // Disabled text (20% black)
  --text-inverse: #ffffff;             // White text for dark backgrounds
  --text-link: #0066cc;                // Link color
  --text-link-hover: #0052a3;          // Link hover color
  --text-success: #10b981;             // Success text
  --text-warning: #f59e0b;             // Warning text
  --text-error: #ef4444;               // Error text
}
```

### Semantic Typography Categories

```less
// Heading Typography
:root {
  // Display headings (for hero sections and marketing)
  --heading-display-size: var(--font-size-6xl);      // 60px
  --heading-display-weight: var(--font-weight-extrabold);
  --heading-display-line-height: var(--line-height-tight);
  --heading-display-letter-spacing: var(--letter-spacing-tight);
  
  // Primary headings (page titles)
  --heading-primary-size: var(--font-size-4xl);      // 36px
  --heading-primary-weight: var(--font-weight-bold);
  --heading-primary-line-height: var(--line-height-tight);
  --heading-primary-letter-spacing: var(--letter-spacing-tight);
  
  // Secondary headings (section titles)
  --heading-secondary-size: var(--font-size-2xl);    // 24px
  --heading-secondary-weight: var(--font-weight-semibold);
  --heading-secondary-line-height: var(--line-height-snug);
  --heading-secondary-letter-spacing: var(--letter-spacing-normal);
  
  // Tertiary headings (subsection titles)
  --heading-tertiary-size: var(--font-size-xl);      // 20px
  --heading-tertiary-weight: var(--font-weight-semibold);
  --heading-tertiary-line-height: var(--line-height-snug);
  --heading-tertiary-letter-spacing: var(--letter-spacing-normal);
}

// Body Typography
:root {
  // Body text (primary content)
  --body-size: var(--font-size-base);                // 16px
  --body-weight: var(--font-weight-normal);
  --body-line-height: var(--line-height-normal);
  --body-letter-spacing: var(--letter-spacing-normal);
  
  // Large body text (emphasis content)
  --body-large-size: var(--font-size-lg);            // 18px
  --body-large-weight: var(--font-weight-normal);
  --body-large-line-height: var(--line-height-relaxed);
  --body-large-letter-spacing: var(--letter-spacing-normal);
  
  // Small body text (secondary content)
  --body-small-size: var(--font-size-sm);            // 14px
  --body-small-weight: var(--font-weight-normal);
  --body-small-line-height: var(--line-height-normal);
  --body-small-letter-spacing: var(--letter-spacing-normal);
}

// UI Typography
:root {
  // Button text
  --button-size: var(--font-size-sm);                // 14px
  --button-weight: var(--font-weight-medium);
  --button-line-height: var(--line-height-none);
  --button-letter-spacing: var(--letter-spacing-wide);
  --button-text-transform: uppercase;
  
  // Input text
  --input-size: var(--font-size-base);               // 16px
  --input-weight: var(--font-weight-normal);
  --input-line-height: var(--line-height-snug);
  --input-letter-spacing: var(--letter-spacing-normal);
  
  // Label text
  --label-size: var(--font-size-sm);                 // 14px
  --label-weight: var(--font-weight-medium);
  --label-line-height: var(--line-height-snug);
  --label-letter-spacing: var(--letter-spacing-normal);
  
  // Caption text
  --caption-size: var(--font-size-xs);               // 12px
  --caption-weight: var(--font-weight-normal);
  --caption-line-height: var(--line-height-normal);
  --caption-letter-spacing: var(--letter-spacing-normal);
}
```

---

## Component Typography Patterns

### Header Component Typography

```less
.app-header {
  font-family: var(--font-family-base);
  
  .header-logo {
    font-size: var(--font-size-xl);                  // 20px
    font-weight: var(--font-weight-bold);
    color: var(--text-primary);
    text-decoration: none;
    letter-spacing: var(--letter-spacing-tight);
    
    &:hover {
      color: var(--text-link);
    }
  }
  
  .breadcrumb-nav {
    font-size: var(--font-size-sm);                  // 14px
    font-weight: var(--font-weight-normal);
    color: var(--text-secondary);
    
    a {
      color: var(--text-link);
      text-decoration: none;
      
      &:hover {
        color: var(--text-link-hover);
        text-decoration: underline;
      }
    }
    
    .breadcrumb-separator {
      color: var(--text-tertiary);
      margin: 0 0.5rem;
    }
  }
  
  .search-placeholder {
    font-size: var(--font-size-sm);                  // 14px
    font-weight: var(--font-weight-normal);
    color: var(--text-tertiary);
  }
}
```

### Card Component Typography

```less
.ui.card {
  font-family: var(--font-family-base);
  
  .header {
    font-size: var(--heading-tertiary-size);         // 20px
    font-weight: var(--heading-tertiary-weight);
    color: var(--text-primary);
    line-height: var(--heading-tertiary-line-height);
    letter-spacing: var(--heading-tertiary-letter-spacing);
    margin-bottom: 0.5rem;
  }
  
  .meta {
    font-size: var(--caption-size);                  // 12px
    font-weight: var(--caption-weight);
    color: var(--text-tertiary);
    line-height: var(--caption-line-height);
    text-transform: uppercase;
    letter-spacing: var(--letter-spacing-wide);
  }
  
  .description {
    font-size: var(--body-size);                     // 16px
    font-weight: var(--body-weight);
    color: var(--text-secondary);
    line-height: var(--body-line-height);
    letter-spacing: var(--body-letter-spacing);
  }
  
  .extra.content {
    font-size: var(--body-small-size);               // 14px
    color: var(--text-secondary);
    line-height: var(--body-small-line-height);
  }
}
```

---

## Semantic Typography Rules

### Heading Hierarchy

```less
// Semantic heading styles following the design system
h1, .h1 {
  font-size: var(--heading-primary-size);            // 36px
  font-weight: var(--heading-primary-weight);
  line-height: var(--heading-primary-line-height);
  letter-spacing: var(--heading-primary-letter-spacing);
  color: var(--text-primary);
  margin: 0 0 1rem 0;
  font-family: var(--font-family-heading);
}

h2, .h2 {
  font-size: var(--heading-secondary-size);          // 24px
  font-weight: var(--heading-secondary-weight);
  line-height: var(--heading-secondary-line-height);
  letter-spacing: var(--heading-secondary-letter-spacing);
  color: var(--text-primary);
  margin: 2rem 0 1rem 0;
  font-family: var(--font-family-heading);
}

h3, .h3 {
  font-size: var(--heading-tertiary-size);           // 20px
  font-weight: var(--heading-tertiary-weight);
  line-height: var(--heading-tertiary-line-height);
  letter-spacing: var(--heading-tertiary-letter-spacing);
  color: var(--text-primary);
  margin: 1.5rem 0 0.75rem 0;
  font-family: var(--font-family-heading);
}

h4, .h4 {
  font-size: var(--font-size-lg);                    // 18px
  font-weight: var(--font-weight-semibold);
  line-height: var(--line-height-snug);
  color: var(--text-primary);
  margin: 1.25rem 0 0.5rem 0;
  font-family: var(--font-family-heading);
}

h5, .h5 {
  font-size: var(--font-size-base);                  // 16px
  font-weight: var(--font-weight-semibold);
  line-height: var(--line-height-snug);
  color: var(--text-primary);
  margin: 1rem 0 0.5rem 0;
  font-family: var(--font-family-heading);
}

h6, .h6 {
  font-size: var(--font-size-sm);                    // 14px
  font-weight: var(--font-weight-semibold);
  line-height: var(--line-height-snug);
  color: var(--text-secondary);
  margin: 1rem 0 0.5rem 0;
  text-transform: uppercase;
  letter-spacing: var(--letter-spacing-wide);
  font-family: var(--font-family-heading);
}
```

### Text Emphasis and Modifiers

```less
// Text emphasis styles
.text-emphasis {
  font-weight: var(--font-weight-medium);
  color: var(--text-primary);
}

.text-strong, strong, b {
  font-weight: var(--font-weight-bold);
  color: var(--text-primary);
}

.text-light {
  font-weight: var(--font-weight-light);
}

.text-italic, em, i {
  font-style: italic;
}

// Text color variations
.text-primary {
  color: var(--text-primary) !important;
}

.text-secondary {
  color: var(--text-secondary) !important;
}

.text-tertiary {
  color: var(--text-tertiary) !important;
}

.text-disabled {
  color: var(--text-disabled) !important;
}

.text-link {
  color: var(--text-link) !important;
}

.text-success {
  color: var(--text-success) !important;
}

.text-warning {
  color: var(--text-warning) !important;
}

.text-error {
  color: var(--text-error) !important;
}
```

---

## Utility Classes

### Font Size Utilities

```less
// Font size utility classes following the typography scale
.text-xs { font-size: var(--font-size-xs) !important; }     // 12px
.text-sm { font-size: var(--font-size-sm) !important; }     // 14px
.text-base { font-size: var(--font-size-base) !important; } // 16px
.text-lg { font-size: var(--font-size-lg) !important; }     // 18px
.text-xl { font-size: var(--font-size-xl) !important; }     // 20px
.text-2xl { font-size: var(--font-size-2xl) !important; }   // 24px
.text-3xl { font-size: var(--font-size-3xl) !important; }   // 30px
.text-4xl { font-size: var(--font-size-4xl) !important; }   // 36px
.text-5xl { font-size: var(--font-size-5xl) !important; }   // 48px
.text-6xl { font-size: var(--font-size-6xl) !important; }   // 60px
.text-7xl { font-size: var(--font-size-7xl) !important; }   // 72px
```

### Font Weight Utilities

```less
// Font weight utility classes
.font-light { font-weight: var(--font-weight-light) !important; }       // 300
.font-normal { font-weight: var(--font-weight-normal) !important; }     // 400
.font-medium { font-weight: var(--font-weight-medium) !important; }     // 500
.font-semibold { font-weight: var(--font-weight-semibold) !important; } // 600
.font-bold { font-weight: var(--font-weight-bold) !important; }         // 700
.font-extrabold { font-weight: var(--font-weight-extrabold) !important; } // 800
```

### Line Height Utilities

```less
// Line height utility classes
.leading-none { line-height: var(--line-height-none) !important; }       // 1
.leading-tight { line-height: var(--line-height-tight) !important; }     // 1.25
.leading-snug { line-height: var(--line-height-snug) !important; }       // 1.375
.leading-normal { line-height: var(--line-height-normal) !important; }   // 1.5
.leading-relaxed { line-height: var(--line-height-relaxed) !important; } // 1.625
.leading-loose { line-height: var(--line-height-loose) !important; }     // 2
```

---

## Responsive Typography

### Mobile-First Typography Scale

```less
// Base typography (mobile)
body {
  font-size: var(--font-size-sm);                    // 14px on mobile
  line-height: var(--line-height-normal);
}

h1, .h1 {
  font-size: var(--font-size-2xl);                   // 24px on mobile
}

h2, .h2 {
  font-size: var(--font-size-xl);                    // 20px on mobile
}

// Tablet typography adjustments
@media (min-width: 768px) {
  body {
    font-size: var(--font-size-base);                // 16px on tablet
  }
  
  h1, .h1 {
    font-size: var(--font-size-3xl);                 // 30px on tablet
  }
  
  h2, .h2 {
    font-size: var(--font-size-2xl);                 // 24px on tablet
  }
}

// Desktop typography adjustments
@media (min-width: 1024px) {
  h1, .h1 {
    font-size: var(--heading-primary-size);          // 36px on desktop
  }
  
  h2, .h2 {
    font-size: var(--heading-secondary-size);        // 24px on desktop
  }
}
```

---

## Best Practices

### 1. Use Semantic Typography Variables

```less
// ✅ Good - Semantic and maintainable
.page-title {
  font-size: var(--heading-primary-size);
  font-weight: var(--heading-primary-weight);
  line-height: var(--heading-primary-line-height);
}

// ❌ Avoid - Hard to maintain
.page-title {
  font-size: 36px;
  font-weight: 700;
  line-height: 1.25;
}
```

### 2. Maintain Consistent Hierarchy

```less
// ✅ Good - Clear hierarchy
.article {
  .article-title {
    font-size: var(--heading-primary-size);          // 36px
    margin-bottom: 1rem;
  }
  
  .section-title {
    font-size: var(--heading-secondary-size);        // 24px
    margin: 2rem 0 1rem 0;
  }
  
  .body-text {
    font-size: var(--body-size);                     // 16px
    line-height: var(--body-line-height);
    margin-bottom: 1rem;
  }
}
```

### 3. Optimize for Accessibility

```less
// ✅ Good - Accessible typography
.accessible-text {
  font-size: var(--font-size-base);                  // Minimum 16px
  line-height: var(--line-height-normal);            // 1.5 minimum
  color: var(--text-primary);                        // High contrast
  max-width: 65ch;                                    // Optimal line length
}
```

---

## Quick Reference Guide

### Typography Scale Quick Reference

| Variable | Size | Usage |
|----------|------|-------|
| `--font-size-xs` | 12px | Captions, badges, timestamps |
| `--font-size-sm` | 14px | Labels, small text, UI elements |
| `--font-size-base` | 16px | **Body text** - primary content |
| `--font-size-lg` | 18px | Large body text, emphasis |
| `--font-size-xl` | 20px | Tertiary headings, large UI text |
| `--font-size-2xl` | 24px | Secondary headings |
| `--font-size-3xl` | 30px | Large headings, statistics |
| `--font-size-4xl` | 36px | **Primary headings** - page titles |
| `--font-size-5xl` | 48px | Hero headings |
| `--font-size-6xl` | 60px | **Display headings** - marketing |

### Font Weight Quick Reference

| Variable | Weight | Usage |
|----------|--------|-------|
| `--font-weight-light` | 300 | Subtle text, decorative |
| `--font-weight-normal` | 400 | **Body text** - primary content |
| `--font-weight-medium` | 500 | **Emphasis** - important text |
| `--font-weight-semibold` | 600 | **Headings** - section titles |
| `--font-weight-bold` | 700 | **Strong emphasis** - primary headings |
| `--font-weight-extrabold` | 800 | Display text, hero elements |

### Component Typography Patterns

| Component | Font Size | Font Weight | Line Height |
|-----------|-----------|-------------|-------------|
| Page Titles | `--font-size-4xl` | `--font-weight-bold` | `--line-height-tight` |
| Section Titles | `--font-size-2xl` | `--font-weight-semibold` | `--line-height-snug` |
| Body Text | `--font-size-base` | `--font-weight-normal` | `--line-height-normal` |
| Button Text | `--font-size-sm` | `--font-weight-medium` | `--line-height-none` |
| Input Text | `--font-size-base` | `--font-weight-normal` | `--line-height-snug` |
| Labels | `--font-size-sm` | `--font-weight-medium` | `--line-height-snug` |
| Captions | `--font-size-xs` | `--font-weight-normal` | `--line-height-normal` |

---

## Implementation Checklist

### ✅ Setup Checklist
- [ ] Define CSS custom properties for typography scale
- [ ] Create semantic typography aliases
- [ ] Set up utility classes for font sizes, weights, and line heights
- [ ] Configure responsive typography breakpoints
- [ ] Establish component-specific typography patterns
- [ ] Set up font loading optimization

### ✅ Usage Checklist
- [ ] Use variables instead of hardcoded values
- [ ] Maintain consistent hierarchy across components
- [ ] Apply progressive typography enhancement
- [ ] Ensure accessibility compliance
- [ ] Test typography across all device sizes
- [ ] Optimize font loading performance

### ✅ Quality Assurance
- [ ] Visual hierarchy consistency across components
- [ ] Proper typography scaling (mobile to desktop)
- [ ] Accessibility compliance (contrast, size, line height)
- [ ] Reading experience optimization
- [ ] Performance optimization (font loading, CSS output)
- [ ] Cross-browser compatibility testing

This typography system ensures visual consistency, excellent readability, and scalable design across your entire FrameNet application while working seamlessly with Fomantic-UI's component system and maintaining optimal accessibility standards.