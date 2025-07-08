# Complete Responsive Design Documentation

A comprehensive guide to creating responsive layouts and components that adapt beautifully across all devices and screen sizes.

---

## Table of Contents

1. [Breakpoint System](#1-breakpoint-system)
2. [Responsive Card Lists](#2-responsive-card-lists)
3. [Responsive Tables](#3-responsive-tables)
4. [Responsive Navigation](#4-responsive-navigation)
5. [Responsive Forms](#5-responsive-forms)
6. [Responsive Grid Layouts](#6-responsive-grid-layouts)
7. [Mobile-First Patterns](#7-mobile-first-patterns)
8. [Touch Interactions](#8-touch-interactions)
9. [Performance Considerations](#9-performance-considerations)
10. [Testing & Debugging](#10-testing--debugging)

---

## 1. Breakpoint System

### Standard Breakpoints

Your responsive system uses a comprehensive breakpoint structure that covers all modern devices:

```css
/* Small Mobile */
@media (max-width: 480px) { /* 0-480px */ }

/* Mobile */
@media (max-width: 767px) { /* 0-767px */ }

/* Small Tablet */
@media (min-width: 768px) and (max-width: 991px) { /* 768-991px */ }

/* Tablet */
@media (min-width: 768px) and (max-width: 1023px) { /* 768-1023px */ }

/* Large Tablet */
@media (min-width: 992px) and (max-width: 1199px) { /* 992-1199px */ }

/* Desktop */
@media (min-width: 1024px) { /* 1024px+ */ }

/* Large Desktop */
@media (min-width: 1200px) { /* 1200px+ */ }

/* Extra Large */
@media (min-width: 1400px) { /* 1400px+ */ }

/* Ultra Large */
@media (min-width: 1600px) { /* 1600px+ */ }
```

### Utility Classes for Responsive Control

#### Show/Hide Classes
```html
<!-- Show only on mobile -->
<div class="show-mobile hide-tablet hide-desktop">
  Mobile-only content
</div>

<!-- Hide on mobile, show on tablet and up -->
<div class="hide-mobile show-tablet">
  Tablet and desktop content
</div>

<!-- Show only on desktop -->
<div class="hide-mobile hide-tablet show-desktop">
  Desktop-only content
</div>
```

#### Responsive Text Alignment
```html
<!-- Center on mobile, left on desktop -->
<h1 class="text-center text-desktop-left">Page Title</h1>

<!-- Right on desktop, center on mobile -->
<div class="text-mobile-center text-desktop-right">
  Responsive alignment
</div>
```

#### Responsive Flexbox Direction
```html
<!-- Column on mobile, row on desktop -->
<div class="flex-container flex-mobile-column flex-desktop-row">
  <div class="sidebar-content">Sidebar</div>
  <div class="main-content">Main Content</div>
</div>
```

---

## 2. Responsive Card Lists

The most common responsive pattern - cards that adapt from single column on mobile to multiple columns on larger screens.

### Basic Responsive Card Grid

#### HTML Structure
```html
<div class="card-grid">
  <div class="ui card">
    <div class="image">
      <img src="user1.jpg" alt="User Profile">
    </div>
    <div class="content">
      <div class="header">John Doe</div>
      <div class="meta">Software Developer</div>
      <div class="description">
        Experienced full-stack developer specializing in React and Node.js applications.
      </div>
    </div>
    <div class="extra content">
      <div class="ui two buttons">
        <div class="ui basic button">View</div>
        <div class="ui basic button">Edit</div>
      </div>
    </div>
  </div>
  
  <div class="ui card">
    <div class="content">
      <div class="header">Jane Smith</div>
      <div class="meta">UX Designer</div>
      <div class="description">
        Creative designer focused on user experience and interface design.
      </div>
    </div>
    <div class="extra content">
      <div class="ui two buttons">
        <div class="ui basic button">View</div>
        <div class="ui basic button">Edit</div>
      </div>
    </div>
  </div>
  
  <!-- More cards... -->
</div>
```

#### Responsive Behavior
```css
/* Mobile: Single column */
.card-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: 1fr;
}

/* Small Tablet: 2 columns */
@media (min-width: 768px) {
  .card-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
}

/* Large Tablet: 3 columns */
@media (min-width: 992px) {
  .card-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Desktop: 4 columns */
@media (min-width: 1200px) {
  .card-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
  }
}

/* Extra Large: 5 columns */
@media (min-width: 1600px) {
  .card-grid {
    grid-template-columns: repeat(5, 1fr);
  }
}
```

### Advanced Card Grid Variants

#### Auto-Fit Cards (Flexible Column Count)
```html
<div class="card-grid auto-fit">
  <!-- Cards adapt automatically to available space -->
</div>
```

```css
.card-grid.auto-fit {
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
}

@media (min-width: 768px) {
  .card-grid.auto-fit {
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
  }
}
```

#### Dense Card Layout
```html
<div class="card-grid dense">
  <!-- Compact cards for data-heavy interfaces -->
</div>
```

```css
.card-grid.dense {
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 0.75rem;
}

.card-grid.dense .ui.card {
  padding: 0.75rem;
}

.card-grid.dense .header {
  font-size: 1rem;
}
```

#### Large Feature Cards
```html
<div class="card-grid large">
  <!-- Prominent cards for featured content -->
</div>
```

```css
.card-grid.large {
  grid-template-columns: 1fr;
  gap: 2rem;
}

@media (min-width: 768px) {
  .card-grid.large {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1200px) {
  .card-grid.large {
    grid-template-columns: repeat(3, 1fr);
  }
}

.card-grid.large .ui.card {
  min-height: 300px;
}
```

### Card List with Sidebar

#### HTML Structure
```html
<div class="crud-grid with-sidebar">
  <div class="sidebar-content">
    <!-- Filters and categories -->
    <div class="ui form">
      <div class="field">
        <label>Filter by Role</label>
        <div class="ui dropdown selection">
          <input type="hidden" name="role">
          <i class="dropdown icon"></i>
          <div class="default text">All Roles</div>
          <div class="menu">
            <div class="item" data-value="admin">Administrator</div>
            <div class="item" data-value="editor">Editor</div>
            <div class="item" data-value="viewer">Viewer</div>
          </div>
        </div>
      </div>
      <div class="field">
        <label>Status</label>
        <div class="ui checkbox">
          <input type="checkbox" checked>
          <label>Active Users</label>
        </div>
      </div>
    </div>
  </div>
  
  <div class="main-content">
    <div class="card-grid">
      <!-- User cards here -->
    </div>
  </div>
</div>
```

#### Responsive Behavior
```css
/* Mobile: Stack sidebar below cards */
@media (max-width: 767px) {
  .crud-grid.with-sidebar {
    grid-template-columns: 1fr;
    grid-template-areas: 
      "main"
      "sidebar";
  }
  
  .sidebar-content {
    grid-area: sidebar;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e1e4e8;
  }
  
  .main-content {
    grid-area: main;
  }
}
```

### Horizontal Card List (Mobile)

#### HTML Structure
```html
<div class="card-list horizontal">
  <div class="ui card">
    <div class="content">
      <div class="header">Quick Action 1</div>
      <div class="description">Description here</div>
    </div>
  </div>
  <div class="ui card">
    <div class="content">
      <div class="header">Quick Action 2</div>
      <div class="description">Description here</div>
    </div>
  </div>
</div>
```

#### Mobile Horizontal Scrolling
```css
.card-list.horizontal {
  display: flex;
  gap: 1rem;
  overflow-x: auto;
  padding-bottom: 1rem;
  -webkit-overflow-scrolling: touch;
}

.card-list.horizontal .ui.card {
  flex: 0 0 280px;
  max-width: 280px;
}

/* Desktop: Grid layout */
@media (min-width: 768px) {
  .card-list.horizontal {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    overflow-x: visible;
  }
  
  .card-list.horizontal .ui.card {
    flex: none;
    max-width: none;
  }
}
```

---

## 3. Responsive Tables

Tables present unique challenges on mobile devices due to their fixed column structure.

### Responsive Table Wrapper

#### HTML Structure
```html
<div class="table-container">
  <table class="ui table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Last Login</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <div class="user-cell">
            <img class="ui avatar image" src="user1.jpg">
            <div class="content">
              <div class="name">John Doe</div>
              <div class="title">Software Developer</div>
            </div>
          </div>
        </td>
        <td>john.doe@example.com</td>
        <td>
          <div class="ui label blue">Administrator</div>
        </td>
        <td>
          <span class="status-indicator success">
            <span class="status-dot"></span>
            Active
          </span>
        </td>
        <td>2 hours ago</td>
        <td>
          <div class="ui dropdown">
            <div class="text">Actions</div>
            <i class="dropdown icon"></i>
            <div class="menu">
              <div class="item">Edit</div>
              <div class="item">Delete</div>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
```

#### Responsive Table Behavior
```css
.table-container {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

/* Mobile: Horizontal scroll */
@media (max-width: 767px) {
  .table-container {
    margin: 0 -1rem;
    padding: 0 1rem;
  }
  
  .ui.table {
    min-width: 600px;
    font-size: 0.9rem;
  }
  
  .ui.table th,
  .ui.table td {
    padding: 0.5rem;
    white-space: nowrap;
  }
  
  /* Hide less important columns on mobile */
  .ui.table .hide-mobile {
    display: none;
  }
}
```

### Card-Style Table (Mobile Alternative)

#### HTML Structure for Mobile Cards
```html
<div class="table-cards mobile-only">
  <div class="table-card">
    <div class="card-header">
      <div class="user-info">
        <img class="ui avatar image" src="user1.jpg">
        <div class="content">
          <div class="name">John Doe</div>
          <div class="email">john.doe@example.com</div>
        </div>
      </div>
      <div class="card-actions">
        <div class="ui dropdown">
          <i class="ellipsis vertical icon"></i>
          <div class="menu">
            <div class="item">Edit</div>
            <div class="item">Delete</div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-content">
      <div class="info-row">
        <span class="label">Role:</span>
        <div class="ui label blue">Administrator</div>
      </div>
      <div class="info-row">
        <span class="label">Status:</span>
        <span class="status-indicator success">
          <span class="status-dot"></span>
          Active
        </span>
      </div>
      <div class="info-row">
        <span class="label">Last Login:</span>
        <span>2 hours ago</span>
      </div>
    </div>
  </div>
</div>
```

#### Mobile Card Styling
```css
.table-cards {
  display: none;
}

@media (max-width: 767px) {
  .table-container {
    display: none;
  }
  
  .table-cards {
    display: block;
  }
  
  .table-card {
    background: white;
    border: 1px solid #e1e4e8;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    padding: 1rem;
  }
  
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
  }
  
  .info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f5f5f5;
  }
  
  .info-row:last-child {
    border-bottom: none;
  }
  
  .label {
    font-weight: 600;
    color: #666;
  }
}
```

---

## 4. Responsive Navigation

Navigation components must adapt from full menus on desktop to collapsed, touch-friendly interfaces on mobile.

### Mobile Sidebar with Overlay

#### HTML Structure
```html
<div class="app-layout">
  <header class="app-header">
    <div class="header-left">
      <button class="mobile-menu-toggle">
        <i class="bars icon"></i>
      </button>
      <a href="/" class="logo">
        <div class="logo-icon">A</div>
        <span class="logo-text">MyApp</span>
      </a>
    </div>
  </header>
  
  <nav class="app-sidebar">
    <!-- Navigation content -->
  </nav>
  
  <div class="sidebar-overlay"></div>
  
  <main class="app-main">
    <!-- Content -->
  </main>
</div>
```

#### Mobile Sidebar Behavior
```css
/* Desktop: Normal sidebar */
@media (min-width: 768px) {
  .app-sidebar {
    position: relative;
    transform: none;
  }
  
  .sidebar-overlay {
    display: none;
  }
  
  .mobile-menu-toggle {
    display: none;
  }
}

/* Mobile: Sliding sidebar */
@media (max-width: 767px) {
  .app-layout {
    grid-template-columns: 1fr;
    grid-template-areas: 
      "header"
      "main";
  }
  
  .app-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 280px;
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    z-index: 1001;
    background: white;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  }
  
  .app-sidebar.open {
    transform: translateX(0);
  }
  
  .sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
  }
  
  .sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
  }
  
  .logo-text {
    display: none;
  }
}
```

#### JavaScript for Mobile Menu
```javascript
// Mobile menu toggle functionality
const mobileToggle = document.querySelector('.mobile-menu-toggle');
const sidebar = document.querySelector('.app-sidebar');
const overlay = document.querySelector('.sidebar-overlay');

function openSidebar() {
  sidebar.classList.add('open');
  overlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeSidebar() {
  sidebar.classList.remove('open');
  overlay.classList.remove('active');
  document.body.style.overflow = '';
}

mobileToggle.addEventListener('click', openSidebar);
overlay.addEventListener('click', closeSidebar);

// Close on navigation
sidebar.addEventListener('click', (e) => {
  if (e.target.closest('.nav-item')) {
    closeSidebar();
  }
});

// Close on escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && sidebar.classList.contains('open')) {
    closeSidebar();
  }
});
```

### Responsive Breadcrumbs

#### HTML Structure
```html
<nav class="breadcrumb-nav">
  <a href="/dashboard" class="breadcrumb-link">
    <i class="home icon"></i>
    <span class="breadcrumb-text">Dashboard</span>
  </a>
  <span class="breadcrumb-separator">/</span>
  <a href="/users" class="breadcrumb-link">
    <span class="breadcrumb-text">Users</span>
  </a>
  <span class="breadcrumb-separator">/</span>
  <a href="/users/departments" class="breadcrumb-link">
    <span class="breadcrumb-text">Departments</span>
  </a>
  <span class="breadcrumb-separator">/</span>
  <span class="breadcrumb-current">Engineering Team</span>
</nav>
```

#### Mobile Breadcrumb Behavior
```css
/* Desktop: Full breadcrumbs */
.breadcrumb-nav {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
}

/* Mobile: Simplified breadcrumbs */
@media (max-width: 767px) {
  .breadcrumb-nav {
    max-width: 200px;
    overflow: hidden;
  }
  
  /* Hide intermediate breadcrumbs */
  .breadcrumb-link:not(:first-child) {
    display: none;
  }
  
  .breadcrumb-separator:not(:last-of-type) {
    display: none;
  }
  
  /* Show only home and current */
  .breadcrumb-link:first-child,
  .breadcrumb-current {
    display: inline;
  }
  
  /* Add ellipsis indicator */
  .breadcrumb-nav::before {
    content: "...";
    margin: 0 0.5rem;
    color: #666;
  }
  
  .breadcrumb-text {
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
}
```

---

## 5. Responsive Forms

Forms need special attention on mobile devices for usability and accessibility.

### Mobile-Optimized Form Layout

#### HTML Structure
```html
<div class="content-container narrow">
  <div class="form-container">
    <h2 class="form-title">Create New User</h2>
    
    <div class="ui form">
      <div class="form-grid mobile-single">
        <div class="field">
          <label>First Name</label>
          <div class="ui input">
            <input type="text" placeholder="Enter first name" autocomplete="given-name">
          </div>
        </div>
        
        <div class="field">
          <label>Last Name</label>
          <div class="ui input">
            <input type="text" placeholder="Enter last name" autocomplete="family-name">
          </div>
        </div>
      </div>
      
      <div class="field">
        <label>Email Address</label>
        <div class="ui input">
          <input type="email" placeholder="user@example.com" autocomplete="email">
        </div>
      </div>
      
      <div class="form-grid mobile-single">
        <div class="field">
          <label>Phone Number</label>
          <div class="ui input">
            <input type="tel" placeholder="(555) 123-4567" autocomplete="tel">
          </div>
        </div>
        
        <div class="field">
          <label>Department</label>
          <div class="ui dropdown selection">
            <input type="hidden" name="department">
            <i class="dropdown icon"></i>
            <div class="default text">Select Department</div>
            <div class="menu">
              <div class="item" data-value="engineering">Engineering</div>
              <div class="item" data-value="design">Design</div>
              <div class="item" data-value="marketing">Marketing</div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="form-actions">
        <button type="button" class="ui button">Cancel</button>
        <button type="submit" class="ui button primary">Create User</button>
      </div>
    </div>
  </div>
</div>
```

#### Responsive Form Behavior
```css
/* Desktop: Two-column form */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #e1e4e8;
}

/* Mobile: Single column */
@media (max-width: 767px) {
  .form-grid.mobile-single {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .form-actions .ui.button {
    width: 100%;
    min-height: 44px; /* Touch-friendly size */
  }
  
  /* Larger inputs for mobile */
  .ui.input input {
    min-height: 44px;
    font-size: 16px; /* Prevents zoom on iOS */
  }
  
  .ui.dropdown {
    min-height: 44px;
  }
}
```

### Touch-Friendly Input Enhancements

#### Mobile Input Improvements
```css
/* Touch-friendly form elements */
@media (max-width: 767px) {
  /* Larger touch targets */
  .ui.checkbox,
  .ui.radio {
    min-height: 44px;
    display: flex;
    align-items: center;
  }
  
  .ui.checkbox input,
  .ui.radio input {
    transform: scale(1.2);
  }
  
  /* Better spacing */
  .field {
    margin-bottom: 1.5rem;
  }
  
  /* Larger labels */
  label {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    display: block;
  }
  
  /* Input focus improvements */
  .ui.input input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
  }
}
```

---

## 6. Responsive Grid Layouts

Advanced responsive patterns for complex layouts.

### Dashboard Grid

#### HTML Structure
```html
<div class="dashboard-container">
  <div class="dashboard-grid">
    <!-- Key Metrics -->
    <div class="widget metric-widget">
      <div class="widget-header">
        <h3>Total Users</h3>
      </div>
      <div class="widget-content">
        <div class="metric-value">1,247</div>
        <div class="metric-change positive">+12%</div>
      </div>
    </div>
    
    <div class="widget metric-widget">
      <div class="widget-header">
        <h3>Active Sessions</h3>
      </div>
      <div class="widget-content">
        <div class="metric-value">89</div>
        <div class="metric-change negative">-3%</div>
      </div>
    </div>
    
    <!-- Chart Widget -->
    <div class="widget chart-widget">
      <div class="widget-header">
        <h3>User Growth</h3>
        <div class="widget-actions">
          <div class="ui dropdown">
            <div class="text">Last 30 days</div>
            <i class="dropdown icon"></i>
            <div class="menu">
              <div class="item">Last 7 days</div>
              <div class="item">Last 30 days</div>
              <div class="item">Last 90 days</div>
            </div>
          </div>
        </div>
      </div>
      <div class="widget-content">
        <canvas id="growth-chart"></canvas>
      </div>
    </div>
    
    <!-- Activity Feed -->
    <div class="widget activity-widget">
      <div class="widget-header">
        <h3>Recent Activity</h3>
      </div>
      <div class="widget-content">
        <div class="activity-list">
          <div class="activity-item">
            <div class="activity-icon">
              <i class="user plus icon"></i>
            </div>
            <div class="activity-content">
              <div class="activity-text">New user registered</div>
              <div class="activity-time">2 minutes ago</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

#### Responsive Dashboard Behavior
```css
/* Mobile: Single column stack */
.dashboard-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: 1fr;
}

/* Small Tablet: 2 columns, metrics on top */
@media (min-width: 768px) {
  .dashboard-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  
  .metric-widget {
    grid-column: span 1;
  }
  
  .chart-widget {
    grid-column: span 2;
  }
}

/* Large Tablet: 3 columns */
@media (min-width: 992px) {
  .dashboard-grid {
    grid-template-columns: repeat(3, 1fr);
  }
  
  .chart-widget {
    grid-column: span 2;
  }
  
  .activity-widget {
    grid-column: span 1;
  }
}

/* Desktop: 4 columns with spanning */
@media (min-width: 1200px) {
  .dashboard-grid {
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
  }
  
  .metric-widget {
    grid-column: span 1;
  }
  
  .chart-widget {
    grid-column: span 2;
  }
  
  .activity-widget {
    grid-column: span 1;
  }
}
```

### Gallery Grid with Aspect Ratios

#### HTML Structure
```html
<div class="gallery-grid">
  <div class="gallery-item">
    <img src="image1.jpg" alt="Gallery Image 1">
    <div class="gallery-overlay">
      <div class="gallery-actions">
        <button class="ui button icon">
          <i class="eye icon"></i>
        </button>
        <button class="ui button icon">
          <i class="download icon"></i>
        </button>
      </div>
    </div>
  </div>
  
  <div class="gallery-item featured">
    <img src="image2.jpg" alt="Featured Image">
    <div class="gallery-overlay">
      <div class="gallery-info">
        <h4>Featured Content</h4>
        <p>This is a featured gallery item</p>
      </div>
    </div>
  </div>
  
  <!-- More gallery items -->
</div>
```

#### Responsive Gallery Behavior
```css
.gallery-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  grid-auto-rows: 200px;
}

.gallery-item.featured {
  grid-column: span 2;
  grid-row: span 2;
}

/* Mobile: Smaller grid items */
@media (max-width: 767px) {
  .gallery-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    grid-auto-rows: 150px;
    gap: 0.5rem;
  }
  
  .gallery-item.featured {
    grid-column: span 2;
    grid-row: span 1; /* Reduce span on mobile */
  }
}

/* Large screens: Bigger grid */
@media (min-width: 1200px) {
  .gallery-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    grid-auto-rows: 250px;
    gap: 1.5rem;
  }
}
```

---

## 7. Mobile-First Patterns

Design patterns that prioritize mobile experience while enhancing for larger screens.

### Progressive Enhancement Approach

#### Mobile-First CSS Structure
```css
/* Base styles (Mobile) */
.component {
  /* Mobile styles first */
  width: 100%;
  padding: 1rem;
  font-size: 0.9rem;
}

/* Enhance for larger screens */
@media (min-width: 768px) {
  .component {
    /* Tablet enhancements */
    padding: 1.5rem;
    font-size: 1rem;
  }
}

@media (min-width: 1024px) {
  .component {
    /* Desktop enhancements */
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
  }
}
```

### Mobile Action Patterns

#### Bottom Action Bar
```html
<div class="mobile-actions">
  <button class="action-button primary">
    <i class="save icon"></i>
    <span>Save</span>
  </button>
  <button class="action-button">
    <i class="share icon"></i>
    <span>Share</span>
  </button>
  <button class="action-button">
    <i class="more icon"></i>
    <span>More</span>
  </button>
</div>
```

```css
.mobile-actions {
  display: none;
}

/* Mobile: Show bottom action bar */
@media (max-width: 767px) {
  .mobile-actions {
    display: flex;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 1px solid #e1e4e8;
    padding: 1rem;
    gap: 1rem;
    z-index: 100;
    /* Safe area for notched phones */
    padding-bottom: calc(1rem + env(safe-area-inset-bottom));
  }
  
  .action-button {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border: 1px solid #e1e4e8;
    border-radius: 0.5rem;
    min-height: 60px;
    font-size: 0.8rem;
  }
  
  .action-button.primary {
    background: #007bff;
    color: white;
    border-color: #007bff;
  }
  
  .action-button i {
    margin-bottom: 0.25rem;
    font-size: 1.2rem;
  }
  
  /* Add padding to content to account for fixed bar */
  .app-main {
    padding-bottom: 100px;
  }
}
```

#### Floating Action Button
```html
<div class="fab-container">
  <button class="fab primary">
    <i class="plus icon"></i>
  </button>
</div>
```

```css
.fab-container {
  display: none;
}

@media (max-width: 767px) {
  .fab-container {
    display: block;
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    z-index: 100;
  }
  
  .fab {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    border: none;
    background: #007bff;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: transform 0.2s ease;
  }
  
  .fab:hover {
    transform: scale(1.1);
  }
  
  .fab i {
    font-size: 1.5rem;
  }
}
```

### Swipe Gestures and Touch Interactions

#### Swipeable Cards
```html
<div class="swipe-card" data-id="1">
  <div class="card-content">
    <div class="card-main">
      <h4>John Doe</h4>
      <p>Software Developer</p>
    </div>
  </div>
  <div class="card-actions">
    <div class="action delete">
      <i class="trash icon"></i>
    </div>
    <div class="action edit">
      <i class="edit icon"></i>
    </div>
  </div>
</div>
```

```css
.swipe-card {
  position: relative;
  background: white;
  border: 1px solid #e1e4e8;
  border-radius: 0.5rem;
  margin-bottom: 1rem;
  overflow: hidden;
}

.card-content {
  padding: 1rem;
  transition: transform 0.3s ease;
}

.card-actions {
  position: absolute;
  top: 0;
  right: 0;
  height: 100%;
  display: flex;
  transform: translateX(100%);
}

.action {
  width: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.action.edit {
  background: #007bff;
}

.action.delete {
  background: #dc3545;
}

/* Show actions on swipe */
.swipe-card.swiped .card-content {
  transform: translateX(-120px);
}

.swipe-card.swiped .card-actions {
  transform: translateX(0);
}
```

#### Touch Gesture JavaScript
```javascript
// Simple swipe detection
let startX = 0;
let currentX = 0;
let cardBeingDragged = null;

document.addEventListener('touchstart', (e) => {
  const card = e.target.closest('.swipe-card');
  if (card) {
    startX = e.touches[0].clientX;
    cardBeingDragged = card;
  }
});

document.addEventListener('touchmove', (e) => {
  if (!cardBeingDragged) return;
  
  currentX = e.touches[0].clientX;
  const diffX = startX - currentX;
  
  if (diffX > 0) {
    // Swiping left
    const content = cardBeingDragged.querySelector('.card-content');
    content.style.transform = `translateX(-${Math.min(diffX, 120)}px)`;
  }
});

document.addEventListener('touchend', (e) => {
  if (!cardBeingDragged) return;
  
  const diffX = startX - currentX;
  
  if (diffX > 60) {
    // Complete the swipe
    cardBeingDragged.classList.add('swiped');
  } else {
    // Snap back
    const content = cardBeingDragged.querySelector('.card-content');
    content.style.transform = 'translateX(0)';
  }
  
  cardBeingDragged = null;
});
```

---

## 8. Touch Interactions

Optimizing interfaces for touch devices with proper sizing and interactions.

### Touch-Friendly Sizing

#### Minimum Touch Targets
```css
/* Ensure minimum 44px touch targets */
.touch-target {
  min-width: 44px;
  min-height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Apply to common interactive elements */
@media (max-width: 767px) {
  .ui.button {
    min-height: 44px;
    padding: 0.75rem 1rem;
  }
  
  .nav-item {
    min-height: 48px;
    padding: 0.75rem 1rem;
  }
  
  .dropdown-item {
    min-height: 44px;
    padding: 0.75rem 1rem;
  }
  
  .pagination-button,
  .pagination-page {
    min-width: 44px;
    min-height: 44px;
  }
}
```

### Touch Feedback

#### Visual Touch Feedback
```css
.touch-feedback {
  position: relative;
  overflow: hidden;
}

.touch-feedback::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255,255,255,0.5);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.touch-feedback:active::before {
  width: 300px;
  height: 300px;
}

/* Apply to buttons */
@media (max-width: 767px) {
  .ui.button {
    @extend .touch-feedback;
  }
  
  .nav-item {
    @extend .touch-feedback;
  }
}
```

### Pull-to-Refresh Pattern

#### HTML Structure
```html
<div class="pull-refresh-container">
  <div class="pull-refresh-indicator">
    <div class="refresh-arrow">
      <i class="arrow down icon"></i>
    </div>
    <div class="refresh-spinner">
      <div class="ui loader active mini"></div>
    </div>
    <div class="refresh-text">Pull to refresh</div>
  </div>
  
  <div class="scrollable-content">
    <!-- Your content here -->
  </div>
</div>
```

#### Pull-to-Refresh JavaScript
```javascript
class PullToRefresh {
  constructor(container, onRefresh) {
    this.container = container;
    this.onRefresh = onRefresh;
    this.indicator = container.querySelector('.pull-refresh-indicator');
    this.content = container.querySelector('.scrollable-content');
    this.isRefreshing = false;
    this.threshold = 80;
    
    this.init();
  }
  
  init() {
    let startY = 0;
    let currentY = 0;
    let isDragging = false;
    
    this.content.addEventListener('touchstart', (e) => {
      if (this.content.scrollTop === 0) {
        startY = e.touches[0].clientY;
        isDragging = true;
      }
    });
    
    this.content.addEventListener('touchmove', (e) => {
      if (!isDragging || this.isRefreshing) return;
      
      currentY = e.touches[0].clientY;
      const diff = currentY - startY;
      
      if (diff > 0 && this.content.scrollTop === 0) {
        e.preventDefault();
        const pullDistance = Math.min(diff, this.threshold * 1.5);
        
        this.indicator.style.transform = `translateY(${pullDistance}px)`;
        this.indicator.style.opacity = pullDistance / this.threshold;
        
        if (pullDistance >= this.threshold) {
          this.indicator.classList.add('ready');
        } else {
          this.indicator.classList.remove('ready');
        }
      }
    });
    
    this.content.addEventListener('touchend', (e) => {
      if (!isDragging) return;
      
      const diff = currentY - startY;
      
      if (diff >= this.threshold && !this.isRefreshing) {
        this.startRefresh();
      } else {
        this.resetIndicator();
      }
      
      isDragging = false;
    });
  }
  
  startRefresh() {
    this.isRefreshing = true;
    this.indicator.classList.add('refreshing');
    
    this.onRefresh(() => {
      this.endRefresh();
    });
  }
  
  endRefresh() {
    this.isRefreshing = false;
    this.indicator.classList.remove('refreshing', 'ready');
    this.resetIndicator();
  }
  
  resetIndicator() {
    this.indicator.style.transform = 'translateY(-100%)';
    this.indicator.style.opacity = '0';
  }
}

// Usage
const refreshContainer = document.querySelector('.pull-refresh-container');
new PullToRefresh(refreshContainer, (done) => {
  // Simulate API call
  setTimeout(() => {
    // Refresh your data here
    console.log('Data refreshed!');
    done();
  }, 2000);
});
```

---

## 9. Performance Considerations

Optimizing responsive designs for better performance across devices.

### CSS Performance

#### Efficient Media Queries
```css
/* Group related breakpoints */
@media (max-width: 767px) {
  .sidebar { transform: translateX(-100%); }
  .mobile-menu { display: block; }
  .desktop-only { display: none; }
}

@media (min-width: 768px) {
  .sidebar { transform: none; }
  .mobile-menu { display: none; }
  .desktop-only { display: block; }
}

/* Avoid complex selectors in media queries */
/* Bad */
@media (max-width: 767px) {
  .container .card .content .header { font-size: 1rem; }
}

/* Good */
@media (max-width: 767px) {
  .card-header { font-size: 1rem; }
}
```

#### Optimize Animations
```css
/* Use transform and opacity for animations */
.slide-animation {
  transform: translateX(-100%);
  transition: transform 0.3s ease;
}

.slide-animation.open {
  transform: translateX(0);
}

/* Avoid animating layout properties */
/* Bad */
.bad-animation {
  left: -300px;
  transition: left 0.3s ease;
}

/* Good */
.good-animation {
  transform: translateX(-300px);
  transition: transform 0.3s ease;
}
```

### JavaScript Performance

#### Debounced Resize Handling
```javascript
// Debounce resize events
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

const handleResize = debounce(() => {
  // Expensive resize operations
  recalculateLayout();
  updateChartDimensions();
}, 250);

window.addEventListener('resize', handleResize);
```

#### Intersection Observer for Lazy Loading
```javascript
// Lazy load images and content
const imageObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const img = entry.target;
      img.src = img.dataset.src;
      img.classList.remove('lazy');
      imageObserver.unobserve(img);
    }
  });
});

document.querySelectorAll('img[data-src]').forEach(img => {
  imageObserver.observe(img);
});
```

---

## 10. Testing & Debugging

Tools and techniques for testing responsive designs.

### Browser DevTools Testing

#### Device Simulation
```javascript
// Test responsive breakpoints
const breakpoints = [
  { name: 'Small Mobile', width: 320 },
  { name: 'Mobile', width: 375 },
  { name: 'Large Mobile', width: 414 },
  { name: 'Tablet', width: 768 },
  { name: 'Desktop', width: 1024 },
  { name: 'Large Desktop', width: 1440 }
];

// Console helper for quick breakpoint testing
function testBreakpoint(width) {
  console.log(`Testing ${width}px width`);
  document.documentElement.style.width = width + 'px';
}
```

### Accessibility Testing

#### Screen Reader Testing
```html
<!-- Ensure proper heading structure -->
<h1>Main Page Title</h1>
  <h2>Section Title</h2>
    <h3>Subsection Title</h3>

<!-- Provide alternative text -->
<img src="chart.jpg" alt="User growth chart showing 15% increase over 6 months">

<!-- Use proper form labels -->
<label for="username">Username</label>
<input type="text" id="username" name="username">

<!-- Indicate required fields -->
<label for="email">Email Address <span aria-label="required">*</span></label>
<input type="email" id="email" name="email" required aria-describedby="email-error">
<div id="email-error" class="error-message">Please enter a valid email address</div>
```

### Performance Testing

#### Core Web Vitals Monitoring
```javascript
// Monitor Largest Contentful Paint (LCP)
new PerformanceObserver((entryList) => {
  for (const entry of entryList.getEntries()) {
    console.log('LCP:', entry.startTime);
  }
}).observe({entryTypes: ['largest-contentful-paint']});

// Monitor Cumulative Layout Shift (CLS)
let clsValue = 0;
new PerformanceObserver((entryList) => {
  for (const entry of entryList.getEntries()) {
    if (!entry.hadRecentInput) {
      clsValue += entry.value;
      console.log('CLS:', clsValue);
    }
  }
}).observe({entryTypes: ['layout-shift']});

// Monitor First Input Delay (FID)
new PerformanceObserver((entryList) => {
  for (const entry of entryList.getEntries()) {
    console.log('FID:', entry.processingStart - entry.startTime);
  }
}).observe({entryTypes: ['first-input']});
```

### Responsive Testing Checklist

#### Layout Testing
- [ ] **Mobile Portrait** (320px-480px): Single column layouts work
- [ ] **Mobile Landscape** (480px-767px): Content remains readable
- [ ] **Tablet Portrait** (768px-1023px): Two-column layouts function
- [ ] **Tablet Landscape** (1024px-1199px): Desktop features appear
- [ ] **Desktop** (1200px+): Full layout with all panels
- [ ] **Large Screens** (1400px+): Content doesn't stretch too wide

#### Interaction Testing
- [ ] **Touch Targets**: Minimum 44px on mobile
- [ ] **Hover States**: Disabled on touch devices
- [ ] **Swipe Gestures**: Function on supported elements
- [ ] **Keyboard Navigation**: Works across all breakpoints
- [ ] **Focus Indicators**: Visible and appropriate

#### Content Testing
- [ ] **Text Readability**: Appropriate font sizes
- [ ] **Image Scaling**: Images scale properly
- [ ] **Video Responsive**: Videos fit containers
- [ ] **Table Overflow**: Tables scroll horizontally
- [ ] **Form Usability**: Forms work on mobile

#### Performance Testing
- [ ] **Load Times**: Under 3 seconds on 3G
- [ ] **Smooth Animations**: 60fps on mobile
- [ ] **Memory Usage**: No excessive memory consumption
- [ ] **Battery Impact**: Reasonable battery usage

This comprehensive responsive documentation provides everything you need to create adaptive, mobile-first interfaces that work beautifully across all devices and screen sizes!
