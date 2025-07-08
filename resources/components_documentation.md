# Complete Components System Documentation

A comprehensive guide to the UI components system designed for modern CRUD applications with annotation capabilities.

---

## Table of Contents

1. [Header Component](#1-header-component)
2. [Sidebar Navigation](#2-sidebar-navigation)
3. [Tools Panel](#3-tools-panel)
4. [Action Bars](#4-action-bars)
5. [Status & Loading Components](#5-status--loading-components)
6. [Utility Components](#6-utility-components)
7. [Interactive Elements](#7-interactive-elements)
8. [Usage Examples](#usage-examples)
9. [Component Combinations](#component-combinations)
10. [Best Practices](#best-practices)

---

## 1. Header Component

The primary navigation and branding component that appears at the top of your application.

### `app-header` - Main Application Header

**Purpose**: Provides consistent branding, navigation, search, and user controls across the application.

**Structure**:
```css
position: sticky;
top: 0;
z-index: 1000;
background: white;
border-bottom: 1px solid #e1e4e8;
height: 60px;
```

**HTML Structure**:
```html
<header class="app-header">
  <div class="header-left">
    <!-- Logo and navigation -->
  </div>
  <div class="header-center">
    <!-- Search and primary actions -->
  </div>
  <div class="header-right">
    <!-- User controls and notifications -->
  </div>
</header>
```

### Header Sections

#### `header-left` - Logo and Navigation
**Purpose**: Contains the application logo, mobile menu toggle, and breadcrumb navigation.

**Components**:
- Logo with icon support
- Mobile menu toggle button
- Breadcrumb navigation
- Primary navigation links

**HTML Example**:
```html
<div class="header-left">
  <button class="mobile-menu-toggle">
    <i class="bars icon"></i>
  </button>
  <a href="/" class="logo">
    <div class="logo-icon">A</div>
    <span class="logo-text">MyApp</span>
  </a>
  <nav class="breadcrumb-nav">
    <a href="/dashboard" class="breadcrumb-link">Dashboard</a>
    <span class="breadcrumb-separator">/</span>
    <span class="breadcrumb-current">Users</span>
  </nav>
</div>
```

**Features**:
- Responsive logo (icon-only on mobile)
- Accessible mobile toggle
- Interactive breadcrumbs with hover states
- Flexible navigation structure

#### `header-center` - Search and Primary Actions
**Purpose**: Houses the main search functionality and primary application actions.

**Components**:
- Global search bar
- Quick action buttons
- Filter toggles
- Context-sensitive tools

**HTML Example**:
```html
<div class="header-center">
  <div class="search-bar">
    <div class="ui input icon">
      <i class="search icon"></i>
      <input type="text" placeholder="Search users, projects, or content...">
    </div>
  </div>
  <div class="quick-actions">
    <button class="ui button icon">
      <i class="plus icon"></i>
    </button>
  </div>
</div>
```

**Features**:
- Auto-expanding search input
- Focus state management
- Search suggestions support
- Keyboard shortcuts (Ctrl/Cmd + K)

#### `header-right` - User Controls
**Purpose**: Contains user-specific controls, notifications, and account management.

**Components**:
- Notification bell with badge
- User avatar and menu
- Settings access
- Help/support links

**HTML Example**:
```html
<div class="header-right">
  <div class="notification-bell">
    <i class="bell icon"></i>
    <span class="notification-badge">3</span>
  </div>
  <div class="user-menu">
    <div class="user-avatar">JD</div>
    <div class="user-info">
      <div class="user-name">John Doe</div>
      <div class="user-role">Administrator</div>
    </div>
  </div>
</div>
```

**Features**:
- Animated notification badges
- Dropdown menu support
- Avatar with fallback initials
- Role-based content display

### Logo Component

#### `logo` - Application Brand
**Purpose**: Displays the application brand with icon and text.

**Variations**:

##### Standard Logo
```html
<a href="/" class="logo">
  <div class="logo-icon">A</div>
  <span class="logo-text">MyApp</span>
</a>
```

##### Icon-Only (Mobile)
```html
<a href="/" class="logo icon-only">
  <div class="logo-icon">A</div>
</a>
```

##### With Image
```html
<a href="/" class="logo">
  <img src="/logo.svg" alt="MyApp" class="logo-image">
  <span class="logo-text">MyApp</span>
</a>
```

**Styling Features**:
- Responsive text hiding
- Customizable icon backgrounds
- Hover and focus states
- Brand color integration

### Breadcrumb Navigation

#### `breadcrumb-nav` - Hierarchical Navigation
**Purpose**: Shows the user's current location in the application hierarchy.

**HTML Structure**:
```html
<nav class="breadcrumb-nav">
  <a href="/dashboard" class="breadcrumb-link">
    <i class="home icon"></i>
    Dashboard
  </a>
  <span class="breadcrumb-separator">/</span>
  <a href="/users" class="breadcrumb-link">Users</a>
  <span class="breadcrumb-separator">/</span>
  <span class="breadcrumb-current">John Doe</span>
</nav>
```

**Features**:
- Icon support for home and other levels
- Hover states for interactive links
- Customizable separators
- Truncation for long paths
- Mobile-friendly collapsing

### Search Bar Component

#### `search-bar` - Global Search
**Purpose**: Provides application-wide search functionality with enhanced UX.

**HTML Structure**:
```html
<div class="search-bar">
  <div class="ui input icon">
    <i class="search icon"></i>
    <input type="text" placeholder="Search..." class="search-input">
  </div>
  <div class="search-suggestions">
    <!-- Auto-populated search suggestions -->
  </div>
</div>
```

**Features**:
- Expandable on focus
- Real-time search suggestions
- Keyboard navigation support
- Recent searches memory
- Category-based filtering

**JavaScript Integration**:
```javascript
// Search functionality
const searchInput = document.querySelector('.search-input');
const suggestions = document.querySelector('.search-suggestions');

searchInput.addEventListener('input', (e) => {
  // Implement search logic
  showSuggestions(e.target.value);
});
```

---

## 2. Sidebar Navigation

The primary navigation component providing access to all application sections.

### `app-sidebar` - Main Navigation Sidebar

**Purpose**: Hierarchical navigation with sections, icons, badges, and user information.

**Structure**:
```css
width: 280px;
background: #f8f9fa;
border-right: 1px solid #e1e4e8;
overflow-y: auto;
```

**HTML Structure**:
```html
<nav class="app-sidebar">
  <div class="sidebar-content">
    <div class="nav-section">
      <div class="section-title">Main</div>
      <!-- Navigation items -->
    </div>
  </div>
  <div class="sidebar-footer">
    <!-- User info and actions -->
  </div>
</nav>
```

### Navigation Sections

#### `nav-section` - Grouped Navigation
**Purpose**: Groups related navigation items under section headers.

**HTML Structure**:
```html
<div class="nav-section">
  <div class="section-title">Administration</div>
  <a href="/users" class="nav-item active">
    <i class="nav-icon users icon"></i>
    <span class="nav-text">User Management</span>
    <span class="nav-badge primary">24</span>
  </a>
  <a href="/roles" class="nav-item">
    <i class="nav-icon shield icon"></i>
    <span class="nav-text">Roles & Permissions</span>
  </a>
</div>
```

**Features**:
- Collapsible sections
- Section-specific styling
- Hierarchical organization
- Role-based visibility

#### `nav-item` - Individual Navigation Links
**Purpose**: Individual navigation links with icons, labels, and badges.

**States**:
- Default: Standard navigation item
- `active`: Currently selected page
- `disabled`: Unavailable or restricted
- `expanded`: Has visible submenu

**HTML Variations**:

##### Standard Navigation Item
```html
<a href="/dashboard" class="nav-item">
  <i class="nav-icon dashboard icon"></i>
  <span class="nav-text">Dashboard</span>
</a>
```

##### With Badge
```html
<a href="/messages" class="nav-item">
  <i class="nav-icon message icon"></i>
  <span class="nav-text">Messages</span>
  <span class="nav-badge warning">5</span>
</a>
```

##### With Submenu
```html
<div class="nav-item-group">
  <a href="/content" class="nav-item has-submenu">
    <i class="nav-icon folder icon"></i>
    <span class="nav-text">Content</span>
    <i class="nav-arrow right icon"></i>
  </a>
  <div class="nav-submenu">
    <a href="/content/articles" class="nav-subitem">Articles</a>
    <a href="/content/media" class="nav-subitem">Media Library</a>
  </div>
</div>
```

**Badge Variations**:
- `primary`: Blue badges for general counts
- `success`: Green badges for positive metrics
- `warning`: Orange badges for attention items
- `danger`: Red badges for critical issues

### Navigation Icons

#### `nav-icon` - Semantic Icons
**Purpose**: Provides visual context for navigation items with consistent styling.

**Color Coding**:
```css
.nav-icon.primary { color: #007bff; }    /* Administration */
.nav-icon.success { color: #28a745; }    /* Data/Content */
.nav-icon.warning { color: #ffc107; }    /* Monitoring */
.nav-icon.danger { color: #dc3545; }     /* Security */
.nav-icon.info { color: #17a2b8; }       /* Analytics */
```

**Common Icon Mappings**:
- Dashboard: `dashboard icon`
- Users: `users icon`
- Content: `folder icon`
- Analytics: `chart line icon`
- Settings: `cog icon`
- Security: `shield icon`
- Messages: `message icon`

### Sidebar Footer

#### `sidebar-footer` - User Information Panel
**Purpose**: Displays current user information and quick access to account functions.

**HTML Structure**:
```html
<div class="sidebar-footer">
  <div class="user-panel">
    <div class="user-avatar large">JD</div>
    <div class="user-details">
      <div class="user-name">John Doe</div>
      <div class="user-role">Administrator</div>
      <div class="user-status online">
        <span class="status-dot"></span>
        Online
      </div>
    </div>
    <div class="user-actions">
      <button class="ui button icon mini">
        <i class="cog icon"></i>
      </button>
    </div>
  </div>
</div>
```

**Features**:
- User avatar with status indication
- Role display
- Quick settings access
- Online/offline status
- Logout functionality

---

## 3. Tools Panel

The contextual tools and widgets panel that appears on the right side of the application.

### `app-tools` - Tools Panel Container

**Purpose**: Houses contextual tools, widgets, and secondary information relevant to the current view.

**Structure**:
```css
width: 320px;
background: #ffffff;
border-left: 1px solid #e1e4e8;
overflow-y: auto;
```

**HTML Structure**:
```html
<aside class="app-tools">
  <div class="tools-header">
    <h3 class="tools-title">Properties</h3>
    <button class="tools-toggle">
      <i class="times icon"></i>
    </button>
  </div>
  <div class="tools-content">
    <!-- Tool sections and widgets -->
  </div>
</aside>
```

### Tool Sections

#### `tool-section` - Grouped Tool Areas
**Purpose**: Groups related tools and controls under collapsible sections.

**HTML Structure**:
```html
<div class="tool-section">
  <div class="section-header">
    <h4 class="section-title">Object Properties</h4>
    <button class="section-toggle">
      <i class="chevron down icon"></i>
    </button>
  </div>
  <div class="section-content">
    <!-- Tool content -->
  </div>
</div>
```

**Features**:
- Collapsible sections
- Icon indicators
- Smooth animations
- Persistent state

#### `tool-widget` - Individual Tool Components
**Purpose**: Self-contained tool components with specific functionality.

**HTML Structure**:
```html
<div class="tool-widget">
  <div class="widget-header">
    <h5 class="widget-title">Color Picker</h5>
    <div class="widget-actions">
      <button class="ui button mini">Reset</button>
    </div>
  </div>
  <div class="widget-content">
    <!-- Widget-specific content -->
  </div>
</div>
```

**Widget Types**:
- Property editors
- Color pickers
- Layer managers
- File browsers
- Recent items
- Quick actions

### Tool Items

#### `tool-item` - Individual Tool Controls
**Purpose**: Individual interactive elements within tool sections.

**HTML Variations**:

##### Button Tool
```html
<div class="tool-item">
  <button class="tool-button">
    <i class="tool-icon pencil icon"></i>
    <span class="tool-label">Draw</span>
    <span class="tool-shortcut">D</span>
  </button>
</div>
```

##### Property Editor
```html
<div class="tool-item">
  <label class="tool-label">Opacity</label>
  <div class="tool-control">
    <input type="range" min="0" max="100" value="100" class="tool-slider">
    <span class="tool-value">100%</span>
  </div>
</div>
```

##### Toggle Control
```html
<div class="tool-item">
  <div class="ui toggle checkbox">
    <input type="checkbox" id="visible">
    <label for="visible">Visible</label>
  </div>
</div>
```

**Features**:
- Keyboard shortcuts display
- Real-time value updates
- Contextual help tooltips
- State persistence

---

## 4. Action Bars

Floating or fixed action bars that provide quick access to primary actions.

### `action-bar` - Primary Action Container

**Purpose**: Sticky action bar for primary page actions and controls.

**Structure**:
```css
position: sticky;
top: 60px; /* Below header */
z-index: 100;
background: white;
border-bottom: 1px solid #e1e4e8;
padding: 1rem 2rem;
```

**HTML Structure**:
```html
<div class="action-bar">
  <div class="action-bar-left">
    <h2 class="page-title">User Management</h2>
    <span class="page-subtitle">24 users total</span>
  </div>
  <div class="action-bar-center">
    <div class="page-info">
      <span class="info-item">
        <i class="check circle icon"></i>
        5 active
      </span>
      <span class="info-item">
        <i class="clock icon"></i>
        3 pending
      </span>
    </div>
  </div>
  <div class="action-bar-right">
    <div class="control-group">
      <button class="ui button">
        <i class="download icon"></i>
        Export
      </button>
      <button class="ui button primary">
        <i class="plus icon"></i>
        Add User
      </button>
    </div>
  </div>
</div>
```

### Action Bar Sections

#### `action-bar-left` - Title and Context
**Purpose**: Displays page title, subtitle, and contextual information.

**Components**:
- Page title
- Subtitle or description
- Context indicators
- Status badges

#### `action-bar-center` - Information Display
**Purpose**: Shows relevant metrics, status information, or filters.

**Components**:
- Status counts
- Filter indicators
- Progress information
- Real-time metrics

#### `action-bar-right` - Primary Actions
**Purpose**: Houses the most important actions for the current view.

**Components**:
- Primary action buttons
- Secondary actions
- Dropdown menus
- Export/import tools

### Action Bar Variants

#### `floating` - Floating Action Bar
**Purpose**: Floating action bar that appears above content.

```html
<div class="action-bar floating">
  <div class="action-group">
    <button class="ui button circular primary large">
      <i class="plus icon"></i>
    </button>
  </div>
</div>
```

**Features**:
- Fixed positioning
- Circular buttons
- Material Design styling
- Auto-hide on scroll

#### `bottom` - Bottom Action Bar
**Purpose**: Mobile-friendly action bar at bottom of screen.

```html
<div class="action-bar bottom">
  <button class="action-button">
    <i class="save icon"></i>
    <span>Save</span>
  </button>
  <button class="action-button">
    <i class="share icon"></i>
    <span>Share</span>
  </button>
</div>
```

**Features**:
- Mobile-optimized
- Equal-width buttons
- Icon + text layout
- Safe area support

---

## 5. Status & Loading Components

Components for displaying system states, loading indicators, and user feedback.

### Status Indicators

#### `status-indicator` - System Status Display
**Purpose**: Shows the current state of items, processes, or system components.

**HTML Structure**:
```html
<span class="status-indicator success">
  <span class="status-dot"></span>
  <span class="status-text">Active</span>
</span>
```

**Status Types**:
- `success`: Green indicator for positive states
- `warning`: Orange indicator for caution states
- `danger`: Red indicator for error states
- `info`: Blue indicator for informational states
- `neutral`: Gray indicator for inactive states

**Variations**:

##### With Icon
```html
<span class="status-indicator success">
  <i class="status-icon check circle icon"></i>
  <span class="status-text">Completed</span>
</span>
```

##### Dot Only
```html
<span class="status-indicator warning dot-only">
  <span class="status-dot"></span>
</span>
```

##### Pulsing (For Active States)
```html
<span class="status-indicator info pulse">
  <span class="status-dot"></span>
  <span class="status-text">Processing</span>
</span>
```

### Loading Components

#### `loading-overlay` - Full Screen Loading
**Purpose**: Covers the entire interface during major loading operations.

**HTML Structure**:
```html
<div class="loading-overlay">
  <div class="loading-content">
    <div class="ui loader active large"></div>
    <div class="loading-text">Loading your data...</div>
    <div class="loading-progress">
      <div class="ui progress">
        <div class="bar" style="width: 45%;"></div>
      </div>
      <div class="progress-text">45% complete</div>
    </div>
  </div>
</div>
```

**Features**:
- Backdrop overlay
- Centered loading indicator
- Progress bar support
- Descriptive text
- Cancellation option

#### `loading-spinner` - Inline Loading
**Purpose**: Shows loading state for specific components or sections.

**HTML Variations**:

##### Small Spinner
```html
<div class="loading-spinner small">
  <div class="ui loader active small"></div>
</div>
```

##### With Text
```html
<div class="loading-spinner">
  <div class="ui loader active"></div>
  <span class="loading-text">Updating...</span>
</div>
```

##### Button Loading State
```html
<button class="ui button loading">
  Saving...
</button>
```

### Empty States

#### `empty-state` - No Data Display
**Purpose**: Friendly message when no data is available with call-to-action.

**HTML Structure**:
```html
<div class="empty-state">
  <div class="empty-icon">
    <i class="folder open outline icon huge"></i>
  </div>
  <div class="empty-title">No projects found</div>
  <div class="empty-description">
    Get started by creating your first project
  </div>
  <div class="empty-actions">
    <button class="ui button primary">
      <i class="plus icon"></i>
      Create Project
    </button>
  </div>
</div>
```

**Features**:
- Large illustrative icon
- Clear messaging
- Primary action button
- Secondary help links

### Toast Notifications

#### `toast` - Feedback Messages
**Purpose**: Temporary notifications for user feedback and system messages.

**HTML Structure**:
```html
<div class="toast success">
  <div class="toast-content">
    <i class="toast-icon success check circle icon"></i>
    <div class="toast-message">
      <div class="toast-title">Success!</div>
      <div class="toast-description">User has been created successfully</div>
    </div>
    <button class="toast-close">×</button>
  </div>
</div>
```

**Toast Types**:
- `success`: Green for positive feedback
- `warning`: Orange for warnings
- `error`: Red for errors
- `info`: Blue for information

**Features**:
- Auto-dismiss timers
- Manual close button
- Stacking support
- Animation effects
- Action buttons

**JavaScript Integration**:
```javascript
// Show toast notification
function showToast(type, title, message) {
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `
    <div class="toast-content">
      <i class="toast-icon ${type} ${getIconForType(type)} icon"></i>
      <div class="toast-message">
        <div class="toast-title">${title}</div>
        <div class="toast-description">${message}</div>
      </div>
      <button class="toast-close">×</button>
    </div>
  `;
  
  document.body.appendChild(toast);
  
  // Auto-remove after 5 seconds
  setTimeout(() => {
    toast.remove();
  }, 5000);
}
```

---

## 6. Utility Components

Reusable utility components for common interface patterns.

### Breadcrumb Component

#### `breadcrumb` - Standalone Navigation
**Purpose**: Hierarchical navigation component that can be used anywhere.

**HTML Structure**:
```html
<nav class="breadcrumb">
  <a href="/dashboard" class="breadcrumb-item">
    <i class="home icon"></i>
    Home
  </a>
  <div class="breadcrumb-divider">/</div>
  <a href="/users" class="breadcrumb-item">Users</a>
  <div class="breadcrumb-divider">/</div>
  <div class="breadcrumb-item active">John Doe</div>
</nav>
```

**Features**:
- Customizable dividers
- Icon support
- Active state
- Mobile truncation

### Quick Filters

#### `quick-filters` - Filter Controls
**Purpose**: Horizontal filter controls for rapid data filtering.

**HTML Structure**:
```html
<div class="quick-filters">
  <div class="filter-group">
    <button class="filter-button active" data-filter="all">
      All Users
      <span class="filter-count">24</span>
    </button>
    <button class="filter-button" data-filter="active">
      Active
      <span class="filter-count">18</span>
    </button>
    <button class="filter-button" data-filter="pending">
      Pending
      <span class="filter-count">6</span>
    </button>
  </div>
</div>
```

**Features**:
- Count indicators
- Active state management
- Keyboard navigation
- Badge styling

### Tab Navigation

#### `tab-nav` - Tab Interface
**Purpose**: Tabbed navigation for sectioned content.

**HTML Structure**:
```html
<nav class="tab-nav">
  <a href="#overview" class="tab-item active">
    <i class="dashboard icon"></i>
    Overview
  </a>
  <a href="#details" class="tab-item">
    <i class="list icon"></i>
    Details
  </a>
  <a href="#permissions" class="tab-item">
    <i class="shield icon"></i>
    Permissions
    <span class="tab-badge">3</span>
  </a>
</nav>
```

**Features**:
- Icon support
- Badge indicators
- Responsive scrolling
- Keyboard navigation

### Search with Suggestions

#### `search-with-suggestions` - Enhanced Search
**Purpose**: Search component with auto-complete and suggestions.

**HTML Structure**:
```html
<div class="search-with-suggestions">
  <div class="ui input icon">
    <i class="search icon"></i>
    <input type="text" placeholder="Search users..." class="search-input">
  </div>
  <div class="search-suggestions">
    <div class="suggestion-group">
      <div class="suggestion-title">Recent Searches</div>
      <a href="#" class="suggestion-item">
        <i class="clock icon"></i>
        John Smith
      </a>
    </div>
    <div class="suggestion-group">
      <div class="suggestion-title">Users</div>
      <a href="#" class="suggestion-item">
        <i class="user icon"></i>
        Jane Doe
      </a>
    </div>
  </div>
</div>
```

**Features**:
- Grouped suggestions
- Recent searches
- Keyboard navigation
- Type-ahead filtering

### Pagination Controls

#### `pagination` - Page Navigation
**Purpose**: Navigation controls for paginated data.

**HTML Structure**:
```html
<div class="pagination">
  <button class="pagination-button" disabled>
    <i class="chevron left icon"></i>
    Previous
  </button>
  <div class="pagination-pages">
    <button class="pagination-page">1</button>
    <button class="pagination-page active">2</button>
    <button class="pagination-page">3</button>
    <span class="pagination-ellipsis">...</span>
    <button class="pagination-page">10</button>
  </div>
  <button class="pagination-button">
    Next
    <i class="chevron right icon"></i>
  </button>
</div>
```

**Features**:
- Ellipsis for large ranges
- Keyboard navigation
- Mobile-friendly
- Custom page sizes

### Card Headers

#### `card-header` - Card Title Component
**Purpose**: Consistent header styling for cards and panels.

**HTML Structure**:
```html
<div class="card-header">
  <div class="card-title">
    <h3>User Details</h3>
    <span class="card-subtitle">Last updated 2 hours ago</span>
  </div>
  <div class="card-actions">
    <button class="ui button icon">
      <i class="edit icon"></i>
    </button>
    <button class="ui button icon">
      <i class="ellipsis vertical icon"></i>
    </button>
  </div>
</div>
```

**Features**:
- Title and subtitle support
- Action buttons
- Icon integration
- Responsive layout

---

## 7. Interactive Elements

Components that provide rich interactivity and user engagement.

### Dropdown Menus

#### `dropdown-menu` - Context Menus
**Purpose**: Contextual dropdown menus with rich content support.

**HTML Structure**:
```html
<div class="dropdown-menu">
  <button class="dropdown-trigger">
    Actions
    <i class="chevron down icon"></i>
  </button>
  <div class="dropdown-content">
    <a href="#" class="dropdown-item">
      <i class="edit icon"></i>
      Edit User
    </a>
    <a href="#" class="dropdown-item">
      <i class="copy icon"></i>
      Duplicate
    </a>
    <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item danger">
      <i class="trash icon"></i>
      Delete User
    </a>
  </div>
</div>
```

### Modal Dialogs

#### `modal-dialog` - Dialog Windows
**Purpose**: Modal dialogs for focused tasks and confirmations.

**HTML Structure**:
```html
<div class="modal-dialog">
  <div class="modal-backdrop"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h3 class="modal-title">Confirm Deletion</h3>
      <button class="modal-close">
        <i class="times icon"></i>
      </button>
    </div>
    <div class="modal-body">
      <p>Are you sure you want to delete this user? This action cannot be undone.</p>
    </div>
    <div class="modal-footer">
      <button class="ui button">Cancel</button>
      <button class="ui button danger">Delete</button>
    </div>
  </div>
</div>
```

### Tooltip Components

#### `tooltip` - Contextual Help
**Purpose**: Hover tooltips for additional information and help.

**HTML Structure**:
```html
<span class="tooltip" data-tooltip="This field is required">
  <i class="help circle icon"></i>
</span>
```

**Features**:
- Multiple positions (top, bottom, left, right)
- Rich HTML content support
- Keyboard accessible
- Touch-friendly

---

## Usage Examples

### Complete Dashboard Header
```html
<header class="app-header">
  <div class="header-left">
    <button class="mobile-menu-toggle">
      <i class="bars icon"></i>
    </button>
    <a href="/" class="logo">
      <div class="logo-icon">A</div>
      Analytics Pro
    </a>
    <nav class="breadcrumb-nav">
      <a href="/dashboard" class="breadcrumb-link">
        <i class="home icon"></i>
        Dashboard
      </a>
      <span class="breadcrumb-separator">/</span>
      <span class="breadcrumb-current">User Analytics</span>
    </nav>
  </div>
  <div class="header-center">
    <div class="search-with-suggestions">
      <div class="ui input icon">
        <i class="search icon"></i>
        <input type="text" placeholder="Search users, events, or metrics...">
      </div>
    </div>
  </div>
  <div class="header-right">
    <div class="notification-bell">
      <i class="bell icon"></i>
      <span class="notification-badge">3</span>
    </div>
    <div class="user-menu">
      <div class="user-avatar">AD</div>
      <div class="user-info">
        <div class="user-name">Admin User</div>
        <div class="user-role">Administrator</div>
      </div>
    </div>
  </div>
</header>
```

### Complete Sidebar Navigation
```html
<nav class="app-sidebar">
  <div class="sidebar-content">
    <!-- Main Navigation -->
    <div class="nav-section">
      <div class="section-title">Overview</div>
      <a href="/dashboard" class="nav-item active">
        <i class="nav-icon primary dashboard icon"></i>
        <span class="nav-text">Dashboard</span>
      </a>
      <a href="/analytics" class="nav-item">
        <i class="nav-icon info chart line icon"></i>
        <span class="nav-text">Analytics</span>
        <span class="nav-badge info">New</span>
      </a>
    </div>

    <!-- User Management -->
    <div class="nav-section">
      <div class="section-title">User Management</div>
      <a href="/users" class="nav-item">
        <i class="nav-icon primary users icon"></i>
        <span class="nav-text">Users</span>
        <span class="nav-badge primary">247</span>
      </a>
      <div class="nav-item-group">
        <a href="/roles" class="nav-item has-submenu">
          <i class="nav-icon warning shield icon"></i>
          <span class="nav-text">Roles & Permissions</span>
          <i class="nav-arrow right icon"></i>
        </a>
        <div class="nav-submenu">
          <a href="/roles/admin" class="nav-subitem">Administrators</a>
          <a href="/roles/editors" class="nav-subitem">Editors</a>
          <a href="/roles/viewers" class="nav-subitem">Viewers</a>
        </div>
      </div>
    </div>

    <!-- Content Management -->
    <div class="nav-section">
      <div class="section-title">Content</div>
      <a href="/content" class="nav-item">
        <i class="nav-icon success folder icon"></i>
        <span class="nav-text">Content Library</span>
      </a>
      <a href="/annotations" class="nav-item">
        <i class="nav-icon info tag icon"></i>
        <span class="nav-text">Annotations</span>
        <span class="nav-badge warning">12</span>
      </a>
    </div>

    <!-- System -->
    <div class="nav-section">
      <div class="section-title">System</div>
      <a href="/settings" class="nav-item">
        <i class="nav-icon cog icon"></i>
        <span class="nav-text">Settings</span>
      </a>
      <a href="/logs" class="nav-item">
        <i class="nav-icon danger file text icon"></i>
        <span class="nav-text">System Logs</span>
      </a>
    </div>
  </div>

  <!-- Sidebar Footer -->
  <div class="sidebar-footer">
    <div class="user-panel">
      <div class="user-avatar large">AD</div>
      <div class="user-details">
        <div class="user-name">Admin User</div>
        <div class="user-role">System Administrator</div>
        <div class="user-status online">
          <span class="status-dot"></span>
          Online
        </div>
      </div>
      <div class="user-actions">
        <button class="ui button icon mini">
          <i class="cog icon"></i>
        </button>
      </div>
    </div>
  </div>
</nav>
```

### Complete Tools Panel
```html
<aside class="app-tools">
  <div class="tools-header">
    <h3 class="tools-title">Properties</h3>
    <button class="tools-toggle">
      <i class="times icon"></i>
    </button>
  </div>
  
  <div class="tools-content">
    <!-- Object Properties -->
    <div class="tool-section">
      <div class="section-header">
        <h4 class="section-title">Object Properties</h4>
        <button class="section-toggle">
          <i class="chevron down icon"></i>
        </button>
      </div>
      <div class="section-content">
        <div class="tool-item">
          <label class="tool-label">Name</label>
          <div class="tool-control">
            <input type="text" value="Bounding Box 1" class="ui input">
          </div>
        </div>
        <div class="tool-item">
          <label class="tool-label">Opacity</label>
          <div class="tool-control">
            <input type="range" min="0" max="100" value="85" class="tool-slider">
            <span class="tool-value">85%</span>
          </div>
        </div>
        <div class="tool-item">
          <div class="ui toggle checkbox">
            <input type="checkbox" id="visible" checked>
            <label for="visible">Visible</label>
          </div>
        </div>
      </div>
    </div>

    <!-- Drawing Tools -->
    <div class="tool-section">
      <div class="section-header">
        <h4 class="section-title">Drawing Tools</h4>
      </div>
      <div class="section-content">
        <div class="tool-item">
          <button class="tool-button active">
            <i class="tool-icon mouse pointer icon"></i>
            <span class="tool-label">Select</span>
            <span class="tool-shortcut">V</span>
          </button>
        </div>
        <div class="tool-item">
          <button class="tool-button">
            <i class="tool-icon square outline icon"></i>
            <span class="tool-label">Rectangle</span>
            <span class="tool-shortcut">R</span>
          </button>
        </div>
        <div class="tool-item">
          <button class="tool-button">
            <i class="tool-icon circle outline icon"></i>
            <span class="tool-label">Circle</span>
            <span class="tool-shortcut">C</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Color Picker Widget -->
    <div class="tool-widget">
      <div class="widget-header">
        <h5 class="widget-title">Color</h5>
        <div class="widget-actions">
          <button class="ui button mini">Reset</button>
        </div>
      </div>
      <div class="widget-content">
        <div class="color-picker">
          <div class="color-preview" style="background-color: #3498db;"></div>
          <input type="color" value="#3498db" class="color-input">
        </div>
      </div>
    </div>

    <!-- Layers Widget -->
    <div class="tool-widget">
      <div class="widget-header">
        <h5 class="widget-title">Layers</h5>
        <div class="widget-actions">
          <button class="ui button mini icon">
            <i class="plus icon"></i>
          </button>
        </div>
      </div>
      <div class="widget-content">
        <div class="layer-list">
          <div class="layer-item active">
            <div class="layer-visibility">
              <i class="eye icon"></i>
            </div>
            <div class="layer-name">Annotations</div>
            <div class="layer-actions">
              <i class="ellipsis vertical icon"></i>
            </div>
          </div>
          <div class="layer-item">
            <div class="layer-visibility">
              <i class="eye slash icon"></i>
            </div>
            <div class="layer-name">Background</div>
            <div class="layer-actions">
              <i class="ellipsis vertical icon"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</aside>
```

### Complete Action Bar with Status
```html
<div class="action-bar">
  <div class="action-bar-left">
    <h2 class="page-title">Video Annotation</h2>
    <span class="page-subtitle">training-video-001.mp4</span>
  </div>
  <div class="action-bar-center">
    <div class="page-info">
      <span class="info-item">
        <i class="tag icon"></i>
        24 annotations
      </span>
      <span class="info-item">
        <i class="clock icon"></i>
        Last saved 2 minutes ago
      </span>
      <div class="status-indicator success">
        <span class="status-dot"></span>
        Auto-save active
      </div>
    </div>
  </div>
  <div class="action-bar-right">
    <div class="control-group">
      <button class="ui button">
        <i class="download icon"></i>
        Export
      </button>
      <button class="ui button">
        <i class="share icon"></i>
        Share
      </button>
      <button class="ui button primary">
        <i class="save icon"></i>
        Save Changes
      </button>
    </div>
  </div>
</div>
```

---

## Component Combinations

### Dashboard Page Layout
```html
<div class="app-layout">
  <!-- Header with search and notifications -->
  <header class="app-header">
    <!-- Header content as shown above -->
  </header>

  <!-- Sidebar navigation -->
  <nav class="app-sidebar">
    <!-- Sidebar content as shown above -->
  </nav>

  <!-- Main dashboard content -->
  <main class="app-main">
    <!-- Action bar -->
    <div class="action-bar">
      <div class="action-bar-left">
        <h2 class="page-title">Dashboard</h2>
        <span class="page-subtitle">Welcome back, Admin</span>
      </div>
      <div class="action-bar-right">
        <div class="quick-filters">
          <button class="filter-button active">Today</button>
          <button class="filter-button">This Week</button>
          <button class="filter-button">This Month</button>
        </div>
      </div>
    </div>

    <!-- Main content with status indicators -->
    <div class="content-container wide">
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value">1,247</div>
          <div class="stat-label">Total Users</div>
          <div class="status-indicator success">
            <span class="status-dot"></span>
            +12% from last month
          </div>
        </div>
        <!-- More stats -->
      </div>
    </div>
  </main>

  <!-- Tools panel for dashboard widgets -->
  <aside class="app-tools">
    <!-- Tools content for dashboard customization -->
  </aside>
</div>
```

### CRUD Interface with Loading States
```html
<div class="app-layout no-tools">
  <header class="app-header">
    <!-- Standard header -->
  </header>

  <nav class="app-sidebar">
    <!-- Navigation with user management active -->
  </nav>

  <main class="app-main">
    <!-- Action bar with filters -->
    <div class="action-bar">
      <div class="action-bar-left">
        <h2 class="page-title">User Management</h2>
      </div>
      <div class="action-bar-center">
        <div class="quick-filters">
          <button class="filter-button active">
            All Users
            <span class="filter-count">247</span>
          </button>
          <button class="filter-button">
            Active
            <span class="filter-count">231</span>
          </button>
          <button class="filter-button">
            Pending
            <span class="filter-count">16</span>
          </button>
        </div>
      </div>
      <div class="action-bar-right">
        <div class="search-with-suggestions">
          <div class="ui input icon">
            <i class="search icon"></i>
            <input type="text" placeholder="Search users...">
          </div>
        </div>
        <button class="ui button primary">
          <i class="plus icon"></i>
          Add User
        </button>
      </div>
    </div>

    <!-- Content with loading state -->
    <div class="content-container wide">
      <div class="table-container">
        <!-- Loading overlay while data loads -->
        <div class="loading-overlay">
          <div class="loading-content">
            <div class="ui loader active"></div>
            <div class="loading-text">Loading user data...</div>
          </div>
        </div>
        
        <!-- Table content (hidden during loading) -->
        <table class="ui table">
          <!-- Table content -->
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination">
        <button class="pagination-button" disabled>
          <i class="chevron left icon"></i>
          Previous
        </button>
        <div class="pagination-pages">
          <button class="pagination-page active">1</button>
          <button class="pagination-page">2</button>
          <button class="pagination-page">3</button>
        </div>
        <button class="pagination-button">
          Next
          <i class="chevron right icon"></i>
        </button>
      </div>
    </div>
  </main>
</div>
```

### Annotation Interface
```html
<div class="annotation-workspace">
  <!-- Annotation toolbar -->
  <div class="annotation-toolbar">
    <div class="toolbar-left">
      <button class="ui button icon">
        <i class="arrow left icon"></i>
      </button>
      <div class="breadcrumb">
        <span class="breadcrumb-item">Projects</span>
        <span class="breadcrumb-divider">/</span>
        <span class="breadcrumb-item">Training Videos</span>
        <span class="breadcrumb-divider">/</span>
        <span class="breadcrumb-item active">video-001.mp4</span>
      </div>
    </div>
    <div class="toolbar-center">
      <div class="playback-controls">
        <button class="ui button icon">
          <i class="play icon"></i>
        </button>
        <span class="playback-time">0:00 / 5:24</span>
      </div>
    </div>
    <div class="toolbar-right">
      <div class="status-indicator success">
        <span class="status-dot"></span>
        Auto-save active
      </div>
      <button class="ui button">
        <i class="save icon"></i>
        Save
      </button>
    </div>
  </div>

  <!-- Annotation sidebar -->
  <div class="annotation-sidebar">
    <div class="tool-section">
      <div class="section-title">Objects</div>
      <div class="object-list">
        <div class="object-item active">
          <div class="object-name">Person 1</div>
          <div class="object-type">person</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main canvas area -->
  <div class="annotation-canvas">
    <div class="canvas-container">
      <!-- Video/image with annotation overlays -->
    </div>
  </div>

  <!-- Properties panel -->
  <div class="annotation-properties">
    <!-- Properties as shown in tools panel example -->
  </div>

  <!-- Timeline -->
  <div class="annotation-timeline">
    <div class="timeline-track">
      <!-- Keyframe indicators -->
    </div>
  </div>
</div>
```

---

## Best Practices

### Component Usage Guidelines

#### 1. **Consistency**
- Use the same component patterns throughout your application
- Maintain consistent spacing and sizing
- Follow established color schemes for status indicators
- Use standard icon sets across all components

#### 2. **Accessibility**
- Include proper ARIA labels and roles
- Ensure keyboard navigation works for all interactive elements
- Maintain sufficient color contrast for status indicators
- Provide alternative text for icon-only buttons

```html
<!-- Good: Accessible button -->
<button class="tool-button" aria-label="Select tool" title="Select tool (V)">
  <i class="tool-icon mouse pointer icon" aria-hidden="true"></i>
  <span class="tool-label">Select</span>
  <span class="tool-shortcut">V</span>
</button>
```

#### 3. **Performance**
- Use CSS transforms for animations instead of changing layout properties
- Implement virtual scrolling for large lists
- Lazy load heavy components like modals
- Optimize image assets in components

#### 4. **Responsive Design**
- Components automatically adapt to mobile layouts
- Use touch-friendly sizes on mobile devices
- Collapse navigation appropriately
- Stack components vertically on smaller screens

### State Management

#### Loading States
```html
<!-- Show loading state while data is being fetched -->
<div class="nav-item loading">
  <div class="ui loader active mini"></div>
  <span class="nav-text">Loading...</span>
</div>
```

#### Error States
```html
<!-- Show error state when something goes wrong -->
<div class="empty-state error">
  <div class="empty-icon">
    <i class="exclamation triangle icon huge"></i>
  </div>
  <div class="empty-title">Failed to load data</div>
  <div class="empty-actions">
    <button class="ui button" onclick="retryLoad()">
      <i class="refresh icon"></i>
      Try Again
    </button>
  </div>
</div>
```

#### Success States
```html
<!-- Show success feedback -->
<div class="toast success">
  <div class="toast-content">
    <i class="toast-icon success check circle icon"></i>
    <div class="toast-message">
      <div class="toast-title">Success!</div>
      <div class="toast-description">User profile updated successfully</div>
    </div>
  </div>
</div>
```

### Component Customization

#### CSS Custom Properties
Use CSS custom properties for easy theming:

```css
:root {
  --primary-color: #007bff;
  --success-color: #28a745;
  --warning-color: #ffc107;
  --danger-color: #dc3545;
  --sidebar-width: 280px;
  --header-height: 60px;
  --tools-width: 320px;
}
```

#### Modifier Classes
Create modifier classes for component variations:

```html
<!-- Large notification badge -->
<div class="notification-bell large">
  <i class="bell icon"></i>
  <span class="notification-badge">99+</span>
</div>

<!-- Compact sidebar -->
<nav class="app-sidebar compact">
  <!-- Reduced spacing and sizes -->
</nav>
```

### JavaScript Integration

#### Event Handling
```javascript
// Handle navigation clicks
document.addEventListener('click', (e) => {
  if (e.target.closest('.nav-item')) {
    const navItem = e.target.closest('.nav-item');
    setActiveNavItem(navItem);
  }
  
  if (e.target.closest('.toast-close')) {
    const toast = e.target.closest('.toast');
    hideToast(toast);
  }
});

// Handle search input
const searchInput = document.querySelector('.search-input');
searchInput.addEventListener('input', debounce((e) => {
  performSearch(e.target.value);
}, 300));
```

#### State Persistence
```javascript
// Save sidebar state
function toggleSidebar() {
  const sidebar = document.querySelector('.app-sidebar');
  sidebar.classList.toggle('collapsed');
  localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
}

// Restore sidebar state
window.addEventListener('load', () => {
  const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
  if (isCollapsed) {
    document.querySelector('.app-sidebar').classList.add('collapsed');
  }
});
```

### Testing Components

#### Visual Testing
- Test components in different browsers
- Verify responsive behavior at various screen sizes
- Check dark mode and high contrast compatibility
- Validate print styles for action bars and navigation

#### Functional Testing
- Test keyboard navigation for all interactive elements
- Verify screen reader compatibility
- Test touch interactions on mobile devices
- Validate form submission and error handling

#### Performance Testing
- Measure component render times
- Test with large datasets (1000+ navigation items)
- Verify smooth animations on lower-end devices
- Monitor memory usage with dynamic components

This comprehensive component system provides the building blocks for creating professional, accessible, and maintainable user interfaces that work seamlessly with your grid system.