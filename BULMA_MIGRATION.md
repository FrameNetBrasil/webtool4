# Fomantic-UI to Bulma Migration Guide

## Overview
This document outlines the migration from Fomantic-UI to Bulma CSS framework for FNBr Webtool 4.0.

## Installation Status ✅
- ✅ Bulma 1.0.4 installed via npm
- ✅ SASS compilation configured in Vite
- ✅ Directory structure created
- ✅ Base variables and colors migrated
- ✅ Alpine.js components for interactivity created

## Component Mapping

### Layout Components

| Fomantic-UI | Bulma Equivalent | Alpine.js Component | Status | Notes |
|-------------|------------------|---------------------|---------|-------|
| `ui container` | `container` | None | ✅ Ready | Direct mapping |
| `ui grid` | `columns` | None | ✅ Ready | `.columns` wrapper with `.column` children |
| `ui row` | `columns` | None | ✅ Ready | Each row becomes `.columns` |
| `ui column` | `column is-*` | None | ✅ Ready | Size with `is-1` to `is-12` |
| `ui segment` | `box` or `section` | None | ✅ Ready | Context dependent |

### Navigation Components

| Fomantic-UI | Bulma Equivalent | Alpine.js Component | Status | Notes |
|-------------|------------------|---------------------|---------|-------|
| `ui menu` | `navbar` or `menu` | `navbar` | ✅ Ready | Context dependent |
| `ui dropdown` | `dropdown` | `dropdown` | ✅ Ready | Requires Alpine.js for interactivity |
| `ui breadcrumb` | `breadcrumb` | None | ✅ Ready | Direct mapping |
| `ui pagination` | `pagination` | None | ✅ Ready | Direct mapping |
| `ui sidebar` | `menu` | None | 🔄 Pending | Custom implementation needed |

### Form Components

| Fomantic-UI | Bulma Equivalent | Alpine.js Component | Status | Notes |
|-------------|------------------|---------------------|---------|-------|
| `ui form` | `form` with `.field` | `formValidation` | ✅ Ready | Form structure changes |
| `ui input` | `input` with `.control` | None | ✅ Ready | Wrapper structure needed |
| `ui button` | `button` | None | ✅ Ready | Direct mapping with variants |
| `ui checkbox` | `checkbox` | None | ✅ Ready | Structure changes |
| `ui radio` | `radio` | None | ✅ Ready | Structure changes |
| `ui dropdown` | `select` or custom | `dropdown` | ✅ Ready | Select or custom dropdown |

### Content Components

| Fomantic-UI | Bulma Equivalent | Alpine.js Component | Status | Notes |
|-------------|------------------|---------------------|---------|-------|
| `ui card` | `card` | None | ✅ Ready | Direct mapping |
| `ui message` | `notification` | `notification` | ✅ Ready | Alpine.js for dismiss functionality |
| `ui modal` | `modal` | `modal` | ✅ Ready | Alpine.js for show/hide logic |
| `ui table` | `table` | None | ✅ Ready | Direct mapping |
| `ui list` | Custom | None | 🔄 Pending | Custom implementation |
| `ui loader` | `is-loading` | None | ✅ Ready | Loading states |

### Interactive Components

| Fomantic-UI | Bulma Equivalent | Alpine.js Component | Status | Notes |
|-------------|------------------|---------------------|---------|-------|
| `ui accordion` | Custom | `accordion` | ✅ Ready | Full Alpine.js implementation |
| `ui tabs` | `tabs` | `tabs` | ✅ Ready | Alpine.js for switching logic |
| `ui progress` | `progress` | None | ✅ Ready | Direct mapping |
| `ui dimmer` | `modal-background` | None | ✅ Ready | Part of modal system |

## Class Migration Examples

### Grid System
```html
<!-- Before (Fomantic-UI) -->
<div class="ui grid">
  <div class="eight wide column">Content</div>
  <div class="eight wide column">Content</div>
</div>

<!-- After (Bulma) -->
<div class="columns">
  <div class="column is-half">Content</div>
  <div class="column is-half">Content</div>
</div>
```

### Buttons
```html
<!-- Before (Fomantic-UI) -->
<button class="ui primary button">Click me</button>
<button class="ui secondary button">Cancel</button>

<!-- After (Bulma) -->
<button class="button is-primary">Click me</button>
<button class="button">Cancel</button>
```

### Cards
```html
<!-- Before (Fomantic-UI) -->
<div class="ui card">
  <div class="content">
    <div class="header">Title</div>
    <div class="description">Content</div>
  </div>
</div>

<!-- After (Bulma) -->
<div class="card">
  <div class="card-content">
    <p class="title is-4">Title</p>
    <p class="content">Content</p>
  </div>
</div>
```

### Forms
```html
<!-- Before (Fomantic-UI) -->
<div class="ui form">
  <div class="field">
    <label>Name</label>
    <input type="text" name="name">
  </div>
</div>

<!-- After (Bulma) -->
<form>
  <div class="field">
    <label class="label">Name</label>
    <div class="control">
      <input class="input" type="text" name="name">
    </div>
  </div>
</form>
```

### Modals with Alpine.js
```html
<!-- Before (Fomantic-UI) -->
<div class="ui modal" id="myModal">
  <div class="header">Modal Title</div>
  <div class="content">Modal content</div>
</div>

<!-- After (Bulma + Alpine.js) -->
<div x-data="modal()" class="modal" :class="{ 'is-active': isActive }">
  <div class="modal-background" @click="close()"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Modal Title</p>
      <button class="delete" @click="close()"></button>
    </header>
    <section class="modal-card-body">
      Modal content
    </section>
  </div>
</div>
```

## Color System

### CSS Custom Properties (Maintained)
All existing CSS custom properties for domain-specific colors are preserved:
- Frame entities: `--frame-color`, `--frame-bg`, `--frame-border`
- LU entities: `--lu-color`, `--lu-bg`, `--lu-border`
- Construction entities: `--construction-color`, etc.
- Annotation layers: `--layer-frame`, `--layer-fe`, etc.

### Bulma Variables
New SASS variables mapped to existing color palette:
```scss
// Primary colors
$primary: #2563eb;        // blue-600
$success: #16a34a;        // green-600
$warning: #f59e0b;        // yellow-500
$danger: #dc2626;         // red-600
$info: #4f46e5;          // indigo-600
```

## Migration Phases

### Phase 1: Foundation ✅ COMPLETED
- [x] Install Bulma and SASS
- [x] Configure Vite for SASS compilation
- [x] Create directory structure
- [x] Migrate color system
- [x] Create Alpine.js components

### Phase 2: Core Components 🔄 IN PROGRESS
- [ ] Create layout templates (header, sidebar, footer)
- [ ] Migrate reusable components (cards, forms, buttons)
- [ ] Test Alpine.js interactive components
- [ ] Update dataGrid component for Bulma classes

### Phase 3: Template Migration 🔄 PENDING
- [ ] Update main layout templates
- [ ] Migrate annotation interfaces
- [ ] Migrate report templates
- [ ] Update dashboard and management pages

### Phase 4: Testing & Cleanup 🔄 PENDING
- [ ] Cross-browser testing
- [ ] Accessibility compliance check
- [ ] Performance optimization
- [ ] Remove Fomantic-UI dependencies

## Development Workflow

### Building Assets
```bash
# Development with hot reload
npm run dev

# Production build
npm run build
```

### Testing Components
1. Create component in SASS
2. Test Alpine.js functionality
3. Verify responsive behavior
4. Check accessibility

### File Organization
```
resources/
├── sass/
│   ├── abstracts/          # Variables, functions, mixins
│   ├── base/               # Reset, typography
│   ├── components/         # UI components
│   ├── layout/            # Layout templates
│   ├── pages/             # Page-specific styles
│   └── utilities/         # Helpers, overrides
└── js/
    └── components/
        └── bulmaComponents.js  # Alpine.js components
```

## Breaking Changes

### Template Structure Changes
- Grid system: `ui grid` → `columns`
- Form structure: Additional `.control` wrappers needed
- Card content: `.content` → `.card-content`
- Button groups: Structure changes for `.buttons`

### JavaScript Changes
- Interactive components now require Alpine.js
- Custom events for component communication
- No more Fomantic-UI JavaScript dependencies

### CSS Class Changes
- Size classes: `eight wide` → `is-half`
- Color classes: `ui primary` → `is-primary`
- State classes: `active` → `is-active`

## Migration Checklist

### Before Starting
- [ ] Backup current templates
- [ ] Create feature branch
- [ ] Set up parallel development environment

### During Migration
- [ ] Component-by-component approach
- [ ] Test each component thoroughly
- [ ] Maintain functionality parity
- [ ] Document any custom solutions

### After Migration
- [ ] Remove Fomantic-UI dependencies
- [ ] Update documentation
- [ ] Team training on new patterns
- [ ] Performance comparison

## Resources

- [Bulma Documentation](https://bulma.io/documentation/)
- [Alpine.js Documentation](https://alpinejs.dev/)
- [Migration Examples Repository](internal link)
- [Component Library](internal link)

## Support

For questions or issues during migration:
1. Check this documentation
2. Review component examples
3. Test in isolation
4. Document custom solutions

---

**Last Updated:** {{ date }}  
**Migration Status:** Phase 1 Complete, Phase 2 In Progress