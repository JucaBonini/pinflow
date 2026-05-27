---
name: Vibrant Automation Utility
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#5e3f3c'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#936e6b'
  outline-variant: '#e8bcb8'
  surface-tint: '#c0001b'
  primary: '#b7001a'
  on-primary: '#ffffff'
  primary-container: '#e60023'
  on-primary-container: '#fff7f6'
  inverse-primary: '#ffb3ad'
  secondary: '#565e74'
  on-secondary: '#ffffff'
  secondary-container: '#dae2fd'
  on-secondary-container: '#5c647a'
  tertiary: '#0056ba'
  on-tertiary: '#ffffff'
  tertiary-container: '#1c6ee1'
  on-tertiary-container: '#f9f8ff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdad7'
  primary-fixed-dim: '#ffb3ad'
  on-primary-fixed: '#410004'
  on-primary-fixed-variant: '#930012'
  secondary-fixed: '#dae2fd'
  secondary-fixed-dim: '#bec6e0'
  on-secondary-fixed: '#131b2e'
  on-secondary-fixed-variant: '#3f465c'
  tertiary-fixed: '#d8e2ff'
  tertiary-fixed-dim: '#adc6ff'
  on-tertiary-fixed: '#001a42'
  on-tertiary-fixed-variant: '#004395'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
  code-sm:
    fontFamily: JetBrains Mono
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 40px
  xl: 64px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 48px
---

## Brand & Style
The design system focuses on high-performance automation for visual creators. It bridges the gap between Pinterest’s organic, discovery-focused aesthetic and the disciplined efficiency required of a professional SaaS tool. 

The visual style is **Minimalist / Modern**, characterized by ample white space, precise alignment, and high-contrast focal points. It aims to evoke a sense of reliability and speed, ensuring users feel in control of their automated workflows. The interface remains secondary to the user's content (images and videos), providing a neutral but sophisticated frame for visual media.

## Colors
The palette is anchored by the primary **Pinterest Red (#E60023)**, used strategically for primary actions, progress indicators, and brand-critical touchpoints. To balance this intensity, the system utilizes a **Slate Gray** scale for text and structural elements, providing a professional "software" feel that distinguishes the tool from the Pinterest consumer app.

- **Primary:** Brand Red. Reserved for the "Primary" button state and active status indicators.
- **Secondary:** Deep Slate (#0F172A). Used for high-level navigation and primary headings to provide grounding.
- **Surface:** Clean White (#FFFFFF) and a faint off-white (#F8FAFC) for background layering.
- **Success/Info:** Tertiary Blue (#3B82F6) is used for secondary data visualizations or non-critical system updates to avoid "red-fatigue."

## Typography
This design system utilizes **Inter** for all UI roles to ensure maximum legibility and a systematic, technical feel. 

Headlines use a tighter letter-spacing and semi-bold weights to create a strong hierarchy against the neutral background. Body copy is optimized for readability with a generous line height. For labels and small metadata, a slightly increased font-weight is used to maintain visibility even at smaller scales. When displaying URLs or automation logs, a monospaced font (JetBrains Mono) is introduced for clarity.

## Layout & Spacing
The layout follows a **Fluid Grid** model with a 12-column structure for desktop. 

- **Desktop (1440px+):** 48px outside margins, 24px gutters.
- **Tablet (768px - 1439px):** 32px outside margins, 20px gutters.
- **Mobile (Up to 767px):** 16px outside margins, 16px gutters.

Spacing follows an 8px base unit. Component-level spacing (padding inside cards and buttons) should stick to the `sm` (12px) and `md` (24px) tokens to maintain the "Modern SaaS" aesthetic. Complex dashboards should utilize a "Sidebar-plus-Main-Canvas" layout where the sidebar remains fixed and the canvas flows fluidly.

## Elevation & Depth
Depth is achieved through **Tonal Layers** supplemented by **Ambient Shadows**. 

1. **Level 0 (Background):** #F8FAFC (Off-white) - The lowest layer.
2. **Level 1 (Cards/Surface):** #FFFFFF (Pure White) - Used for the main content containers. These feature a very soft, diffused shadow: `0px 4px 12px rgba(15, 23, 42, 0.05)`.
3. **Level 2 (Dropdowns/Modals):** #FFFFFF - These feature a more pronounced shadow to signify interaction priority: `0px 10px 24px rgba(15, 23, 42, 0.1)`.
4. **Active States:** Subtle 1px borders in #E2E8F0 (Light Slate) are used to define boundaries on Level 1 elements when they are in an interactive or hover state.

## Shapes
The shape language is consistently **Rounded**, reflecting the approachability of the Pinterest ecosystem while remaining structured. 

Standard components (Buttons, Inputs, Small Cards) use the **8px (0.5rem)** radius. Larger containers or visual media previews use the **16px (1rem)** radius to soften the layout. Elements like tags or "Active" indicators may use a fully rounded (pill) shape to distinguish them from functional UI buttons.

## Components

- **Buttons:** Primary buttons are Solid Pinterest Red with white text. Secondary buttons use a white background with a Slate-200 border. No gradients; flat colors only.
- **Cards:** White surfaces with 16px rounded corners. Image previews within cards should be top-aligned with no margin, filling the width of the card.
- **Drag-and-Drop Zones:** Dashed border (#CBD5E1) with a light blue-tinted background on hover. Icons within these zones should be primary red to draw the eye.
- **Progress Bars:** A 4px thick track in Slate-100 with a Pinterest Red fill. For batch uploads, include a "percentage complete" label in `body-sm`.
- **Input Fields (URLs):** High-contrast 1px border (#E2E8F0) that turns Primary Red on focus. Use a 12px horizontal padding. Include a clear "paste" icon or button within the field for utility.
- **Chips/Tags:** Used for Pinterest boards or categories. Subtle gray backgrounds (#F1F5F9) with Slate-700 text to keep them secondary to the main content.
- **Status Indicators:** Small colored dots (Red for 'Busy/Error', Green for 'Live', Blue for 'Scheduled') paired with `label-md` text.