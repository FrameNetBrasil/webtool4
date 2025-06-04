You should **prioritize Fomantic-UI components** whenever they exist and meet your needs, then use the custom components I've defined to fill the gaps or enhance the base library. Here's the recommended approach:

## **Use Fomantic-UI When Available:**

### **Core Components (Use Fomantic-UI)**
```html
<!-- Use Fomantic-UI's existing components -->
<button class="ui button primary">
  <i class="plus icon"></i>
  Add User
</button>

<div class="ui input icon">
  <i class="search icon"></i>
  <input type="text" placeholder="Search...">
</div>

<div class="ui card">
  <div class="content">
    <div class="header">User Name</div>
    <div class="description">User details...</div>
  </div>
</div>

<table class="ui table">
  <thead>
    <tr><th>Name</th><th>Email</th></tr>
  </thead>
  <tbody>
    <tr><td>John</td><td>john@example.com</td></tr>
  </tbody>
</table>

<div class="ui progress">
  <div class="bar" style="width: 60%;"></div>
</div>

<div class="ui loader active"></div>
```

## **Use Custom Components for Application Structure:**

### **Layout & Navigation (Use Custom)**
These don't exist in Fomantic-UI or need specific behavior:

```html
<!-- Custom application layout -->
<div class="app-layout">
  <header class="app-header">
    <!-- Custom header with logo, breadcrumbs, search -->
  </header>
  <nav class="app-sidebar">
    <!-- Custom sidebar navigation -->
  </nav>
  <main class="app-main">
    <!-- Content using Fomantic-UI components inside -->
  </main>
  <aside class="app-tools">
    <!-- Custom tools panel -->
  </aside>
</div>

<!-- Custom action bars -->
<div class="action-bar">
  <div class="action-bar-left">
    <h2 class="page-title">Users</h2>
  </div>
  <div class="action-bar-right">
    <!-- Fomantic-UI buttons inside custom container -->
    <button class="ui button primary">Add User</button>
  </div>
</div>

<!-- Custom status indicators -->
<span class="status-indicator success">
  <span class="status-dot"></span>
  Active
</span>
```

## **Hybrid Approach - Best of Both:**

### **Enhanced Fomantic-UI Components**
Wrap Fomantic-UI components in custom containers for additional functionality:

```html
<!-- Custom search with Fomantic-UI input -->
<div class="search-with-suggestions">
  <div class="ui input icon">
    <i class="search icon"></i>
    <input type="text" placeholder="Search...">
  </div>
  <div class="search-suggestions">
    <!-- Custom suggestions dropdown -->
  </div>
</div>

<!-- Custom card grid with Fomantic-UI cards -->
<div class="card-grid">
  <div class="ui card">
    <div class="content">
      <div class="header">Project Name</div>
    </div>
  </div>
  <!-- More Fomantic-UI cards -->
</div>

<!-- Custom toast with Fomantic-UI styling -->
<div class="toast success">
  <div class="toast-content">
    <i class="ui icon check circle"></i>
    <div class="toast-message">Success!</div>
  </div>
</div>
```

## **Component Decision Matrix:**

| Component Type | Use Fomantic-UI | Use Custom | Reason |
|----------------|----------------|------------|---------|
| **Buttons** | ✅ `ui button` | ❌ | Well-designed, themeable |
| **Forms** | ✅ `ui form` | ❌ | Complete form system |
| **Tables** | ✅ `ui table` | ❌ | Rich features, sorting |
| **Cards** | ✅ `ui card` | ❌ | Flexible content container |
| **Modals** | ✅ `ui modal` | ❌ | Full-featured dialogs |
| **Dropdowns** | ✅ `ui dropdown` | ❌ | Rich interactions |
| **Progress** | ✅ `ui progress` | ❌ | Animated, themeable |
| **Loaders** | ✅ `ui loader` | ❌ | Smooth animations |
| **Icons** | ✅ Fomantic icons | ❌ | Comprehensive icon set |
| **App Layout** | ❌ | ✅ `app-layout` | Specific to your needs |
| **Sidebar Nav** | ❌ | ✅ `app-sidebar` | Custom navigation structure |
| **Action Bars** | ❌ | ✅ `action-bar` | Application-specific |
| **Status Indicators** | ❌ | ✅ `status-indicator` | Custom visual design |
| **Grid Systems** | ❌ | ✅ `dashboard-grid` | Specialized layouts |
| **Tools Panel** | ❌ | ✅ `app-tools` | Annotation-specific |

## **Updated Implementation Strategy:**

### **1. Start with Fomantic-UI Base**
```html
<!-- Use Fomantic-UI for core interactions -->
<div class="content-container">
  <div class="ui form">
    <div class="field">
      <label>User Name</label>
      <div class="ui input">
        <input type="text" placeholder="Enter name...">
      </div>
    </div>
    <button class="ui button primary">Save User</button>
  </div>
</div>
```

### **2. Add Custom Layout Structure**
```html
<!-- Wrap in custom layout components -->
<div class="app-layout">
  <header class="app-header">
    <!-- Custom header -->
  </header>
  <main class="app-main">
    <div class="content-container">
      <!-- Fomantic-UI components here -->
      <div class="ui form">...</div>
    </div>
  </main>
</div>
```

### **3. Enhance with Custom Utilities**
```html
<!-- Add custom status and layout components -->
<div class="action-bar">
  <div class="action-bar-left">
    <h2 class="page-title">Edit User</h2>
    <span class="status-indicator success">
      <span class="status-dot"></span>
      Saved
    </span>
  </div>
  <div class="action-bar-right">
    <button class="ui button">Cancel</button>
    <button class="ui button primary">Save Changes</button>
  </div>
</div>
```

## **Key Benefits of This Approach:**

1. **Leverage Fomantic-UI's polish** for forms, buttons, tables, modals
2. **Custom components** handle application-specific layouts and patterns
3. **Consistent theming** across both systems
4. **Reduced development time** by not reinventing core UI components
5. **Better maintenance** using established library components
6. **Flexibility** to extend Fomantic-UI when needed

**Bottom line:** Use Fomantic-UI as your UI component foundation, and layer custom components on top for application structure, specialized layouts, and enhanced functionality that the library doesn't provide!
