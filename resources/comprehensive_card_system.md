# Comprehensive Card System Documentation

A complete guide to implementing and using cards in your FrameNet application, combining Fomantic-UI's card system with custom extensions for specific use cases.

---

## Table of Contents

1. [Card Philosophy](#card-philosophy)
2. [Base Card System](#base-card-system)
3. [Card Types and Use Cases](#card-types-and-use-cases)
4. [Grid Integration](#grid-integration)
5. [Custom Card Variants](#custom-card-variants)
6. [LESS Implementation](#less-implementation)
7. [JavaScript Integration](#javascript-integration)
8. [Best Practices](#best-practices)

---

## Card Philosophy

### When to Use Cards
Cards should be used when you need to:
- **Group related information** in a scannable format
- **Provide action targets** for navigation or selection
- **Display content previews** with metadata
- **Create visual hierarchy** in data-heavy interfaces
- **Enable quick comparison** between items

### When NOT to Use Cards
Avoid cards for:
- **Simple lists** where text-only is sufficient
- **Data tables** where row-based layout is more efficient
- **Linear content** like articles or documentation
- **Single actions** where buttons are more appropriate

---

## Base Card System

### Fomantic-UI Foundation
All cards build on Fomantic-UI's base card structure:

```html
<div class="ui card">
  <div class="image">
    <!-- Image content -->
  </div>
  <div class="content">
    <div class="header">Card Title</div>
    <div class="meta">Metadata</div>
    <div class="description">Card description</div>
  </div>
  <div class="extra content">
    <!-- Additional actions or info -->
  </div>
</div>
```

### Card Grid Integration
Cards work seamlessly with your grid system:

```html
<div class="card-grid">
  <div class="ui card">...</div>
  <div class="ui card">...</div>
  <div class="ui card">...</div>
</div>
```

**Grid Variants:**
- `card-grid` - Standard responsive grid (320px minimum)
- `card-grid dense` - Compact grid (280px minimum)
- `card-grid wide-cards` - Large cards (400px minimum)
- `card-grid small-cards` - Small cards (240px minimum)

---

## Card Types and Use Cases

### 1. **Option Cards** - Navigation and Selection

**Purpose:** Primary navigation to different sections/features
**When to use:** Main landing pages, category selection, feature discovery

```html
<div class="ui card option-card" data-category="reports">
  <div class="content">
    <div class="option-card-icon reports">
      <i class="fas fa-chart-bar"></i>
    </div>
    <div class="header">Reports & Analytics</div>
    <div class="description">
      Comprehensive analysis tools for FrameNet data including frames and lexical units.
    </div>
    <div class="option-card-meta">
      <span class="option-card-count">1,230 reports</span>
      <span class="option-card-updated">Updated 2h ago</span>
    </div>
  </div>
</div>
```

**Key Features:**
- Large colored icon
- Clear title and description
- Metadata (counts, status, timestamps)
- Hover effects and click animations

### 2. **Data Cards** - Entity Display

**Purpose:** Display individual data entities (users, frames, constructions)
**When to use:** Browse views, search results, entity listings

```html
<div class="ui card data-card" data-entity-id="123">
  <div class="content">
    <div class="data-card-header">
      <div class="data-card-avatar">
        <i class="user icon"></i>
      </div>
      <div class="data-card-title">
        <div class="header">John Doe</div>
        <div class="meta">Administrator</div>
      </div>
      <div class="data-card-status">
        <div class="ui green label">Active</div>
      </div>
    </div>
    <div class="description">
      Research administrator specializing in semantic frame analysis and lexical unit categorization.
    </div>
    <div class="data-card-stats">
      <div class="statistic">
        <div class="value">47</div>
        <div class="label">Annotations</div>
      </div>
      <div class="statistic">
        <div class="value">12</div>
        <div class="label">Reports</div>
      </div>
    </div>
  </div>
  <div class="extra content">
    <div class="data-card-actions">
      <button class="ui button mini">View</button>
      <button class="ui button mini primary">Edit</button>
    </div>
  </div>
</div>
```

**Key Features:**
- Avatar/icon representation
- Status indicators
- Key statistics
- Quick action buttons

### 3. **Summary Cards** - Metrics and KPIs

**Purpose:** Display key metrics, statistics, and performance indicators
**When to use:** Dashboards, analytics pages, overview sections

```html
<div class="ui card summary-card primary">
  <div class="content">
    <div class="summary-card-header">
      <div class="summary-card-icon">
        <i class="users icon"></i>
      </div>
      <div class="summary-card-trend positive">
        <i class="arrow up icon"></i>
        +12%
      </div>
    </div>
    <div class="summary-card-value">
      <div class="statistic">
        <div class="value">1,247</div>
        <div class="label">Total Users</div>
      </div>
    </div>
    <div class="summary-card-description">
      Active users in the last 30 days
    </div>
  </div>
</div>
```

**Key Features:**
- Large numeric values
- Trend indicators
- Colored themes
- Contextual icons

### 4. **Action Cards** - Quick Actions

**Purpose:** Provide quick access to common actions and tools
**When to use:** Quick action panels, workflow shortcuts, tool palettes

```html
<div class="ui card action-card" data-action="export">
  <div class="content">
    <div class="action-card-icon">
      <i class="download icon"></i>
    </div>
    <div class="header">Export Data</div>
    <div class="description">
      Download reports and data in various formats (CSV, JSON, XML)
    </div>
    <div class="action-card-shortcut">
      <kbd>Ctrl</kbd> + <kbd>E</kbd>
    </div>
  </div>
</div>
```

**Key Features:**
- Action-oriented icons
- Keyboard shortcuts
- Clear action description
- Single-purpose focus

### 5. **Content Cards** - Rich Content Display

**Purpose:** Display rich content with images, videos, or complex layouts
**When to use:** Media galleries, content libraries, article previews

```html
<div class="ui card content-card">
  <div class="image">
    <img src="frame-diagram.png" alt="Frame Diagram">
    <div class="content-card-overlay">
      <button class="ui button circular icon">
        <i class="play icon"></i>
      </button>
    </div>
  </div>
  <div class="content">
    <div class="header">Cooking Frame Analysis</div>
    <div class="meta">
      <span class="date">Updated 3 days ago</span>
      <span class="category">Semantic Frames</span>
    </div>
    <div class="description">
      Comprehensive analysis of the cooking semantic frame including core elements, 
      frame-to-frame relations, and lexical unit associations.
    </div>
  </div>
  <div class="extra content">
    <div class="content-card-tags">
      <div class="ui label mini">cooking</div>
      <div class="ui label mini">food preparation</div>
      <div class="ui label mini">cuisine</div>
    </div>
  </div>
</div>
```

**Key Features:**
- Rich media support
- Tag/category systems
- Overlay actions
- Detailed metadata

### 6. **Form Cards** - Input Containers

**Purpose:** Group related form fields and inputs
**When to use:** Multi-section forms, configuration panels, settings

```html
<div class="ui card form-card">
  <div class="content">
    <div class="header">
      <i class="settings icon"></i>
      Account Settings
    </div>
    <div class="description">
      Configure your account preferences and security settings
    </div>
  </div>
  <div class="content">
    <div class="ui form">
      <div class="field">
        <label>Display Name</label>
        <input type="text" value="John Doe">
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" value="john.doe@example.com">
      </div>
      <div class="field">
        <div class="ui checkbox">
          <input type="checkbox" id="notifications">
          <label for="notifications">Email notifications</label>
        </div>
      </div>
    </div>
  </div>
  <div class="extra content">
    <div class="ui buttons">
      <button class="ui button">Cancel</button>
      <button class="ui button primary">Save Changes</button>
    </div>
  </div>
</div>
```

**Key Features:**
- Form field grouping
- Clear section headers
- Action buttons
- Validation support

### 7. **Status Cards** - System Information

**Purpose:** Display system status, alerts, and notifications
**When to use:** System dashboards, monitoring panels, alert systems

```html
<div class="ui card status-card warning">
  <div class="content">
    <div class="status-card-header">
      <div class="status-card-icon">
        <i class="exclamation triangle icon"></i>
      </div>
      <div class="status-card-level">Warning</div>
      <div class="status-card-time">2 minutes ago</div>
    </div>
    <div class="header">High Memory Usage Detected</div>
    <div class="description">
      System memory usage has exceeded 85% threshold. Consider reviewing 
      active processes and optimizing memory allocation.
    </div>
    <div class="status-card-details">
      <div class="detail-item">
        <strong>Current Usage:</strong> 87.3%
      </div>
      <div class="detail-item">
        <strong>Threshold:</strong> 85%
      </div>
    </div>
  </div>
  <div class="extra content">
    <button class="ui button mini">View Details</button>
    <button class="ui button mini primary">Optimize</button>
  </div>
</div>
```

**Key Features:**
- Status level indicators
- Timestamp information
- Detailed metrics
- Action buttons

---

## Grid Integration

### Responsive Behavior
Cards automatically adapt to different screen sizes using your grid system:

```css
/* Desktop: 3-4 cards per row */
.card-grid {
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
}

/* Tablet: 2-3 cards per row */
@media (max-width: 1024px) {
  .card-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  }
}

/* Mobile: 1 card per row */
@media (max-width: 768px) {
  .card-grid {
    grid-template-columns: 1fr;
  }
}
```

### Grid Variations by Card Type

**Option Cards:** Use `card-grid` or `card-grid wide-cards`
```html
<div class="card-grid wide-cards">
  <div class="ui card option-card">...</div>
</div>
```

**Data Cards:** Use `card-grid` or `card-grid dense`
```html
<div class="card-grid dense">
  <div class="ui card data-card">...</div>
</div>
```

**Summary Cards:** Use `stats-grid` for metrics
```html
<div class="stats-grid">
  <div class="ui card summary-card">...</div>
</div>
```

---

## Custom Card Variants

### Size Variants
Building on Fomantic-UI's size system:

```html
<!-- Mini cards for compact displays -->
<div class="ui mini card">...</div>

<!-- Tiny cards for dense layouts -->
<div class="ui tiny card">...</div>

<!-- Small cards for sidebar content -->
<div class="ui small card">...</div>

<!-- Large cards for featured content -->
<div class="ui large card">...</div>

<!-- Huge cards for hero sections -->
<div class="ui huge card">...</div>
```

### Interactive States
Enhanced interaction states for better UX:

```html
<!-- Selectable cards -->
<div class="ui card selectable">...</div>

<!-- Selected state -->
<div class="ui card selectable selected">...</div>

<!-- Disabled cards -->
<div class="ui card disabled">...</div>

<!-- Loading state -->
<div class="ui card loading">...</div>

<!-- Hover emphasis -->
<div class="ui card hoverable">...</div>
```

### Themed Cards
Color-themed cards for different contexts:

```html
<!-- Semantic colors -->
<div class="ui card primary">...</div>
<div class="ui card secondary">...</div>
<div class="ui card positive">...</div>
<div class="ui card negative">...</div>
<div class="ui card warning">...</div>
<div class="ui card info">...</div>

<!-- Custom theme cards -->
<div class="ui card reports-theme">...</div>
<div class="ui card data-theme">...</div>
<div class="ui card annotations-theme">...</div>
```

---

## LESS Implementation

### Base Card Extensions
```less
// Card Grid System Extensions
.card-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));

  &.dense {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 0.75rem;
  }

  &.wide-cards {
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
  }

  &.small-cards {
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1rem;
  }

  // Responsive behavior
  @media (max-width: 768px) {
    grid-template-columns: 1fr;
    gap: 1rem;

    &.dense,
    &.wide-cards,
    &.small-cards {
      grid-template-columns: 1fr;
    }
  }
}

// Base card enhancements
.ui.card {
  // Interactive states
  &.selectable {
    cursor: pointer;
    transition: all 0.3s ease;

    &:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
      transform: translateY(-2px);
    }

    &.selected {
      border-color: @primaryColor;
      box-shadow: 0 0 0 2px @primaryColor;
    }
  }

  &.hoverable:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transform: translateY(-4px);
  }

  // Loading state
  &.loading {
    pointer-events: none;
    
    &::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.8);
      z-index: 10;
    }

    &::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      margin: -10px 0 0 -10px;
      border: 2px solid @primaryColor;
      border-top-color: transparent;
      border-radius: 50%;
      animation: card-loading 1s linear infinite;
      z-index: 11;
    }
  }
}

@keyframes card-loading {
  to {
    transform: rotate(360deg);
  }
}
```

### Option Cards
```less
.option-card {
  transition: all 0.3s ease;
  cursor: pointer;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }

  .option-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 20px;
    color: white;
    flex-shrink: 0;

    // Color themes
    &.reports { background: linear-gradient(135deg, #5E6AD2, #4C51BF); }
    &.data { background: linear-gradient(135deg, #10B981, #059669); }
    &.annotations { background: linear-gradient(135deg, #F59E0B, #D97706); }
    &.search { background: linear-gradient(135deg, #3B82F6, #2563EB); }
    &.settings { background: linear-gradient(135deg, #6B7280, #4B5563); }
  }

  .option-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    font-size: 0.8rem;
    color: @mutedTextColor;

    .option-card-count {
      background: @lightGrey;
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      font-weight: 500;
    }

    .option-card-updated {
      color: @lightTextColor;
    }
  }
}
```

### Data Cards
```less
.data-card {
  .data-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;

    .data-card-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: @primaryColor;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .data-card-title {
      flex: 1;

      .header {
        margin-bottom: 0.25rem;
      }

      .meta {
        color: @mutedTextColor;
        font-size: 0.85rem;
      }
    }

    .data-card-status {
      flex-shrink: 0;
    }
  }

  .data-card-stats {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;

    .statistic {
      text-align: center;

      .value {
        font-size: 1.2rem;
        font-weight: 600;
        color: @primaryColor;
      }

      .label {
        font-size: 0.75rem;
        color: @mutedTextColor;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }
    }
  }

  .data-card-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
  }
}
```

### Summary Cards
```less
.summary-card {
  .summary-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;

    .summary-card-icon {
      width: 40px;
      height: 40px;
      border-radius: 6px;
      background: rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    .summary-card-trend {
      font-size: 0.875rem;
      font-weight: 600;

      &.positive {
        color: @positiveColor;
      }

      &.negative {
        color: @negativeColor;
      }

      &.neutral {
        color: @mutedTextColor;
      }
    }
  }

  .summary-card-value {
    margin-bottom: 0.5rem;

    .statistic {
      .value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
      }

      .label {
        font-size: 0.875rem;
        color: @mutedTextColor;
      }
    }
  }

  .summary-card-description {
    font-size: 0.8rem;
    color: @lightTextColor;
  }

  // Themed variants
  &.primary {
    .summary-card-icon {
      background: rgba(94, 106, 210, 0.1);
      color: @primaryColor;
    }
  }

  &.positive {
    .summary-card-icon {
      background: rgba(16, 185, 129, 0.1);
      color: @positiveColor;
    }
  }

  &.warning {
    .summary-card-icon {
      background: rgba(245, 158, 11, 0.1);
      color: @warningColor;
    }
  }

  &.negative {
    .summary-card-icon {
      background: rgba(239, 68, 68, 0.1);
      color: @negativeColor;
    }
  }
}
```

### Action Cards
```less
.action-card {
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .action-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: @primaryColor;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto 1rem;
  }

  .action-card-shortcut {
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: @mutedTextColor;

    kbd {
      background: @lightGrey;
      border: 1px solid @borderColor;
      border-radius: 3px;
      padding: 0.125rem 0.25rem;
      font-family: monospace;
      font-size: 0.7rem;
    }
  }
}
```

### Content Cards
```less
.content-card {
  .content-card-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .image:hover .content-card-overlay {
    opacity: 1;
  }

  .content-card-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
  }
}
```

### Form Cards
```less
.form-card {
  .ui.form {
    .field {
      margin-bottom: 1rem;
    }
  }

  .extra.content {
    .ui.buttons {
      width: 100%;
    }
  }
}
```

### Status Cards
```less
.status-card {
  .status-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;

    .status-card-icon {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }

    .status-card-level {
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.05em;
    }

    .status-card-time {
      margin-left: auto;
      font-size: 0.75rem;
      color: @mutedTextColor;
    }
  }

  .status-card-details {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid @borderColor;

    .detail-item {
      margin-bottom: 0.5rem;
      font-size: 0.875rem;

      &:last-child {
        margin-bottom: 0;
      }
    }
  }

  // Status level themes
  &.info {
    border-left: 4px solid @infoColor;

    .status-card-icon {
      background: rgba(59, 130, 246, 0.1);
      color: @infoColor;
    }

    .status-card-level {
      color: @infoColor;
    }
  }

  &.warning {
    border-left: 4px solid @warningColor;

    .status-card-icon {
      background: rgba(245, 158, 11, 0.1);
      color: @warningColor;
    }

    .status-card-level {
      color: @warningColor;
    }
  }

  &.negative {
    border-left: 4px solid @negativeColor;

    .status-card-icon {
      background: rgba(239, 68, 68, 0.1);
      color: @negativeColor;
    }

    .status-card-level {
      color: @negativeColor;
    }
  }

  &.positive {
    border-left: 4px solid @positiveColor;

    .status-card-icon {
      background: rgba(16, 185, 129, 0.1);
      color: @positiveColor;
    }

    .status-card-level {
      color: @positiveColor;
    }
  }
}
```

---

## JavaScript Integration

### Card Interactions
```javascript
// Card selection handling
document.addEventListener('click', (e) => {
  const card = e.target.closest('.ui.card.selectable');
  if (card) {
    // Toggle selection
    card.classList.toggle('selected');
    
    // Emit selection event
    card.dispatchEvent(new CustomEvent('card:select', {
      detail: { 
        selected: card.classList.contains('selected'),
        data: card.dataset 
      }
    }));
  }
});

// Option card navigation
document.addEventListener('click', (e) => {
  const optionCard = e.target.closest('.option-card');
  if (optionCard) {
    const category = optionCard.dataset.category;
    navigateToCategory(category);
  }
});

// Action card execution
document.addEventListener('click', (e) => {
  const actionCard = e.target.closest('.action-card');
  if (actionCard) {
    const action = actionCard.dataset.action;
    executeAction(action);
  }
});

// Card loading states
function setCardLoading(card, loading = true) {
  if (loading) {
    card.classList.add('loading');
  } else {
    card.classList.remove('loading');
  }
}

// Card animations
function animateCardEntry(cards) {
  cards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      card.style.transition = 'all 0.3s ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, index * 100);
  });
}
```

### Card Data Binding
```javascript
// Dynamic card creation
function createDataCard(data) {
  return `
    <div class="ui card data-card" data-entity-id="${data.id}">
      <div class="content">
        <div class="data-card-header">
          <div class="data-card-avatar">
            <i class="${data.icon} icon"></i>
          </div>
          <div class="data-card-title">
            <div class="header">${data.name}</div>
            <div class="meta">${data.role}</div>
          </div>
          <div class="data-card-status">
            <div class="ui ${data.statusColor} label">${data.status}</div>
          </div>
        </div>
        <div class="description">${data.description}</div>
        <div class="data-card-stats">
          ${data.stats.map(stat => `
            <div class="statistic">
              <div class="value">${stat.value}</div>
              <div class="label">${stat.label}</div>
            </div>
          `).join('')}
        </div>
      </div>
      <div class="extra content">
        <div class="data-card-actions">
          <button class="ui button mini" onclick="viewEntity('${data.id}')">View</button>
          <button class="ui button mini primary" onclick="editEntity('${data.id}')">Edit</button>
        </div>
      </div>
    </div>
  `;
}

// Update summary card values
function updateSummaryCard(cardElement, newData) {
  const valueElement = cardElement.querySelector('.summary-card-value .value');
  const trendElement = cardElement.querySelector('.summary-card-trend');
  
  // Animate value change
  animateValueChange(valueElement, newData.value);
  
  // Update trend
  if (trendElement) {
    trendElement.className = `summary-card-trend ${newData.trend.direction}`;
    trendElement.innerHTML = `<i class="arrow ${newData.trend.direction === 'positive' ? 'up' : 'down'} icon"></i> ${newData.trend.percentage}%`;
  }
}

function animateValueChange(element, newValue) {
  const currentValue = parseInt(element.textContent);
  const increment = (newValue - currentValue) / 20;
  let current = currentValue;
  
  const timer = setInterval(() => {
    current += increment;
    element.textContent = Math.round(current);
    
    if (Math.round(current) === newValue) {
      clearInterval(timer);
      element.textContent = newValue;
    }
  }, 50);
}
```

---

## Best Practices

### Design Guidelines

#### **Visual Hierarchy**
1. **Use card types consistently** - Same type for same purpose across the app
2. **Maintain aspect ratios** - Keep cards proportional for visual balance
3. **Limit information density** - Don't overcrowd cards with too much content
4. **Use white space effectively** - Allow content to breathe within cards

#### **Content Organization**
1. **Lead with the most important information** - Title and key data first
2. **Group related information** - Use sections within cards logically
3. **Make actions clear** - Button placement and labeling should be obvious
4. **Provide context** - Include metadata and status indicators

#### **Color and Theming**
1. **Use semantic colors** - Green for positive, red for negative, etc.
2. **Maintain brand consistency** - Stick to your color palette
3. **Consider accessibility** - Ensure sufficient contrast ratios
4. **Use color purposefully** - Don't just add color for decoration

### Performance Considerations

#### **Rendering Optimization**
```javascript
// Virtual scrolling for large card lists
class VirtualCardGrid {
  constructor(container, cardHeight = 200, buffer = 5) {
    this.container = container;
    this.cardHeight = cardHeight;
    this.buffer = buffer;
    this.visibleItems = [];
    this.allItems = [];
    
    this.setupScrollListener();
  }
  
  setData(items) {
    this.allItems = items;
    this.renderVisibleCards();
  }
  
  setupScrollListener() {
    this.container.addEventListener('scroll', 
      this.throttle(() => this.renderVisibleCards(), 16)
    );
  }
  
  renderVisibleCards() {
    const scrollTop = this.container.scrollTop;
    const containerHeight = this.container.clientHeight;
    
    const startIndex = Math.max(0, 
      Math.floor(scrollTop / this.cardHeight) - this.buffer
    );
    const endIndex = Math.min(this.allItems.length - 1,
      Math.ceil((scrollTop + containerHeight) / this.cardHeight) + this.buffer
    );
    
    // Render only visible cards
    this.renderCards(startIndex, endIndex);
  }
  
  throttle(func, delay) {
    let timeoutId;
    let lastExecTime = 0;
    return function (...args) {
      const currentTime = Date.now();
      
      if (currentTime - lastExecTime > delay) {
        func.apply(this, args);
        lastExecTime = currentTime;
      } else {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
          func.apply(this, args);
          lastExecTime = Date.now();
        }, delay - (currentTime - lastExecTime));
      }
    };
  }
}
```

#### **Lazy Loading**
```javascript
// Lazy load card content
function setupLazyCards() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const card = entry.target;
        loadCardContent(card);
        observer.unobserve(card);
      }
    });
  }, {
    rootMargin: '50px'
  });
  
  document.querySelectorAll('.card[data-lazy]').forEach(card => {
    observer.observe(card);
  });
}

function loadCardContent(card) {
  const entityId = card.dataset.entityId;
  const cardType = card.dataset.cardType;
  
  // Show loading state
  card.classList.add('loading');
  
  // Fetch and render content
  fetchEntityData(entityId)
    .then(data => renderCardContent(card, data, cardType))
    .finally(() => card.classList.remove('loading'));
}
```

### Accessibility Guidelines

#### **Keyboard Navigation**
```javascript
// Enable keyboard navigation for cards
document.addEventListener('keydown', (e) => {
  const focusedCard = document.activeElement.closest('.ui.card');
  if (!focusedCard) return;
  
  const cards = Array.from(document.querySelectorAll('.ui.card'));
  const currentIndex = cards.indexOf(focusedCard);
  
  switch (e.key) {
    case 'ArrowRight':
      e.preventDefault();
      focusNextCard(cards, currentIndex, 1);
      break;
    case 'ArrowLeft':
      e.preventDefault();
      focusNextCard(cards, currentIndex, -1);
      break;
    case 'Enter':
    case ' ':
      e.preventDefault();
      activateCard(focusedCard);
      break;
  }
});

function focusNextCard(cards, currentIndex, direction) {
  const nextIndex = currentIndex + direction;
  if (nextIndex >= 0 && nextIndex < cards.length) {
    cards[nextIndex].focus();
  }
}
```

#### **Screen Reader Support**
```html
<!-- Accessible card structure -->
<div class="ui card" 
     tabindex="0" 
     role="button" 
     aria-label="View reports section with 1,230 available reports"
     aria-describedby="card-desc-1">
  <div class="content">
    <div class="option-card-icon reports" aria-hidden="true">
      <i class="fas fa-chart-bar"></i>
    </div>
    <div class="header">Reports & Analytics</div>
    <div class="description" id="card-desc-1">
      Comprehensive analysis tools for FrameNet data including frames and lexical units.
    </div>
    <div class="option-card-meta">
      <span class="option-card-count" aria-label="1,230 reports available">1,230 reports</span>
      <span class="option-card-updated" aria-label="Last updated 2 hours ago">Updated 2h ago</span>
    </div>
  </div>
</div>
```

### Testing Strategies

#### **Visual Testing**
```javascript
// Automated visual regression testing
describe('Card Components', () => {
  test('option cards render correctly', async () => {
    const cards = await page.$('.option-card');
    
    for (const card of cards) {
      // Test hover state
      await card.hover();
      await page.screenshot({ 
        path: `option-card-hover-${Date.now()}.png`,
        clip: await card.boundingBox()
      });
      
      // Test click state
      await card.click();
      await page.waitForTimeout(300); // Wait for animation
      await page.screenshot({ 
        path: `option-card-active-${Date.now()}.png`,
        clip: await card.boundingBox()
      });
    }
  });
  
  test('cards responsive behavior', async () => {
    const viewports = [
      { width: 1200, height: 800 }, // Desktop
      { width: 768, height: 1024 }, // Tablet
      { width: 375, height: 667 }   // Mobile
    ];
    
    for (const viewport of viewports) {
      await page.setViewport(viewport);
      await page.screenshot({ 
        path: `cards-${viewport.width}x${viewport.height}.png`,
        fullPage: true
      });
    }
  });
});
```

#### **Interaction Testing**
```javascript
// Test card interactions
describe('Card Interactions', () => {
  test('option cards navigate correctly', async () => {
    const reportCard = await page.$('[data-category="reports"]');
    await reportCard.click();
    
    await page.waitForNavigation();
    expect(page.url()).toContain('/reports');
  });
  
  test('data cards show correct information', async () => {
    const dataCard = await page.$('[data-entity-id="123"]');
    const title = await dataCard.$eval('.header', el => el.textContent);
    
    expect(title).toBe('John Doe');
  });
  
  test('summary cards update values', async () => {
    const summaryCard = await page.$('.summary-card');
    const initialValue = await summaryCard.$eval('.value', el => el.textContent);
    
    // Trigger update
    await page.click('#refresh-stats');
    
    // Wait for animation
    await page.waitForFunction(
      (card, initial) => {
        const current = card.querySelector('.value').textContent;
        return current !== initial;
      },
      {},
      summaryCard,
      initialValue
    );
  });
});
```

### Common Patterns and Examples

#### **Card Filtering and Sorting**
```javascript
class CardFilter {
  constructor(containerSelector) {
    this.container = document.querySelector(containerSelector);
    this.cards = Array.from(this.container.querySelectorAll('.ui.card'));
    this.filters = {};
  }
  
  addFilter(key, value) {
    this.filters[key] = value;
    this.applyFilters();
  }
  
  removeFilter(key) {
    delete this.filters[key];
    this.applyFilters();
  }
  
  applyFilters() {
    this.cards.forEach(card => {
      const matches = Object.entries(this.filters).every(([key, value]) => {
        const cardValue = card.dataset[key];
        return !value || cardValue === value || cardValue?.includes(value);
      });
      
      card.style.display = matches ? 'block' : 'none';
    });
    
    this.updateResultsCount();
  }
  
  sortCards(sortBy, direction = 'asc') {
    const sortedCards = [...this.cards].sort((a, b) => {
      const aValue = this.getSortValue(a, sortBy);
      const bValue = this.getSortValue(b, sortBy);
      
      if (direction === 'asc') {
        return aValue > bValue ? 1 : -1;
      } else {
        return aValue < bValue ? 1 : -1;
      }
    });
    
    sortedCards.forEach(card => this.container.appendChild(card));
  }
  
  getSortValue(card, sortBy) {
    switch (sortBy) {
      case 'name':
        return card.querySelector('.header')?.textContent || '';
      case 'date':
        return new Date(card.dataset.updated || 0);
      case 'count':
        return parseInt(card.querySelector('.option-card-count')?.textContent || '0');
      default:
        return card.dataset[sortBy] || '';
    }
  }
  
  updateResultsCount() {
    const visible = this.cards.filter(card => card.style.display !== 'none').length;
    const total = this.cards.length;
    
    const resultsElement = document.querySelector('.results-info');
    if (resultsElement) {
      resultsElement.textContent = `${visible} of ${total} items`;
    }
  }
}

// Usage
const cardFilter = new CardFilter('.card-grid');

// Add filter controls
document.querySelector('#category-filter').addEventListener('change', (e) => {
  cardFilter.addFilter('category', e.target.value);
});

document.querySelector('#sort-select').addEventListener('change', (e) => {
  const [sortBy, direction] = e.target.value.split('-');
  cardFilter.sortCards(sortBy, direction);
});
```

#### **Card State Management**
```javascript
class CardStateManager {
  constructor() {
    this.selectedCards = new Set();
    this.cardStates = new Map();
  }
  
  selectCard(cardId) {
    this.selectedCards.add(cardId);
    this.updateCardVisual(cardId, 'selected', true);
    this.emitSelectionChange();
  }
  
  deselectCard(cardId) {
    this.selectedCards.delete(cardId);
    this.updateCardVisual(cardId, 'selected', false);
    this.emitSelectionChange();
  }
  
  toggleCard(cardId) {
    if (this.selectedCards.has(cardId)) {
      this.deselectCard(cardId);
    } else {
      this.selectCard(cardId);
    }
  }
  
  selectAll() {
    const allCards = document.querySelectorAll('.ui.card[data-entity-id]');
    allCards.forEach(card => {
      this.selectCard(card.dataset.entityId);
    });
  }
  
  deselectAll() {
    this.selectedCards.forEach(cardId => {
      this.deselectCard(cardId);
    });
  }
  
  updateCardVisual(cardId, state, value) {
    const card = document.querySelector(`[data-entity-id="${cardId}"]`);
    if (card) {
      card.classList.toggle(state, value);
    }
  }
  
  emitSelectionChange() {
    document.dispatchEvent(new CustomEvent('cards:selectionChanged', {
      detail: {
        selected: Array.from(this.selectedCards),
        count: this.selectedCards.size
      }
    }));
  }
  
  getSelectedCards() {
    return Array.from(this.selectedCards);
  }
}

// Usage
const cardState = new CardStateManager();

// Listen for selection changes
document.addEventListener('cards:selectionChanged', (e) => {
  const { count } = e.detail;
  const bulkActions = document.querySelector('.bulk-actions');
  
  if (count > 0) {
    bulkActions.style.display = 'block';
    bulkActions.querySelector('.selection-count').textContent = `${count} selected`;
  } else {
    bulkActions.style.display = 'none';
  }
});
```

---

## Summary

This comprehensive card system provides:

### **7 Distinct Card Types:**
1. **Option Cards** - Navigation and feature discovery
2. **Data Cards** - Entity display and management
3. **Summary Cards** - Metrics and KPIs
4. **Action Cards** - Quick actions and tools
5. **Content Cards** - Rich media and content
6. **Form Cards** - Input grouping and forms
7. **Status Cards** - System information and alerts

### **Complete Integration:**
- **Grid System** - Responsive layouts for all card types
- **Fomantic-UI** - Built on proven component foundation
- **Design System** - Consistent with your application theme
- **Accessibility** - Full keyboard and screen reader support
- **Performance** - Optimized for large datasets

### **Extensible Architecture:**
- **LESS Mixins** - Easy theming and customization
- **JavaScript Classes** - Reusable functionality
- **State Management** - Selection, filtering, and sorting
- **Animation System** - Smooth transitions and feedback

This system ensures consistent, accessible, and performant card usage throughout your FrameNet application while maintaining flexibility for future requirements.