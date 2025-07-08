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

## Card Variables (card.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | card.variables | Card background color | `#FFFFFF` | Color |
| `@border` | card.variables | Card border | `1px solid rgba(34,36,38,.1)` | Border |
| `@borderRadius` | card.variables | Card border radius | `@relativeBorderRadius` | Size |
| `@boxShadow` | card.variables | Card shadow | `0px 1px 3px 0px rgba(34, 36, 38, 0.15)` | Shadow |
| `@padding` | card.variables | Card content padding | `1em` | Size |
| `@width` | card.variables | Default card width | `290px` | Size |

### Image Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@imageBackground` | card.variables | Image background | `transparent` | Color |
| `@imagePadding` | card.variables | Image padding | `0em` | Size |
| `@imageBorder` | card.variables | Image border | `none` | Border |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@contentBackground` | card.variables | Content area background | `none` | Color |
| `@contentMargin` | card.variables | Content margin | `0em` | Size |
| `@contentPadding` | card.variables | Content padding | `1em 1em` | Size |
| `@contentFontSize` | card.variables | Content font size | `1em` | Size |
| `@contentBorder` | card.variables | Content border | `none` | Border |
| `@contentBorderRadius` | card.variables | Content border radius | `0em` | Size |

### Header Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@headerMargin` | card.variables | Header margin | `0em 0em 0.28571429em` | Size |
| `@headerFontWeight` | card.variables | Header font weight | `bold` | String |
| `@headerFontSize` | card.variables | Header font size | `1.28571429em` | Size |
| `@headerLineHeight` | card.variables | Header line height | `1.28571429em` | Size |
| `@headerColor` | card.variables | Header text color | `rgba(0, 0, 0, 0.85)` | Color |

### Meta Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@metaFontSize` | card.variables | Meta text font size | `1em` | Size |
| `@metaSpacing` | card.variables | Meta spacing | `0.3em` | Size |
| `@metaColor` | card.variables | Meta text color | `rgba(0, 0, 0, 0.4)` | Color |

### Description Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@descriptionDistance` | card.variables | Description distance from header | `0.5em` | Size |
| `@descriptionColor` | card.variables | Description text color | `rgba(0, 0, 0, 0.68)` | Color |

### Extra Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@extraContentBackground` | card.variables | Extra content background | `none` | Color |
| `@extraContentPosition` | card.variables | Extra content position | `static` | String |
| `@extraContentTop` | card.variables | Extra content top offset | `0em` | Size |
| `@extraContentLeft` | card.variables | Extra content left offset | `0em` | Size |
| `@extraContentWidth` | card.variables | Extra content width | `auto` | Size |
| `@extraContentPadding` | card.variables | Extra content padding | `0.75em 1em` | Size |
| `@extraContentBorder` | card.variables | Extra content border | `1px solid rgba(0, 0, 0, 0.05)` | Border |
| `@extraContentColor` | card.variables | Extra content text color | `rgba(0, 0, 0, 0.4)` | Color |

---

## Table Variables (table.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | table.variables | Table background color | `#FFFFFF` | Color |
| `@border` | table.variables | Table border | `1px solid rgba(34,36,38,.15)` | Border |
| `@borderRadius` | table.variables | Table border radius | `@relativeBorderRadius` | Size |
| `@color` | table.variables | Table text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@borderCollapse` | table.variables | Border collapse mode | `separate` | String |
| `@borderSpacing` | table.variables | Border spacing | `0px` | Size |

### Header Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@headerBackground` | table.variables | Header background | `#F9FAFB` | Color |
| `@headerColor` | table.variables | Header text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@headerVerticalPadding` | table.variables | Header vertical padding | `0.92857143em` | Size |
| `@headerHorizontalPadding` | table.variables | Header horizontal padding | `0.78571429em` | Size |
| `@headerFontStyle` | table.variables | Header font style | `none` | String |
| `@headerFontWeight` | table.variables | Header font weight | `bold` | String |
| `@headerTextTransform` | table.variables | Header text transform | `none` | String |
| `@headerBorder` | table.variables | Header border | `1px solid rgba(34,36,38,.1)` | Border |
| `@headerDivider` | table.variables | Header cell divider | `none` | Border |

### Cell Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@cellVerticalPadding` | table.variables | Cell vertical padding | `0.78571429em` | Size |
| `@cellHorizontalPadding` | table.variables | Cell horizontal padding | `0.78571429em` | Size |
| `@cellVerticalAlign` | table.variables | Cell vertical alignment | `inherit` | String |
| `@cellTextAlign` | table.variables | Cell text alignment | `inherit` | String |
| `@cellBorder` | table.variables | Cell border | `1px solid rgba(34,36,38,.1)` | Border |

### Footer Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@footerBoxShadow` | table.variables | Footer shadow | `none` | Shadow |
| `@footerBorder` | table.variables | Footer border | `1px solid rgba(34,36,38,.15)` | Border |
| `@footerDivider` | table.variables | Footer divider | `none` | Border |
| `@footerBackground` | table.variables | Footer background | `#F9FAFB` | Color |

### Striped Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@stripedBackground` | table.variables | Striped row background | `rgba(0, 0, 50, 0.02)` | Color |
| `@invertedStripedBackground` | table.variables | Inverted striped background | `rgba(255, 255, 255, 0.05)` | Color |

### Selectable Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@selectableBackground` | table.variables | Selectable row background | `rgba(0, 0, 0, 0.05)` | Color |
| `@selectableTextColor` | table.variables | Selectable row text color | `rgba(0, 0, 0, 0.95)` | Color |
| `@selectableInvertedBackground` | table.variables | Inverted selectable background | `rgba(255, 255, 255, 0.08)` | Color |
| `@selectableInvertedTextColor` | table.variables | Inverted selectable text color | `rgba(255, 255, 255, 1)` | Color |

### Active Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@activeBackground` | table.variables | Active row background | `#E0E0E0` | Color |
| `@activeColor` | table.variables | Active row text color | `rgba(0, 0, 0, 0.95)` | Color |
| `@activeBoxShadow` | table.variables | Active row shadow | `0px 0px 0px 1px rgba(0, 0, 0, 0.1) inset` | Shadow |

---

## Segment Variables (segment.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | segment.variables | Segment background color | `#FFFFFF` | Color |
| `@border` | segment.variables | Segment border | `1px solid rgba(34,36,38,.15)` | Border |
| `@borderRadius` | segment.variables | Segment border radius | `@relativeBorderRadius` | Size |
| `@boxShadow` | segment.variables | Segment shadow | `0px 1px 2px 0 rgba(34, 36, 38, 0.15)` | Shadow |
| `@margin` | segment.variables | Segment margin | `1rem 0em` | Size |
| `@padding` | segment.variables | Segment padding | `1em 1em` | Size |

### Text Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@color` | segment.variables | Segment text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@fontSize` | segment.variables | Segment font size | `1rem` | Size |
| `@lineHeight` | segment.variables | Segment line height | `1.4285em` | Size |

### Attached Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@attachedOffset` | segment.variables | Attached segment offset | `0px` | Size |
| `@attachedTopOffset` | segment.variables | Top attached offset | `0px` | Size |
| `@attachedBottomOffset` | segment.variables | Bottom attached offset | `0px` | Size |
| `@attachedHorizontalOffset` | segment.variables | Horizontal attached offset | `-1px` | Size |
| `@attachedWidth` | segment.variables | Attached segment width | `calc(100% - (@attachedHorizontalOffset * 2))` | Size |
| `@attachedBoxShadow` | segment.variables | Attached segment shadow | `none` | Shadow |
| `@attachedBorder` | segment.variables | Attached segment border | `1px solid @borderColor` | Border |

### Clearing Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@clearingMargin` | segment.variables | Clearing segment margin | `@margin` | Size |
| `@clearingPadding` | segment.variables | Clearing segment padding | `@padding` | Size |

### Colored Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@coloredBorderSize` | segment.variables | Colored border size | `2px` | Size |
| `@coloredBorderRadius` | segment.variables | Colored border radius | `0px @borderRadius @borderRadius 0px` | Size |

### Emphasis Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@secondaryBackground` | segment.variables | Secondary background | `#F3F4F5` | Color |
| `@secondaryColor` | segment.variables | Secondary text color | `rgba(0, 0, 0, 0.6)` | Color |
| `@tertiaryBackground` | segment.variables | Tertiary background | `#DCDDDE` | Color |
| `@tertiaryColor` | segment.variables | Tertiary text color | `rgba(0, 0, 0, 0.6)` | Color |

### Inverted Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@invertedBackground` | segment.variables | Inverted background | `#1B1C1D` | Color |
| `@invertedBorder` | segment.variables | Inverted border | `none` | Border |
| `@invertedBoxShadow` | segment.variables | Inverted shadow | `none` | Shadow |
| `@invertedColor` | segment.variables | Inverted text color | `rgba(255, 255, 255, 0.9)` | Color |

---

## Header Variables (header.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@fontWeight` | header.variables | Header font weight | `bold` | String |
| `@fontFamily` | header.variables | Header font family | `@headerFont` | Font Stack |
| `@lineHeight` | header.variables | Header line height | `1.28571429em` | Size |
| `@textTransform` | header.variables | Header text transform | `none` | String |
| `@margin` | header.variables | Header margin | `calc(2rem - 0.1428571428571429em) 0em 1rem` | Size |

### Sizes
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@h1` | header.variables | H1 header size | `2rem` | Size |
| `@h2` | header.variables | H2 header size | `1.714rem` | Size |
| `@h3` | header.variables | H3 header size | `1.28rem` | Size |
| `@h4` | header.variables | H4 header size | `1.071rem` | Size |
| `@h5` | header.variables | H5 header size | `1rem` | Size |
| `@h6` | header.variables | H6 header size | `0.857rem` | Size |

### UI Header Sizes
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@hugeFontSize` | header.variables | Huge UI header size | `2em` | Size |
| `@largeFontSize` | header.variables | Large UI header size | `1.714em` | Size |
| `@mediumFontSize` | header.variables | Medium UI header size | `1.28em` | Size |
| `@smallFontSize` | header.variables | Small UI header size | `1.071em` | Size |
| `@tinyFontSize` | header.variables | Tiny UI header size | `1em` | Size |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@contentLineHeight` | header.variables | Content line height | `1.2` | Size |
| `@contentFontWeight` | header.variables | Content font weight | `normal` | String |
| `@contentColor` | header.variables | Content text color | `rgba(0, 0, 0, 0.68)` | Color |

### Sub Header Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@subHeaderFontSize` | header.variables | Sub header font size | `1.14285714em` | Size |
| `@subHeaderLineHeight` | header.variables | Sub header line height | `1.2em` | Size |
| `@subHeaderColor` | header.variables | Sub header text color | `rgba(0, 0, 0, 0.6)` | Color |

### Icon Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@iconMargin` | header.variables | Icon margin | `0.25rem` | Size |
| `@iconAlignment` | header.variables | Icon alignment | `middle` | String |

### Image Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@imageMargin` | header.variables | Image margin | `0.25rem` | Size |
| `@imageAlignment` | header.variables | Image alignment | `middle` | String |
| `@imageWidth` | header.variables | Image width | `2.5em` | Size |
| `@imageHeight` | header.variables | Image height | `auto` | Size |

---

## List Variables (list.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@itemVerticalPadding` | list.variables | List item vertical padding | `0.21428571em` | Size |
| `@itemHorizontalPadding` | list.variables | List item horizontal padding | `0em` | Size |
| `@itemPadding` | list.variables | Combined item padding | `@itemVerticalPadding @itemHorizontalPadding` | Size |
| `@itemLineHeight` | list.variables | List item line height | `1.14285714em` | Size |
| `@color` | list.variables | List text color | `rgba(0, 0, 0, 0.87)` | Color |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@contentMargin` | list.variables | Content margin | `0em 0em 0em 1em` | Size |
| `@contentLineHeight` | list.variables | Content line height | `@itemLineHeight` | Size |

### Header Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@headerFontWeight` | list.variables | Header font weight | `bold` | String |
| `@headerMargin` | list.variables | Header margin | `0em` | Size |

### Description Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@descriptionDistance` | list.variables | Description distance | `0.25em` | Size |
| `@descriptionColor` | list.variables | Description text color | `rgba(0, 0, 0, 0.7)` | Color |

### Icon Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@iconDistance` | list.variables | Icon distance from content | `0.25rem` | Size |
| `@iconOffset` | list.variables | Icon vertical offset | `0em` | Size |
| `@iconTransition` | list.variables | Icon transition | `color 0.1s ease` | Transition |

### Image Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@imageDistance` | list.variables | Image distance from content | `0.5rem` | Size |
| `@imageAlignment` | list.variables | Image alignment | `top` | String |

### Bullet Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@bulletDistance` | list.variables | Bullet distance from content | `1rem` | Size |
| `@bulletOffset` | list.variables | Bullet vertical offset | `-0.125rem` | Size |
| `@bulletOpacity` | list.variables | Bullet opacity | `1` | Number |
| `@bulletCharacter` | list.variables | Bullet character | `'•'` | String |
| `@bulletColor` | list.variables | Bullet color | `inherit` | Color |
| `@bulletLinkColor` | list.variables | Bullet link color | `@linkColor` | Color |

### Horizontal Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@horizontalSpacing` | list.variables | Horizontal list spacing | `1rem` | Size |
| `@horizontalIconDistance` | list.variables | Horizontal icon distance | `0.25rem` | Size |
| `@horizontalVerticalAlign` | list.variables | Horizontal vertical alignment | `middle` | String |

---

## Message Variables (message.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | message.variables | Message background color | `#F8F8F9` | Color |
| `@color` | message.variables | Message text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@borderRadius` | message.variables | Message border radius | `@relativeBorderRadius` | Size |
| `@boxShadow` | message.variables | Message shadow | `0px 0px 0px 1px rgba(34, 36, 38, 0.22) inset, 0px 2px 4px 0px rgba(34, 36, 38, 0.12), 0px 2px 10px 0px rgba(34, 36, 38, 0.15)` | Shadow |
| `@padding` | message.variables | Message padding | `1em 1.5em` | Size |
| `@margin` | message.variables | Message margin | `1em 0em` | Size |
| `@minHeight` | message.variables | Message minimum height | `1em` | Size |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@lineHeight` | message.variables | Message line height | `1.4285em` | Size |
| `@lineHeightOffset` | message.variables | Line height offset | `calc((1em - 1.4285em) / 2)` | Size |

### Header Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@headerFontSize` | message.variables | Header font size | `1.14285714em` | Size |
| `@headerFontWeight` | message.variables | Header font weight | `bold` | String |
| `@headerDisplay` | message.variables | Header display | `block` | String |
| `@headerColor` | message.variables | Header text color | `rgba(0, 0, 0, 0.85)` | Color |
| `@headerMargin` | message.variables | Header margin | `0em 0em 0rem 0em` | Size |

### Paragraph Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@paragraphMargin` | message.variables | Paragraph margin | `0.75em 0em 0em` | Size |
| `@paragraphOpacity` | message.variables | Paragraph opacity | `0.85` | Number |

### List Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@listMargin` | message.variables | List margin | `0.5em 0em 0em` | Size |
| `@listItemMargin` | message.variables | List item margin | `0em 0em 0.3em 1em` | Size |
| `@listItemIndent` | message.variables | List item indent | `1em` | Size |

### Icon Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@iconDistance` | message.variables | Icon distance from content | `0.6em` | Size |
| `@iconOpacity` | message.variables | Icon opacity | `0.8` | Number |
| `@iconSize` | message.variables | Icon size | `1.5em` | Size |

### Close Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@closeTopDistance` | message.variables | Close button top distance | `0.78571429em` | Size |
| `@closeRightDistance` | message.variables | Close button right distance | `0.5em` | Size |
| `@closeOpacity` | message.variables | Close button opacity | `0.7` | Number |
| `@closeTransition` | message.variables | Close button transition | `opacity 0.1s ease` | Transition |

### State Colors
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@positiveBackgroundColor` | message.variables | Positive message background | `#FCFFF5` | Color |
| `@positiveTextColor` | message.variables | Positive message text | `#2C662D` | Color |
| `@positiveBorderColor` | message.variables | Positive message border | `#A3C293` | Color |
| `@negativeBackgroundColor` | message.variables | Negative message background | `#FFF6F6` | Color |
| `@negativeTextColor` | message.variables | Negative message text | `#9F3A38` | Color |
| `@negativeBorderColor` | message.variables | Negative message border | `#E0B4B4` | Color |
| `@infoBackgroundColor` | message.variables | Info message background | `#F8FFFF` | Color |
| `@infoTextColor` | message.variables | Info message text | `#276F86` | Color |
| `@infoBorderColor` | message.variables | Info message border | `#A9D5DE` | Color |
| `@warningBackgroundColor` | message.variables | Warning message background | `#FFFAF3` | Color |
| `@warningTextColor` | message.variables | Warning message text | `#573A08` | Color |
| `@warningBorderColor` | message.variables | Warning message border | `#C9BA9B` | Color |
| `@errorBackgroundColor` | message.variables | Error message background | `#FFF6F6` | Color |
| `@errorTextColor` | message.variables | Error message text | `#9F3A38` | Color |
| `@errorBorderColor` | message.variables | Error message border | `#E0B4B4` | Color |
| `@successBackgroundColor` | message.variables | Success message background | `#FCFFF5` | Color |
| `@successTextColor` | message.variables | Success message text | `#2C662D` | Color |
| `@successBorderColor` | message.variables | Success message border | `#A3C293` | Color |

---

## Checkbox Variables (checkbox.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@checkboxSize` | checkbox.variables | Checkbox size | `17px` | Size |
| `@checkboxColor` | checkbox.variables | Checkbox text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@checkboxLineHeight` | checkbox.variables | Checkbox line height | `17px` | Size |
| `@labelDistance` | checkbox.variables | Label distance from checkbox | `1.85714286em` | Size |
| `@labelLineHeight` | checkbox.variables | Label line height | `1.17` | Size |
| `@labelFontSize` | checkbox.variables | Label font size | `1em` | Size |

### Checkbox Element Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@checkboxBackground` | checkbox.variables | Checkbox background color | `#FFFFFF` | Color |
| `@checkboxBorder` | checkbox.variables | Checkbox border | `1px solid rgba(34, 36, 38, 0.35)` | Border |
| `@checkboxBorderRadius` | checkbox.variables | Checkbox border radius | `@relativeBorderRadius` | Size |
| `@checkboxTransition` | checkbox.variables | Checkbox transition | `box-shadow 0.1s ease, opacity 0.1s ease, transform 0.1s ease, background-color 0.1s ease` | Transition |

### Check Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@checkboxCheckTop` | checkbox.variables | Check mark top position | `0px` | Size |
| `@checkboxCheckLeft` | checkbox.variables | Check mark left position | `0px` | Size |
| `@checkboxCheckSize` | checkbox.variables | Check mark size | `@checkboxSize` | Size |
| `@checkOpacity` | checkbox.variables | Check mark opacity | `0` | Number |
| `@checkColor` | checkbox.variables | Check mark color | `rgba(0, 0, 0, 0.87)` | Color |
| `@checkTransition` | checkbox.variables | Check mark transition | `border 0.1s ease, opacity 0.1s ease, transform 0.1s ease, background 0.1s ease` | Transition |

### Radio Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@radioSize` | checkbox.variables | Radio button size | `15px` | Size |
| `@radioCheckRadius` | checkbox.variables | Radio check radius | `50%` | Size |
| `@radioCheckTop` | checkbox.variables | Radio check top position | `1px` | Size |
| `@radioCheckLeft` | checkbox.variables | Radio check left position | `1px` | Size |
| `@radioCheckSize` | checkbox.variables | Radio check size | `13px` | Size |

### Toggle Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@toggleWidth` | checkbox.variables | Toggle width | `3.5rem` | Size |
| `@toggleHeight` | checkbox.variables | Toggle height | `1.5rem` | Size |
| `@toggleHandleSize` | checkbox.variables | Toggle handle size | `1.5rem` | Size |
| `@toggleLaneBackground` | checkbox.variables | Toggle lane background | `rgba(0, 0, 0, 0.05)` | Color |
| `@toggleLaneHeight` | checkbox.variables | Toggle lane height | `1rem` | Size |
| `@toggleLaneWidth` | checkbox.variables | Toggle lane width | `3.25rem` | Size |
| `@toggleHandleBackground` | checkbox.variables | Toggle handle background | `#FFFFFF` | Color |
| `@toggleHandleBorderRadius` | checkbox.variables | Toggle handle border radius | `50rem` | Size |
| `@toggleHandleBoxShadow` | checkbox.variables | Toggle handle shadow | `0px 1px 2px 0 rgba(34, 36, 38, 0.15), 0px 0px 0px 1px rgba(34, 36, 38, 0.15) inset` | Shadow |
| `@toggleHandleTransition` | checkbox.variables | Toggle handle transition | `background 0.3s ease, left 0.3s ease` | Transition |

### Slider Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@sliderLineHeight` | checkbox.variables | Slider line height | `1rem` | Size |
| `@sliderWidth` | checkbox.variables | Slider width | `3.5rem` | Size |
| `@sliderHeight` | checkbox.variables | Slider height | `1.25rem` | Size |
| `@sliderHandleSize` | checkbox.variables | Slider handle size | `1.25rem` | Size |
| `@sliderLineColor` | checkbox.variables | Slider line color | `rgba(34, 36, 38, 0.35)` | Color |
| `@sliderLineRadius` | checkbox.variables | Slider line radius | `1rem` | Size |
| `@sliderLineHeight` | checkbox.variables | Slider line height | `0.25rem` | Size |
| `@sliderHandleBackground` | checkbox.variables | Slider handle background | `#FFFFFF` | Color |
| `@sliderHandleBorderRadius` | checkbox.variables | Slider handle border radius | `1rem` | Size |
| `@sliderHandleBoxShadow` | checkbox.variables | Slider handle shadow | `0px 1px 2px 0 rgba(34, 36, 38, 0.15), 0px 0px 0px 1px rgba(34, 36, 38, 0.15) inset` | Shadow |
| `@sliderHandleTransition` | checkbox.variables | Slider handle transition | `left 0.3s ease` | Transition |

### States
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@checkboxHoverBackground` | checkbox.variables | Checkbox hover background | `#FFFFFF` | Color |
| `@checkboxHoverBorderColor` | checkbox.variables | Checkbox hover border color | `rgba(34, 36, 38, 0.35)` | Color |
| `@checkboxPressedBackground` | checkbox.variables | Checkbox pressed background | `rgba(34, 36, 38, 0.35)` | Color |
| `@checkboxPressedBorderColor` | checkbox.variables | Checkbox pressed border color | `rgba(34, 36, 38, 0.35)` | Color |
| `@checkboxPressedColor` | checkbox.variables | Checkbox pressed text color | `rgba(0, 0, 0, 0.95)` | Color |
| `@checkboxCheckedBackground` | checkbox.variables | Checked checkbox background | `@primaryColor` | Color |
| `@checkboxCheckedBorderColor` | checkbox.variables | Checked checkbox border color | `@primaryColor` | Color |
| `@checkboxCheckedCheckColor` | checkbox.variables | Checked checkbox mark color | `rgba(255, 255, 255, 1)` | Color |
| `@checkboxActiveBackground` | checkbox.variables | Active checkbox background | `#F0F0F0` | Color |
| `@checkboxActiveBorderColor` | checkbox.variables | Active checkbox border color | `rgba(34, 36, 38, 0.35)` | Color |
| `@checkboxActiveCheckColor` | checkbox.variables | Active checkbox mark color | `rgba(0, 0, 0, 0.95)` | Color |
| `@checkboxIndeterminateBackground` | checkbox.variables | Indeterminate background | `@primaryColor` | Color |
| `@checkboxIndeterminateBorderColor` | checkbox.variables | Indeterminate border color | `@primaryColor` | Color |
| `@checkboxIndeterminateCheckColor` | checkbox.variables | Indeterminate mark color | `rgba(255, 255, 255, 1)` | Color |

---

## Accordion Variables (accordion.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | accordion.variables | Accordion background | `transparent` | Color |
| `@borderRadius` | accordion.variables | Accordion border radius | `@relativeBorderRadius` | Size |
| `@margin` | accordion.variables | Accordion margin | `1rem 0em` | Size |

### Title Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@titleBackground` | accordion.variables | Title background color | `transparent` | Color |
| `@titleBorder` | accordion.variables | Title border | `1px solid rgba(34,36,38,.15)` | Border |
| `@titleColor` | accordion.variables | Title text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@titlePadding` | accordion.variables | Title padding | `0.5em 0em 0.5em 1em` | Size |
| `@titleFontSize` | accordion.variables | Title font size | `1em` | Size |
| `@titleFontWeight` | accordion.variables | Title font weight | `normal` | String |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@contentBackground` | accordion.variables | Content background color | `transparent` | Color |
| `@contentBorder` | accordion.variables | Content border | `1px solid rgba(34,36,38,.15)` | Border |
| `@contentMargin` | accordion.variables | Content margin | `0em` | Size |
| `@contentPadding` | accordion.variables | Content padding | `0.5em 0em 1em 1em` | Size |
| `@contentColor` | accordion.variables | Content text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@contentTransition` | accordion.variables | Content transition | `background-color 0.1s ease, box-shadow 0.1s ease` | Transition |

### Icon Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@iconColor` | accordion.variables | Icon color | `rgba(0, 0, 0, 0.87)` | Color |
| `@iconTransition` | accordion.variables | Icon transition | `transform 0.1s ease, color 0.1s ease` | Transition |
| `@iconOpacity` | accordion.variables | Icon opacity | `1` | Number |
| `@iconWidth` | accordion.variables | Icon width | `1.25em` | Size |
| `@iconHeight` | accordion.variables | Icon height | `1em` | Size |
| `@iconMargin` | accordion.variables | Icon margin | `0em 0.25rem 0em 0rem` | Size |

### Hover Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@titleHoverBackground` | accordion.variables | Title hover background | `transparent` | Color |
| `@titleHoverColor` | accordion.variables | Title hover text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@iconHoverOpacity` | accordion.variables | Icon hover opacity | `1` | Number |

### Active Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@activeTitleBackground` | accordion.variables | Active title background | `transparent` | Color |
| `@activeTitleColor` | accordion.variables | Active title text color | `rgba(0, 0, 0, 0.95)` | Color |
| `@activeIconOpacity` | accordion.variables | Active icon opacity | `1` | Number |

---

## Progress Variables (progress.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | progress.variables | Progress background | `rgba(0, 0, 0, 0.1)` | Color |
| `@border` | progress.variables | Progress border | `none` | Border |
| `@borderRadius` | progress.variables | Progress border radius | `@relativeBorderRadius` | Size |
| `@margin` | progress.variables | Progress margin | `1em 0em 2.5em` | Size |
| `@padding` | progress.variables | Progress padding | `0em` | Size |

### Bar Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@barBackground` | progress.variables | Progress bar background | `#888888` | Color |
| `@barBorderRadius` | progress.variables | Progress bar border radius | `@relativeBorderRadius` | Size |
| `@barHeight` | progress.variables | Progress bar height | `1.75em` | Size |
| `@barTransition` | progress.variables | Progress bar transition | `width 0.1s ease, background-color 0.1s ease` | Transition |

### Label Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@labelBackground` | progress.variables | Label background | `transparent` | Color |
| `@labelColor` | progress.variables | Label text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@labelLeft` | progress.variables | Label left position | `0.5em` | Size |
| `@labelTop` | progress.variables | Label top position | `50%` | Size |
| `@labelFontSize` | progress.variables | Label font size | `1em` | Size |
| `@labelFontWeight` | progress.variables | Label font weight | `bold` | String |
| `@labelTextAlign` | progress.variables | Label text alignment | `left` | String |

### States
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@successBarBackground` | progress.variables | Success bar background | `@positiveColor` | Color |
| `@successColor` | progress.variables | Success text color | `@positiveColor` | Color |
| `@warningBarBackground` | progress.variables | Warning bar background | `@warningColor` | Color |
| `@warningColor` | progress.variables | Warning text color | `@warningColor` | Color |
| `@errorBarBackground` | progress.variables | Error bar background | `@negativeColor` | Color |
| `@errorColor` | progress.variables | Error text color | `@negativeColor` | Color |
| `@activeBarBackground` | progress.variables | Active bar background | `@primaryColor` | Color |
| `@activeColor` | progress.variables | Active text color | `@primaryColor` | Color |

---

## Dimmer Variables (dimmer.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@backgroundColor` | dimmer.variables | Dimmer background color | `rgba(0, 0, 0, 0.85)` | Color |
| `@lineHeight` | dimmer.variables | Dimmer line height | `1` | Size |
| `@perspective` | dimmer.variables | Dimmer perspective | `2000px` | Size |
| `@transformStyle` | dimmer.variables | Dimmer transform style | `preserve-3d` | String |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@contentColor` | dimmer.variables | Content text color | `#FFFFFF` | Color |
| `@contentZIndex` | dimmer.variables | Content z-index | `1` | Number |

### Inverted Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@invertedBackgroundColor` | dimmer.variables | Inverted dimmer background | `rgba(255, 255, 255, 0.85)` | Color |
| `@invertedContentColor` | dimmer.variables | Inverted content color | `rgba(0, 0, 0, 0.87)` | Color |

---

## Popup Variables (popup.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | popup.variables | Popup background color | `#FFFFFF` | Color |
| `@border` | popup.variables | Popup border | `1px solid rgba(34,36,38,.15)` | Border |
| `@borderRadius` | popup.variables | Popup border radius | `@relativeBorderRadius` | Size |
| `@boxShadow` | popup.variables | Popup shadow | `0px 2px 4px 0px rgba(34, 36, 38, 0.12), 0px 2px 10px 0px rgba(34, 36, 38, 0.15)` | Shadow |
| `@color` | popup.variables | Popup text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@margin` | popup.variables | Popup margin | `0em` | Size |
| `@fontSize` | popup.variables | Popup font size | `0.92857143rem` | Size |
| `@fontWeight` | popup.variables | Popup font weight | `normal` | String |
| `@fontStyle` | popup.variables | Popup font style | `normal` | String |
| `@lineHeight` | popup.variables | Popup line height | `1.4285em` | Size |
| `@maxWidth` | popup.variables | Popup maximum width | `250px` | Size |
| `@zIndex` | popup.variables | Popup z-index | `1900` | Number |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@padding` | popup.variables | Content padding | `0.833em 1em` | Size |
| `@minHeight` | popup.variables | Content minimum height | `2rem` | Size |

### Header Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@headerFontSize` | popup.variables | Header font size | `1.125em` | Size |
| `@headerFontWeight` | popup.variables | Header font weight | `bold` | String |
| `@headerColor` | popup.variables | Header text color | `rgba(0, 0, 0, 0.85)` | Color |
| `@headerDistance` | popup.variables | Header distance from content | `0.75rem` | Size |

### Arrow Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@arrowSize` | popup.variables | Arrow size | `0.71428571em` | Size |
| `@arrowColor` | popup.variables | Arrow color | `#FFFFFF` | Color |
| `@arrowBoxShadow` | popup.variables | Arrow shadow | `1px 1px 0px 0px rgba(34, 36, 38, 0.15)` | Shadow |
| `@arrowDistance` | popup.variables | Arrow distance from popup | `0em` | Size |
| `@arrowOffset` | popup.variables | Arrow offset | `@arrowSize` | Size |
| `@arrowBuffer` | popup.variables | Arrow buffer space | `@arrowSize` | Size |

### Loading Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@loaderSize` | popup.variables | Loader size | `1.5em` | Size |
| `@loaderLineWidth` | popup.variables | Loader line width | `0.2em` | Size |
| `@loaderFillColor` | popup.variables | Loader fill color | `rgba(0, 0, 0, 0.1)` | Color |
| `@loaderLineColor` | popup.variables | Loader line color | `@grey` | Color |

### Inverted Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@invertedBackground` | popup.variables | Inverted popup background | `#1B1C1D` | Color |
| `@invertedColor` | popup.variables | Inverted popup text color | `rgba(255, 255, 255, 0.9)` | Color |
| `@invertedBorder` | popup.variables | Inverted popup border | `none` | Border |
| `@invertedHeaderColor` | popup.variables | Inverted header color | `rgba(255, 255, 255, 0.9)` | Color |
| `@invertedArrowColor` | popup.variables | Inverted arrow color | `#1B1C1D` | Color |

---

## Breadcrumb Variables (breadcrumb.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@display` | breadcrumb.variables | Breadcrumb display | `inline-block` | String |
| `@verticalMargin` | breadcrumb.variables | Breadcrumb vertical margin | `0em` | Size |
| `@fontSize` | breadcrumb.variables | Breadcrumb font size | `1em` | Size |
| `@textColor` | breadcrumb.variables | Breadcrumb text color | `rgba(0, 0, 0, 0.4)` | Color |
| `@fontWeight` | breadcrumb.variables | Breadcrumb font weight | `normal` | String |

### Divider Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@dividerSpacing` | breadcrumb.variables | Divider spacing | `0.5rem` | Size |
| `@dividerOpacity` | breadcrumb.variables | Divider opacity | `0.7` | Number |
| `@dividerColor` | breadcrumb.variables | Divider color | `rgba(0, 0, 0, 0.4)` | Color |
| `@dividerSize` | breadcrumb.variables | Divider font size | `0.9em` | Size |

### Link Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@linkColor` | breadcrumb.variables | Link color | `@linkColor` | Color |
| `@linkHoverColor` | breadcrumb.variables | Link hover color | `@linkHoverColor` | Color |

### Active Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@activeColor` | breadcrumb.variables | Active section color | `rgba(0, 0, 0, 0.95)` | Color |
| `@activeFontWeight` | breadcrumb.variables | Active section font weight | `bold` | String |

### Size Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@miniSize` | breadcrumb.variables | Mini breadcrumb size | `0.71428571rem` | Size |
| `@tinySize` | breadcrumb.variables | Tiny breadcrumb size | `0.85714286rem` | Size |
| `@smallSize` | breadcrumb.variables | Small breadcrumb size | `0.92857143rem` | Size |
| `@mediumSize` | breadcrumb.variables | Medium breadcrumb size | `1rem` | Size |
| `@largeSize` | breadcrumb.variables | Large breadcrumb size | `1.14285714rem` | Size |
| `@bigSize` | breadcrumb.variables | Big breadcrumb size | `1.28571429rem` | Size |
| `@hugeSize` | breadcrumb.variables | Huge breadcrumb size | `1.42857143rem` | Size |
| `@massiveSize` | breadcrumb.variables | Massive breadcrumb size | `1.71428571rem` | Size |

---

## Icon Variables (icon.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@fontFamily` | icon.variables | Icon font family | `Icons` | String |
| `@fontWeight` | icon.variables | Icon font weight | `normal` | String |
| `@fontStyle` | icon.variables | Icon font style | `normal` | String |
| `@textDecoration` | icon.variables | Icon text decoration | `inherit` | String |
| `@textAlign` | icon.variables | Icon text alignment | `center` | String |
| `@speak` | icon.variables | Screen reader speak | `none` | String |
| `@fontSmoothing` | icon.variables | Font smoothing | `antialiased` | String |
| `@backfaceVisibility` | icon.variables | Backface visibility | `hidden` | String |

### Sizing
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@width` | icon.variables | Icon width | `1.18em` | Size |
| `@height` | icon.variables | Icon height | `1em` | Size |
| `@lineHeight` | icon.variables | Icon line height | `1` | Size |

### Size Variations
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@miniSize` | icon.variables | Mini icon size | `0.4em` | Size |
| `@tinySize` | icon.variables | Tiny icon size | `0.5em` | Size |
| `@smallSize` | icon.variables | Small icon size | `0.75em` | Size |
| `@mediumSize` | icon.variables | Medium icon size | `1em` | Size |
| `@largeSize` | icon.variables | Large icon size | `1.5em` | Size |
| `@bigSize` | icon.variables | Big icon size | `2em` | Size |
| `@hugeSize` | icon.variables | Huge icon size | `4em` | Size |
| `@massiveSize` | icon.variables | Massive icon size | `8em` | Size |

### Loading Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@loadingDuration` | icon.variables | Loading animation duration | `2s` | Time |

### Link Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@linkOpacity` | icon.variables | Link icon opacity | `0.8` | Number |
| `@linkDuration` | icon.variables | Link transition duration | `0.1s` | Time |
| `@linkHoverOpacity` | icon.variables | Link hover opacity | `1` | Number |

### Circular Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@circularBorderRadius` | icon.variables | Circular icon border radius | `500rem` | Size |
| `@circularSize` | icon.variables | Circular icon size | `2em` | Size |
| `@circularPadding` | icon.variables | Circular icon padding | `0.5em 0em` | Size |
| `@circularBoxShadow` | icon.variables | Circular icon shadow | `0em 0em 0em 0.1em rgba(0, 0, 0, 0.1) inset` | Shadow |

### Bordered Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@borderedSize` | icon.variables | Bordered icon size | `2em` | Size |
| `@borderedVerticalPadding` | icon.variables | Bordered vertical padding | `0.5em` | Size |
| `@borderedHorizontalPadding` | icon.variables | Bordered horizontal padding | `0.41666667em` | Size |
| `@borderedBorder` | icon.variables | Bordered icon border | `0.1em solid rgba(0, 0, 0, 0.1)` | Border |

### Inverted Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@invertedColor` | icon.variables | Inverted icon color | `#FFFFFF` | Color |
| `@invertedCircularColor` | icon.variables | Inverted circular color | `#1B1C1D` | Color |
| `@invertedBorderedColor` | icon.variables | Inverted bordered color | `#1B1C1D` | Color |
| `@invertedShadow` | icon.variables | Inverted icon shadow | `0em 0em 0em 0.1em rgba(255, 255, 255, 0.1) inset` | Shadow |

---

## Step Variables (step.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@stepMargin` | step.variables | Step margin | `0em` | Size |
| `@verticalMargin` | step.variables | Vertical step margin | `0em 0em 1em 0em` | Size |
| `@horizontalMargin` | step.variables | Horizontal step margin | `0em -@arrowSize 0em 0em` | Size |
| `@arrowSize` | step.variables | Arrow size | `1.14285714rem` | Size |
| `@lineHeight` | step.variables | Step line height | `1.14285714em` | Size |

### Background Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@background` | step.variables | Step background | `rgba(0, 0, 0, 0.1)` | Color |
| `@borderRadius` | step.variables | Step border radius | `0em` | Size |
| `@border` | step.variables | Step border | `none` | Border |
| `@boxShadow` | step.variables | Step shadow | `none` | Shadow |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@color` | step.variables | Step text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@fontWeight` | step.variables | Step font weight | `normal` | String |
| `@padding` | step.variables | Step padding | `1.14285714em 2em 1.14285714em 2em` | Size |
| `@position` | step.variables | Step position | `relative` | String |

### Icon Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@iconDistance` | step.variables | Icon distance | `0.25rem` | Size |
| `@iconSize` | step.variables | Icon size | `2.5em` | Size |
| `@iconAlign` | step.variables | Icon alignment | `middle` | String |

### Title Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@titleFontFamily` | step.variables | Title font family | `@headerFont` | Font Stack |
| `@titleFontWeight` | step.variables | Title font weight | `bold` | String |
| `@titleFontSize` | step.variables | Title font size | `1.14285714em` | Size |
| `@titleColor` | step.variables | Title text color | `rgba(0, 0, 0, 0.85)` | Color |

### Description Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@descriptionDistance` | step.variables | Description distance | `0.25em` | Size |
| `@descriptionFontSize` | step.variables | Description font size | `0.92857143em` | Size |
| `@descriptionFontWeight` | step.variables | Description font weight | `normal` | String |
| `@descriptionColor` | step.variables | Description text color | `rgba(0, 0, 0, 0.87)` | Color |

### Arrow Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@arrowBackground` | step.variables | Arrow background | `rgba(0, 0, 0, 0.1)` | Color |
| `@arrowTopOffset` | step.variables | Arrow top offset | `50%` | Size |
| `@arrowRightOffset` | step.variables | Arrow right offset | `0em` | Size |
| `@arrowBorderWidth` | step.variables | Arrow border width | `@arrowSize` | Size |

### Hover Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@hoverBackground` | step.variables | Hover background | `rgba(0, 0, 0, 0.06)` | Color |
| `@hoverColor` | step.variables | Hover text color | `rgba(0, 0, 0, 0.8)` | Color |

### Down Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@downBackground` | step.variables | Pressed background | `rgba(0, 0, 0, 0.03)` | Color |
| `@downColor` | step.variables | Pressed text color | `rgba(0, 0, 0, 0.9)` | Color |

### Active Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@activeBackground` | step.variables | Active step background | `@primaryColor` | Color |
| `@activeColor` | step.variables | Active step text color | `rgba(255, 255, 255, 0.95)` | Color |
| `@activeTitleColor` | step.variables | Active title color | `rgba(255, 255, 255, 1)` | Color |
| `@activeArrowBackground` | step.variables | Active arrow background | `@primaryColor` | Color |

### Completed Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@completedBackground` | step.variables | Completed step background | `@positiveColor` | Color |
| `@completedColor` | step.variables | Completed step text color | `rgba(255, 255, 255, 0.95)` | Color |
| `@completedTitleColor` | step.variables | Completed title color | `rgba(255, 255, 255, 1)` | Color |

### Disabled Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@disabledBackground` | step.variables | Disabled step background | `@background` | Color |
| `@disabledColor` | step.variables | Disabled step text color | `rgba(40, 40, 40, 0.3)` | Color |

---

## Label Variables (label.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@verticalAlign` | label.variables | Label vertical alignment | `baseline` | String |
| `@background` | label.variables | Label background color | `#E8E8E8` | Color |
| `@backgroundImage` | label.variables | Label background image | `none` | String |
| `@color` | label.variables | Label text color | `rgba(0, 0, 0, 0.87)` | Color |
| `@fontSize` | label.variables | Label font size | `0.85714286em` | Size |
| `@fontWeight` | label.variables | Label font weight | `bold` | String |
| `@borderRadius` | label.variables | Label border radius | `@relativeBorderRadius` | Size |
| `@border` | label.variables | Label border | `0px solid transparent` | Border |
| `@lineHeight` | label.variables | Label line height | `1` | Size |
| `@textTransform` | label.variables | Label text transform | `none` | String |

| `@verticalMargin` | label.variables | Label vertical margin | `0em` | Size |
| `@horizontalMargin` | label.variables | Label horizontal margin | `0.14285714em` | Size |
| `@margin` | label.variables | Combined label margin | `@verticalMargin @horizontalMargin @verticalMargin 0em` | Size |
| `@verticalPadding` | label.variables | Label vertical padding | `0.5833em` | Size |
| `@horizontalPadding` | label.variables | Label horizontal padding | `0.833em` | Size |
| `@padding` | label.variables | Combined label padding | `@verticalPadding @horizontalPadding` | Size |

### Transition Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@transition` | label.variables | Label transition | `background 0.1s ease` | Transition |

### Detail Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@detailBackground` | label.variables | Detail background | `rgba(0, 0, 0, 0.1)` | Color |
| `@detailMargin` | label.variables | Detail margin | `1em 0em 0em 1em` | Size |
| `@detailPadding` | label.variables | Detail padding | `@verticalPadding @horizontalPadding` | Size |
| `@detailFontWeight` | label.variables | Detail font weight | `bold` | String |

### Delete Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@deleteOpacity` | label.variables | Delete icon opacity | `0.5` | Number |
| `@deleteSize` | label.variables | Delete icon size | `0.92307692em` | Size |
| `@deleteMargin` | label.variables | Delete icon margin | `0.5em 0em 0em 0.5em` | Size |
| `@deleteTransition` | label.variables | Delete icon transition | `background 0.1s ease` | Transition |

### Pointing Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@pointingBorderColor` | label.variables | Pointing border color | `rgba(0, 0, 0, 0.87)` | Color |
| `@pointingBorderWidth` | label.variables | Pointing border width | `1px` | Size |
| `@pointingTriangleSize` | label.variables | Pointing triangle size | `0.6666em` | Size |
| `@pointingTriangleTransition` | label.variables | Pointing triangle transition | `background 0.1s ease` | Transition |
| `@pointingTriangleZIndex` | label.variables | Pointing triangle z-index | `2` | Number |

### Corner Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@cornerSizeRatio` | label.variables | Corner size ratio | `1` | Number |
| `@cornerBackgroundColor` | label.variables | Corner background color | `@primaryColor` | Color |
| `@cornerFontSize` | label.variables | Corner font size | `0.64285714em` | Size |
| `@cornerTopDistance` | label.variables | Corner top distance | `1em` | Size |
| `@cornerRightDistance` | label.variables | Corner right distance | `1em` | Size |

### Ribbon Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@ribbonTriangleSize` | label.variables | Ribbon triangle size | `1.2em` | Size |
| `@ribbonShadowColor` | label.variables | Ribbon shadow color | `rgba(0, 0, 0, 0.15)` | Color |
| `@ribbonMargin` | label.variables | Ribbon margin | `1rem` | Size |
| `@ribbonOffset` | label.variables | Ribbon offset | `-@ribbonMargin` | Size |
| `@ribbonDistance` | label.variables | Ribbon distance | `@ribbonMargin` | Size |

### Attached Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@attachedSegmentPadding` | label.variables | Attached segment padding | `2rem` | Size |
| `@attachedVerticalPadding` | label.variables | Attached vertical padding | `0.75em` | Size |
| `@attachedHorizontalPadding` | label.variables | Attached horizontal padding | `1em` | Size |
| `@attachedCornerBorderRadius` | label.variables | Attached corner radius | `@relativeBorderRadius` | Size |

### Tag Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@tagCircleColor` | label.variables | Tag circle color | `@textColor` | Color |
| `@tagCircleSize` | label.variables | Tag circle size | `0.5em` | Size |
| `@tagHorizontalPadding` | label.variables | Tag horizontal padding | `1.5em` | Size |
| `@tagCircleBoxShadow` | label.variables | Tag circle shadow | `0 -1px 1px 0 rgba(0, 0, 0, 0.3) inset` | Shadow |
| `@tagTriangleRightOffset` | label.variables | Tag triangle right offset | `-1.25em` | Size |
| `@tagTriangleTopOffset` | label.variables | Tag triangle top offset | `1.25em` | Size |
| `@tagTriangleSize` | label.variables | Tag triangle size | `1.56em` | Size |
| `@tagTransition` | label.variables | Tag transition | `all 0.1s ease` | Transition |

---

## Loader Variables (loader.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@loaderSize` | loader.variables | Default loader size | `2.25714286em` | Size |
| `@loaderLineWidth` | loader.variables | Loader line width | `0.2em` | Size |
| `@loaderFillColor` | loader.variables | Loader fill color | `rgba(0, 0, 0, 0.1)` | Color |
| `@loaderLineColor` | loader.variables | Loader line color | `@grey` | Color |
| `@loaderSpeed` | loader.variables | Loader animation speed | `0.6s` | Time |

### Size Variations
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@miniLoaderSize` | loader.variables | Mini loader size | `1em` | Size |
| `@tinyLoaderSize` | loader.variables | Tiny loader size | `1.14285714em` | Size |
| `@smallLoaderSize` | loader.variables | Small loader size | `1.71428571em` | Size |
| `@mediumLoaderSize` | loader.variables | Medium loader size | `2.25714286em` | Size |
| `@largeLoaderSize` | loader.variables | Large loader size | `3.42857143em` | Size |
| `@bigLoaderSize` | loader.variables | Big loader size | `3.71428571em` | Size |
| `@hugeLoaderSize` | loader.variables | Huge loader size | `4.14285714em` | Size |
| `@massiveLoaderSize` | loader.variables | Massive loader size | `4.57142857em` | Size |

### Inline Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@inlineLoaderSize` | loader.variables | Inline loader size | `1em` | Size |
| `@inlineMargin` | loader.variables | Inline loader margin | `0em 0.25rem 0em 0.5rem` | Size |

### Text Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@invertedLoaderFillColor` | loader.variables | Inverted loader fill color | `rgba(255, 255, 255, 0.15)` | Color |
| `@invertedLoaderLineColor` | loader.variables | Inverted loader line color | `@white` | Color |

---

## Grid Variables (grid.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@minWidth` | grid.variables | Grid minimum width | `320px` | Size |
| `@gutterWidth` | grid.variables | Grid gutter width | `2rem` | Size |
| `@rowSpacing` | grid.variables | Row spacing | `1rem` | Size |

### Column Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@columnCount` | grid.variables | Number of columns | `16` | Number |
| `@oneWide` | grid.variables | One column width | `(1 / @columnCount * 100%)` | Size |
| `@twoWide` | grid.variables | Two column width | `(2 / @columnCount * 100%)` | Size |
| `@threeWide` | grid.variables | Three column width | `(3 / @columnCount * 100%)` | Size |
| `@fourWide` | grid.variables | Four column width | `(4 / @columnCount * 100%)` | Size |
| `@fiveWide` | grid.variables | Five column width | `(5 / @columnCount * 100%)` | Size |
| `@sixWide` | grid.variables | Six column width | `(6 / @columnCount * 100%)` | Size |
| `@sevenWide` | grid.variables | Seven column width | `(7 / @columnCount * 100%)` | Size |
| `@eightWide` | grid.variables | Eight column width | `(8 / @columnCount * 100%)` | Size |
| `@nineWide` | grid.variables | Nine column width | `(9 / @columnCount * 100%)` | Size |
| `@tenWide` | grid.variables | Ten column width | `(10 / @columnCount * 100%)` | Size |
| `@elevenWide` | grid.variables | Eleven column width | `(11 / @columnCount * 100%)` | Size |
| `@twelveWide` | grid.variables | Twelve column width | `(12 / @columnCount * 100%)` | Size |
| `@thirteenWide` | grid.variables | Thirteen column width | `(13 / @columnCount * 100%)` | Size |
| `@fourteenWide` | grid.variables | Fourteen column width | `(14 / @columnCount * 100%)` | Size |
| `@fifteenWide` | grid.variables | Fifteen column width | `(15 / @columnCount * 100%)` | Size |
| `@sixteenWide` | grid.variables | Sixteen column width | `(16 / @columnCount * 100%)` | Size |

### Responsive Breakpoints
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@mobileBreakpoint` | grid.variables | Mobile breakpoint | `@tabletBreakpoint - 1px` | Size |
| `@tabletBreakpoint` | grid.variables | Tablet breakpoint | `768px` | Size |
| `@computerBreakpoint` | grid.variables | Computer breakpoint | `992px` | Size |
| `@largeMonitorBreakpoint` | grid.variables | Large monitor breakpoint | `1200px` | Size |
| `@widescreenMonitorBreakpoint` | grid.variables | Widescreen breakpoint | `1920px` | Size |

### Container Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@mobileMinWidth` | grid.variables | Mobile minimum width | `@minWidth` | Size |
| `@mobileMaxWidth` | grid.variables | Mobile maximum width | `@tabletBreakpoint - 1px` | Size |
| `@mobileMargin` | grid.variables | Mobile margin | `1em` | Size |
| `@mobileGridWidth` | grid.variables | Mobile grid width | `auto` | Size |
| `@tabletMinWidth` | grid.variables | Tablet minimum width | `@tabletBreakpoint` | Size |
| `@tabletMaxWidth` | grid.variables | Tablet maximum width | `@computerBreakpoint - 1px` | Size |
| `@tabletMargin` | grid.variables | Tablet margin | `1em` | Size |
| `@tabletGridWidth` | grid.variables | Tablet grid width | `auto` | Size |
| `@computerMinWidth` | grid.variables | Computer minimum width | `@computerBreakpoint` | Size |
| `@computerMaxWidth` | grid.variables | Computer maximum width | `@largeMonitorBreakpoint - 1px` | Size |
| `@computerMargin` | grid.variables | Computer margin | `1em` | Size |
| `@computerGridWidth` | grid.variables | Computer grid width | `auto` | Size |

---

## Container Variables (container.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@maxWidth` | container.variables | Container maximum width | `100%` | Size |
| `@mobileMinWidth` | container.variables | Mobile minimum width | `320px` | Size |
| `@mobileMaxWidth` | container.variables | Mobile maximum width | `767px` | Size |
| `@mobileMargin` | container.variables | Mobile margin | `1em` | Size |
| `@mobileGutter` | container.variables | Mobile gutter | `1em` | Size |
| `@tabletMinWidth` | container.variables | Tablet minimum width | `768px` | Size |
| `@tabletMaxWidth` | container.variables | Tablet maximum width | `991px` | Size |
| `@tabletMargin` | container.variables | Tablet margin | `1em` | Size |
| `@tabletGutter` | container.variables | Tablet gutter | `1em` | Size |
| `@computerMinWidth` | container.variables | Computer minimum width | `992px` | Size |
| `@computerMaxWidth` | container.variables | Computer maximum width | `1199px` | Size |
| `@computerMargin` | container.variables | Computer margin | `1em` | Size |
| `@computerGutter` | container.variables | Computer gutter | `1.5em` | Size |
| `@largeMonitorMinWidth` | container.variables | Large monitor min width | `1200px` | Size |
| `@largeMonitorMaxWidth` | container.variables | Large monitor max width | `1919px` | Size |
| `@largeMonitorMargin` | container.variables | Large monitor margin | `1em` | Size |
| `@largeMonitorGutter` | container.variables | Large monitor gutter | `2em` | Size |

---

## Divider Variables (divider.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@margin` | divider.variables | Divider margin | `1rem 0rem` | Size |
| `@color` | divider.variables | Divider color | `rgba(0, 0, 0, 0.1)` | Color |
| `@size` | divider.variables | Divider size | `1px` | Size |
| `@textColor` | divider.variables | Divider text color | `rgba(0, 0, 0, 0.85)` | Color |
| `@fontWeight` | divider.variables | Divider text font weight | `bold` | String |
| `@textTransform` | divider.variables | Divider text transform | `uppercase` | String |
| `@fontSize` | divider.variables | Divider text font size | `0.9em` | Size |

### Content Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@contentBackground` | divider.variables | Content background | `@pageBackground` | Color |
| `@contentPadding` | divider.variables | Content padding | `0rem 1rem` | Size |

### Section Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@sectionMargin` | divider.variables | Section divider margin | `2rem 0rem` | Size |

### Clearing Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@clearingMargin` | divider.variables | Clearing divider margin | `0rem` | Size |

### Vertical Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@verticalMargin` | divider.variables | Vertical divider margin | `0rem 1rem` | Size |
| `@verticalHeight` | divider.variables | Vertical divider height | `100%` | Size |

---

## Image Variables (image.variables)

### Basic Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@verticalAlign` | image.variables | Image vertical alignment | `middle` | String |
| `@maxWidth` | image.variables | Image maximum width | `100%` | Size |
| `@height` | image.variables | Image height | `auto` | Size |

### Size Variations
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@miniWidth` | image.variables | Mini image width | `35px` | Size |
| `@tinyWidth` | image.variables | Tiny image width | `80px` | Size |
| `@smallWidth` | image.variables | Small image width | `150px` | Size |
| `@mediumWidth` | image.variables | Medium image width | `300px` | Size |
| `@largeWidth` | image.variables | Large image width | `450px` | Size |
| `@bigWidth` | image.variables | Big image width | `600px` | Size |
| `@hugeWidth` | image.variables | Huge image width | `800px` | Size |
| `@massiveWidth` | image.variables | Massive image width | `960px` | Size |

### Avatar Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@avatarSize` | image.variables | Avatar image size | `2em` | Size |
| `@avatarMargin` | image.variables | Avatar image margin | `0.25em 0.25em 0.25em 0em` | Size |

### Bordered Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@borderedBorder` | image.variables | Bordered image border | `1px solid rgba(0, 0, 0, 0.1)` | Border |
| `@borderedBorderRadius` | image.variables | Bordered image radius | `@relativeBorderRadius` | Size |

### Circular Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@circularBorderRadius` | image.variables | Circular image radius | `500rem` | Size |

### Fluid Properties
| Variable | File | Description | Default | Type |
|----------|------|-------------|---------|------|
| `@fluidMaxWidth` | image.variables | Fluid image max width | `100%` | Size |
| `@fluidHeight` | image.variables | Fluid image height | `auto` | Size |

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

### Variable Naming Conventions
- `@background` - Background colors
- `@color` - Text colors
- `@border` - Border properties
- `@margin` / `@padding` - Spacing
- `@fontSize` / `@fontWeight` - Typography
- `@hoverX` / `@activeX` / `@focusX` - State variations
- `@variationX` - Boolean flags to enable/disable features

### Most Commonly Customized Variables
1. **Global colors** - `@primaryColor`, `@secondaryColor`, color palette
2. **Typography** - `@fontName`, `@fontSize`, `@headerFont`
3. **Spacing** - `@borderRadius`, `@gutterWidth`, component padding/margins
4. **Component colors** - Button, menu, input backgrounds and text colors
5. **State colors** - Hover, active, focus, error states

---

*This comprehensive inventory covers all major Fomantic UI components and their customizable variables. Use this as your single reference guide instead of hunting through multiple theme files!*

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