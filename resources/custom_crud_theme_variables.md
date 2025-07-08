# Custom CRUD-Optimized Fomantic UI Theme

A theme specifically designed for data-dense CRUD applications with annotation capabilities.

## Design Philosophy

- **Information density without clutter**
- **Clear visual hierarchy for relationships**
- **Modern web-app aesthetics (not desktop-like)**
- **Optimized for frequent interactions**
- **Annotation-friendly interface**

---

## site.variables - Global Modifications

```less
/*******************************
    CRUD-Optimized Global Theme
*******************************/

/* Modern Color Palette - Inspired by Linear/Notion */
@primaryColor: #5E6AD2;           // Modern purple-blue
@secondaryColor: #6B7280;         // Neutral gray
@green: #10B981;                  // Success green
@red: #EF4444;                    // Error red
@orange: #F59E0B;                 // Warning orange
@blue: @primaryColor;
@grey: #F3F4F6;                   // Light background gray

/* Typography - Clean and readable */
@fontName: 'Inter';               // Modern, readable font
@fontSize: 14px;                  // Slightly smaller for density
@lineHeight: 1.5;                 // Better readability
@headerFont: 'Inter', @fallbackFonts;
@pageFont: 'Inter', @fallbackFonts;

/* Layout - Tighter spacing for density */
@borderRadius: 6px;               // Modern, subtle radius
@pageBackground: #FAFBFC;         // Soft, warm white
@textColor: #1F2937;              // Darker, more readable
@linkColor: @primaryColor;

/* Compact sizing for data density */
@miniSize: 0.7rem;
@tinySize: 0.8rem;
@smallSize: 0.9rem;
@mediumSize: 1rem;
@largeSize: 1.1rem;
```

---

## button.variables - Refined Buttons

```less
/*******************************
    CRUD-Optimized Buttons
*******************************/

/* Smaller, more subtle buttons */
@verticalPadding: 0.6em;          // Reduced padding
@horizontalPadding: 1.2em;        // Reduced padding
@fontWeight: 500;                 // Medium weight, not bold
@textTransform: none;             // No uppercase
@borderRadius: 6px;               // Consistent with global

/* Subtle styling */
@background: #F9FAFB;
@color: #374151;
@border: 1px solid #E5E7EB;

/* Refined hover states */
@hoverBackgroundColor: #F3F4F6;
@hoverColor: #1F2937;
@hoverBoxShadow: 0 1px 2px rgba(0, 0, 0, 0.05);

/* Primary button - accent color */
@primaryBackgroundColor: @primaryColor;
@primaryColor: #FFFFFF;
@primaryHoverBackgroundColor: darken(@primaryColor, 8%);

/* Icon buttons for CRUD operations */
@iconOnlyPadding: 0.5em;
@iconOnlySize: 2.2em;
```

---

## table.variables - Data-Dense Tables

```less
/*******************************
    CRUD-Optimized Tables
*******************************/

/* Compact table design */
@cellVerticalPadding: 0.5em;      // Tighter rows
@cellHorizontalPadding: 0.75em;   // Adequate column spacing
@headerVerticalPadding: 0.6em;
@headerHorizontalPadding: 0.75em;

/* Subtle borders and backgrounds */
@border: 1px solid #E5E7EB;
@headerBackground: #F9FAFB;
@headerColor: #374151;
@headerFontWeight: 600;           // Semibold, not bold
@headerFontSize: 0.875em;         // Slightly smaller

/* Striped rows for readability */
@stripedBackground: #F9FAFB;
@selectableBackground: #EEF2FF;   // Light purple for selection
@selectableTextColor: #1F2937;

/* Hover states */
@hoverBackground: #F3F4F6;
@activeBackground: #EEF2FF;
@activeColor: #1F2937;
```

---

## input.variables - Clean Form Elements

```less
/*******************************
    CRUD-Optimized Inputs
*******************************/

/* Refined input styling */
@background: #FFFFFF;
@border: 1px solid #D1D5DB;
@borderRadius: 6px;
@verticalPadding: 0.6em;          // Compact but usable
@horizontalPadding: 0.75em;

/* Modern focus states */
@focusBorderColor: @primaryColor;
@focusBoxShadow: 0 0 0 3px rgba(94, 106, 210, 0.1);
@focusBackground: #FFFFFF;

/* Error states */
@errorBackground: #FEF2F2;
@errorBorder: #F87171;
@errorColor: #DC2626;

/* Placeholder styling */
@placeholderColor: #9CA3AF;
@placeholderFocusColor: #6B7280;
```

---

## form.variables - Efficient Forms

```less
/*******************************
    CRUD-Optimized Forms
*******************************/

/* Tighter form spacing */
@fieldMargin: 0em 0em 0.8em;      // Reduced from 1em
@labelMargin: 0em 0em 0.4em 0em;  // Tighter label spacing
@labelColor: #374151;
@labelFontWeight: 500;            // Medium weight
@labelFontSize: 0.875em;          // Slightly smaller

/* Inline forms for CRUD operations */
@inlineFieldMargin: 0em 1em 0em 0em;
@inlineInputWidth: auto;
```

---

## card.variables - Information Cards

```less
/*******************************
    CRUD-Optimized Cards
*******************************/

/* Subtle card design */
@background: #FFFFFF;
@border: 1px solid #E5E7EB;
@borderRadius: 8px;               // Slightly larger radius
@boxShadow: 0 1px 3px rgba(0, 0, 0, 0.05); // Very subtle
@padding: 1.25em;                 // Generous but not excessive

/* Card headers */
@headerFontSize: 1.1em;           // Not too large
@headerFontWeight: 600;           // Semibold
@headerColor: #1F2937;
@headerMargin: 0em 0em 0.75em;

/* Card content */
@contentColor: #4B5563;           // Slightly muted
@metaColor: #6B7280;              // More muted for metadata
```

---

## menu.variables - Navigation Optimized

```less
/*******************************
    CRUD-Optimized Menus
*******************************/

/* Clean menu design */
@background: #FFFFFF;
@border: 1px solid #E5E7EB;
@borderRadius: 6px;
@boxShadow: 0 1px 3px rgba(0, 0, 0, 0.05);

/* Compact menu items */
@itemPadding: 0.6em 1em;          // Tighter padding
@itemTextColor: #4B5563;
@itemFontWeight: 500;

/* Subtle hover states */
@hoverItemBackground: #F3F4F6;
@hoverItemTextColor: #1F2937;
@activeItemBackground: #EEF2FF;
@activeItemTextColor: @primaryColor;

/* Breadcrumb-style navigation */
@breadcrumbSpacing: 0.5em;
@breadcrumbArrowColor: #9CA3AF;
```

---

## modal.variables - Dialog Optimization

```less
/*******************************
    CRUD-Optimized Modals
*******************************/

/* Modern modal styling */
@background: #FFFFFF;
@borderRadius: 12px;              // More rounded for modern feel
@boxShadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 
            0 10px 10px -5px rgba(0, 0, 0, 0.04);

/* Header styling */
@headerBackground: #FFFFFF;
@headerColor: #1F2937;
@headerPadding: 1.5rem 1.5rem 1rem;
@headerFontSize: 1.25rem;
@headerFontWeight: 600;

/* Content spacing */
@contentPadding: 0rem 1.5rem 1.5rem;

/* Actions area */
@actionBackground: #F9FAFB;
@actionPadding: 1rem 1.5rem;
@actionBorderTop: 1px solid #E5E7EB;
```

---

## message.variables - Notification System

```less
/*******************************
    CRUD-Optimized Messages
*******************************/

/* Subtle message styling */
@background: #F8FAFC;
@border: 1px solid #E2E8F0;
@borderRadius: 6px;
@padding: 1em 1.25em;
@boxShadow: none;                 // Remove heavy shadow

/* State colors - modern palette */
@positiveBackgroundColor: #F0FDF4;
@positiveTextColor: #166534;
@positiveBorderColor: #BBF7D0;

@negativeBackgroundColor: #FEF2F2;
@negativeTextColor: #DC2626;
@negativeBorderColor: #FECACA;

@infoBackgroundColor: #EFF6FF;
@infoTextColor: #1E40AF;
@infoBorderColor: #BFDBFE;

@warningBackgroundColor: #FFFBEB;
@warningTextColor: #D97706;
@warningBorderColor: #FDE68A;
```

---

## Special Additions for Annotation Interface

```less
/*******************************
    Annotation-Specific Variables
*******************************/

/* Annotation toolbar */
@annotationToolbarBackground: #1F2937;
@annotationToolbarHeight: 3rem;
@annotationToolColor: #F3F4F6;
@annotationToolActiveColor: @primaryColor;

/* Bounding box colors */
@boundingBoxDefault: #3B82F6;     // Blue
@boundingBoxSelected: #EF4444;    // Red
@boundingBoxHover: #10B981;       // Green
@boundingBoxStroke: 2px;
@boundingBoxOpacity: 0.3;

/* Canvas controls */
@canvasControlSize: 2.5rem;
@canvasControlBackground: rgba(255, 255, 255, 0.9);
@canvasControlBorder: 1px solid #E5E7EB;
@canvasControlRadius: 6px;
@canvasControlShadow: 0 2px 4px rgba(0, 0, 0, 0.1);

/* Sidebar for object relationships */
@sidebarWidth: 280px;
@sidebarBackground: #F9FAFB;
@sidebarBorder: 1px solid #E5E7EB;
@relationshipLineColor: #9CA3AF;
@relationshipActiveColor: @primaryColor;
```

---

## Layout Recommendations

### 1. Use CSS Grid for Complex Layouts
```css
.crud-layout {
  display: grid;
  grid-template-areas: 
    "sidebar header header"
    "sidebar main tools"
    "sidebar main tools";
  grid-template-columns: 280px 1fr 300px;
  grid-template-rows: 60px 1fr;
  height: 100vh;
}
```

### 2. Sticky Headers for Long Tables
```css
.ui.table thead th {
  position: sticky;
  top: 0;
  z-index: 10;
  background: var(--header-background);
}
```

### 3. Responsive Breakpoints
```css
@media (max-width: 1200px) {
  .tools-panel { display: none; }
  .crud-layout { grid-template-columns: 280px 1fr; }
}

@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .crud-layout { grid-template-columns: 1fr; }
}
```

---

## Key Design Principles Applied

1. **Reduced Visual Weight**: Lighter shadows, subtle borders, muted colors
2. **Information Density**: Tighter spacing while maintaining usability
3. **Clear Hierarchy**: Consistent font weights and sizes for different content types
4. **Modern Color Palette**: Inspired by contemporary design systems
5. **Consistent Interaction States**: Predictable hover/active/focus behaviors
6. **Annotation-Friendly**: High contrast elements for precise interaction

This theme balances the need for information density with modern web aesthetics, avoiding the "desktop application" look while maintaining functionality for complex CRUD operations.