# CSS Variables to Fomantic-UI Equivalence Table

## Primary Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-primary: #5E6AD2` | `@primaryColor: @blue` | Different color - Fomantic uses `#2185d0` |
| `--color-primary-hover: #4C51BF` | `@primaryColorHover` | Auto-calculated in Fomantic |
| `--color-primary-light: #EEF2FF` | `@primaryBackground: #dff0ff` | Similar purpose, different shade |
| `--color-primary-border: #C7D2FE` | `@primaryBorderColor: @primaryColor` | Fomantic uses primary color |
| `--color-primary-bg: #EBF4FF` | `@primaryBackground: #dff0ff` | Close match |
| `--color-primary-dark: #3730A3` | `@primaryColorFocus` | Auto-calculated darker variant |

## Gray Scale

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-white: #FFFFFF` | `@white: #fff` | Exact match |
| `--color-gray-50: #F9FAFB` | `@offWhite: #f9fafb` | Exact match |
| `--color-gray-100: #F3F4F6` | `@darkWhite: #f3f4f5` | Very close match |
| `--color-gray-200: #E5E7EB` | `@borderColor: rgba(34, 36, 38, 0.15)` | Different approach |
| `--color-gray-300: #D1D5DB` | `@solidBorderColor: #d4d4d5` | Close match |
| `--color-gray-400: #9CA3AF` | `@unselectedTextColor: rgba(0, 0, 0, 0.4)` | Different approach |
| `--color-gray-500: #6B7280` | `@mutedTextColor: rgba(0, 0, 0, 0.6)` | Different approach |
| `--color-gray-600: #4B5563` | `@grey: #767676` | Different shade |
| `--color-gray-700: #374151` | `@darkTextColor: rgba(0, 0, 0, 0.85)` | Different approach |
| `--color-gray-800: #1F2937` | `@textColor: rgba(0, 0, 0, 0.87)` | Different approach |
| `--color-gray-900: #111827` | `@black: #1b1c1d` | Close match |

## Success Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-success: #10B981` | `@positiveColor: @green` (`#21ba45`) | Different shade |
| `--color-success-dark: #059669` | `@positiveColorHover` | Auto-calculated |
| `--color-success-darker: #065F46` | `@positiveTextColor: #2c662d` | Different shade |
| `--color-success-light: #D1FAE5` | `@positiveBackgroundColor: #fcfff5` | Different shade |

## Warning Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-warning: #F59E0B` | `@warningColor: #f2c037` | Different shade |
| `--color-warning-dark: #D97706` | `@warningTextColor: #573a08` | Different purpose |
| `--color-warning-darker: #92400E` | `@warningHeaderColor: #794b02` | Close match |
| `--color-warning-light: #FEF3C7` | `@warningBackgroundColor: #fffaf3` | Close match |
| `--color-warning-bg: #FDE68A` | `@yellowBackground: #fff8db` | Different shade |

## Error/Danger Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-error: #EF4444` | `@negativeColor: @red` (`#db2828`) | Different shade |
| `--color-error-dark: #DC2626` | `@negativeColorHover` | Auto-calculated |
| `--color-error-darker: #991B1B` | `@negativeTextColor: #9f3a38` | Different shade |
| `--color-error-darkest: #a93226` | `@negativeHeaderColor: #912d2b` | Close match |
| `--color-error-light: #FEE2E2` | `@negativeBackgroundColor: #fff6f6` | Close match |
| `--color-danger: #e74c3c` | `@red: #db2828` | Different shade |

## Info Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-info: #3B82F6` | `@infoColor: #31ccec` | Different shade |
| `--color-info-dark: #2563EB` | `@blue: #2185d0` | Different shade |
| `--color-info-darker: #1E40AF` | `@infoHeaderColor: #0e566c` | Different shade |
| `--color-info-light: #DBEAFE` | `@infoBackgroundColor: #f8ffff` | Different shade |

## Base Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-blue: #3B82F6` | `@blue: #2185d0` | Different shade |
| `--color-green: #10B981` | `@green: #21ba45` | Different shade |
| `--color-red: #EF4444` | `@red: #db2828` | Different shade |
| `--color-yellow: #F59E0B` | `@yellow: #fbbd08` | Different shade |
| `--color-purple: #8B5CF6` | `@purple: #a333c8` | Different shade |
| `--color-indigo: #6366F1` | `@violet: #6435c9` | Close conceptually |
| `--color-pink: #EC4899` | `@pink: #e03997` | Very close match |

## Text Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-text-primary: #1F2937` | `@textColor: rgba(0, 0, 0, 0.87)` | Different approach |
| `--color-text-secondary: #6B7280` | `@mutedTextColor: rgba(0, 0, 0, 0.6)` | Different approach |
| `--color-text-tertiary: #9CA3AF` | `@lightTextColor: rgba(0, 0, 0, 0.4)` | Different approach |
| `--color-text-light: #F9FAFB` | `@invertedTextColor: rgba(255, 255, 255, 0.9)` | Similar purpose |
| `--color-text-white: #FFFFFF` | `@white: #fff` | Exact match |
| `--color-text-muted: #D1D5DB` | `@unselectedTextColor: rgba(0, 0, 0, 0.4)` | Different approach |

## Background Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-bg-primary: #FFFFFF` | `@pageBackground: #fff` | Exact match |
| `--color-bg-secondary: #F9FAFB` | `@offWhite: #f9fafb` | Exact match |
| `--color-bg-tertiary: #F3F4F6` | `@darkWhite: #f3f4f5` | Very close match |
| `--color-bg-dark: #1F2937` | `@black: #1b1c1d` | Different shade |
| `--color-bg-darker: #111827` | `@fullBlack: #000` | Different approach |
| `--color-bg-overlay: rgba(0, 0, 0, 0.5)` | `@transparentBlack: rgba(0, 0, 0, 0.05)` | Different opacity |

## Border Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-border-primary: #E5E7EB` | `@borderColor: rgba(34, 36, 38, 0.15)` | Different approach |
| `--color-border-secondary: #D1D5DB` | `@solidBorderColor: #d4d4d5` | Close match |
| `--color-border-light: #F3F4F6` | `@lightGrey: #dcddde` | Different shade |
| `--color-border-dark: #374151` | `@strongBorderColor: rgba(34, 36, 38, 0.22)` | Different approach |

## Shadow Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-shadow-light: rgba(0, 0, 0, 0.05)` | `@subtleTransparentBlack: rgba(0, 0, 0, 0.03)` | Close match |
| `--color-shadow-medium: rgba(0, 0, 0, 0.1)` | `@strongTransparentBlack: rgba(0, 0, 0, 0.1)` | Exact match |
| `--color-shadow-dark: rgba(0, 0, 0, 0.2)` | `@veryStrongTransparentBlack: rgba(0, 0, 0, 0.15)` | Close match |

## Form/Input Colors

| CSS Variable | Fomantic-UI Variable | Notes |
|-------------|---------------------|-------|
| `--color-input-bg: #FFFFFF` | `@inputBackground: @white` | Exact match |
| `--color-input-bg-alt: #F9FAFB` | No direct equivalent | - |
| `--color-input-border: #E5E7EB` | `@borderColor` | Same as border-primary |
| `--color-input-focus: #5E6AD2` | `@focusedFormBorderColor: #85b7d9` | Different color |
| `--color-input-placeholder: #9CA3AF` | `@inputPlaceholderColor` | Auto-calculated |

## Additional Mappings

### Sizing Variables
- Your system uses pixel-based rem values, Fomantic uses em-based sizing
- `@emSize: 14px` is the base size in Fomantic
- Font sizes: `@fontSize: 14px`, `@h1-@h6` for headings

### Border Radius
- Your `--color-border-radius` variables would map to:
  - `@defaultBorderRadius: @absoluteBorderRadius`
  - `@relativeBorderRadius: @relative4px`

### Transitions
- Fomantic uses: `@defaultDuration: 0.1s` and `@defaultEasing: ease`

### Breakpoints
- `@mobileBreakpoint: 320px`
- `@tabletBreakpoint: 768px`
- `@computerBreakpoint: 992px`
- `@largeMonitorBreakpoint: 1200px`

## Key Differences

1. **Color Philosophy**: Your system uses exact hex values, while Fomantic uses a mix of hex and rgba with calculated variations
2. **Naming Convention**: Your system uses CSS custom properties (`--color-`), Fomantic uses Less variables (`@`)
3. **Color Calculations**: Fomantic auto-calculates hover/focus states, your system defines them explicitly
4. **Transparency**: Fomantic makes heavy use of rgba for subtle variations
5. **Semantic Naming**: Fomantic uses more semantic names (positive/negative vs success/error)

## Migration Recommendations

1. **Color Adjustment**: Most colors need slight adjustments to match Fomantic's palette
2. **Use Fomantic's Calculations**: Leverage the auto-calculated hover/focus states
3. **Adopt Semantic Naming**: Consider using positive/negative alongside success/error
4. **Transparency Patterns**: Adopt Fomantic's rgba approach for better theme flexibility
5. **Size System**: Adapt to Fomantic's em-based sizing system for better scalability