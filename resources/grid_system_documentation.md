# Complete Grid System Documentation

A comprehensive guide to the CSS Grid and Flexbox system designed for modern CRUD applications with annotation capabilities.

---

## Table of Contents

1. [Main App Layout](#1-main-app-layout)
2. [Content Containers](#2-content-containers)
3. [Specialized Grids](#3-specialized-grids)
4. [Advanced Layouts](#4-advanced-layouts)
5. [Flexible Systems](#5-flexible-systems)
6. [Usage Examples](#usage-examples)
7. [Responsive Behavior](#responsive-behavior)
8. [Best Practices](#best-practices)

---

## 1. Main App Layout

The foundation of your application's structure using CSS Grid for precise layout control.

### `app-layout` - Main Application Grid Structure

**Purpose**: Creates the primary application shell with header, sidebar, main content, and tools panel.

**CSS Grid Areas**:
```css
grid-template-areas:
  "header header header"
  "sidebar main tools";
grid-template-columns: 280px 1fr 320px;
grid-template-rows: 60px 1fr;
```

**HTML Structure**:
```html
<div class="app-layout">
  <header class="app-header">...</header>
  <nav class="app-sidebar">...</nav>
  <main class="app-main">...</main>
  <aside class="app-tools">...</aside>
</div>
```

**Features**:
- Fixed header height (60px)
- Flexible main content area
- Sidebar width: 280px
- Tools panel width: 320px
- Full viewport height (100vh)

### Layout Variations

#### `no-tools` - Two-Panel Layout
**Purpose**: Removes the tools panel for simpler interfaces.

```css
grid-template-areas:
  "header header"
  "sidebar main";
grid-template-columns: 280px 1fr;
```

**Usage**:
```html
<div class="app-layout no-tools">
  <!-- Header and sidebar remain, tools panel hidden -->
</div>
```

**Best for**: Simple CRUD operations, list views, basic forms.

#### `no-sidebar` - Main + Tools Layout
**Purpose**: Removes sidebar for focused content with contextual tools.

```css
grid-template-areas:
  "header header"
  "main tools";
grid-template-columns: 1fr 320px;
```

**Usage**:
```html
<div class="app-layout no-sidebar">
  <!-- No sidebar, main content with tools panel -->
</div>
```

**Best for**: Document editing, content creation, detailed forms.

#### `minimal` - Single Panel Layout
**Purpose**: Maximum space for content, minimal UI.

```css
grid-template-areas:
  "header"
  "main";
grid-template-columns: 1fr;
```

**Usage**:
```html
<div class="app-layout minimal">
  <!-- Only header and main content -->
</div>
```

**Best for**: Reports, dashboards, full-screen content.

#### `fullscreen` - Annotation/Media Layout
**Purpose**: Specialized layout for video/image annotation with toolbar.

```css
grid-template-areas:
  "toolbar"
  "canvas";
grid-template-columns: 1fr;
grid-template-rows: 60px 1fr;
```

**Usage**:
```html
<div class="app-layout fullscreen">
  <div class="annotation-toolbar">...</div>
  <div class="annotation-canvas">...</div>
</div>
```

**Best for**: Video annotation, image editing, media review.

---

## 2. Content Containers

Responsive wrappers that control content width and spacing within the main content area.

### `content-container` - Responsive Content Wrapper

**Purpose**: Provides consistent content spacing and responsive width management.

**Default Settings**:
```css
max-width: 1200px;
margin: 0 auto;
padding: 2rem;
```

**HTML Usage**:
```html
<main class="app-main">
  <div class="content-container">
    <!-- Your content here -->
  </div>
</main>
```

### Width Variations

#### `wide` - Maximum Screen Usage
```css
max-width: 95%;
```
**Best for**: Data tables, dashboards, charts, galleries.

#### `narrow` - Focused Content
```css
max-width: 800px;
```
**Best for**: Forms, settings pages, simple content.

#### `reading` - Optimal Reading Width
```css
max-width: 65ch;
```
**Best for**: Articles, documentation, text-heavy content.

#### `fluid` - Full Width
```css
max-width: 100%;
padding: 1.5rem;
```
**Best for**: Edge-to-edge content, full-width components.

#### `compact` - Minimal Spacing
```css
padding: 1rem;
```
**Best for**: Dense layouts, embedded content.

**Example Usage**:
```html
<!-- Wide layout for data tables -->
<div class="content-container wide">
  <div class="table-container">...</div>
</div>

<!-- Reading-optimized for documentation -->
<div class="content-container reading">
  <article>...</article>
</div>
```

---

## 3. Specialized Grids

Purpose-built grid systems for specific content types and user interfaces.

### `crud-grid` - CRUD Operations Layout

**Purpose**: Flexible grid for Create, Read, Update, Delete operations.

**Default**: Single column
```css
grid-template-columns: 1fr;
gap: 1.5rem;
```

#### Variations

##### `with-sidebar` - Master-Detail View
```css
grid-template-columns: 300px 1fr;
gap: 2rem;
```

**HTML Structure**:
```html
<div class="crud-grid with-sidebar">
  <div class="sidebar-content">
    <!-- Filters, categories, quick actions -->
  </div>
  <div class="main-content">
    <!-- Data table or cards -->
  </div>
</div>
```

**Best for**: User management, product catalogs, content management.

##### `split-view` - Equal Panels
```css
grid-template-columns: 1fr 1fr;
gap: 2rem;
```

**Best for**: Comparison views, before/after, diff views.

##### `master-detail` - Proportional Panels
```css
grid-template-columns: 2fr 3fr;
gap: 2rem;
```

**Best for**: List + detail, email-style interfaces.

##### `three-panel` - Triple Layout
```css
grid-template-columns: 1fr 2fr 1fr;
gap: 1.5rem;
```

**Best for**: Complex workflows, multi-step processes.

### `dashboard-grid` - Dashboard Widgets

**Purpose**: Auto-fitting grid for dashboard widgets and metrics.

```css
grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
gap: 1.5rem;
margin: 1.5rem 0;
```

**HTML Structure**:
```html
<div class="dashboard-grid">
  <div class="widget"><!-- Chart --></div>
  <div class="widget"><!-- Stats --></div>
  <div class="widget"><!-- Activity --></div>
  <div class="widget"><!-- Progress --></div>
</div>
```

#### Variations

##### `dense` - Compact Widgets
```css
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 1rem;
```

##### `wide` - Large Widgets
```css
grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
gap: 2rem;
```

**Responsive Behavior**:
- **Desktop**: Multiple columns based on available space
- **Tablet**: 2-3 columns
- **Mobile**: Single column

### `card-grid` - Card-Based Layouts

**Purpose**: Grid system for card-based content display.

```css
grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
gap: 1rem;
```

**HTML Structure**:
```html
<div class="card-grid">
  <div class="ui card">
    <div class="content">
      <div class="header">User Name</div>
      <div class="description">User details...</div>
    </div>
  </div>
  <!-- More cards -->
</div>
```

#### Variations

##### `dense` - Compact Cards
```css
grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
gap: 0.75rem;
```

##### `wide-cards` - Large Cards
```css
grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
gap: 1.5rem;
```

##### `small-cards` - Mini Cards
```css
grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
gap: 1rem;
```

##### `single-row` - Horizontal Scroll
```css
grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
grid-template-rows: 1fr;
```

### `form-grid` - Form Layouts

**Purpose**: Responsive grid system for forms and input layouts.

**Default**: Single column
```css
grid-template-columns: 1fr;
gap: 1.5rem;
```

#### Variations

##### `two-column` - Paired Fields
```css
grid-template-columns: 1fr 1fr;
gap: 1rem 2rem;
```

**HTML Structure**:
```html
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
```

##### `three-column` - Triple Fields
```css
grid-template-columns: 1fr 1fr 1fr;
gap: 1rem 1.5rem;
```

##### `sidebar-form` - Form with Sidebar
```css
grid-template-columns: 200px 1fr;
gap: 2rem;
align-items: start;
```

**HTML Structure**:
```html
<div class="form-grid sidebar-form">
  <div class="form-sidebar">
    <!-- Navigation, help, context -->
  </div>
  <div class="form-content">
    <!-- Main form fields -->
  </div>
</div>
```

##### `auto-fit` - Dynamic Columns
```css
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 1.5rem;
```

### `stats-grid` - Metrics and Statistics

**Purpose**: Grid for displaying statistical information and KPIs.

```css
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 1.5rem;
margin-bottom: 2rem;
```

**HTML Structure**:
```html
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-value">1,247</div>
    <div class="stat-label">Total Users</div>
    <div class="stat-change positive">+12%</div>
  </div>
  <!-- More stats -->
</div>
```

#### Variations

##### `large-stats` - Big Metrics
```css
grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
gap: 2rem;
```

##### `small-stats` - Compact Metrics
```css
grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
gap: 1rem;
```

##### `two-column` / `four-column` - Fixed Columns
```css
grid-template-columns: 1fr 1fr; /* or repeat(4, 1fr) */
```

### `gallery-grid` - Image/Video Galleries

**Purpose**: Grid system for media galleries with aspect ratio control.

```css
grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
gap: 1rem;
```

**HTML Structure**:
```html
<div class="gallery-grid">
  <div class="gallery-item">
    <img src="image1.jpg" alt="Description">
  </div>
  <div class="gallery-item">
    <video poster="thumbnail.jpg">
      <source src="video1.mp4" type="video/mp4">
    </video>
  </div>
</div>
```

#### Variations

##### `large` - Big Thumbnails
```css
grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
gap: 1.5rem;
```

##### `small` - Compact Thumbnails
```css
grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
gap: 0.75rem;
```

##### `square` - Forced Aspect Ratio
```css
grid-auto-rows: 1fr;

.gallery-item {
  aspect-ratio: 1;
}
```

---

## 4. Advanced Layouts

Complex layout systems for specialized use cases and advanced interfaces.

### `split-layout` - Comparison Views

**Purpose**: Side-by-side comparison interfaces with equal or proportional panels.

#### `horizontal` - Side-by-Side
```css
grid-template-columns: 1fr 1fr;
grid-template-rows: 1fr;
```

**HTML Structure**:
```html
<div class="split-layout horizontal">
  <div class="split-panel">
    <!-- Left content -->
  </div>
  <div class="split-panel">
    <!-- Right content -->
  </div>
</div>
```

#### `vertical` - Top-Bottom
```css
grid-template-columns: 1fr;
grid-template-rows: 1fr 1fr;
```

#### `thirds` - Three Panels
```css
grid-template-columns: 1fr 1fr 1fr;
```

#### `golden` - Golden Ratio
```css
grid-template-columns: 1.618fr 1fr;
```

**Best for**: Document comparison, before/after views, A/B testing interfaces.

### `sidebar-layout` - Detail Pages with Sidebars

**Purpose**: Content with contextual sidebar for navigation, metadata, or tools.

#### `left` - Left Sidebar
```css
grid-template-columns: 300px 1fr;
gap: 2rem;
```

**HTML Structure**:
```html
<div class="sidebar-layout left">
  <div class="sidebar-content">
    <!-- Navigation, filters, metadata -->
  </div>
  <div class="main-content">
    <!-- Primary content -->
  </div>
</div>
```

#### `right` - Right Sidebar
```css
grid-template-columns: 1fr 300px;
gap: 2rem;
```

#### Width Variations
- `wide-sidebar`: 400px sidebar
- `narrow-sidebar`: 250px sidebar

**Features**:
- Sticky sidebar positioning
- Responsive collapse on mobile
- Contextual content areas

### `annotation-workspace` - Video/Image Annotation

**Purpose**: Specialized layout for media annotation with toolbar, canvas, and properties.

```css
grid-template-areas:
  "toolbar toolbar toolbar"
  "sidebar canvas properties"
  "timeline timeline timeline";
grid-template-columns: 280px 1fr 320px;
grid-template-rows: 60px 1fr 120px;
```

**HTML Structure**:
```html
<div class="annotation-workspace">
  <div class="annotation-toolbar">
    <!-- Tools, zoom, playback controls -->
  </div>
  <div class="annotation-sidebar">
    <!-- Object list, layers -->
  </div>
  <div class="annotation-canvas">
    <!-- Video/image with overlays -->
  </div>
  <div class="annotation-properties">
    <!-- Selected object properties -->
  </div>
  <div class="annotation-timeline">
    <!-- Video timeline, keyframes -->
  </div>
</div>
```

#### Variations

##### `no-timeline` - Static Media
```css
grid-template-areas:
  "toolbar toolbar toolbar"
  "sidebar canvas properties";
grid-template-rows: 60px 1fr;
```

##### `fullscreen-canvas` - Focused Annotation
```css
grid-template-areas:
  "toolbar"
  "canvas";
grid-template-columns: 1fr;
```

**Best for**: Video annotation, image labeling, bounding box creation.

### `holy-grail` - Classic Layout

**Purpose**: Traditional web layout with header, footer, sidebar, and aside.

```css
grid-template-areas:
  "header header header"
  "sidebar main aside"
  "footer footer footer";
grid-template-columns: 250px 1fr 200px;
grid-template-rows: auto 1fr auto;
min-height: 100vh;
```

**HTML Structure**:
```html
<div class="holy-grail">
  <header class="hg-header">...</header>
  <nav class="hg-sidebar">...</nav>
  <main class="hg-main">...</main>
  <aside class="hg-aside">...</aside>
  <footer class="hg-footer">...</footer>
</div>
```

**Best for**: Traditional websites, documentation sites, marketing pages.

---

## 5. Flexible Systems

Alternative layout systems using Flexbox and dynamic grid behaviors.

### `flex-container` - Flexbox-Based Layouts

**Purpose**: Flexible layouts for dynamic content that doesn't fit rigid grids.

**Default**: Horizontal flex
```css
display: flex;
gap: 1.5rem;
```

#### Variations

##### `column` - Vertical Stacking
```css
flex-direction: column;
```

##### `wrap` - Flexible Wrapping
```css
flex-wrap: wrap;
```

##### `center` - Centered Content
```css
justify-content: center;
align-items: center;
```

##### `between` - Space Between
```css
justify-content: space-between;
```

##### `around` - Space Around
```css
justify-content: space-around;
```

**HTML Structure**:
```html
<div class="flex-container between">
  <div class="flex-item">Item 1</div>
  <div class="flex-item">Item 2</div>
  <div class="flex-item">Item 3</div>
</div>
```

### `auto-grid` - Auto-Fitting Grids with Constraints

**Purpose**: Grids that automatically adjust column count based on available space.

```css
display: grid;
gap: 1rem;
```

#### Size Variations

##### `min-200` - Minimum 200px Columns
```css
grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
```

##### `min-250` / `min-300` / `min-400`
Similar pattern with different minimum widths.

##### `max-4-cols` - Maximum 4 Columns
```css
grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
max-width: 1000px;
```

**Best for**: Dynamic content, responsive cards, flexible layouts.

### `masonry-grid` - Pinterest-Style Layouts

**Purpose**: Vertical masonry layout where items flow into columns of varying heights.

```css
columns: 300px;
column-gap: 1rem;
```

**HTML Structure**:
```html
<div class="masonry-grid">
  <div class="masonry-item">
    <!-- Variable height content -->
  </div>
  <div class="masonry-item">
    <!-- Different height content -->
  </div>
</div>
```

#### Variations
- `narrow`: 250px columns
- `wide`: 400px columns

**CSS for Items**:
```css
.masonry-item {
  break-inside: avoid;
  margin-bottom: 1rem;
  page-break-inside: avoid;
}
```

### `responsive-grid` - Grid with Spanning Capabilities

**Purpose**: Grid system with items that can span multiple columns or rows.

```css
display: grid;
gap: 1rem;
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
```

**HTML Structure**:
```html
<div class="responsive-grid">
  <div class="grid-item">Normal item</div>
  <div class="grid-item span-2">Wide item</div>
  <div class="grid-item span-full">Full width item</div>
  <div class="grid-item row-span-2">Tall item</div>
</div>
```

**Spanning Classes**:
- `.span-2`: Spans 2 columns
- `.span-3`: Spans 3 columns
- `.span-full`: Spans all columns
- `.row-span-2`: Spans 2 rows

---

## Usage Examples

### Dashboard Layout
```html
<div class="app-layout">
  <header class="app-header">...</header>
  <nav class="app-sidebar">...</nav>
  <main class="app-main">
    <div class="content-container wide">
      <!-- Key metrics -->
      <div class="stats-grid">
        <div class="stat-card">...</div>
        <div class="stat-card">...</div>
        <div class="stat-card">...</div>
        <div class="stat-card">...</div>
      </div>
      
      <!-- Dashboard widgets -->
      <div class="dashboard-grid">
        <div class="chart-widget">...</div>
        <div class="activity-widget">...</div>
        <div class="progress-widget">...</div>
      </div>
    </div>
  </main>
  <aside class="app-tools">...</aside>
</div>
```

### CRUD Interface
```html
<div class="app-layout no-tools">
  <header class="app-header">...</header>
  <nav class="app-sidebar">...</nav>
  <main class="app-main">
    <div class="content-container wide">
      <div class="crud-grid with-sidebar">
        <div class="sidebar-content">
          <!-- Filters and categories -->
        </div>
        <div class="main-content">
          <div class="table-container">
            <table class="ui table">...</table>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
```

### Form Interface
```html
<div class="app-layout minimal">
  <header class="app-header">...</header>
  <main class="app-main">
    <div class="content-container narrow">
      <div class="form-grid sidebar-form">
        <div class="form-sidebar">
          <!-- Form navigation -->
        </div>
        <div class="form-content">
          <div class="form-grid two-column">
            <div class="field">...</div>
            <div class="field">...</div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>
```

### Annotation Interface
```html
<div class="annotation-workspace">
  <div class="annotation-toolbar">
    <!-- Drawing tools, zoom controls -->
  </div>
  <div class="annotation-sidebar">
    <!-- Object list, layers -->
  </div>
  <div class="annotation-canvas">
    <!-- Video/image with bounding boxes -->
  </div>
  <div class="annotation-properties">
    <!-- Selected object properties -->
  </div>
  <div class="annotation-timeline">
    <!-- Video timeline -->
  </div>
</div>
```

---

## Responsive Behavior

### Mobile (≤767px)
- All multi-column grids become single-column
- Sidebars slide in from left
- Content containers use full width
- Touch-friendly spacing

### Tablet (768px-1023px)
- Two-column layouts remain
- Tools panels hidden
- Reduced column counts
- Optimized spacing

### Desktop (1024px+)
- Full layout capabilities
- All panels visible
- Maximum column counts
- Generous spacing

### Large Screens (1200px+)
- Increased spacing
- Larger minimum column sizes
- Enhanced visual hierarchy

---

## Best Practices

### Grid Selection
1. **Use `app-layout`** for main application structure
2. **Choose specific grids** for content types (crud-grid, dashboard-grid, etc.)
3. **Combine grids** for complex interfaces
4. **Use flexbox** for dynamic content

### Performance
1. **Avoid deep nesting** of grids
2. **Use CSS variables** for consistent spacing
3. **Leverage browser caching** with external stylesheets
4. **Minimize layout shifts** with proper sizing

### Accessibility
1. **Maintain logical DOM order** regardless of visual layout
2. **Use semantic HTML** within grid containers
3. **Ensure keyboard navigation** works across grid items
4. **Test with screen readers**

### Maintenance
1. **Use consistent naming** conventions
2. **Document custom modifications**
3. **Keep responsive breakpoints** aligned
4. **Test across devices** regularly

This grid system provides the foundation for building modern, responsive CRUD applications with specialized support for annotation interfaces and complex data relationships.