---
name: Kinetic Developer Logic
colors:
  surface: '#0d131f'
  surface-dim: '#0d131f'
  surface-bright: '#333946'
  surface-container-lowest: '#080e1a'
  surface-container-low: '#161c27'
  surface-container: '#1a202c'
  surface-container-high: '#242a36'
  surface-container-highest: '#2f3542'
  on-surface: '#dde2f3'
  on-surface-variant: '#c5c6cd'
  inverse-surface: '#dde2f3'
  inverse-on-surface: '#2a303d'
  outline: '#8f9097'
  outline-variant: '#45474c'
  surface-tint: '#bdc7dc'
  primary: '#bdc7dc'
  on-primary: '#273141'
  primary-container: '#2d3748'
  on-primary-container: '#96a0b5'
  inverse-primary: '#555f71'
  secondary: '#e6c446'
  on-secondary: '#3b2f00'
  secondary-container: '#ac8e0a'
  on-secondary-container: '#332900'
  tertiary: '#bcc7dd'
  on-tertiary: '#263142'
  tertiary-container: '#2c3749'
  on-tertiary-container: '#95a0b5'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#d9e3f9'
  primary-fixed-dim: '#bdc7dc'
  on-primary-fixed: '#121c2c'
  on-primary-fixed-variant: '#3d4759'
  secondary-fixed: '#ffe17c'
  secondary-fixed-dim: '#e6c446'
  on-secondary-fixed: '#231b00'
  on-secondary-fixed-variant: '#564500'
  tertiary-fixed: '#d8e3fa'
  tertiary-fixed-dim: '#bcc7dd'
  on-tertiary-fixed: '#111c2c'
  on-tertiary-fixed-variant: '#3c475a'
  background: '#0d131f'
  on-background: '#dde2f3'
  surface-variant: '#2f3542'
typography:
  headline-xl:
    fontFamily: Geist
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Geist
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  code-sm:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-caps:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 4px
  container-margin: 2rem
  gutter: 1.5rem
  component-padding-x: 1rem
  component-padding-y: 0.75rem
---

## Brand & Style

This design system is engineered for the TAVP stack, prioritizing high-performance, lean architecture, and developer-centric utility. The brand personality is efficient, technical, and "electrically" charged—symbolizing the speed of Phalcon and the reactivity of Alpine.js.

The visual style is **Minimalist with a Developer-Tooling twist**. It utilizes a "Dark Mode" primary aesthetic to reduce eye strain and provide a familiar environment for engineers. The interface relies on crisp borders, intentional whitespace, and high-contrast accents to guide the eye through complex data and documentation without unnecessary decoration.

## Colors

The palette is anchored in deep, sophisticated slates and navies, providing a high-contrast foundation for the vibrant "Electric Yellow" highlight.

- **Primary Background & Text:** `#2D3748` serves as the structural base for containers.
- **Electric Accent:** `#ECC94B` (Gold/Yellow) is used sparingly for call-to-actions, status indicators, and energetic highlights.
- **Deep Neutral:** `#1A202C` is used for the primary page background to create depth between the surface and the background.
- **Subtle Slate:** `#4A5568` is utilized for secondary text and low-priority borders to maintain a lean hierarchy.

## Typography

The typographic system balances the modern, technical precision of **Geist** with the universal legibility of **Inter**. To emphasize the developer-first nature of the stack, **JetBrains Mono** is employed for data labels, technical metrics, and code snippets.

For mobile screens, `headline-xl` should scale down to `28px` with a `34px` line height to maintain readability without overwhelming the viewport. All headings should use a tight letter-spacing to reinforce the "lean" brand identity.

## Layout & Spacing

The layout follows a **Fluid Grid** model with a maximum content width of 1280px for desktop environments. We use a strictly defined 4px baseline grid to ensure mathematical harmony across all components.

- **Desktop (1024px+):** 12-column grid, 24px gutters, 48px side margins.
- **Tablet (768px - 1023px):** 8-column grid, 20px gutters, 32px side margins.
- **Mobile (<767px):** 4-column grid, 16px gutters, 16px side margins.

Content is organized in "Performance Zones," where critical metrics and code blocks are given generous whitespace (32px-48px) to prevent cognitive overload.

## Elevation & Depth

To maintain a "high-performance" feel, the system avoids heavy shadows. Depth is achieved through **Tonal Layers** and **Low-Contrast Outlines**:

- **Level 0 (Background):** `#1A202C` (Deepest layer).
- **Level 1 (Cards/Containers):** `#2D3748` with a subtle `1px` border of `#4A5568`.
- **Level 2 (Modals/Popovers):** `#2D3748` with a thin, sharp `1px` border of the primary highlight `#ECC94B` at 30% opacity.

Instead of traditional blurs, use "Hard Step" depth: a 2px offset solid shadow in `#000000` for interactive elements like buttons to give them a tactile, mechanical feel.

## Shapes

The shape language is "Soft" yet precise. A `0.25rem` (4px) corner radius is the standard for cards and input fields, providing a modern touch without sacrificing the "industrial" aesthetic. 

- **Interactive Elements:** Use `rounded-sm` (2px) for a sharper, tool-like appearance.
- **Status Indicators:** Use `rounded-full` (pill) exclusively for tags and status badges.

## Components

### Buttons & Inputs
Buttons feature a high-contrast design. The primary button uses the Electric Yellow (`#ECC94B`) background with deep slate text. Input fields are dark (`#1A202C`) with subtle slate borders that glow slightly when focused.

### Performance Cards
Cards should be used for data visualization. They feature a top-border accent of 2px in the brand yellow to signify active metrics. The background remains `#2D3748` to separate it from the page floor.

### Code Blocks
Syntax highlighting must use a custom theme based on the TAVP palette. Background: `#1A202C`. Keywords: `#ECC94B`. Strings: `#A0AEC0`. Functions: `#F6AD55`.

### Performance Visualizations
Charts (line or bar) should use a single-stroke color of `#ECC94B` with a subtle gradient fill below the line to emphasize the "energy" of the data flow. Use JetBrains Mono for all axes and legends.