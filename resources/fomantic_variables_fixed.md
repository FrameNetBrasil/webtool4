# Complete Fomantic UI Variables Documentation

A comprehensive reference guide to all customizable variables in Fomantic UI's `site.variables` file, organized by category and type.

## How to Use This Guide

- **Variable Name**: The exact variable name to use in your `.variables` files
- **Description**: What the variable controls
- **Default Value**: Default value from the site.variables file
- **Type**: Color, Size, Boolean, String, etc.
- **Usage**: Where and how this variable is typically used

---

## Font Configuration

### Core Font Settings
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@fontName` | Main font family name | `"Lato"` | String |
| `@fontFileNameLatin` | Latin font file base name | `"LatoLatin"` | String |
| `@fontFileNameSupplement` | Supplemental font file base name | `"Lato"` | String |
| `@fallbackFonts` | System font fallback stack | Bootstrap-based system fonts | Font Stack |
| `@headerFont` | Header font stack | `@fontName, @fallbackFonts` | Font Stack |
| `@pageFont` | Body text font stack | `@fontName, @fallbackFonts` | Font Stack |
| `@fontDisplay` | Font display strategy | `swap` | String |
| `@textRendering` | Text rendering optimization | `optimizeLegibility` | String |

### Font Loading Control
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@importFonts` | Whether to import font files | `true` | Boolean |
| `@importGoogleFonts` | Whether to import from Google Fonts | `false` | Boolean |
| `@googleFontName` | Google Fonts font name | `@fontName` | String |
| `@googleFontSizes` | Google Fonts weight/style config | `"ital,wght@0,400%3B0,700%3B0,400%3B1,700"` | String |
| `@googleSubset` | Google Fonts character subset | `"latin"` | String |
| `@googleFontDisplay` | Google Fonts display strategy | `"swap"` | String |
| `@googleProtocol` | Protocol for Google Fonts | `"https://"` | String |

### Unicode Ranges
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@unicodeRangeLatin` | Unicode range for Latin characters | Extensive Latin range | Unicode Range |
| `@unicodeRangeSupplement` | Unicode range for supplemental characters | Extended Latin + symbols | Unicode Range |

### Font Weights
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@bold` | Bold font weight value | `bold` | String/Number |
| `@normal` | Normal font weight value | `normal` | String/Number |

---

## Typography & Text

### Base Typography
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@emSize` | Base em unit size (controls all relative sizing) | `14px` | Size |
| `@fontSize` | Base font size for body text | `14px` | Size |
| `@lineHeight` | Default line height for body text | `1.4285em` | Size |
| `@textColor` | Default text color | `rgba(0, 0, 0, 0.87)` | Color |

### Header Typography
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@headerFontWeight` | Font weight for headers | `@bold` | String |
| `@headerLineHeight` | Line height for headers | `unit((18 / 14), em)` | Size |
| `@h1` | H1 header size | `unit((28 / 14), rem)` | Size |
| `@h2` | H2 header size | `unit((24 / 14), rem)` | Size |
| `@h3` | H3 header size | `unit((18 / 14), rem)` | Size |
| `@h4` | H4 header size | `unit((15 / 14), rem)` | Size |
| `@h5` | H5 header size | `unit((14 / 14), rem)` | Size |
| `@h6` | H6 header size | `unit((12 / 14), rem)` | Size |

### Paragraph & Text Elements
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@paragraphMargin` | Margin for paragraph elements | `0 0 1em` | Size |
| `@paragraphLineHeight` | Line height for paragraphs | `@lineHeight` | Size |

### Link Styling
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@linkColor` | Default link color | `#4183c4` | Color |
| `@linkUnderline` | Link underline style | `none` | String |
| `@linkHoverColor` | Link hover color (auto-calculated) | `darken(saturate(@linkColor, 20), 15, relative)` | Color |
| `@linkHoverUnderline` | Link hover underline style | `@linkUnderline` | String |

### Text Highlighting
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@highlightBackground` | Background for highlighted text | `#cce2ff` | Color |
| `@highlightColor` | Text color for highlighted text | `@textColor` | Color |
| `@inputHighlightBackground` | Background for highlighted input text | `rgba(100, 100, 100, 0.4)` | Color |
| `@inputHighlightColor` | Text color for highlighted input text | `@textColor` | Color |

---

## Color System

### Primary & Secondary Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@primaryColor` | Main brand color | `@blue` | Color |
| `@secondaryColor` | Secondary brand color | `@black` | Color |
| `@lightPrimaryColor` | Light version of primary color | `@lightBlue` | Color |
| `@lightSecondaryColor` | Light version of secondary color | `@lightBlack` | Color |
| `@blendingBaseColor` | Base color for blending calculations | `#ccc` | Color |

### Core Color Palette
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@red` | Red color in palette | `#db2828` | Color |
| `@orange` | Orange color in palette | `#f2711c` | Color |
| `@yellow` | Yellow color in palette | `#fbbd08` | Color |
| `@olive` | Olive color in palette | `#b5cc18` | Color |
| `@green` | Green color in palette | `#21ba45` | Color |
| `@teal` | Teal color in palette | `#00b5ad` | Color |
| `@blue` | Blue color in palette | `#2185d0` | Color |
| `@violet` | Violet color in palette | `#6435c9` | Color |
| `@purple` | Purple color in palette | `#a333c8` | Color |
| `@pink` | Pink color in palette | `#e03997` | Color |
| `@brown` | Brown color in palette | `#a5673f` | Color |
| `@grey` | Grey color in palette | `#767676` | Color |
| `@black` | Black color in palette | `#1b1c1d` | Color |

### Light Color Variants
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@lightRed` | Light red variant | `#ff695e` | Color |
| `@lightOrange` | Light orange variant | `#ff851b` | Color |
| `@lightYellow` | Light yellow variant | `#ffe21f` | Color |
| `@lightOlive` | Light olive variant | `#d9e778` | Color |
| `@lightGreen` | Light green variant | `#2ecc40` | Color |
| `@lightTeal` | Light teal variant | `#6dffff` | Color |
| `@lightBlue` | Light blue variant | `#54c8ff` | Color |
| `@lightViolet` | Light violet variant | `#a291fb` | Color |
| `@lightPurple` | Light purple variant | `#dc73ff` | Color |
| `@lightPink` | Light pink variant | `#ff8edf` | Color |
| `@lightBrown` | Light brown variant | `#d67c1c` | Color |
| `@lightGrey` | Light grey variant | `#dcddde` | Color |
| `@lightBlack` | Light black variant | `#545454` | Color |

### Neutral Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@fullBlack` | Pure black | `#000` | Color |
| `@offWhite` | Off-white neutral | `#f9fafb` | Color |
| `@darkWhite` | Dark white neutral | `#f3f4f5` | Color |
| `@midWhite` | Mid white neutral | `#dcddde` | Color |
| `@white` | Pure white | `#fff` | Color |

### Background Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@primaryBackground` | Primary color background | `#dff0ff` | Color |
| `@secondaryBackground` | Secondary color background | `#f4f4f4` | Color |
| `@redBackground` | Red background | `#ffe8e6` | Color |
| `@orangeBackground` | Orange background | `#ffedde` | Color |
| `@yellowBackground` | Yellow background | `#fff8db` | Color |
| `@oliveBackground` | Olive background | `#fbfdef` | Color |
| `@greenBackground` | Green background | `#e5f9e7` | Color |
| `@tealBackground` | Teal background | `#e1f7f7` | Color |
| `@blueBackground` | Blue background | `#dff0ff` | Color |
| `@violetBackground` | Violet background | `#eae7ff` | Color |
| `@purpleBackground` | Purple background | `#f6e7ff` | Color |
| `@pinkBackground` | Pink background | `#ffe3fb` | Color |
| `@brownBackground` | Brown background | `#f1e2d3` | Color |
| `@greyBackground` | Grey background | `#f4f4f4` | Color |
| `@blackBackground` | Black background | `#f4f4f4` | Color |

---

## Layout & Sizing

### Base Sizing System
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@relativeBorderRadius` | Relative border radius | `@relative4px` | Size |
| `@absoluteBorderRadius` | Absolute border radius | `@4px` | Size |
| `@defaultBorderRadius` | Default border radius used throughout | `@absoluteBorderRadius` | Size |

### Size Scale Ratios
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@miniSize` | Mini size ratio | `(11 / 14)` | Ratio |
| `@tinySize` | Tiny size ratio | `(12 / 14)` | Ratio |
| `@smallSize` | Small size ratio | `(13 / 14)` | Ratio |
| `@mediumSize` | Medium size ratio | `(14 / 14)` | Ratio |
| `@largeSize` | Large size ratio | `(16 / 14)` | Ratio |
| `@bigSize` | Big size ratio | `(18 / 14)` | Ratio |
| `@hugeSize` | Huge size ratio | `(20 / 14)` | Ratio |
| `@massiveSize` | Massive size ratio | `(24 / 14)` | Ratio |

### Page Layout
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@pageBackground` | Main page background color | `#fff` | Color |
| `@pageOverflowX` | Page horizontal overflow behavior | `hidden` | String |

---

## Form Elements

### Input Configuration
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@inputBackground` | Default input background | `@white` | Color |
| `@inputVerticalPadding` | Input vertical padding | `@relative11px` | Size |
| `@inputHorizontalPadding` | Input horizontal padding | `@relative14px` | Size |
| `@inputPadding` | Combined input padding | `@inputVerticalPadding @inputHorizontalPadding` | Size |
| `@inputColor` | Input text color | `@textColor` | Color |
| `@inputLineHeight` | Input line height | `unit((17 / 14), em)` | Size |

### Input Placeholder Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@inputPlaceholderColor` | Input placeholder color | `lighten(@inputColor, 75)` | Color |
| `@inputPlaceholderFocusColor` | Input placeholder focus color | `lighten(@inputColor, 45)` | Color |

### Focus States
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@focusedFormBorderColor` | Form element focus border (inputs, textareas) | `#85b7d9` | Color |
| `@focusedFormMutedBorderColor` | Muted focus border (dropdowns, larger blocks) | `#96c8da` | Color |

---

## Scroll Bars

### Scrollbar Configuration
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@useCustomScrollbars` | Whether to style scrollbars | `true` | Boolean |
| `@customScrollbarWidth` | Scrollbar width | `10px` | Size |
| `@customScrollbarHeight` | Scrollbar height | `10px` | Size |

### Scrollbar Track
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@trackBackground` | Scrollbar track background | `rgba(0, 0, 0, 0.1)` | Color |
| `@trackBorderRadius` | Scrollbar track border radius | `0` | Size |
| `@trackInvertedBackground` | Inverted scrollbar track background | `rgba(255, 255, 255, 0.1)` | Color |

### Scrollbar Thumb
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@thumbBorderRadius` | Scrollbar thumb border radius | `5px` | Size |
| `@thumbBackground` | Scrollbar thumb background | `rgba(0, 0, 0, 0.25)` | Color |
| `@thumbTransition` | Scrollbar thumb transition | `color 0.2s ease` | Transition |
| `@thumbInactiveBackground` | Inactive thumb background | `rgba(0, 0, 0, 0.15)` | Color |
| `@thumbHoverBackground` | Thumb hover background | `rgba(128, 135, 139, 0.8)` | Color |
| `@thumbInvertedBackground` | Inverted thumb background | `rgba(255, 255, 255, 0.25)` | Color |
| `@thumbInvertedInactiveBackground` | Inverted inactive thumb background | `rgba(255, 255, 255, 0.15)` | Color |
| `@thumbInvertedHoverBackground` | Inverted thumb hover background | `rgba(255, 255, 255, 0.35)` | Color |

---

## Animation & Transitions

### Default Transitions
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@defaultDuration` | Default transition duration | `0.1s` | Time |
| `@defaultEasing` | Default transition easing | `ease` | String |

---

## Responsive Breakpoints

### Breakpoint Configuration
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@mobileBreakpoint` | Mobile breakpoint | `320px` | Size |
| `@tabletBreakpoint` | Tablet breakpoint | `768px` | Size |
| `@computerBreakpoint` | Computer/desktop breakpoint | `992px` | Size |
| `@largeMonitorBreakpoint` | Large monitor breakpoint | `1200px` | Size |
| `@widescreenMonitorBreakpoint` | Widescreen monitor breakpoint | `1920px` | Size |

### Derived Screen Sizes
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@largestMobileScreen` | Largest mobile screen size | `(@tabletBreakpoint - 0.02px)` | Size |
| `@largestTabletScreen` | Largest tablet screen size | `(@computerBreakpoint - 0.02px)` | Size |
| `@largestSmallMonitor` | Largest small monitor size | `(@largeMonitorBreakpoint - 0.02px)` | Size |
| `@largestLargeMonitor` | Largest large monitor size | `(@widescreenMonitorBreakpoint - 0.02px)` | Size |

---

## Semantic State Colors

### Positive/Success State
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@positiveColor` | Positive accent color | `@green` | Color |
| `@positiveBackgroundColor` | Positive background | `#fcfff5` | Color |
| `@positiveBorderColor` | Positive border color | `#a3c293` | Color |
| `@positiveHeaderColor` | Positive header color | `#1a531b` | Color |
| `@positiveTextColor` | Positive text color | `#2c662d` | Color |

### Negative/Error State
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@negativeColor` | Negative accent color | `@red` | Color |
| `@negativeBackgroundColor` | Negative background | `#fff6f6` | Color |
| `@negativeBorderColor` | Negative border color | `#e0b4b4` | Color |
| `@negativeHeaderColor` | Negative header color | `#912d2b` | Color |
| `@negativeTextColor` | Negative text color | `#9f3a38` | Color |

### Info State
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@infoColor` | Info accent color | `#31ccec` | Color |
| `@infoBackgroundColor` | Info background | `#f8ffff` | Color |
| `@infoBorderColor` | Info border color | `#a9d5de` | Color |
| `@infoHeaderColor` | Info header color | `#0e566c` | Color |
| `@infoTextColor` | Info text color | `#276f86` | Color |

### Warning State
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@warningColor` | Warning accent color | `#f2c037` | Color |
| `@warningBorderColor` | Warning border color | `#c9ba9b` | Color |
| `@warningBackgroundColor` | Warning background | `#fffaf3` | Color |
| `@warningHeaderColor` | Warning header color | `#794b02` | Color |
| `@warningTextColor` | Warning text color | `#573a08` | Color |

### State Aliases
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@successBackgroundColor` | Success background (alias) | `@positiveBackgroundColor` | Color |
| `@successColor` | Success color (alias) | `@positiveColor` | Color |
| `@successBorderColor` | Success border (alias) | `@positiveBorderColor` | Color |
| `@successHeaderColor` | Success header (alias) | `@positiveHeaderColor` | Color |
| `@successTextColor` | Success text (alias) | `@positiveTextColor` | Color |
| `@errorBackgroundColor` | Error background (alias) | `@negativeBackgroundColor` | Color |
| `@errorColor` | Error color (alias) | `@negativeColor` | Color |
| `@errorBorderColor` | Error border (alias) | `@negativeBorderColor` | Color |
| `@errorHeaderColor` | Error header (alias) | `@negativeHeaderColor` | Color |
| `@errorTextColor` | Error text (alias) | `@negativeTextColor` | Color |

---

## Border Configuration

### Border Radius & Styling
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@circularRadius` | Circular border radius | `500rem` | Size |
| `@borderWidth` | Default border width | `1px` | Size |

### Border Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@borderColor` | Default border color | `rgba(34, 36, 38, 0.15)` | Color |
| `@strongBorderColor` | Strong border color | `rgba(34, 36, 38, 0.22)` | Color |
| `@internalBorderColor` | Internal border color | `rgba(34, 36, 38, 0.1)` | Color |
| `@selectedBorderColor` | Selected border color | `rgba(34, 36, 38, 0.35)` | Color |
| `@strongSelectedBorderColor` | Strong selected border color | `rgba(34, 36, 38, 0.5)` | Color |
| `@disabledBorderColor` | Disabled border color | `rgba(34, 36, 38, 0.5)` | Color |
| `@solidInternalBorderColor` | Solid internal border color | `#fafafa` | Color |
| `@solidBorderColor` | Solid border color | `#d4d4d5` | Color |
| `@solidSelectedBorderColor` | Solid selected border color | `#bcbdbd` | Color |
| `@whiteBorderColor` | White border color | `rgba(255, 255, 255, 0.1)` | Color |
| `@selectedWhiteBorderColor` | Selected white border color | `rgba(255, 255, 255, 0.8)` | Color |
| `@solidWhiteBorderColor` | Solid white border color | `#555` | Color |
| `@selectedSolidWhiteBorderColor` | Selected solid white border color | `#999` | Color |

---

## Shadows & Visual Effects

### Shadow Effects
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@subtleShadow` | Subtle shadow effect | `0 1px 2px 0 @borderColor` | Shadow |
| `@bottomShadow` | Bottom-only shadow | `0 2px 1px -1px @borderColor` | Shadow |
| `@floatingShadow` | Floating element shadow | `0 2px 4px 0 rgba(34, 36, 38, 0.12), 0 2px 10px 0 rgba(34, 36, 38, 0.15)` | Shadow |
| `@floatingShadowHover` | Floating shadow on hover | `0 2px 4px 0 rgba(34, 36, 38, 0.15), 0 2px 10px 0 rgba(34, 36, 38, 0.25)` | Shadow |
| `@invertedFloatingShadow` | Inverted floating shadow | `0 2px 4px 0 rgba(225, 225, 225, 0.1), 0 2px 10px 0 rgba(225, 225, 225, 0.5)` | Shadow |

### Text Shadows
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@textShadow` | Default text shadow | `none` | Shadow |
| `@invertedTextShadow` | Inverted text shadow | `@textShadow` | Shadow |

---

## Alpha Colors & Transparency

### Transparent Black Variants
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@subtleTransparentBlack` | Subtle transparent black | `rgba(0, 0, 0, 0.03)` | Color |
| `@transparentBlack` | Standard transparent black | `rgba(0, 0, 0, 0.05)` | Color |
| `@strongTransparentBlack` | Strong transparent black | `rgba(0, 0, 0, 0.1)` | Color |
| `@veryStrongTransparentBlack` | Very strong transparent black | `rgba(0, 0, 0, 0.15)` | Color |

### Transparent White Variants
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@subtleTransparentWhite` | Subtle transparent white | `rgba(255, 255, 255, 0.02)` | Color |
| `@transparentWhite` | Standard transparent white | `rgba(255, 255, 255, 0.08)` | Color |
| `@strongTransparentWhite` | Strong transparent white | `rgba(255, 255, 255, 0.15)` | Color |

---

## Text Color Variations

### Neutral Text Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@darkTextColor` | Dark text color | `rgba(0, 0, 0, 0.85)` | Color |
| `@mutedTextColor` | Muted text color | `rgba(0, 0, 0, 0.6)` | Color |
| `@lightTextColor` | Light text color | `rgba(0, 0, 0, 0.4)` | Color |
| `@unselectedTextColor` | Unselected text color | `rgba(0, 0, 0, 0.4)` | Color |
| `@hoveredTextColor` | Hovered text color | `rgba(0, 0, 0, 0.8)` | Color |
| `@pressedTextColor` | Pressed text color | `rgba(0, 0, 0, 0.9)` | Color |
| `@selectedTextColor` | Selected text color | `rgba(0, 0, 0, 0.95)` | Color |

### Inverted Text Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@invertedTextColor` | Inverted text color | `rgba(255, 255, 255, 0.9)` | Color |
| `@invertedMutedTextColor` | Inverted muted text color | `rgba(255, 255, 255, 0.8)` | Color |
| `@invertedLightTextColor` | Inverted light text color | `rgba(255, 255, 255, 0.7)` | Color |
| `@invertedUnselectedTextColor` | Inverted unselected text color | `rgba(255, 255, 255, 0.5)` | Color |
| `@invertedHoveredTextColor` | Inverted hovered text color | `rgba(255, 255, 255, 1)` | Color |
| `@invertedPressedTextColor` | Inverted pressed text color | `rgba(255, 255, 255, 1)` | Color |
| `@invertedSelectedTextColor` | Inverted selected text color | `rgba(255, 255, 255, 1)` | Color |

---

## Brand Colors

### Social Media Brand Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@facebookColor` | Facebook brand color | `#3b5998` | Color |
| `@twitterColor` | Twitter brand color | `#1da1f2` | Color |
| `@googlePlusColor` | Google Plus brand color | `#dd4b39` | Color |
| `@linkedInColor` | LinkedIn brand color | `#0077b5` | Color |
| `@youtubeColor` | YouTube brand color | `#f00` | Color |
| `@pinterestColor` | Pinterest brand color | `#bd081c` | Color |
| `@vkColor` | VK brand color | `#45668e` | Color |
| `@instagramColor` | Instagram brand color | `#49769c` | Color |
| `@telegramColor` | Telegram brand color | `#08c` | Color |
| `@whatsAppColor` | WhatsApp brand color | `#25d366` | Color |

---

## Exact Pixel Values

### Pixel-Perfect Sizing (1px - 64px)
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@1px` | 1 pixel in rem units | `unit((1 / @emSize), rem)` | Size |
| `@2px` | 2 pixels in rem units | `unit((2 / @emSize), rem)` | Size |
| `@3px` | 3 pixels in rem units | `unit((3 / @emSize), rem)` | Size |
| `@4px` | 4 pixels in rem units | `unit((4 / @emSize), rem)` | Size |
| `@5px` | 5 pixels in rem units | `unit((5 / @emSize), rem)` | Size |
| `@6px` | 6 pixels in rem units | `unit((6 / @emSize), rem)` | Size |
| `@7px` | 7 pixels in rem units | `unit((7 / @emSize), rem)` | Size |
| `@8px` | 8 pixels in rem units | `unit((8 / @emSize), rem)` | Size |
| `@9px` | 9 pixels in rem units | `unit((9 / @emSize), rem)` | Size |
| `@10px` | 10 pixels in rem units | `unit((10 / @emSize), rem)` | Size |
| `@11px` | 11 pixels in rem units | `unit((11 / @emSize), rem)` | Size |
| `@12px` | 12 pixels in rem units | `unit((12 / @emSize), rem)` | Size |
| `@13px` | 13 pixels in rem units | `unit((13 / @emSize), rem)` | Size |
| `@14px` | 14 pixels in rem units | `unit((14 / @emSize), rem)` | Size |
| `@15px` | 15 pixels in rem units | `unit((15 / @emSize), rem)` | Size |
| `@16px` | 16 pixels in rem units | `unit((16 / @emSize), rem)` | Size |
| `@17px` | 17 pixels in rem units | `unit((17 / @emSize), rem)` | Size |
| `@18px` | 18 pixels in rem units | `unit((18 / @emSize), rem)` | Size |
| `@19px` | 19 pixels in rem units | `unit((19 / @emSize), rem)` | Size |
| `@20px` | 20 pixels in rem units | `unit((20 / @emSize), rem)` | Size |
| `@21px` | 21 pixels in rem units | `unit((21 / @emSize), rem)` | Size |
| `@22px` | 22 pixels in rem units | `unit((22 / @emSize), rem)` | Size |
| `@23px` | 23 pixels in rem units | `unit((23 / @emSize), rem)` | Size |
| `@24px` | 24 pixels in rem units | `unit((24 / @emSize), rem)` | Size |
| `@25px` | 25 pixels in rem units | `unit((25 / @emSize), rem)` | Size |
| `@26px` | 26 pixels in rem units | `unit((26 / @emSize), rem)` | Size |
| `@27px` | 27 pixels in rem units | `unit((27 / @emSize), rem)` | Size |
| `@28px` | 28 pixels in rem units | `unit((28 / @emSize), rem)` | Size |
| `@29px` | 29 pixels in rem units | `unit((29 / @emSize), rem)` | Size |
| `@30px` | 30 pixels in rem units | `unit((30 / @emSize), rem)` | Size |
| `@31px` | 31 pixels in rem units | `unit((31 / @emSize), rem)` | Size |
| `@32px` | 32 pixels in rem units | `unit((32 / @emSize), rem)` | Size |
| `@33px` | 33 pixels in rem units | `unit((33 / @emSize), rem)` | Size |
| `@34px` | 34 pixels in rem units | `unit((34 / @emSize), rem)` | Size |
| `@35px` | 35 pixels in rem units | `unit((35 / @emSize), rem)` | Size |
| `@36px` | 36 pixels in rem units | `unit((36 / @emSize), rem)` | Size |
| `@37px` | 37 pixels in rem units | `unit((37 / @emSize), rem)` | Size |
| `@38px` | 38 pixels in rem units | `unit((38 / @emSize), rem)` | Size |
| `@39px` | 39 pixels in rem units | `unit((39 / @emSize), rem)` | Size |
| `@40px` | 40 pixels in rem units | `unit((40 / @emSize), rem)` | Size |
| `@41px` | 41 pixels in rem units | `unit((41 / @emSize), rem)` | Size |
| `@42px` | 42 pixels in rem units | `unit((42 / @emSize), rem)` | Size |
| `@43px` | 43 pixels in rem units | `unit((43 / @emSize), rem)` | Size |
| `@44px` | 44 pixels in rem units | `unit((44 / @emSize), rem)` | Size |
| `@45px` | 45 pixels in rem units | `unit((45 / @emSize), rem)` | Size |
| `@46px` | 46 pixels in rem units | `unit((46 / @emSize), rem)` | Size |
| `@47px` | 47 pixels in rem units | `unit((47 / @emSize), rem)` | Size |
| `@48px` | 48 pixels in rem units | `unit((48 / @emSize), rem)` | Size |
| `@49px` | 49 pixels in rem units | `unit((49 / @emSize), rem)` | Size |
| `@50px` | 50 pixels in rem units | `unit((50 / @emSize), rem)` | Size |
| `@51px` | 51 pixels in rem units | `unit((51 / @emSize), rem)` | Size |
| `@52px` | 52 pixels in rem units | `unit((52 / @emSize), rem)` | Size |
| `@53px` | 53 pixels in rem units | `unit((53 / @emSize), rem)` | Size |
| `@54px` | 54 pixels in rem units | `unit((54 / @emSize), rem)` | Size |
| `@55px` | 55 pixels in rem units | `unit((55 / @emSize), rem)` | Size |
| `@56px` | 56 pixels in rem units | `unit((56 / @emSize), rem)` | Size |
| `@57px` | 57 pixels in rem units | `unit((57 / @emSize), rem)` | Size |
| `@58px` | 58 pixels in rem units | `unit((58 / @emSize), rem)` | Size |
| `@59px` | 59 pixels in rem units | `unit((59 / @emSize), rem)` | Size |
| `@60px` | 60 pixels in rem units | `unit((60 / @emSize), rem)` | Size |
| `@61px` | 61 pixels in rem units | `unit((61 / @emSize), rem)` | Size |
| `@62px` | 62 pixels in rem units | `unit((62 / @emSize), rem)` | Size |
| `@63px` | 63 pixels in rem units | `unit((63 / @emSize), rem)` | Size |
| `@64px` | 64 pixels in rem units | `unit((64 / @emSize), rem)` | Size |

### Relative Pixel Values (Em Units)
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@relative1px` | 1 pixel in em units | `unit((1 / @emSize), em)` | Size |
| `@relative2px` | 2 pixels in em units | `unit((2 / @emSize), em)` | Size |
| `@relative3px` | 3 pixels in em units | `unit((3 / @emSize), em)` | Size |
| `@relative4px` | 4 pixels in em units | `unit((4 / @emSize), em)` | Size |
| `@relative5px` | 5 pixels in em units | `unit((5 / @emSize), em)` | Size |
| `@relative6px` | 6 pixels in em units | `unit((6 / @emSize), em)` | Size |
| `@relative7px` | 7 pixels in em units | `unit((7 / @emSize), em)` | Size |
| `@relative8px` | 8 pixels in em units | `unit((8 / @emSize), em)` | Size |
| `@relative9px` | 9 pixels in em units | `unit((9 / @emSize), em)` | Size |
| `@relative10px` | 10 pixels in em units | `unit((10 / @emSize), em)` | Size |
| `@relative11px` | 11 pixels in em units | `unit((11 / @emSize), em)` | Size |
| `@relative12px` | 12 pixels in em units | `unit((12 / @emSize), em)` | Size |
| `@relative13px` | 13 pixels in em units | `unit((13 / @emSize), em)` | Size |
| `@relative14px` | 14 pixels in em units | `unit((14 / @emSize), em)` | Size |
| `@relative15px` | 15 pixels in em units | `unit((15 / @emSize), em)` | Size |
| `@relative16px` | 16 pixels in em units | `unit((16 / @emSize), em)` | Size |
| `@relative17px` | 17 pixels in em units | `unit((17 / @emSize), em)` | Size |
| `@relative18px` | 18 pixels in em units | `unit((18 / @emSize), em)` | Size |
| `@relative19px` | 19 pixels in em units | `unit((19 / @emSize), em)` | Size |
| `@relative20px` | 20 pixels in em units | `unit((20 / @emSize), em)` | Size |
| `@relative21px` | 21 pixels in em units | `unit((21 / @emSize), em)` | Size |
| `@relative22px` | 22 pixels in em units | `unit((22 / @emSize), em)` | Size |
| `@relative23px` | 23 pixels in em units | `unit((23 / @emSize), em)` | Size |
| `@relative24px` | 24 pixels in em units | `unit((24 / @emSize), em)` | Size |
| `@relative25px` | 25 pixels in em units | `unit((25 / @emSize), em)` | Size |
| `@relative26px` | 26 pixels in em units | `unit((26 / @emSize), em)` | Size |
| `@relative27px` | 27 pixels in em units | `unit((27 / @emSize), em)` | Size |
| `@relative28px` | 28 pixels in em units | `unit((28 / @emSize), em)` | Size |
| `@relative29px` | 29 pixels in em units | `unit((29 / @emSize), em)` | Size |
| `@relative30px` | 30 pixels in em units | `unit((30 / @emSize), em)` | Size |
| `@relative31px` | 31 pixels in em units | `unit((31 / @emSize), em)` | Size |
| `@relative32px` | 32 pixels in em units | `unit((32 / @emSize), em)` | Size |
| `@relative33px` | 33 pixels in em units | `unit((33 / @emSize), em)` | Size |
| `@relative34px` | 34 pixels in em units | `unit((34 / @emSize), em)` | Size |
| `@relative35px` | 35 pixels in em units | `unit((35 / @emSize), em)` | Size |
| `@relative36px` | 36 pixels in em units | `unit((36 / @emSize), em)` | Size |
| `@relative37px` | 37 pixels in em units | `unit((37 / @emSize), em)` | Size |
| `@relative38px` | 38 pixels in em units | `unit((38 / @emSize), em)` | Size |
| `@relative39px` | 39 pixels in em units | `unit((39 / @emSize), em)` | Size |
| `@relative40px` | 40 pixels in em units | `unit((40 / @emSize), em)` | Size |
| `@relative41px` | 41 pixels in em units | `unit((41 / @emSize), em)` | Size |
| `@relative42px` | 42 pixels in em units | `unit((42 / @emSize), em)` | Size |
| `@relative43px` | 43 pixels in em units | `unit((43 / @emSize), em)` | Size |
| `@relative44px` | 44 pixels in em units | `unit((44 / @emSize), em)` | Size |
| `@relative45px` | 45 pixels in em units | `unit((45 / @emSize), em)` | Size |
| `@relative46px` | 46 pixels in em units | `unit((46 / @emSize), em)` | Size |
| `@relative47px` | 47 pixels in em units | `unit((47 / @emSize), em)` | Size |
| `@relative48px` | 48 pixels in em units | `unit((48 / @emSize), em)` | Size |
| `@relative49px` | 49 pixels in em units | `unit((49 / @emSize), em)` | Size |
| `@relative50px` | 50 pixels in em units | `unit((50 / @emSize), em)` | Size |
| `@relative51px` | 51 pixels in em units | `unit((51 / @emSize), em)` | Size |
| `@relative52px` | 52 pixels in em units | `unit((52 / @emSize), em)` | Size |
| `@relative53px` | 53 pixels in em units | `unit((53 / @emSize), em)` | Size |
| `@relative54px` | 54 pixels in em units | `unit((54 / @emSize), em)` | Size |
| `@relative55px` | 55 pixels in em units | `unit((55 / @emSize), em)` | Size |
| `@relative56px` | 56 pixels in em units | `unit((56 / @emSize), em)` | Size |
| `@relative57px` | 57 pixels in em units | `unit((57 / @emSize), em)` | Size |
| `@relative58px` | 58 pixels in em units | `unit((58 / @emSize), em)` | Size |
| `@relative59px` | 59 pixels in em units | `unit((59 / @emSize), em)` | Size |
| `@relative60px` | 60 pixels in em units | `unit((60 / @emSize), em)` | Size |
| `@relative61px` | 61 pixels in em units | `unit((61 / @emSize), em)` | Size |
| `@relative62px` | 62 pixels in em units | `unit((62 / @emSize), em)` | Size |
| `@relative63px` | 63 pixels in em units | `unit((63 / @emSize), em)` | Size |
| `@relative64px` | 64 pixels in em units | `unit((64 / @emSize), em)` | Size |

---

## Calculated Em Sizes

### Raw Size Calculations
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@miniRaw` | Mini raw size calculation | `unit((round(@miniSize * @emSize) / @emSize))` | Number |
| `@tinyRaw` | Tiny raw size calculation | `unit((round(@tinySize * @emSize) / @emSize))` | Number |
| `@smallRaw` | Small raw size calculation | `unit((round(@smallSize * @emSize) / @emSize))` | Number |
| `@mediumRaw` | Medium raw size calculation | `unit((round(@mediumSize * @emSize) / @emSize))` | Number |
| `@largeRaw` | Large raw size calculation | `unit((round(@largeSize * @emSize) / @emSize))` | Number |
| `@bigRaw` | Big raw size calculation | `unit((round(@bigSize * @emSize) / @emSize))` | Number |
| `@hugeRaw` | Huge raw size calculation | `unit((round(@hugeSize * @emSize) / @emSize))` | Number |
| `@massiveRaw` | Massive raw size calculation | `unit((round(@massiveSize * @emSize) / @emSize))` | Number |

### Rem Units
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@mini` | Mini size in rem | `unit(@miniRaw, rem)` | Size |
| `@tiny` | Tiny size in rem | `unit(@tinyRaw, rem)` | Size |
| `@small` | Small size in rem | `unit(@smallRaw, rem)` | Size |
| `@medium` | Medium size in rem | `unit(@mediumRaw, rem)` | Size |
| `@large` | Large size in rem | `unit(@largeRaw, rem)` | Size |
| `@big` | Big size in rem | `unit(@bigRaw, rem)` | Size |
| `@huge` | Huge size in rem | `unit(@hugeRaw, rem)` | Size |
| `@massive` | Massive size in rem | `unit(@massiveRaw, rem)` | Size |

### Em Units (Relative)
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@relativeMini` | Mini size in em | `unit(@miniRaw, em)` | Size |
| `@relativeTiny` | Tiny size in em | `unit(@tinyRaw, em)` | Size |
| `@relativeSmall` | Small size in em | `unit(@smallRaw, em)` | Size |
| `@relativeMedium` | Medium size in em | `unit(@mediumRaw, em)` | Size |
| `@relativeLarge` | Large size in em | `unit(@largeRaw, em)` | Size |
| `@relativeBig` | Big size in em | `unit(@bigRaw, em)` | Size |
| `@relativeHuge` | Huge size in em | `unit(@hugeRaw, em)` | Size |
| `@relativeMassive` | Massive size in em | `unit(@massiveRaw, em)` | Size |

### Absolute Units (Rem)
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@absoluteMini` | Mini size in absolute rem | `unit(@miniRaw, rem)` | Size |
| `@absoluteTiny` | Tiny size in absolute rem | `unit(@tinyRaw, rem)` | Size |
| `@absoluteSmall` | Small size in absolute rem | `unit(@smallRaw, rem)` | Size |
| `@absoluteMedium` | Medium size in absolute rem | `unit(@mediumRaw, rem)` | Size |
| `@absoluteLarge` | Large size in absolute rem | `unit(@largeRaw, rem)` | Size |
| `@absoluteBig` | Big size in absolute rem | `unit(@bigRaw, rem)` | Size |
| `@absoluteHuge` | Huge size in absolute rem | `unit(@hugeRaw, rem)` | Size |
| `@absoluteMassive` | Massive size in absolute rem | `unit(@massiveRaw, rem)` | Size |

---

## Grid Column Calculations

### Column Width Percentages
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@columnCount` | Number of grid columns | `16` | Number |
| `@oneWide` | One column width | `(1 / @columnCount * 100%)` | Size |
| `@twoWide` | Two columns width | `(2 / @columnCount * 100%)` | Size |
| `@threeWide` | Three columns width | `(3 / @columnCount * 100%)` | Size |
| `@fourWide` | Four columns width | `(4 / @columnCount * 100%)` | Size |
| `@fiveWide` | Five columns width | `(5 / @columnCount * 100%)` | Size |
| `@sixWide` | Six columns width | `(6 / @columnCount * 100%)` | Size |
| `@sevenWide` | Seven columns width | `(7 / @columnCount * 100%)` | Size |
| `@eightWide` | Eight columns width | `(8 / @columnCount * 100%)` | Size |
| `@nineWide` | Nine columns width | `(9 / @columnCount * 100%)` | Size |
| `@tenWide` | Ten columns width | `(10 / @columnCount * 100%)` | Size |
| `@elevenWide` | Eleven columns width | `(11 / @columnCount * 100%)` | Size |
| `@twelveWide` | Twelve columns width | `(12 / @columnCount * 100%)` | Size |
| `@thirteenWide` | Thirteen columns width | `(13 / @columnCount * 100%)` | Size |
| `@fourteenWide` | Fourteen columns width | `(14 / @columnCount * 100%)` | Size |
| `@fifteenWide` | Fifteen columns width | `(15 / @columnCount * 100%)` | Size |
| `@sixteenWide` | Sixteen columns width | `(16 / @columnCount * 100%)` | Size |

### Fractional Column Widths
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@oneColumn` | One column (100%) | `(1 / 1 * 100%)` | Size |
| `@twoColumn` | Two columns (50%) | `(1 / 2 * 100%)` | Size |
| `@threeColumn` | Three columns (33.33%) | `(1 / 3 * 100%)` | Size |
| `@fourColumn` | Four columns (25%) | `(1 / 4 * 100%)` | Size |
| `@fiveColumn` | Five columns (20%) | `(1 / 5 * 100%)` | Size |
| `@sixColumn` | Six columns (16.66%) | `(1 / 6 * 100%)` | Size |
| `@sevenColumn` | Seven columns (14.28%) | `(1 / 7 * 100%)` | Size |
| `@eightColumn` | Eight columns (12.5%) | `(1 / 8 * 100%)` | Size |
| `@nineColumn` | Nine columns (11.11%) | `(1 / 9 * 100%)` | Size |
| `@tenColumn` | Ten columns (10%) | `(1 / 10 * 100%)` | Size |
| `@elevenColumn` | Eleven columns (9.09%) | `(1 / 11 * 100%)` | Size |
| `@twelveColumn` | Twelve columns (8.33%) | `(1 / 12 * 100%)` | Size |
| `@thirteenColumn` | Thirteen columns (7.69%) | `(1 / 13 * 100%)` | Size |
| `@fourteenColumn` | Fourteen columns (7.14%) | `(1 / 14 * 100%)` | Size |
| `@fifteenColumn` | Fifteen columns (6.66%) | `(1 / 15 * 100%)` | Size |
| `@sixteenColumn` | Sixteen columns (6.25%) | `(1 / 16 * 100%)` | Size |

---

## Component States

### Disabled State
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@disabledOpacity` | Opacity for disabled elements | `0.45` | Number |
| `@disabledPointerEvents` | Pointer events for disabled elements | `none` | String |
| `@disabledTextColor` | Text color for disabled elements | `rgba(40, 40, 40, 0.3)` | Color |
| `@invertedDisabledTextColor` | Inverted disabled text color | `rgba(225, 225, 225, 0.3)` | Color |

---

## Hover State Colors

### Main Color Hover States
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@primaryColorHover` | Primary color hover state | `saturate(darken(@primaryColor, 5), 10, relative)` | Color |
| `@secondaryColorHover` | Secondary color hover state | `saturate(lighten(@secondaryColor, 5), 10, relative)` | Color |
| `@lightPrimaryColorHover` | Light primary color hover state | `saturate(darken(@lightPrimaryColor, 10), 10, relative)` | Color |
| `@lightSecondaryColorHover` | Light secondary color hover state | `saturate(lighten(@lightSecondaryColor, 10), 10, relative)` | Color |

### Palette Color Hover States
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@redHover` | Red hover state | `saturate(darken(@red, 5), 10, relative)` | Color |
| `@orangeHover` | Orange hover state | `saturate(darken(@orange, 5), 10, relative)` | Color |
| `@yellowHover` | Yellow hover state | `saturate(darken(@yellow, 5), 10, relative)` | Color |
| `@oliveHover` | Olive hover state | `saturate(darken(@olive, 5), 10, relative)` | Color |
| `@greenHover` | Green hover state | `saturate(darken(@green, 5), 10, relative)` | Color |
| `@tealHover` | Teal hover state | `saturate(darken(@teal, 5), 10, relative)` | Color |
| `@blueHover` | Blue hover state | `saturate(darken(@blue, 5), 10, relative)` | Color |
| `@violetHover` | Violet hover state | `saturate(darken(@violet, 5), 10, relative)` | Color |
| `@purpleHover` | Purple hover state | `saturate(darken(@purple, 5), 10, relative)` | Color |
| `@pinkHover` | Pink hover state | `saturate(darken(@pink, 5), 10, relative)` | Color |
| `@brownHover` | Brown hover state | `saturate(darken(@brown, 5), 10, relative)` | Color |

### Neutral Color Hover States
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@fullBlackHover` | Full black hover state | `lighten(@fullBlack, 5)` | Color |
| `@blackHover` | Black hover state | `lighten(@black, 5)` | Color |
| `@greyHover` | Grey hover state | `lighten(@grey, 5)` | Color |
| `@whiteHover` | White hover state | `darken(@white, 5)` | Color |
| `@offWhiteHover` | Off-white hover state | `darken(@offWhite, 5)` | Color |
| `@darkWhiteHover` | Dark white hover state | `darken(@darkWhite, 5)` | Color |

---

## Focus State Colors

### Main Color Focus States
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@primaryColorFocus` | Primary color focus state | `saturate(darken(@primaryColor, 8), 20, relative)` | Color |
| `@secondaryColorFocus` | Secondary color focus state | `saturate(lighten(@secondaryColor, 8), 20, relative)` | Color |
| `@lightPrimaryColorFocus` | Light primary color focus state | `saturate(darken(@lightPrimaryColor, 8), 20, relative)` | Color |
| `@lightSecondaryColorFocus` | Light secondary color focus state | `saturate(lighten(@lightSecondaryColor, 8), 20, relative)` | Color |

### Palette Color Focus States
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@redFocus` | Red focus state | `saturate(darken(@red, 8), 20, relative)` | Color |
| `@orangeFocus` | Orange focus state | `saturate(darken(@orange, 8), 20, relative)` | Color |
| `@yellowFocus` | Yellow focus state | `saturate(darken(@yellow, 8), 20, relative)` | Color |
| `@oliveFocus` | Olive focus state | `saturate(darken(@olive, 8), 20, relative)` | Color |
| `@greenFocus` | Green focus state | `saturate(darken(@green, 8), 20, relative)` | Color |
| `@tealFocus` | Teal focus state | `saturate(darken(@teal, 8), 20, relative)` | Color |
| `@blueFocus` | Blue focus state | `saturate(darken(@blue, 8), 20, relative)` | Color |
| `@violetFocus` | Violet focus state | `saturate(darken(@violet, 8), 20, relative)` | Color |
| `@purpleFocus` | Purple focus state | `saturate(darken(@purple, 8), 20, relative)` | Color |
| `@pinkFocus` | Pink focus state | `saturate(darken(@pink, 8), 20, relative)` | Color |
| `@brownFocus` | Brown focus state | `saturate(darken(@brown, 8), 20, relative)` | Color |

---

## Down/Active State Colors

### Main Color Down States
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@primaryColorDown` | Primary color down/pressed state | `darken(@primaryColor, 10)` | Color |
| `@secondaryColorDown` | Secondary color down/pressed state | `lighten(@secondaryColor, 10)` | Color |
| `@lightPrimaryColorDown` | Light primary color down state | `darken(@lightPrimaryColor, 10)` | Color |
| `@lightSecondaryColorDown` | Light secondary color down state | `lighten(@lightSecondaryColor, 10)` | Color |

### Palette Color Down States
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@redDown` | Red down/pressed state | `darken(@red, 10)` | Color |
| `@orangeDown` | Orange down/pressed state | `darken(@orange, 10)` | Color |
| `@yellowDown` | Yellow down/pressed state | `darken(@yellow, 10)` | Color |
| `@oliveDown` | Olive down/pressed state | `darken(@olive, 10)` | Color |
| `@greenDown` | Green down/pressed state | `darken(@green, 10)` | Color |
| `@tealDown` | Teal down/pressed state | `darken(@teal, 10)` | Color |
| `@blueDown` | Blue down/pressed state | `darken(@blue, 10)` | Color |
| `@violetDown` | Violet down/pressed state | `darken(@violet, 10)` | Color |
| `@purpleDown` | Purple down/pressed state | `darken(@purple, 10)` | Color |
| `@pinkDown` | Pink down/pressed state | `darken(@pink, 10)` | Color |
| `@brownDown` | Brown down/pressed state | `darken(@brown, 10)` | Color |

### Active State Colors
| Variable | Description | Default | Type |
|----------|-------------|---------|------|
| `@primaryColorActive` | Primary color active state | `saturate(darken(@primaryColor, 5), 15, relative)` | Color |
| `@secondaryColorActive` | Secondary color active state | `saturate(lighten(@secondaryColor, 5), 15, relative)` | Color |
| `@redActive` | Red active state | `saturate(darken(@red, 5), 15, relative)` | Color |
| `@orangeActive` | Orange active state | `saturate(darken(@orange, 5), 15, relative)` | Color |
| `@yellowActive` | Yellow active state | `saturate(darken(@yellow, 5), 15, relative)` | Color |
| `@oliveActive` | Olive active state | `saturate(darken(@olive, 5), 15, relative)` | Color |
| `@greenActive` | Green active state | `saturate(darken(@green, 5), 15, relative)