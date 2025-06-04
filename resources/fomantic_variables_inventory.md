# Fomantic UI Variables Inventory

A comprehensive reference guide to all customizable variables in Fomantic UI, organized by component and type.

## How to Use This Guide

- **Variable Name**: The exact variable name to use in your `.variables` files
- **File**: Which component file contains this variable
- **Description**: What the variable controls
- **Default Value**: Typical default value (may vary by theme)
- **Type**: Color, Size, Boolean, String, etc.

---

## Global Variables (site.variables)

### Core Colors
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@primaryColor` | site.variables | Main brand color | `@blue` | Color |
| `@secondaryColor` | site.variables | Secondary brand color | `@grey` | Color |
| `@red` | site.variables | Red color in palette | `#DB2828` | Color |
| `@orange` | site.variables | Orange color in palette | `#F2711C` | Color |
| `@yellow` | site.variables | Yellow color in palette | `#FBBD08` | Color |
| `@olive` | site.variables | Olive color in palette | `#B5CC18` | Color |
| `@green` | site.variables | Green color in palette | `#21BA45` | Color |
| `@teal` | site.variables | Teal color in palette | `#00B5AD` | Color |
| `@blue` | site.variables | Blue color in palette | `#2185D0` | Color |
| `@violet` | site.variables | Violet color in palette | `#6435C9` | Color |
| `@purple` | site.variables | Purple color in palette | `#A333C8` | Color |
| `@pink` | site.variables | Pink color in palette | `#E03997` | Color |
| `@brown` | site.variables | Brown color in palette | `#A5673F` | Color |
| `@grey` | site.variables | Grey color in palette | `#767676` | Color |
| `@black` | site.variables | Black color in palette | `#1B1C1D` | Color |

### Typography
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@fontName` | site.variables | Main font family name | `'Lato'` | String |
| `@pageFont` | site.variables | Body text font stack | `@fontName, @fallbackFonts` | Font Stack |
| `@headerFont` | site.variables | Header font stack | `@fontName, @fallbackFonts` | Font Stack |
| `@fontSize` | site.variables | Base font size | `14px` | Size |
| `@emSize` | site.variables | Em unit base size | `14px` | Size |
| `@textColor` | site.variables | Default text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@linkColor` | site.variables | Default link color | `@primaryColor` | Color |
| `@linkUnderline` | site.variables | Link underline style | `none` | String |
| `@linkHoverColor` | site.variables | Link hover color | `darken(@linkColor, 5)` | Color |

### Layout & Spacing
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@pageBackground` | site.variables | Page background color | `#FFFFFF` | Color |
| `@lineHeight` | site.variables | Base line height | `1.4285em` | Size |
| `@borderRadius` | site.variables | Default border radius | `0.28571429rem` | Size |
| `@relativeBorderRadius` | site.variables | Relative border radius | `@borderRadius` | Size |
| `@absoluteBorderRadius` | site.variables | Absolute border radius | `@borderRadius` | Size |

### Sizes
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@miniSize` | site.variables | Mini size multiplier | `0.78571429em` | Size |
| `@tinySize` | site.variables | Tiny size multiplier | `0.85714286em` | Size |
| `@smallSize` | site.variables | Small size multiplier | `0.92857143em` | Size |
| `@mediumSize` | site.variables | Medium size multiplier | `1em` | Size |
| `@largeSize` | site.variables | Large size multiplier | `1.14285714em` | Size |
| `@bigSize` | site.variables | Big size multiplier | `1.28571429em` | Size |
| `@hugeSize` | site.variables | Huge size multiplier | `1.42857143em` | Size |
| `@massiveSize` | site.variables | Massive size multiplier | `1.71428571em` | Size |

---

## Button Variables (button.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | button.variables | Default button background | `#E0E1E2` | Color |
| `@color` | button.variables | Default button text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@borderRadius` | button.variables | Button border radius | `@relativeBorderRadius` | Size |
| `@fontWeight` | button.variables | Button font weight | `bold` | String |
| `@textTransform` | button.variables | Text transformation | `none` | String |
| `@tapColor` | button.variables | Mobile tap highlight color | `transparent` | Color |
| `@lineHeight` | button.variables | Button line height | `1em` | Size |

### Spacing & Dimensions
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@verticalPadding` | button.variables | Top/bottom padding | `0.78571429em` | Size |
| `@horizontalPadding` | button.variables | Left/right padding | `1.5em` | Size |
| `@padding` | button.variables | Combined padding | `@verticalPadding @horizontalPadding` | Size |
| `@verticalMargin` | button.variables | Top/bottom margin | `0em` | Size |
| `@horizontalMargin` | button.variables | Left/right margin | `0.25em` | Size |
| `@margin` | button.variables | Combined margin | `@verticalMargin @horizontalMargin @verticalMargin 0em` | Size |

### States
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@hoverBackgroundColor` | button.variables | Hover background color | `#CACBCD` | Color |
| `@hoverColor` | button.variables | Hover text color | `rgba(0, 0, 0, 0.8)` | Color |
| `@downBackgroundColor` | button.variables | Active/pressed background | `#BABBBC` | Color |
| `@downColor` | button.variables | Active/pressed text color | `rgba(0, 0, 0, 0.9)` | Color |
| `@focusBackgroundColor` | button.variables | Focus background color | `@hoverBackgroundColor` | Color |
| `@focusColor` | button.variables | Focus text color | `@hoverColor` | Color |

### Primary Button
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@primaryColor` | button.variables | Primary button text color | `@invertedTextColor` | Color |
| `@primaryBackgroundColor` | button.variables | Primary button background | `@primaryColor` | Color |
| `@primaryHoverBackgroundColor` | button.variables | Primary button hover background | `darken(@primaryBackgroundColor, 3)` | Color |
| `@primaryFocusBackgroundColor` | button.variables | Primary button focus background | `darken(@primaryBackgroundColor, 8)` | Color |
| `@primaryDownBackgroundColor` | button.variables | Primary button active background | `darken(@primaryBackgroundColor, 10)` | Color |

### Secondary Button
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@secondaryColor` | button.variables | Secondary button text color | `@invertedTextColor` | Color |
| `@secondaryBackgroundColor` | button.variables | Secondary button background | `@secondaryColor` | Color |
| `@secondaryHoverBackgroundColor` | button.variables | Secondary button hover background | `lighten(@secondaryBackgroundColor, 5)` | Color |
| `@secondaryFocusBackgroundColor` | button.variables | Secondary button focus background | `lighten(@secondaryBackgroundColor, 5)` | Color |
| `@secondaryDownBackgroundColor` | button.variables | Secondary button active background | `lighten(@secondaryBackgroundColor, 5)` | Color |

---

## Menu Variables (menu.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | menu.variables | Menu background color | `#FFFFFF` | Color |
| `@border` | menu.variables | Menu border | `1px solid rgba(34,36,38,.15)` | Border |
| `@boxShadow` | menu.variables | Menu shadow | `0px 1px 2px 0 rgba(34, 36, 38, 0.15)` | Shadow |
| `@borderRadius` | menu.variables | Menu border radius | `@relativeBorderRadius` | Size |
| `@minHeight` | menu.variables | Minimum menu height | `2.85714286em` | Size |

### Item Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@itemBackground` | menu.variables | Menu item background | `transparent` | Color |
| `@itemTextColor` | menu.variables | Menu item text color | `rgba(0,0,0,.87)` | Color |
| `@itemPadding` | menu.variables | Menu item padding | `0.92857143em 1.14285714em` | Size |
| `@itemMargin` | menu.variables | Menu item margin | `0em` | Size |
| `@itemFontWeight` | menu.variables | Menu item font weight | `normal` | String |
| `@itemTextTransform` | menu.variables | Menu item text transform | `none` | String |

### Label Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@labelSize` | menu.variables | Label font size | `0.92857143em` | Size |
| `@labelColor` | menu.variables | Label text color | `rgba(0,0,0,.4)` | Color |
| `@labelTextTransform` | menu.variables | Label text transform | `none` | String |
| `@labelFontWeight` | menu.variables | Label font weight | `bold` | String |
| `@labelOffset` | menu.variables | Label offset positioning | `0.3em` | Size |
| `@labelBackground` | menu.variables | Label background color | `#E8E8E8` | Color |
| `@labelVerticalPadding` | menu.variables | Label vertical padding | `0.3em` | Size |
| `@labelHorizontalPadding` | menu.variables | Label horizontal padding | `0.78571429em` | Size |

### States
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@hoverItemBackground` | menu.variables | Item hover background | `rgba(0,0,0,.05)` | Color |
| `@hoverItemTextColor` | menu.variables | Item hover text color | `rgba(0,0,0,.95)` | Color |
| `@activeItemBackground` | menu.variables | Active item background | `rgba(0,0,0,.05)` | Color |
| `@activeItemTextColor` | menu.variables | Active item text color | `rgba(0,0,0,.95)` | Color |
| `@activeItemFontWeight` | menu.variables | Active item font weight | `normal` | String |

### Variation Flags
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@variationMenuVertical` | menu.variables | Enable vertical menu styles | `true` | Boolean |
| `@variationMenuStackable` | menu.variables | Enable stackable menu styles | `true` | Boolean |
| `@variationMenuPointing` | menu.variables | Enable pointing menu styles | `true` | Boolean |
| `@variationMenuSecondary` | menu.variables | Enable secondary menu styles | `true` | Boolean |
| `@variationMenuText` | menu.variables | Enable text menu styles | `true` | Boolean |
| `@variationMenuIcon` | menu.variables | Enable icon menu styles | `true` | Boolean |
| `@variationMenuLabeled` | menu.variables | Enable labeled menu styles | `true` | Boolean |
| `@variationMenuTabular` | menu.variables | Enable tabular menu styles | `true` | Boolean |
| `@variationMenuInverted` | menu.variables | Enable inverted menu styles | `true` | Boolean |

---

## Input Variables (input.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | input.variables | Input background color | `#FFFFFF` | Color |
| `@color` | input.variables | Input text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@border` | input.variables | Input border | `1px solid rgba(34,36,38,.15)` | Border |
| `@borderRadius` | input.variables | Input border radius | `@relativeBorderRadius` | Size |
| `@boxShadow` | input.variables | Input shadow | `0em 0em 0em 0em transparent inset` | Shadow |
| `@lineHeight` | input.variables | Input line height | `1.21428571em` | Size |

### Spacing
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@verticalPadding` | input.variables | Input vertical padding | `0.67857143em` | Size |
| `@horizontalPadding` | input.variables | Input horizontal padding | `1em` | Size |
| `@padding` | input.variables | Combined input padding | `@verticalPadding @horizontalPadding` | Size |

### States
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@focusBackground` | input.variables | Focus state background | `#FFFFFF` | Color |
| `@focusBorderColor` | input.variables | Focus state border color | `@primaryColor` | Color |
| `@focusColor` | input.variables | Focus state text color | `rgba(0, 0, 0, 0.95)` | Color |
| `@errorBackground` | input.variables | Error state background | `#FFF6F6` | Color |
| `@errorBorder` | input.variables | Error state border | `#E0B4B4` | Color |
| `@errorColor` | input.variables | Error state text color | `#9F3A38` | Color |

### Placeholder
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@placeholderColor` | input.variables | Placeholder text color | `rgba(191, 191, 191, 0.87)` | Color |
| `@placeholderFocusColor` | input.variables | Placeholder focus color | `rgba(115, 115, 115, 0.87)` | Color |

---

## Modal Variables (modal.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | modal.variables | Modal background color | `#FFFFFF` | Color |
| `@border` | modal.variables | Modal border | `none` | Border |
| `@borderRadius` | modal.variables | Modal border radius | `@relativeBorderRadius` | Size |
| `@boxShadow` | modal.variables | Modal shadow | `1px 3px 3px 0px rgba(0, 0, 0, 0.2), 1px 3px 15px 2px rgba(0, 0, 0, 0.2)` | Shadow |

### Dimensions
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@width` | modal.variables | Default modal width | `90%` | Size |
| `@margin` | modal.variables | Modal margin | `1rem auto` | Size |
| `@padding` | modal.variables | Modal content padding | `1.5rem` | Size |

### Header
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@headerBackground` | modal.variables | Header background color | `#FFFFFF` | Color |
| `@headerColor` | modal.variables | Header text color | `rgba(0, 0, 0, 0.85)` | Color |
| `@headerPadding` | modal.variables | Header padding | `1.25rem 1.5rem` | Size |
| `@headerFontSize` | modal.variables | Header font size | `1.42857143rem` | Size |
| `@headerFontWeight` | modal.variables | Header font weight | `bold` | String |

### Content
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@contentBackground` | modal.variables | Content background color | `#FFFFFF` | Color |
| `@contentPadding` | modal.variables | Content padding | `1.5rem` | Size |
| `@contentLineHeight` | modal.variables | Content line height | `1.4` | Size |
| `@contentFontSize` | modal.variables | Content font size | `1em` | Size |

### Actions
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@actionBackground` | modal.variables | Actions area background | `#F9FAFB` | Color |
| `@actionPadding` | modal.variables | Actions area padding | `1rem 1.5rem` | Size |
| `@actionAlign` | modal.variables | Actions alignment | `right` | String |

### Dimmer
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@dimmerColor` | modal.variables | Dimmer overlay color | `rgba(0, 0, 0, 0.85)` | Color |
| `@invertedDimmerColor` | modal.variables | Inverted dimmer color | `rgba(255, 255, 255, 0.85)` | Color |

---

## Form Variables (form.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@gutterWidth` | form.variables | Form gutter width | `1em` | Size |
| `@rowDistance` | form.variables | Distance between rows | `1em` | Size |

### Field Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@fieldMargin` | form.variables | Field margin | `0em 0em 1em` | Size |
| `@fieldBackground` | form.variables | Field background | `transparent` | Color |

### Label Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@labelColor` | form.variables | Label text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@labelFontSize` | form.variables | Label font size | `0.92857143em` | Size |
| `@labelFontWeight` | form.variables | Label font weight | `bold` | String |
| `@labelMargin` | form.variables | Label margin | `0em 0em 0.28571429rem 0em` | Size |

### Error States
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@formErrorColor` | form.variables | Form error text color | `#9F3A38` | Color |
| `@formErrorBackground` | form.variables | Form error background | `#FFF6F6` | Color |
| `@formErrorBorder` | form.variables | Form error border | `#E0B4B4` | Color |

---

## Dropdown Variables (dropdown.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | dropdown.variables | Dropdown background | `#FFFFFF` | Color |
| `@border` | dropdown.variables | Dropdown border | `1px solid rgba(34,36,38,.15)` | Border |
| `@borderRadius` | dropdown.variables | Dropdown border radius | `@relativeBorderRadius` | Size |
| `@padding` | dropdown.variables | Dropdown padding | `0.78571429em 1em 0.78571429em 1em` | Size |

### Menu Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@menuBackground` | dropdown.variables | Menu background color | `#FFFFFF` | Color |
| `@menuBorder` | dropdown.variables | Menu border | `1px solid rgba(34,36,38,.15)` | Border |
| `@menuBorderRadius` | dropdown.variables | Menu border radius | `@relativeBorderRadius` | Size |
| `@menuBoxShadow` | dropdown.variables | Menu shadow | `0px 2px 3px 0px rgba(34, 36, 38, 0.15)` | Shadow |

### Item Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@itemPadding` | dropdown.variables | Item padding | `0.71428571rem 1.14285714rem` | Size |
| `@itemColor` | dropdown.variables | Item text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@itemHoverBackground` | dropdown.variables | Item hover background | `rgba(0, 0, 0, 0.05)` | Color |
| `@itemActiveBackground` | dropdown.variables | Item active background | `rgba(0, 0, 0, 0.05)` | Color |

---

## Usage Tips

### Finding Variables
1. **Start with site.variables** for global changes (colors, fonts, sizes)
2. **Check component-specific files** for detailed customization
3. **Look for variation flags** (like `@variationMenuVertical`) to enable/disable features
4. **Search for color-related variables** often inherit from global colors

### Common Patterns
- Most color variables can be set to global colors (e.g., `@primaryColor`)
- Size variables often use em units for scalability
- Boolean variables control which CSS variations are compiled
- Hover/focus/active states usually have separate variables

### Performance Tips
- Set variation flags to `false` for unused features to reduce CSS size
- Use global colors consistently for better theme cohesion
- Override only necessary variables in your custom theme

---

*This inventory covers the most commonly used variables. For complete lists, examine the default `.variables` files in each component's theme folder.*