# LESS Design System - Complete Implementation

Complete implementation of the design system using LESS syntax with tokens, mixins, and utilities.

---

## File Structure

```
src/styles/
├── design-system/
│   ├── tokens/
│   │   ├── _index.less                 # Main tokens export
│   │   ├── _root-variables.less        # CSS custom properties + LESS vars
│   │   ├── _spacing-tokens.less        # Spacing system + mixins
│   │   ├── _typography-tokens.less     # Typography system + mixins
│   │   ├── _color-tokens.less          # Color system + mixins
│   │   ├── _component-tokens.less      # Component tokens + mixins
│   │   └── _utility-tokens.less        # Utility tokens + mixins
│   ├── components/
│   │   └── _components.less            # Main components file
│   └── utilities/
│       └── _utilities.less             # Main utilities file
├── fomantic-ui/
│   ├── site.variables                  # Updated to use design tokens
│   └── semantic.less
├── layout/
│   └── _layout.less                    # Application layout
├── base/
│   └── _reset.less                     # CSS reset
└── main.less                           # Main entry point
```

---

## 1. Tokens Index File

### File: `src/styles/design-system/tokens/_index.less`

```less
//******************************
//    Design System Tokens
//    Barrel Export File
//******************************

/*
  This file serves as the single entry point for all design tokens.
  It imports and re-exports all token files in the correct order.
  
  Import order is important:
  1. Root variables (foundation)
  2. Spacing tokens (layout foundation)
  3. Typography tokens (text foundation)
  4. Color tokens (visual foundation)
  5. Component tokens (composed tokens)
  6. Utility tokens (helpers)
*/

// ===== FOUNDATION TOKENS =====
// Core CSS custom properties - must be imported first
@import './_root-variables.less';

// ===== SPACING TOKENS =====
// Spacing scale, semantic spacing, component spacing
@import './_spacing-tokens.less';

// ===== TYPOGRAPHY TOKENS =====
// Font families, sizes, weights, line heights, semantic typography
@import './_typography-tokens.less';

// ===== COLOR TOKENS =====
// Color scales, semantic colors, theme colors
@import './_color-tokens.less';

// ===== COMPONENT TOKENS =====
// Composed tokens for specific components
@import './_component-tokens.less';

// ===== UTILITY TOKENS =====
// Shadows, borders, transitions, z-index
@import './_utility-tokens.less';

/*
  All tokens are now available as both CSS custom properties and LESS variables.
  Example usage:
  
  .my-component {
    padding: @spacing-4;                    // LESS variable
    padding: var(--spacing-4);             // CSS custom property
    font-size: var(--font-size-base);
    color: var(--text-primary);
    background: var(--bg-primary);
  }
*/
```

---

## 2. Root Variables Foundation

### File: `src/styles/design-system/tokens/_root-variables.less`

```less
//******************************
//    Root Variables Foundation
//******************************

// Define CSS custom properties first
:root {
  /* ===== SPACING SYSTEM ===== */
  
  /* Base spacing scale (8px grid) */
  --spacing-0: 0;
  --spacing-1: 0.25rem;    /* 4px */
  --spacing-2: 0.5rem;     /* 8px */
  --spacing-3: 0.75rem;    /* 12px */
  --spacing-4: 1rem;       /* 16px */
  --spacing-5: 1.25rem;    /* 20px */
  --spacing-6: 1.5rem;     /* 24px */
  --spacing-8: 2rem;       /* 32px */
  --spacing-10: 2.5rem;    /* 40px */
  --spacing-12: 3rem;      /* 48px */
  --spacing-16: 4rem;      /* 64px */
  --spacing-20: 5rem;      /* 80px */
  --spacing-24: 6rem;      /* 96px */
  
  /* Semantic spacing aliases */
  --spacing-xs: var(--spacing-1);
  --spacing-sm: var(--spacing-2);
  --spacing-md: var(--spacing-4);
  --spacing-lg: var(--spacing-6);
  --spacing-xl: var(--spacing-8);
  --spacing-2xl: var(--spacing-12);
  
  /* Component spacing */
  --component-padding: var(--spacing-4);
  --component-margin: var(--spacing-4);
  --component-gap: var(--spacing-4);
  --layout-padding: var(--spacing-8);
  --layout-margin: var(--spacing-8);
  --layout-gap: var(--spacing-6);
  
  /* ===== TYPOGRAPHY SYSTEM ===== */
  
  /* Font families */
  --font-family-base: 'Inter', 'Segoe UI', 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif;
  --font-family-heading: var(--font-family-base);
  --font-family-mono: 'JetBrains Mono', 'Fira Code', 'Monaco', 'Consolas', monospace;
  
  /* Font weights */
  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  --font-weight-extrabold: 800;
  
  /* Font sizes (1.25 modular scale) */
  --font-size-xs: 0.75rem;     /* 12px */
  --font-size-sm: 0.875rem;    /* 14px */
  --font-size-base: 1rem;      /* 16px */
  --font-size-lg: 1.125rem;    /* 18px */
  --font-size-xl: 1.25rem;     /* 20px */
  --font-size-2xl: 1.5rem;     /* 24px */
  --font-size-3xl: 1.875rem;   /* 30px */
  --font-size-4xl: 2.25rem;    /* 36px */
  --font-size-5xl: 3rem;       /* 48px */
  --font-size-6xl: 3.75rem;    /* 60px */
  --font-size-7xl: 4.5rem;     /* 72px */
  
  /* Line heights */
  --line-height-none: 1;
  --line-height-tight: 1.25;
  --line-height-snug: 1.375;
  --line-height-normal: 1.5;
  --line-height-relaxed: 1.625;
  --line-height-loose: 2;
  
  /* Letter spacing */
  --letter-spacing-tighter: -0.05em;
  --letter-spacing-tight: -0.025em;
  --letter-spacing-normal: 0;
  --letter-spacing-wide: 0.025em;
  --letter-spacing-wider: 0.05em;
  --letter-spacing-widest: 0.1em;
  
  /* ===== COLOR SYSTEM ===== */
  
  /* Neutral colors */
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-400: #9ca3af;
  --gray-500: #6b7280;
  --gray-600: #4b5563;
  --gray-700: #374151;
  --gray-800: #1f2937;
  --gray-900: #111827;
  
  /* Text colors */
  --text-primary: var(--gray-900);
  --text-secondary: var(--gray-600);
  --text-tertiary: var(--gray-500);
  --text-disabled: var(--gray-400);
  --text-inverse: #ffffff;
  
  /* Brand colors */
  --primary-50: #eff6ff;
  --primary-100: #dbeafe;
  --primary-500: #3b82f6;
  --primary-600: #2563eb;
  --primary-700: #1d4ed8;
  --primary-900: #1e3a8a;
  
  /* Semantic colors */
  --success-100: #dcfce7;
  --success-500: #22c55e;
  --success-600: #16a34a;
  
  --warning-100: #fef3c7;
  --warning-500: #f59e0b;
  --warning-600: #d97706;
  
  --error-100: #fee2e2;
  --error-500: #ef4444;
  --error-600: #dc2626;
  
  /* Semantic aliases */
  --color-primary: var(--primary-500);
  --color-primary-hover: var(--primary-600);
  --color-success: var(--success-500);
  --color-warning: var(--warning-500);
  --color-error: var(--error-500);
  
  --text-link: var(--primary-600);
  --text-link-hover: var(--primary-700);
  --text-success: var(--success-600);
  --text-warning: var(--warning-600);
  --text-error: var(--error-600);
  
  /* Background colors */
  --bg-primary: #ffffff;
  --bg-secondary: var(--gray-50);
  --bg-tertiary: var(--gray-100);
  
  /* Border colors */
  --border-color: var(--gray-200);
  --border-color-hover: var(--gray-300);
  --border-color-focus: var(--primary-500);
  
  /* ===== UTILITY TOKENS ===== */
  
  /* Shadows */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow-md: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --shadow-xl: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  
  /* Border radius */
  --radius-none: 0;
  --radius-sm: 0.25rem;
  --radius-md: 0.375rem;
  --radius-lg: 0.5rem;
  --radius-xl: 0.75rem;
  --radius-2xl: 1rem;
  --radius-full: 9999px;
  
  /* Transitions */
  --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
  --transition-normal: 250ms cubic-bezier(0.4, 0, 0.2, 1);
  --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
  
  /* Z-index */
  --z-dropdown: 1000;
  --z-sticky: 1020;
  --z-fixed: 1030;
  --z-modal-backdrop: 1040;
  --z-modal: 1050;
  --z-popover: 1060;
  --z-tooltip: 1070;
}

// Convert CSS custom properties to LESS variables for easier usage
// This allows you to use both @variable and var(--variable) syntax

// ===== SPACING VARIABLES =====
@spacing-0: ~'var(--spacing-0)';
@spacing-1: ~'var(--spacing-1)';
@spacing-2: ~'var(--spacing-2)';
@spacing-3: ~'var(--spacing-3)';
@spacing-4: ~'var(--spacing-4)';
@spacing-5: ~'var(--spacing-5)';
@spacing-6: ~'var(--spacing-6)';
@spacing-8: ~'var(--spacing-8)';
@spacing-10: ~'var(--spacing-10)';
@spacing-12: ~'var(--spacing-12)';
@spacing-16: ~'var(--spacing-16)';
@spacing-20: ~'var(--spacing-20)';
@spacing-24: ~'var(--spacing-24)';

// Semantic spacing
@spacing-xs: @spacing-1;
@spacing-sm: @spacing-2;
@spacing-md: @spacing-4;
@spacing-lg: @spacing-6;
@spacing-xl: @spacing-8;
@spacing-2xl: @spacing-12;

// ===== TYPOGRAPHY VARIABLES =====
@font-family-base: ~'var(--font-family-base)';
@font-family-heading: ~'var(--font-family-heading)';
@font-family-mono: ~'var(--font-family-mono)';

@font-weight-light: ~'var(--font-weight-light)';
@font-weight-normal: ~'var(--font-weight-normal)';
@font-weight-medium: ~'var(--font-weight-medium)';
@font-weight-semibold: ~'var(--font-weight-semibold)';
@font-weight-bold: ~'var(--font-weight-bold)';
@font-weight-extrabold: ~'var(--font-weight-extrabold)';

@font-size-xs: ~'var(--font-size-xs)';
@font-size-sm: ~'var(--font-size-sm)';
@font-size-base: ~'var(--font-size-base)';
@font-size-lg: ~'var(--font-size-lg)';
@font-size-xl: ~'var(--font-size-xl)';
@font-size-2xl: ~'var(--font-size-2xl)';
@font-size-3xl: ~'var(--font-size-3xl)';
@font-size-4xl: ~'var(--font-size-4xl)';
@font-size-5xl: ~'var(--font-size-5xl)';
@font-size-6xl: ~'var(--font-size-6xl)';
@font-size-7xl: ~'var(--font-size-7xl)';

@line-height-none: ~'var(--line-height-none)';
@line-height-tight: ~'var(--line-height-tight)';
@line-height-snug: ~'var(--line-height-snug)';
@line-height-normal: ~'var(--line-height-normal)';
@line-height-relaxed: ~'var(--line-height-relaxed)';
@line-height-loose: ~'var(--line-height-loose)';

// ===== COLOR VARIABLES =====
@gray-50: ~'var(--gray-50)';
@gray-100: ~'var(--gray-100)';
@gray-200: ~'var(--gray-200)';
@gray-300: ~'var(--gray-300)';
@gray-400: ~'var(--gray-400)';
@gray-500: ~'var(--gray-500)';
@gray-600: ~'var(--gray-600)';
@gray-700: ~'var(--gray-700)';
@gray-800: ~'var(--gray-800)';
@gray-900: ~'var(--gray-900)';

@text-primary: ~'var(--text-primary)';
@text-secondary: ~'var(--text-secondary)';
@text-tertiary: ~'var(--text-tertiary)';
@text-disabled: ~'var(--text-disabled)';
@text-inverse: ~'var(--text-inverse)';

@primary-50: ~'var(--primary-50)';
@primary-100: ~'var(--primary-100)';
@primary-500: ~'var(--primary-500)';
@primary-600: ~'var(--primary-600)';
@primary-700: ~'var(--primary-700)';
@primary-900: ~'var(--primary-900)';

@color-primary: ~'var(--color-primary)';
@color-primary-hover: ~'var(--color-primary-hover)';
@color-success: ~'var(--color-success)';
@color-warning: ~'var(--color-warning)';
@color-error: ~'var(--color-error)';

@text-link: ~'var(--text-link)';
@text-link-hover: ~'var(--text-link-hover)';
@text-success: ~'var(--text-success)';
@text-warning: ~'var(--text-warning)';
@text-error: ~'var(--text-error)';

@bg-primary: ~'var(--bg-primary)';
@bg-secondary: ~'var(--bg-secondary)';
@bg-tertiary: ~'var(--bg-tertiary)';

@border-color: ~'var(--border-color)';
@border-color-hover: ~'var(--border-color-hover)';
@border-color-focus: ~'var(--border-color-focus)';

// ===== UTILITY VARIABLES =====
@shadow-sm: ~'var(--shadow-sm)';
@shadow-md: ~'var(--shadow-md)';
@shadow-lg: ~'var(--shadow-lg)';
@shadow-xl: ~'var(--shadow-xl)';

@radius-none: ~'var(--radius-none)';
@radius-sm: ~'var(--radius-sm)';
@radius-md: ~'var(--radius-md)';
@radius-lg: ~'var(--radius-lg)';
@radius-xl: ~'var(--radius-xl)';
@radius-2xl: ~'var(--radius-2xl)';
@radius-full: ~'var(--radius-full)';

@transition-fast: ~'var(--transition-fast)';
@transition-normal: ~'var(--transition-normal)';
@transition-slow: ~'var(--transition-slow)';

@z-dropdown: ~'var(--z-dropdown)';
@z-sticky: ~'var(--z-sticky)';
@z-fixed: ~'var(--z-fixed)';
@z-modal-backdrop: ~'var(--z-modal-backdrop)';
@z-modal: ~'var(--z-modal)';
@z-popover: ~'var(--z-popover)';
@z-tooltip: ~'var(--z-tooltip)';
```

---

## 3. Component Tokens with Mixins

### File: `src/styles/design-system/tokens/_component-tokens.less`

```less
//******************************
//    Component Tokens
//******************************

// LESS mixins for common patterns
.spacing-mixin() {
  // Padding mixins
  .padding-all(@size) {
    padding: @size;
  }
  
  .padding-x(@size) {
    padding-left: @size;
    padding-right: @size;
  }
  
  .padding-y(@size) {
    padding-top: @size;
    padding-bottom: @size;
  }
  
  // Margin mixins
  .margin-all(@size) {
    margin: @size;
  }
  
  .margin-x(@size) {
    margin-left: @size;
    margin-right: @size;
  }
  
  .margin-y(@size) {
    margin-top: @size;
    margin-bottom: @size;
  }
}

.typography-mixin() {
  // Heading mixins
  .heading-primary() {
    font-size: @font-size-4xl;
    font-weight: @font-weight-bold;
    line-height: @line-height-tight;
    font-family: @font-family-heading;
    color: @text-primary;
  }
  
  .heading-secondary() {
    font-size: @font-size-2xl;
    font-weight: @font-weight-semibold;
    line-height: @line-height-snug;
    font-family: @font-family-heading;
    color: @text-primary;
  }
  
  .heading-tertiary() {
    font-size: @font-size-xl;
    font-weight: @font-weight-semibold;
    line-height: @line-height-snug;
    font-family: @font-family-heading;
    color: @text-primary;
  }
  
  // Body text mixins
  .body-text() {
    font-size: @font-size-base;
    font-weight: @font-weight-normal;
    line-height: @line-height-normal;
    font-family: @font-family-base;
    color: @text-secondary;
  }
  
  .body-large() {
    font-size: @font-size-lg;
    font-weight: @font-weight-normal;
    line-height: @line-height-relaxed;
    font-family: @font-family-base;
    color: @text-secondary;
  }
  
  .body-small() {
    font-size: @font-size-sm;
    font-weight: @font-weight-normal;
    line-height: @line-height-normal;
    font-family: @font-family-base;
    color: @text-tertiary;
  }
  
  .caption-text() {
    font-size: @font-size-xs;
    font-weight: @font-weight-normal;
    line-height: @line-height-normal;
    font-family: @font-family-base;
    color: @text-tertiary;
  }
}

.component-mixin() {
  // Button component mixin
  .button-base() {
    font-size: @font-size-sm;
    font-weight: @font-weight-medium;
    line-height: @line-height-none;
    font-family: @font-family-base;
    padding: @spacing-2 @spacing-4;
    border-radius: @radius-md;
    border: 2px solid transparent;
    transition: @transition-fast;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    
    &:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
  }
  
  .button-primary() {
    .button-base();
    background-color: @color-primary;
    color: white;
    border-color: @color-primary;
    
    &:hover:not(:disabled) {
      background-color: @color-primary-hover;
      border-color: @color-primary-hover;
    }
  }
  
  .button-secondary() {
    .button-base();
    background-color: transparent;
    color: @text-secondary;
    border-color: @border-color;
    
    &:hover:not(:disabled) {
      background-color: @gray-100;
      border-color: @border-color-hover;
    }
  }
  
  // Input component mixin
  .input-base() {
    font-size: @font-size-base;
    font-weight: @font-weight-normal;
    font-family: @font-family-base;
    padding: @spacing-2 @spacing-3;
    border-radius: @radius-md;
    border: 2px solid @border-color;
    background-color: @bg-primary;
    color: @text-primary;
    transition: @transition-fast;
    width: 100%;
    min-height: 40px;
    
    &::placeholder {
      color: @text-tertiary;
    }
    
    &:hover {
      border-color: @border-color-hover;
    }
    
    &:focus {
      outline: none;
      border-color: @border-color-focus;
      box-shadow: 0 0 0 3px fade(@color-primary, 10%);
    }
    
    &:disabled {
      background-color: @gray-50;
      color: @text-disabled;
      cursor: not-allowed;
    }
  }
  
  // Card component mixin
  .card-base() {
    background-color: @bg-primary;
    border: 1px solid @border-color;
    border-radius: @radius-lg;
    padding: @spacing-6;
    box-shadow: @shadow-sm;
    transition: @transition-normal;
    
    &:hover {
      box-shadow: @shadow-md;
    }
  }
  
  // Badge component mixin
  .badge-base() {
    .caption-text();
    font-weight: @font-weight-bold;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: @spacing-1 @spacing-2;
    border-radius: @radius-full;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: @line-height-none;
    min-width: 20px;
    min-height: 20px;
  }
}

.utility-mixin() {
  // Flexbox mixins
  .flex-center() {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .flex-between() {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  
  .flex-column() {
    display: flex;
    flex-direction: column;
  }
  
  // Position mixins
  .absolute-center() {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
  
  // Container mixin
  .container(@max-width: 1200px) {
    width: 100%;
    max-width: @max-width;
    margin: 0 auto;
    padding: 0 @spacing-6;
    
    @media (max-width: 768px) {
      padding: 0 @spacing-4;
    }
  }
  
  // Responsive breakpoint mixins
  .mobile(@rules) {
    @media (max-width: 767px) {
      @rules();
    }
  }
  
  .tablet(@rules) {
    @media (min-width: 768px) and (max-width: 1023px) {
      @rules();
    }
  }
  
  .desktop(@rules) {
    @media (min-width: 1024px) {
      @rules();
    }
  }
  
  .mobile-up(@rules) {
    @media (min-width: 768px) {
      @rules();
    }
  }
}

// Make all mixins available globally
.spacing-mixin();
.typography-mixin();
.component-mixin();
.utility-mixin();
```

---

## 4. Main LESS Entry Point

### File: `src/styles/main.less`

```less
//******************************
//    Main LESS Entry Point
//    FrameNet Design System
//******************************

/*
  Import order is critical for the cascade:
  
  1. Design System Tokens (CSS Custom Properties + LESS Variables)
  2. CSS Resets and Normalize
  3. Fomantic-UI Framework
  4. Design System Components
  5. Utility Classes
  6. Layout System
  7. Project-specific Overrides
*/

// ===== 1. DESIGN SYSTEM FOUNDATION =====
// Import all design tokens first
@import 'design-system/tokens/_index.less';

// ===== 2. CSS RESET & NORMALIZE =====
@import 'base/_reset.less';

// ===== 3. FOMANTIC-UI FRAMEWORK =====
// Fomantic-UI framework - now configured to use our design tokens
@import 'fomantic-ui/semantic.less';

// ===== 4. DESIGN SYSTEM COMPONENTS =====
@import 'design-system/components/_components.less';

// ===== 5. UTILITY CLASSES =====
@import 'design-system/utilities/_utilities.less';

// ===== 6. LAYOUT SYSTEM =====
@import 'layout/_layout.less';

// ===== 7. PROJECT-SPECIFIC STYLES =====
@import 'project/_project.less';

// Global application styles
*, *::before, *::after {
  box-sizing: border-box;
}

html {
  font-size: 16px; // 1rem = 16px
  line-height: @line-height-normal;
  font-family: @font-family-base;
  color: @text-primary;
  background-color: @bg-primary;
  scroll-behavior: smooth;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

body {
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

#app, #root {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

// Focus management
:focus-visible {
  outline: 2px solid @border-color-focus;
  outline-offset: 2px;
}

// Selection styling
::selection {
  background-color: @primary-100;
  color: @text-primary;
}

// Reduced motion support
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```

---

## 5. Component Styles

### File: `src/styles/design-system/components/_components.less`

```less
//******************************
//    Design System Components
//******************************

// Global component overrides using mixins
.ui.button {
  .button-base();
  
  // Variants
  &.primary {
    .button-primary();
  }
  
  &.secondary {
    .button-secondary();
  }
  
  // Sizes
  &.small {
    font-size: @font-size-xs;
    padding: @spacing-1 @spacing-3;
    min-height: 32px;
  }
  
  &.large {
    font-size: @font-size-base;
    padding: @spacing-3 @spacing-6;
    min-height: 48px;
  }
}

.ui.card {
  .card-base();
  
  .header {
    .heading-tertiary();
    margin-bottom: @spacing-3;
  }
  
  .meta {
    .caption-text();
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: @spacing-2;
  }
  
  .description {
    .body-text();
  }
  
  .extra.content {
    .body-small();
    border-top: 1px solid @border-color;
    .padding-y(@spacing-3);
  }
}

.ui.input {
  input {
    .input-base();
  }
  
  &.error input {
    border-color: @color-error;
    box-shadow: 0 0 0 3px fade(@color-error, 10%);
  }
}

.ui.form {
  .field {
    margin-bottom: @spacing-4;
    
    label {
      .body-small();
      font-weight: @font-weight-medium;
      color: @text-primary;
      display: block;
      margin-bottom: @spacing-2;
      
      &.required::after {
        content: " *";
        color: @text-error;
      }
    }
    
    .help-text {
      .caption-text();
      font-style: italic;
      margin-top: @spacing-1;
    }
    
    .error-message {
      .caption-text();
      color: @text-error;
      font-weight: @font-weight-medium;
      margin-top: @spacing-1;
    }
  }
}
```

---

## 6. Utility Classes

### File: `src/styles/design-system/utilities/_utilities.less`

```less
//******************************
//    Design System Utilities
//******************************

// Generate spacing utilities using LESS loops
.generate-spacing-utilities() {
  @spacing-values: 0, 1, 2, 3, 4, 5, 6, 8, 10, 12, 16, 20, 24;
  
  each(@spacing-values, {
    // Margin utilities
    .m-@{value} { margin: ~'var(--spacing-@{value})' !important; }
    .mt-@{value} { margin-top: ~'var(--spacing-@{value})' !important; }
    .mb-@{value} { margin-bottom: ~'var(--spacing-@{value})' !important; }
    .ml-@{value} { margin-left: ~'var(--spacing-@{value})' !important; }
    .mr-@{value} { margin-right: ~'var(--spacing-@{value})' !important; }
    .mx-@{value} { 
      margin-left: ~'var(--spacing-@{value})' !important; 
      margin-right: ~'var(--spacing-@{value})' !important; 
    }
    .my-@{value} { 
      margin-top: ~'var(--spacing-@{value})' !important; 
      margin-bottom: ~'var(--spacing-@{value})' !important; 
    }
    
    // Padding utilities
    .p-@{value} { padding: ~'var(--spacing-@{value})' !important; }
    .pt-@{value} { padding-top: ~'var(--spacing-@{value})' !important; }
    .pb-@{value} { padding-bottom: ~'var(--spacing-@{value})' !important; }
    .pl-@{value} { padding-left: ~'var(--spacing-@{value})' !important; }
    .pr-@{value} { padding-right: ~'var(--spacing-@{value})' !important; }
    .px-@{value} { 
      padding-left: ~'var(--spacing-@{value})' !important; 
      padding-right: ~'var(--spacing-@{value})' !important; 
    }
    .py-@{value} { 
      padding-top: ~'var(--spacing-@{value})' !important; 
      padding-bottom: ~'var(--spacing-@{value})' !important; 
    }
    
    // Gap utilities
    .gap-@{value} { gap: ~'var(--spacing-@{value})' !important; }
    .gap-x-@{value} { column-gap: ~'var(--spacing-@{value})' !important; }
    .gap-y-@{value} { row-gap: ~'var(--spacing-@{value})' !important; }
  });
}

.generate-spacing-utilities();

// Generate typography utilities
.generate-typography-utilities() {
  @font-sizes: xs, sm, base, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl;
  @font-weights: light, normal, medium, semibold, bold, extrabold;
  
  each(@font-sizes, {
    .text-@{value} { font-size: ~'var(--font-size-@{value})' !important; }
  });
  
  each(@font-weights, {
    .font-@{value} { font-weight: ~'var(--font-weight-@{value})' !important; }
  });
}

.generate-typography-utilities();

// Display utilities
.show { display: block !important; }
.hide { display: none !important; }
.flex { display: flex !important; }
.grid { display: grid !important; }

// Flexbox utilities
.flex-row { flex-direction: row !important; }
.flex-col { flex-direction: column !important; }
.items-center { align-items: center !important; }
.justify-center { justify-content: center !important; }
.justify-between { justify-content: space-between !important; }

// Text utilities
.text-left { text-align: left !important; }
.text-center { text-align: center !important; }
.text-right { text-align: right !important; }

// Color utilities
.text-primary { color: @text-primary !important; }
.text-secondary { color: @text-secondary !important; }
.text-tertiary { color: @text-tertiary !important; }
.text-link { color: @text-link !important; }
.text-success { color: @text-success !important; }
.text-warning { color: @text-warning !important; }
.text-error { color: @text-error !important; }

// Background utilities
.bg-primary { background-color: @bg-primary !important; }
.bg-secondary { background-color: @bg-secondary !important; }

// Border radius utilities
.rounded-none { border-radius: @radius-none !important; }
.rounded-sm { border-radius: @radius-sm !important; }
.rounded { border-radius: @radius-md !important; }
.rounded-lg { border-radius: @radius-lg !important; }
.rounded-full { border-radius: @radius-full !important; }

// Shadow utilities
.shadow-sm { box-shadow: @shadow-sm !important; }
.shadow { box-shadow: @shadow-md !important; }
.shadow-lg { box-shadow: @shadow-lg !important; }
.shadow-xl { box-shadow: @shadow-xl !important; }

// Responsive utilities
.mobile({
  .hide-mobile { display: none !important; }
  .text-mobile-center { text-align: center !important; }
  .flex-mobile-col { flex-direction: column !important; }
});

.desktop({
  .hide-desktop { display: none !important; }
});
```

---

## 7. Layout System

### File: `src/styles/layout/_layout.less`

```less
//******************************
//    Application Layout
//******************************

// Application container
.app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  font-family: @font-family-base;
  color: @text-primary;
  background-color: @bg-primary;
}

// Header layout
.app-header {
  .flex-between();
  height: 64px;
  .padding-x(@spacing-6);
  .padding-y(@spacing-4);
  background-color: @bg-primary;
  border-bottom: 1px solid @border-color;
  position: sticky;
  top: 0;
  z-index: @z-sticky;
  
  .header-logo {
    .heading-tertiary();
    color: @text-primary;
    text-decoration: none;
    
    &:hover {
      color: @text-link;
    }
  }
  
  .header-nav {
    .flex-center();
    gap: @spacing-6;
  }
}

// Sidebar layout
.app-sidebar {
  width: 256px;
  background-color: @bg-primary;
  border-right: 1px solid @border-color;
  padding: @spacing-4;
  overflow-y: auto;
  flex-shrink: 0;
  
  .nav-item {
    .body-small();
    color: @text-secondary;
    text-decoration: none;
    padding: @spacing-3 @spacing-4;
    border-radius: @radius-md;
    display: flex;
    align-items: center;
    margin-bottom: @spacing-1;
    transition: @transition-fast;
    
    &:hover {
      color: @text-primary;
      background-color: @gray-100;
    }
    
    &.active {
      color: @text-link;
      background-color: @primary-50;
      font-weight: @font-weight-medium;
    }
    
    .nav-icon {
      margin-right: @spacing-3;
      width: 20px;
      height: 20px;
    }
  }
  
  .mobile({
    width: 100%;
    border-right: none;
    border-bottom: 1px solid @border-color;
  });
}

// Main content area
.app-main {
  display: flex;
  flex: 1;
  overflow: hidden;
  
  .mobile({
    flex-direction: column;
  });
}

// Content area
.app-content {
  flex: 1;
  padding: @spacing-8;
  overflow-y: auto;
  background-color: @bg-secondary;
  
  .mobile({
    padding: @spacing-4;
  });
}

// Container system
.container {
  .container();
  
  &.sm { .container(640px); }
  &.md { .container(768px); }
  &.lg { .container(1024px); }
  &.xl { .container(1280px); }
}

// Page layout
.page-header {
  margin-bottom: @spacing-8;
  padding-bottom: @spacing-6;
  border-bottom: 1px solid @border-color;
  
  .page-title {
    .heading-primary();
    margin: 0 0 @spacing-2 0;
  }
  
  .page-subtitle {
    .body-large();
    margin: 0;
  }
}

// Card grid layouts
.card-grid {
  display: grid;
  gap: @spacing-6;
  grid-template-columns: 1fr;
  
  .mobile-up({
    &.cols-2 { grid-template-columns: repeat(2, 1fr); }
  });
  
  .desktop({
    &.cols-3 { grid-template-columns: repeat(3, 1fr); }
    &.cols-4 { grid-template-columns: repeat(4, 1fr); }
  });
}
```

---

## 8. Fomantic-UI Integration

### File: `src/styles/fomantic-ui/site.variables`

```less
//******************************
//     User Global Variables
//******************************

// Import design system tokens first
@import '../design-system/tokens/_index.less';

// Map design tokens to Fomantic-UI variables
@mini: @spacing-1;                      // 4px
@tiny: @spacing-2;                      // 8px
@small: @spacing-3;                     // 12px
@medium: @spacing-4;                    // 16px
@large: @spacing-5;                     // 20px
@big: @spacing-6;                       // 24px
@huge: @spacing-8;                      // 32px
@massive: @spacing-10;                  // 40px

// Typography
@fontName: @font-family-base;
@fontSize: @font-size-sm;               // 14px base for Fomantic
@emSize: @font-size-sm;
@normal: @font-weight-normal;
@bold: @font-weight-bold;
@lineHeight: @line-height-normal;
@headerLineHeight: @line-height-tight;

// Headers
@h1: @font-size-4xl;                    // 36px
@h2: @font-size-2xl;                    // 24px
@h3: @font-size-xl;                     // 20px
@h4: @font-size-lg;                     // 18px
@h5: @font-size-base;                   // 16px
@h6: @font-size-sm;                     // 14px

// Colors
@textColor: @text-primary;
@mutedTextColor: @text-secondary;
@lightTextColor: @text-tertiary;
@linkColor: @text-link;
@linkHoverColor: @text-link-hover;

@pageBackground: @bg-primary;
@backgroundColor: @bg-secondary;
@borderColor: @border-color;

@primaryColor: @color-primary;
@red: @color-error;
@green: @color-success;
@orange: @color-warning;

// Component variables
@buttonFontSize: @font-size-sm;
@buttonFontWeight: @font-weight-medium;
@buttonBorderRadius: @radius-md;

@inputFontSize: @font-size-base;
@inputBorderRadius: @radius-md;

@cardPadding: @spacing-6;
@cardBorderRadius: @radius-lg;
@cardShadow: @shadow-sm;

// Responsive breakpoints
@mobileBreakpoint: 768px;
@tabletBreakpoint: 992px;
@computerBreakpoint: 1200px;
```

---

## Usage Examples

### Using LESS Variables and Mixins

```less
// Component using design system mixins
.my-custom-card {
  .card-base();                    // Apply card styling
  .padding-all(@spacing-6);        // Add padding
  
  .card-title {
    .heading-tertiary();           // Apply heading styles
    margin-bottom: @spacing-3;     // Use spacing token
  }
  
  .card-content {
    .body-text();                  // Apply body text styles
  }
  
  // Responsive behavior
  .mobile({
    .padding-all(@spacing-4);      // Less padding on mobile
  });
}
```

### Using Auto-Generated Utilities

```html
<!-- These classes are automatically generated -->
<div class="p-6 m-4 gap-3 text-lg font-semibold text-primary bg-secondary rounded-lg shadow">
  Content with utility classes
</div>
```

### Component Composition

```less
.custom-button {
  .button-base();                 // Base button styles
  .button-primary();              // Primary color scheme
  
  // Custom modifications
  text-transform: none;           // Override uppercase
  letter-spacing: normal;         // Override wide spacing
  
  &.large {
    font-size: @font-size-base;
    padding: @spacing-3 @spacing-6;
    min-height: 48px;
  }
}
```

## Benefits

✅ **Single Source of Truth** - All design decisions in CSS custom properties  
✅ **LESS Power** - Mixins, functions, loops, and nesting  
✅ **Fomantic-UI Integration** - Seamless token usage in components  
✅ **Auto-Generated Utilities** - Consistent utility classes from tokens  
✅ **Responsive Design** - Clean breakpoint mixins  
✅ **Developer Experience** - IntelliSense and compile-time optimizations  
✅ **Maintainability** - Easy to update and scale  

This complete LESS implementation gives you a powerful, maintainable design system that works seamlessly with Fomantic-UI!